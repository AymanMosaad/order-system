<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // ===========================
    // الطلبيات العامة (للجميع)
    // ===========================

    /**
     * عرض التقارير الرئيسية - المبيعات حسب نوع الصنف ونوع المخزن
     */
    public function frontPage(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')
                ->with('error', 'غير مصرح لك بالدخول');
        }

        try {
            $query = Order::with('items.product');

            if ($request->warehouse_type) {
                $query->where('warehouse_type', $request->warehouse_type);
            }

            if ($request->from_date) {
                $query->whereDate('date', '>=', $request->from_date);
            }
            if ($request->to_date) {
                $query->whereDate('date', '<=', $request->to_date);
            }

            $orders = $query->get();

            $productTypes = [
                'حوائط جلوريا', 'حوائط ايكو', 'أرضيات جلوريا', 'أرضيات ايكو',
                'HDC', 'UGC', 'بورسل', 'PORSLIM',
                'SUPER GLOSSY 61×122.5', 'SUPER GLOSSY 61×61'
            ];

            $typeSales = [];
            foreach ($productTypes as $type) {
                $typeSales[$type] = 0;
            }
            $typeSales['أخرى'] = 0;

            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    if ($item->product && $item->product->type) {
                        $productType = $item->product->type;
                        if (isset($typeSales[$productType])) {
                            $typeSales[$productType] += $item->total;
                        } else {
                            $typeSales['أخرى'] += $item->total;
                        }
                    }
                }
            }

            $warehouseTypes = ['محلي', 'تصدير', 'معرض بيع', 'احتكار'];
            $warehouseStats = [];
            foreach ($warehouseTypes as $warehouse) {
                $warehouseOrders = $orders->where('warehouse_type', $warehouse);
                $warehouseStats[$warehouse] = [
                    'orders_count' => $warehouseOrders->count(),
                    'total_quantity' => $warehouseOrders->sum(fn($o) => $o->getTotalQuantity()),
                ];
            }

            $totalOrders = $orders->count();
            $totalQuantity = $orders->sum(fn($o) => $o->getTotalQuantity());

            return view('orders.front', [
                'typeSales' => $typeSales,
                'warehouseStats' => $warehouseStats,
                'totalOrders' => $totalOrders,
                'totalQuantity' => $totalQuantity,
                'selectedWarehouse' => $request->warehouse_type,
                'fromDate' => $request->from_date,
                'toDate' => $request->to_date,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in frontPage: ' . $e->getMessage());
            return view('orders.front', [
                'typeSales' => [],
                'warehouseStats' => [],
                'totalOrders' => 0,
                'totalQuantity' => 0,
            ])->with('error', 'حدث خطأ في تحميل التقرير');
        }
    }

    /**
     * عرض كل الطلبيات (للمدير العام ومدير المبيعات)
     */
    public function index()
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'sales_manager'])) {
            return redirect()->route('orders.userDashboard')
                ->with('error', 'غير مصرح لك بعرض كل الطلبيات');
        }

        try {
            $orders = Order::with('user', 'items')->latest()->paginate(15);
            return view('orders.index', ['orders' => $orders]);
        } catch (\Exception $e) {
            Log::error('Error in index: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ في عرض الطلبيات');
        }
    }

    /**
     * عرض تفاصيل طلبية واحدة
     */
    public function show($id)
    {
        try {
            $order = Order::with('items.product', 'user')->findOrFail($id);

            if (Auth::user()->role == 'sales_rep' && Auth::id() !== $order->user_id) {
                abort(403, 'غير مصرح لك بعرض هذه الطلبية');
            }

            return view('orders.show', ['order' => $order]);
        } catch (\Exception $e) {
            Log::error('Error in show: ' . $e->getMessage());
            return redirect()->route('orders.userDashboard')
                ->with('error', 'الطلبية غير موجودة');
        }
    }

    // ===========================
    // الطلبيات الخاصة بالمستخدم
    // ===========================

    public function userDashboard()
    {
        try {
            $user = Auth::user();
            $orders = $user->orders()->with('items')->latest()->paginate(10);
            $totalOrders = $user->orders()->count();
            $totalItems = OrderItem::whereHas('order', fn($q) => $q->where('user_id', $user->id))->sum('total');

            return view('orders.user_dashboard', [
                'orders'      => $orders,
                'totalOrders' => $totalOrders,
                'totalItems'  => $totalItems
            ]);
        } catch (\Exception $e) {
            Log::error('Error in userDashboard: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ في عرض لوحة التحكم');
        }
    }

    public function create()
    {
        try {
            $products = Product::where('is_active', true)
                ->with('stock')
                ->get();

            return view('orders.create', ['products' => $products]);
        } catch (\Exception $e) {
            Log::error('Error in create: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ في تحميل صفحة الإنشاء');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'trader_name'    => 'nullable|string|max:255',
            'order_number'   => 'nullable|string|max:255',
            'warehouse_type' => 'nullable|string',
            'address'        => 'nullable|string',
            'phone'          => 'nullable|string',
            'driver_name'    => 'nullable|string',
            'date'           => 'required|date',
            'notes'          => 'nullable|string',
            'items'          => 'required|array|min:1',
            'items.*.item_code' => 'required|string',
            'items.*.name'      => 'required|string',
            'items.*.quantity'  => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id'        => Auth::id(),
                'customer_name'  => $validated['customer_name'],
                'trader_name'    => $validated['trader_name'],
                'order_number'   => $validated['order_number'],
                'warehouse_type' => $validated['warehouse_type'],
                'address'        => $validated['address'],
                'phone'          => $validated['phone'],
                'driver_name'    => $validated['driver_name'],
                'date'           => $validated['date'],
                'notes'          => $validated['notes'],
                'status'         => 'جديدة'
            ]);

            foreach ($validated['items'] as $itemData) {
                if (empty($itemData['item_code']) || $itemData['quantity'] < 0.01) {
                    continue;
                }

                $quantity = (float) $itemData['quantity'];

                $product = Product::where('item_code', $itemData['item_code'])->first();

                if (!$product) {
                    throw new \Exception('الصنف غير موجود: ' . $itemData['item_code']);
                }

                $stock = ProductStock::where('product_id', $product->id)->first();
                if ($stock) {
                    if ($stock->current_stock >= $quantity) {
                        $stock->decreaseStock($quantity);
                    } else {
                        throw new \Exception("الرصيد غير كافي للصنف: {$product->name} (متوفر: {$stock->current_stock})");
                    }
                } else {
                    throw new \Exception("لا يوجد رصيد مسجل للصنف: {$product->name}");
                }

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'item_code'  => $itemData['item_code'],
                    'name'       => $itemData['name'],
                    'grade1'     => $quantity,
                    'grade2'     => 0,
                    'grade3'     => 0,
                    'total'      => $quantity
                ]);
            }

            DB::commit();

            if (Auth::user()->role == 'sales_rep') {
                try {
                    $admins = \App\Models\User::whereIn('role', ['super_admin', 'sales_manager'])->get();
                    foreach ($admins as $admin) {
                        try {
                            $admin->notify(new \App\Notifications\NewOrderNotification($order));
                        } catch (\Exception $e) {
                            Log::warning('Failed to send notification to admin ID ' . $admin->id . ': ' . $e->getMessage());
                        }
                    }
                    Log::info('📧 تم إرسال إشعار للمديرين عن طلبية جديدة رقم ' . $order->id . ' بواسطة المستخدم: ' . Auth::user()->name);
                } catch (\Exception $e) {
                    Log::warning('Notification system error: ' . $e->getMessage());
                }
            } else {
                Log::info('👑 تم إنشاء طلبية جديدة رقم ' . $order->id . ' بواسطة المدير: ' . Auth::user()->name);
            }

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'تم حفظ الطلبية بنجاح وتم خصم الرصيد من المخزن');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing order: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $order = Order::with('items.product')->findOrFail($id);

            if (Auth::user()->role == 'sales_rep' && Auth::id() !== $order->user_id) {
                abort(403, 'غير مصرح لك بتعديل هذه الطلبية');
            }

            $products = Product::where('is_active', true)->with('stock')->get();

            return view('orders.edit', [
                'order'    => $order,
                'products' => $products
            ]);
        } catch (\Exception $e) {
            Log::error('Error in edit: ' . $e->getMessage());
            return redirect()->route('orders.userDashboard')
                ->with('error', 'حدث خطأ في تحميل صفحة التعديل');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            if (Auth::user()->role == 'sales_rep' && Auth::id() !== $order->user_id) {
                abort(403, 'غير مصرح لك بتعديل هذه الطلبية');
            }

            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'trader_name'   => 'nullable|string|max:255',
                'order_number'  => 'nullable|string|max:255',
                'warehouse_type'=> 'nullable|string',
                'address'       => 'nullable|string',
                'phone'         => 'nullable|string',
                'driver_name'   => 'nullable|string',
                'date'          => 'required|date',
                'notes'         => 'nullable|string',
                'items'         => 'required|array',
            ]);

            DB::beginTransaction();

            $order->update([
                'customer_name'  => $validated['customer_name'],
                'trader_name'    => $validated['trader_name'],
                'order_number'   => $validated['order_number'],
                'warehouse_type' => $validated['warehouse_type'],
                'address'        => $validated['address'],
                'phone'          => $validated['phone'],
                'driver_name'    => $validated['driver_name'],
                'date'           => $validated['date'],
                'notes'          => $validated['notes'],
            ]);

            foreach ($validated['items'] as $itemData) {
                if (isset($itemData['id'])) {
                    $item = OrderItem::findOrFail($itemData['id']);
                    $oldTotal = $item->total;
                    $newTotal = $itemData['quantity'] ?? $oldTotal;

                    if ($oldTotal !== $newTotal && $item->product_id) {
                        $stock = ProductStock::where('product_id', $item->product_id)->first();
                        if ($stock) {
                            $difference = $oldTotal - $newTotal;
                            if ($difference > 0) {
                                $stock->increaseStock($difference);
                            } elseif ($difference < 0) {
                                if (!$stock->decreaseStock(abs($difference))) {
                                    throw new \Exception("الرصيد غير كافي للصنف: {$item->name}");
                                }
                            }
                        }
                    }

                    $item->update([
                        'grade1' => $newTotal,
                        'grade2' => 0,
                        'grade3' => 0,
                        'total'  => $newTotal
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'تم تحديث الطلبية بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating order: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);

            if (Auth::user()->role == 'sales_rep' && Auth::id() !== $order->user_id) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'error' => 'غير مصرح لك بحذف هذه الطلبية'
                    ], 403);
                }
                return back()->with('error', 'غير مصرح لك بحذف هذه الطلبية');
            }

            DB::beginTransaction();

            foreach ($order->items as $item) {
                if ($item->product_id) {
                    $stock = ProductStock::where('product_id', $item->product_id)->first();
                    if ($stock) {
                        $stock->increaseStock($item->total);
                        Log::info("تم إعادة {$item->total} وحدة للصنف ID: {$item->product_id}");
                    }
                }
            }

            OrderItem::where('order_id', $order->id)->delete();
            $order->delete();

            DB::commit();

            Log::info("تم حذف الطلبية رقم {$id} بنجاح");

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم حذف الطلبية ورجع الرصيد بنجاح'
                ]);
            }

            return redirect()->route('orders.userDashboard')
                ->with('success', 'تم حذف الطلبية ورجع الرصيد بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting order: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            if (request()->expectsJson()) {
                return response()->json([
                    'error' => 'حدث خطأ أثناء حذف الطلبية: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'حدث خطأ أثناء حذف الطلبية: ' . $e->getMessage());
        }
    }

    public function adminDashboard()
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'sales_manager'])) {
            return redirect()->route('orders.userDashboard')
                ->with('error', 'غير مصرح لك بالدخول');
        }

        try {
            $totalOrders = Order::count();
            $totalUsers = \App\Models\User::count();
            $totalProducts = Product::count();
            $totalRevenue = OrderItem::sum('total');

            $recentOrders = Order::with('user')->latest()->take(10)->get();
            $unreadNotifications = auth()->user()->unreadNotifications;

            return view('orders.admin_dashboard', [
                'totalOrders' => $totalOrders,
                'totalUsers' => $totalUsers,
                'totalProducts' => $totalProducts,
                'totalRevenue' => $totalRevenue,
                'recentOrders' => $recentOrders,
                'unreadNotifications' => $unreadNotifications
            ]);
        } catch (\Exception $e) {
            Log::error('Error in adminDashboard: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ في تحميل لوحة التحكم');
        }
    }

    public function advancedReport(Request $request)
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'sales_manager'])) {
            return redirect()->route('orders.userDashboard')
                ->with('error', 'غير مصرح لك بالدخول');
        }

        try {
            $query = Order::with('user', 'items.product');

            if ($request->from_date) {
                $query->whereDate('date', '>=', $request->from_date);
            }
            if ($request->to_date) {
                $query->whereDate('date', '<=', $request->to_date);
            }
            if ($request->warehouse_type) {
                $query->where('warehouse_type', $request->warehouse_type);
            }

            $orders = $query->get();

            $totalOrders = $orders->count();
            $totalQuantity = $orders->sum(fn($o) => $o->getTotalQuantity());

            $userStats = [];
            foreach ($orders->groupBy('user_id') as $userId => $userOrders) {
                $user = $userOrders->first()->user;
                if ($user) {
                    $userStats[] = [
                        'name' => $user->name,
                        'orders_count' => $userOrders->count(),
                        'total_quantity' => $userOrders->sum(fn($o) => $o->getTotalQuantity()),
                    ];
                }
            }

            $typeStats = [];
            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $type = $item->product->type ?? 'أخرى';
                        if (!isset($typeStats[$type])) {
                            $typeStats[$type] = [
                                'orders_count' => 0,
                                'total_quantity' => 0,
                            ];
                        }
                        $typeStats[$type]['orders_count']++;
                        $typeStats[$type]['total_quantity'] += $item->total;
                    }
                }
            }

            $productStats = [];
            $products = Product::with('stock', 'orderItems')->where('is_active', true)->get();
            foreach ($products as $product) {
                $soldQuantity = $product->orderItems->sum('total');
                $ordersCount = $product->orderItems->groupBy('order_id')->count();
                $productStats[] = [
                    'item_code' => $product->item_code,
                    'name' => $product->name,
                    'total_sold' => $soldQuantity,
                    'orders_count' => $ordersCount,
                    'current_stock' => $product->stock->current_stock ?? 0,
                    'is_low' => ($product->stock->current_stock ?? 0) < ($product->stock->min_stock ?? 0),
                ];
            }

            $productStats = collect($productStats)->sortByDesc('total_sold')->values()->all();
            $activeUsers = \App\Models\User::where('is_admin', 0)->count();

            return view('orders.advanced_report', [
                'totalOrders' => $totalOrders,
                'totalQuantity' => $totalQuantity,
                'userStats' => $userStats,
                'typeStats' => $typeStats,
                'productStats' => $productStats,
                'activeUsers' => $activeUsers,
                'fromDate' => $request->from_date,
                'toDate' => $request->to_date,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in advancedReport: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ في تحميل التقرير: ' . $e->getMessage());
        }
    }

    /**
     * عرض طلبيات المصنع (الطلبيات المرسلة للمصنع فقط)
     */
    public function factoryOrders()
    {
        if (Auth::user()->role != 'factory' && Auth::user()->role != 'super_admin') {
            return redirect()->route('orders.userDashboard')
                ->with('error', 'غير مصرح لك بالدخول - هذه الصفحة مخصصة للمصنع فقط');
        }

        try {
            $orders = Order::with('user', 'items')
                ->where('sent_to_factory', 1)
                ->latest()
                ->paginate(20);

            return view('factory.orders', ['orders' => $orders]);
        } catch (\Exception $e) {
            Log::error('Error in factoryOrders: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ في تحميل طلبيات المصنع');
        }
    }

    /**
     * تحديث حالة الطلبية (للمصنع) + إرسال إشعار للمديرين
     */
    public function updateOrderStatus(Request $request, $id)
    {
        if (Auth::user()->role != 'factory' && Auth::user()->role != 'super_admin') {
            return back()->with('error', 'غير مصرح لك بهذه العملية');
        }

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;

        $validated = $request->validate([
            'status' => 'required|in:جديدة,تحت التحميل,تم التحميل,مؤجلة,ملغية',
            'factory_notes' => 'nullable|string|max:500'
        ]);

        $order->status = $validated['status'];
        $order->factory_notes = $validated['factory_notes'] ?? null;
        $order->save();

        // تسجيل التاريخ
        if (class_exists(\App\Models\OrderStatusHistory::class)) {
            try {
                \App\Models\OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => $validated['status'],
                    'notes' => $validated['factory_notes'] ?? null,
                    'user_id' => Auth::id(),
                ]);
            } catch (\Exception $e) {
                Log::warning('Could not save status history: ' . $e->getMessage());
            }
        }

        // إرسال إشعار للمديرين عند تغيير الحالة
        if ($oldStatus != $validated['status']) {
            try {
                $admins = \App\Models\User::whereIn('role', ['super_admin', 'sales_manager'])->get();
                foreach ($admins as $admin) {
                    try {
                        $admin->notify(new \App\Notifications\OrderStatusChangedNotification($order, $oldStatus));
                    } catch (\Exception $e) {
                        Log::warning('Failed to send notification to admin: ' . $e->getMessage());
                    }
                }
                Log::info('📧 تم إرسال إشعار للمديرين عن تغيير حالة الطلبية رقم ' . $order->id . ' إلى ' . $validated['status']);
            } catch (\Exception $e) {
                Log::warning('Notification system error: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'تم تحديث حالة الطلبية بنجاح');
    }

    /**
     * إرسال الطلبية للمصنع (للمدير العام ومدير المبيعات)
     */
    public function sendToFactory($id)
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'sales_manager'])) {
            return back()->with('error', 'غير مصرح لك بهذه العملية');
        }

        $order = Order::findOrFail($id);

        $order->sent_to_factory = 1;
        $order->sent_to_factory_at = now();
        $order->status = 'مرسلة للمصنع';
        $order->save();

        return back()->with('success', 'تم إرسال الطلبية للمصنع بنجاح');
    }
}
