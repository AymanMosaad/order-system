<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // ===========================
    // الطلبيات العامة (للجميع)
    // ===========================

    /**
     * عرض التقارير الرئيسية
     */
    public function frontPage()
    {
        $totalOrders = Order::count();
        $totalItems = OrderItem::sum('total');

        // تجميع البيانات حسب نوع المخزن
        $typeStats = [];
        $warehouseTypes = Order::distinct('warehouse_type')->pluck('warehouse_type')->filter();

        foreach ($warehouseTypes as $warehouse) {
            $orders = Order::where('warehouse_type', $warehouse)->get();
            $itemsByType = [];

            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    $type = $item->type ?? 'بدون تصنيف';
                    $itemsByType[$type] = ($itemsByType[$type] ?? 0) + $item->total;
                }
            }

            $typeStats[$warehouse] = [
                'orders_count' => $orders->count(),
                'total_items' => $orders->sum(fn($o) => $o->getTotalQuantity()),
                'by_type' => $itemsByType
            ];
        }

        return view('orders.front', [
            'totalOrders' => $totalOrders,
            'totalItems' => $totalItems,
            'typeStats' => $typeStats
        ]);
    }

    /**
     * عرض كل الطلبيات
     */
    public function index()
    {
        $orders = Order::with('user', 'items')->latest()->paginate(15);

        return view('orders.index', ['orders' => $orders]);
    }

    /**
     * عرض تفاصيل طلبية واحدة
     */
    public function show($id)
    {
        $order = Order::with('items.product', 'user')->findOrFail($id);

        return view('orders.show', ['order' => $order]);
    }

    // ===========================
    // الطلبيات الخاصة بالمستخدم
    // ===========================

    /**
     * لوحة تحكم المستخدم
     */
    public function userDashboard()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('items')->latest()->paginate(10);
        $totalOrders = $user->orders()->count();
        $totalItems = OrderItem::whereHas('order', fn($q) => $q->where('user_id', $user->id))->sum('total');

        return view('orders.user_dashboard', [
            'orders' => $orders,
            'totalOrders' => $totalOrders,
            'totalItems' => $totalItems
        ]);
    }

    /**
     * صفحة إنشاء طلبية جديدة
     */
    public function create()
    {
        $products = Product::where('is_active', true)
            ->with('stock')
            ->get();

        return view('orders.create', ['products' => $products]);
    }

    /**
     * حفظ طلبية جديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'trader_name' => 'nullable|string|max:255',
            'order_number' => 'nullable|string|max:255',
            'warehouse_type' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'driver_name' => 'nullable|string',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.item_code' => 'required|string',
            'items.*.item_code2' => 'nullable|string',
            'items.*.item_code3' => 'nullable|string',
            'items.*.type' => 'nullable|string',
            'items.*.name' => 'nullable|string',
            'items.*.color' => 'nullable|string',
            'items.*.size' => 'nullable|string',
            'items.*.grade1' => 'required|integer|min:0',
            'items.*.grade2' => 'required|integer|min:0',
            'items.*.grade3' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // إنشاء الطلبية
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_name' => $validated['customer_name'],
                'trader_name' => $validated['trader_name'],
                'order_number' => $validated['order_number'],
                'warehouse_type' => $validated['warehouse_type'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'driver_name' => $validated['driver_name'],
                'date' => $validated['date'],
                'notes' => $validated['notes'],
                'status' => 'جديدة'
            ]);

            // إضافة الأصناف
            foreach ($validated['items'] as $itemData) {
                if (empty($itemData['item_code'])) {
                    continue;
                }

                // حساب الإجمالي
                $total = ($itemData['grade1'] ?? 0) + ($itemData['grade2'] ?? 0) + ($itemData['grade3'] ?? 0);

                // البحث عن المنتج بـ item_code (اختياري)
                $product = null;
                if (!empty($itemData['item_code'])) {
                    $product = Product::where('item_code', $itemData['item_code'])->first();
                }

                // خصم من الرصيد إذا كان المنتج موجود
                if ($product) {
                    $stock = ProductStock::where('product_id', $product->id)->first();
                    if ($stock && $stock->current_stock >= $total) {
                        $stock->decreaseStock($total);
                    } else if ($stock) {
                        throw new \Exception('الرصيد غير كافي للصنف: ' . $itemData['item_code']);
                    }
                }

                // إنشاء الصنف في الطلبية
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product?->id,
                    'item_code' => $itemData['item_code'],
                    'item_code2' => $itemData['item_code2'],
                    'item_code3' => $itemData['item_code3'],
                    'type' => $itemData['type'],
                    'name' => $itemData['name'],
                    'color' => $itemData['color'],
                    'size' => $itemData['size'],
                    'grade1' => $itemData['grade1'] ?? 0,
                    'grade2' => $itemData['grade2'] ?? 0,
                    'grade3' => $itemData['grade3'] ?? 0,
                    'total' => $total
                ]);
            }

            DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'تم حفظ الطلبية بنجاح وخصم الرصيد');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * صفحة تعديل طلبية
     */
    public function edit($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        // التحقق: فقط الموظف صاحب الطلبية يقدر يعدل
        if (Auth::id() !== $order->user_id) {
            abort(403, 'غير مصرح');
        }

        $products = Product::where('is_active', true)->with('stock')->get();

        return view('orders.edit', [
            'order' => $order,
            'products' => $products
        ]);
    }

    /**
     * حفظ التعديلات
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // التحقق
        if (Auth::id() !== $order->user_id) {
            abort(403, 'غير مصرح');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'trader_name' => 'nullable|string|max:255',
            'order_number' => 'nullable|string|max:255',
            'warehouse_type' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'driver_name' => 'nullable|string',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            // تحديث الطلبية
            $order->update([
                'customer_name' => $validated['customer_name'],
                'trader_name' => $validated['trader_name'],
                'order_number' => $validated['order_number'],
                'warehouse_type' => $validated['warehouse_type'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'driver_name' => $validated['driver_name'],
                'date' => $validated['date'],
                'notes' => $validated['notes'],
            ]);

            // تحديث الأصناف
            foreach ($validated['items'] as $index => $itemData) {
                if (isset($itemData['id'])) {
                    // تحديث صنف موجود
                    $item = OrderItem::findOrFail($itemData['id']);
                    $oldTotal = $item->total;
                    $newTotal = ($itemData['grade1'] ?? 0) + ($itemData['grade2'] ?? 0) + ($itemData['grade3'] ?? 0);

                    // تعديل الرصيد إذا تغيرت الكمية
                    if ($oldTotal !== $newTotal && $item->product_id) {
                        $stock = ProductStock::where('product_id', $item->product_id)->first();
                        if ($stock) {
                            $difference = $oldTotal - $newTotal;
                            if ($difference > 0) {
                                $stock->increaseStock($difference);
                            } else {
                                if (!$stock->decreaseStock(abs($difference))) {
                                    throw new \Exception('الرصيد غير كافي');
                                }
                            }
                        }
                    }

                    $item->update([
                        'grade1' => $itemData['grade1'] ?? 0,
                        'grade2' => $itemData['grade2'] ?? 0,
                        'grade3' => $itemData['grade3'] ?? 0,
                        'total' => $newTotal
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'تم تحديث الطلبية بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * حذف طلبية
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        // التحقق
        if (Auth::id() !== $order->user_id) {
            abort(403, 'غير مصرح');
        }

        try {
            DB::beginTransaction();

            // رجعة الرصيد
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    $stock = ProductStock::where('product_id', $item->product_id)->first();
                    if ($stock) {
                        $stock->increaseStock($item->total);
                    }
                }
            }

            $order->delete();

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', 'تم حذف الطلبية ورجع الرصيد بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
