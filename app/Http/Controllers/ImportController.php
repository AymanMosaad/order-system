<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    /**
     * عرض صفحة استيراد المسحوبات
     */
    public function showForm(Request $request)
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'sales_manager'])) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }

        $query = OrderItem::with(['order.customer', 'product'])
            ->whereHas('order', function($q) {
                $q->whereNotNull('order_number');
            });

        if ($request->customer_name) {
            $query->whereHas('order.customer', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer_name . '%');
            });
        }

        if ($request->item_code) {
            $query->where('item_code', 'like', '%' . $request->item_code . '%');
        }

        if ($request->size) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('size', 'like', '%' . $request->size . '%');
            });
        }

        if ($request->grade) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('grade', $request->grade);
            });
        }

        $withdrawals = $query->orderBy('created_at', 'desc')->paginate(50);
        $totalWithdrawals = $query->sum('total');
        $customersCount = $query->distinct('order_id')->count('order_id');
        $itemsCount = $query->count();

        return view('import.withdrawals', compact('withdrawals', 'totalWithdrawals', 'customersCount', 'itemsCount'));
    }

    /**
     * استيراد المسحوبات من ملف Excel
     */
    public function importWithdrawals(Request $request)
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'sales_manager'])) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');
        $data = Excel::toArray([], $file);
        $rows = $data[0] ?? [];

        $stats = [
            'customers' => 0,
            'orders' => 0,
            'items' => 0,
            'withdrawals' => 0,
            'products_created' => 0,
            'products_updated' => 0,
            'errors' => 0,
            'error_list' => []
        ];

        foreach ($rows as $index => $row) {
            if ($index == 0) continue;

            try {
                $nature = trim($row[0] ?? 'صرف مبيعات');
                $orderNumber = trim($row[1] ?? '');
                $date = $row[2] ?? date('Y-m-d');
                $customerCode = trim($row[3] ?? '');
                $customerName = trim($row[4] ?? '');
                $itemCode = trim($row[5] ?? '');
                $itemName = trim($row[6] ?? '');
                $unit = trim($row[7] ?? 'متر');
                $quantity = (float) ($row[8] ?? 0);
                $value = (float) ($row[9] ?? 0);

                $productType = trim($row[10] ?? '');
                $productGroup = trim($row[11] ?? '');
                $grade = trim($row[12] ?? '');
                $size = trim($row[13] ?? '');
                $model = trim($row[14] ?? '');
                $color = trim($row[15] ?? '');
                $warehouse = trim($row[16] ?? '');

                // تحديد نوع العملية
                $transactionType = 'sale'; // افتراضي صرف
                if (str_contains($nature, 'ارتجاع')) {
                    $transactionType = 'return';
                } elseif (str_contains($nature, 'عينة')) {
                    $transactionType = 'sample';
                }

                if (empty($customerName) || empty($itemCode) || $quantity <= 0) {
                    $stats['errors']++;
                    $stats['error_list'][] = "الصف " . ($index + 1) . ": بيانات ناقصة";
                    continue;
                }

                $customer = Customer::where('name', $customerName)->first();
                if (!$customer) {
                    $customer = Customer::create([
                        'name' => $customerName,
                        'code' => $customerCode ?: null,
                        'type' => $this->getCustomerType($nature),
                        'discount_rate' => 0,
                    ]);
                    $stats['customers']++;
                }

                $product = Product::where('item_code', $itemCode)->first();
                if (!$product) {
                    $fullName = $itemName;
                    if ($grade) $fullName .= " - فرز {$grade}";
                    if ($size) $fullName .= " - {$size}";
                    if ($color) $fullName .= " - {$color}";

                    $product = Product::create([
                        'item_code' => $itemCode,
                        'name' => $fullName,
                        'type' => $productType ?: ($productGroup ?? 'سيراميك'),
                        'group' => $productGroup,
                        'grade' => $grade,
                        'size' => $size,
                        'model' => $model,
                        'color' => $color,
                        'price' => 0,
                        'is_active' => true,
                    ]);

                    ProductStock::firstOrCreate(
                        ['product_id' => $product->id],
                        ['current_stock' => 0, 'min_stock' => 50]
                    );

                    $stats['products_created']++;
                } else {
                    $needsUpdate = false;
                    if ($product->type != ($productType ?: ($productGroup ?? 'سيراميك'))) $needsUpdate = true;
                    if ($product->grade != $grade) $needsUpdate = true;
                    if ($product->size != $size) $needsUpdate = true;
                    if ($product->model != $model) $needsUpdate = true;
                    if ($product->color != $color) $needsUpdate = true;

                    if ($needsUpdate) {
                        $product->update([
                            'type' => $productType ?: ($productGroup ?? $product->type),
                            'group' => $productGroup ?? $product->group,
                            'grade' => $grade ?? $product->grade,
                            'size' => $size ?? $product->size,
                            'model' => $model ?? $product->model,
                            'color' => $color ?? $product->color,
                        ]);
                        $stats['products_updated']++;
                    }
                }

                $order = Order::where('order_number', $orderNumber)->first();
                if (!$order) {
                    $order = Order::create([
                        'order_number' => $orderNumber,
                        'customer_id' => $customer->id,
                        'customer_name' => $customerName,
                        'date' => date('Y-m-d', strtotime($date)),
                        'status' => 'مكتملة',
                        'notes' => $nature,
                    ]);
                    $stats['orders']++;
                }

                $price = $product->price ?? 0;
                $discountRate = $customer->discount_rate ?? 0;
                $discount = ($discountRate / 100) * $price;
                $unitPrice = $price - $discount;
                $total = $quantity * $unitPrice;

                // إذا كانت العملية إرتجاع أو عينة، يكون الإجمالي سالب
                if ($transactionType == 'return' || $transactionType == 'sample') {
                    $total = -abs($total);
                }

                OrderItem::updateOrCreate(
                    ['order_id' => $order->id, 'product_id' => $product->id],
                    [
                        'item_code' => $itemCode,
                        'name' => $itemName,
                        'grade1' => $quantity,
                        'unit_price' => $unitPrice,
                        'discount_rate' => $discountRate,
                        'discount_amount' => $discount * $quantity,
                        'total' => $total,
                        'transaction_type' => $transactionType,
                    ]
                );
                $stats['items']++;

                Withdrawal::create([
                    'customer_id' => $customer->id,
                    'order_id' => $order->id,
                    'amount' => $total,
                    'date' => date('Y-m-d', strtotime($date)),
                    'notes' => $nature,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $stats['withdrawals']++;

            } catch (\Exception $e) {
                $stats['errors']++;
                $stats['error_list'][] = "الصف " . ($index + 1) . ": " . $e->getMessage();
            }
        }

        return redirect()->route('import.withdrawals.form')
            ->with('success', "✅ تم الاستيراد بنجاح!\n📊 العملاء الجدد: {$stats['customers']}\n📦 الطلبيات الجديدة: {$stats['orders']}\n📦 العناصر المضافة: {$stats['items']}\n💰 المسحوبات المسجلة: {$stats['withdrawals']}\n🆕 منتجات جديدة: {$stats['products_created']}")
            ->with('import_errors', $stats['error_list']);
    }

    public function updatePrice(Request $request, $id)
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'sales_manager'])) {
            return back()->with('error', 'غير مصرح لك');
        }

        $item = OrderItem::findOrFail($id);
        $oldPrice = $item->unit_price;
        $item->unit_price = $request->price;

        // حساب الإجمالي مع مراعاة نوع العملية
        $total = $item->grade1 * $request->price;
        if ($item->transaction_type == 'return' || $item->transaction_type == 'sample') {
            $total = -abs($total);
        }
        $item->total = $total;
        $item->save();

        // تحديث المسحوبات
        $order = $item->order;
        $totalAmount = 0;
        foreach ($order->items as $oi) {
            $itemTotal = $oi->grade1 * $oi->unit_price;
            if ($order->order_discount > 0) {
                $itemTotal = $itemTotal - ($itemTotal * $order->order_discount / 100);
            }
            $totalAmount += $itemTotal;
        }
        Withdrawal::where('order_id', $item->order_id)->update(['amount' => $totalAmount]);

        return back()->with('success', 'تم تحديث السعر من ' . number_format($oldPrice, 2) . ' إلى ' . number_format($request->price, 2));
    }

    public function updateDiscount(Request $request, $id)
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'sales_manager'])) {
            return back()->with('error', 'غير مصرح لك');
        }

        $item = OrderItem::findOrFail($id);
        $item->discount_rate = $request->discount_rate;
        $discount = ($request->discount_rate / 100) * $item->unit_price;
        $item->discount_amount = $discount * $item->grade1;

        $total = ($item->unit_price * $item->grade1) - $item->discount_amount;
        if ($item->transaction_type == 'return' || $item->transaction_type == 'sample') {
            $total = -abs($total);
        }
        $item->total = $total;
        $item->save();

        $order = $item->order;
        $totalAmount = 0;
        foreach ($order->items as $oi) {
            $itemTotal = $oi->grade1 * $oi->unit_price;
            if ($order->order_discount > 0) {
                $itemTotal = $itemTotal - ($itemTotal * $order->order_discount / 100);
            }
            $totalAmount += $itemTotal;
        }
        Withdrawal::where('order_id', $item->order_id)->update(['amount' => $totalAmount]);

        return back()->with('success', 'تم تحديث الخصم بنجاح');
    }

    private function getCustomerType($nature)
    {
        if (str_contains($nature, 'تصدير')) return 'agent';
        if (str_contains($nature, 'محليه')) return 'dealer';
        return 'cash';
    }
}
