<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cheque;
use App\Models\Withdrawal;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    /**
     * عرض لوحة تحكم المحاسب
     */
    public function dashboard()
    {
        if (!in_array(Auth::user()->role, ['accountant', 'super_admin', 'sales_manager'])) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }

        $totalCustomers = Customer::count();
        $totalWithdrawals = Withdrawal::sum('amount');
        $pendingCheques = Cheque::where('status', 'pending')->sum('amount');
        $totalOrders = Order::count();

        $recentOrders = Order::with('customer')->latest()->take(10)->get();

        $topCustomers = Customer::withSum('withdrawals', 'amount')
            ->orderBy('withdrawals_sum_amount', 'desc')
            ->take(5)
            ->get();

        return view('accounting.dashboard', compact(
            'totalCustomers', 'totalWithdrawals', 'pendingCheques', 'totalOrders',
            'recentOrders', 'topCustomers'
        ));
    }

    /**
     * عرض قائمة العملاء (للمحاسب) مع فلترة
     */
    public function customers(Request $request)
    {
        if (!in_array(Auth::user()->role, ['accountant', 'super_admin', 'sales_manager'])) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }

        $query = Customer::query();

        if ($request->customer_name) {
            $query->where('name', 'like', '%' . $request->customer_name . '%');
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->size) {
            $query->whereHas('orders.items.product', function($q) use ($request) {
                $q->where('size', 'like', '%' . $request->size . '%');
            });
        }

        $customers = $query->withSum('withdrawals', 'amount')
            ->orderBy('name')
            ->paginate(50);

        foreach ($customers as $customer) {
            $customer->total_withdrawals = $customer->withdrawals_sum_amount ?? 0;
        }

        $totalCustomers = Customer::count();
        $totalWithdrawals = Withdrawal::sum('amount');
        $totalAgents = Customer::where('type', 'agent')->count();
        $totalDealers = Customer::where('type', 'dealer')->count();

        $sizes = Product::whereNotNull('size')
            ->where('size', '!=', '')
            ->distinct('size')
            ->pluck('size')
            ->filter()
            ->sort()
            ->values();

        return view('accounting.customers', compact('customers', 'totalCustomers', 'totalWithdrawals', 'totalAgents', 'totalDealers', 'sizes'));
    }

    /**
     * عرض تفاصيل عميل معين وكشف حساب مع خصم الإذن
     */
    public function customerStatement($id)
    {
        if (!in_array(Auth::user()->role, ['accountant', 'super_admin', 'sales_manager'])) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }

        $customer = Customer::with([
            'withdrawals' => function($q) {
                $q->orderBy('date', 'desc');
            },
            'orders' => function($q) {
                $q->with(['items' => function($iq) {
                    $iq->with('product');
                }])->orderBy('date', 'desc');
            }
        ])->findOrFail($id);

        $totalOrders = $customer->orders->count();
        $totalQuantity = 0;
        $totalAmount = 0;
        $totalReturns = 0;
        $totalSamples = 0;

        foreach ($customer->orders as $order) {
            foreach ($order->items as $item) {
                $quantity = $item->grade1;
                $unitPrice = $item->unit_price;
                $itemTotal = $quantity * $unitPrice;

                if ($order->order_discount > 0) {
                    $itemTotal = $itemTotal - ($itemTotal * $order->order_discount / 100);
                }

                if ($item->transaction_type == 'return') {
                    $totalReturns += abs($itemTotal);
                } elseif ($item->transaction_type == 'sample') {
                    $totalSamples += abs($itemTotal);
                }

                $totalQuantity += $quantity;
                $totalAmount += $itemTotal;
            }
        }

        return view('accounting.customer_statement', compact('customer', 'totalOrders', 'totalQuantity', 'totalAmount', 'totalReturns', 'totalSamples'));
    }

    /**
     * عرض مسحوبات عميل معين (صفحة منفصلة)
     */
    public function customerWithdrawals($id)
    {
        if (!in_array(Auth::user()->role, ['accountant', 'super_admin', 'sales_manager'])) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }

        $customer = Customer::with([
            'orders' => function($q) {
                $q->with(['items' => function($iq) {
                    $iq->with('product');
                }])->orderBy('date', 'desc');
            }
        ])->findOrFail($id);

        $withdrawals = [];

        foreach ($customer->orders as $order) {
            foreach ($order->items as $item) {
                $withdrawals[] = (object) [
                    'date' => $order->date,
                    'order_number' => $order->order_number,
                    'item_code' => $item->item_code,
                    'item_name' => $item->name,
                    'product_type' => $item->product->type ?? '',
                    'product_group' => $item->product->group ?? '',
                    'grade' => $item->product->grade ?? '',
                    'size' => $item->product->size ?? '',
                    'model' => $item->product->model ?? '',
                    'color' => $item->product->color ?? '',
                    'quantity' => $item->grade1,
                    'unit_price' => $item->unit_price,
                    'total' => $item->total,
                    'transaction_type' => $item->transaction_type,
                ];
            }
        }

        $totalAmount = $customer->withdrawals->sum('amount') ?? 0;
        $totalQuantity = collect($withdrawals)->sum('quantity');

        return view('accounting.customer_withdrawals', compact('customer', 'withdrawals', 'totalAmount', 'totalQuantity'));
    }

    /**
     * تحديث نسبة خصم العميل
     */
    public function updateDiscount(Request $request, $id)
    {
        if (!in_array(Auth::user()->role, ['accountant', 'super_admin', 'sales_manager'])) {
            return back()->with('error', 'غير مصرح لك');
        }

        $customer = Customer::findOrFail($id);
        $oldDiscountRate = $customer->discount_rate;
        $customer->discount_rate = $request->discount_rate;
        $customer->save();

        foreach ($customer->orders as $order) {
            foreach ($order->items as $item) {
                $price = $item->product->price ?? 0;
                $discount = ($customer->discount_rate / 100) * $price;
                $unitPrice = $price - $discount;
                $item->unit_price = $unitPrice;
                $item->discount_rate = $customer->discount_rate;
                $item->discount_amount = $discount * $item->grade1;

                $total = $item->grade1 * $unitPrice;
                if ($item->transaction_type == 'return' || $item->transaction_type == 'sample') {
                    $total = -abs($total);
                }
                $item->total = $total;
                $item->save();
            }
        }

        foreach ($customer->withdrawals as $withdrawal) {
            $order = $customer->orders->where('id', $withdrawal->order_id)->first();
            if ($order) {
                $totalAmount = 0;
                foreach ($order->items as $item) {
                    $itemTotal = $item->grade1 * $item->unit_price;
                    if ($order->order_discount > 0) {
                        $itemTotal = $itemTotal - ($itemTotal * $order->order_discount / 100);
                    }
                    $totalAmount += $itemTotal;
                }
                $withdrawal->amount = $totalAmount;
                $withdrawal->save();
            }
        }

        return back()->with('success', 'تم تحديث نسبة الخصم من ' . $oldDiscountRate . '% إلى ' . $customer->discount_rate . '%');
    }

    /**
     * تحديث سعر منتج للعميل (من صفحة كشف الحساب)
     */
    public function updateItemPrice(Request $request, $id)
    {
        if (!in_array(Auth::user()->role, ['accountant', 'super_admin', 'sales_manager'])) {
            return back()->with('error', 'غير مصرح لك');
        }

        $item = OrderItem::findOrFail($id);
        $oldPrice = $item->unit_price;
        $item->unit_price = $request->price;

        $total = $item->grade1 * $request->price;
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

        return back()->with('success', 'تم تحديث السعر من ' . number_format($oldPrice, 2) . ' إلى ' . number_format($request->price, 2));
    }

    /**
     * تحديث خصم الإذن
     */
    public function updateOrderDiscount(Request $request, $id)
    {
        if (!in_array(Auth::user()->role, ['accountant', 'super_admin', 'sales_manager'])) {
            return back()->with('error', 'غير مصرح لك');
        }

        $order = Order::findOrFail($id);
        $oldDiscount = $order->order_discount;
        $order->order_discount = $request->order_discount;
        $order->save();

        $totalAmount = 0;
        foreach ($order->items as $item) {
            $itemTotal = $item->grade1 * $item->unit_price;
            if ($order->order_discount > 0) {
                $itemTotal = $itemTotal - ($itemTotal * $order->order_discount / 100);
            }
            $totalAmount += $itemTotal;
        }

        Withdrawal::where('order_id', $order->id)->update(['amount' => $totalAmount]);

        return back()->with('success', 'تم تحديث خصم الإذن من ' . $oldDiscount . '% إلى ' . $order->order_discount . '%');
    }

    /**
     * تحديث جميع الأسعار دفعة واحدة
     */
    public function updateAllPrices(Request $request, $id)
    {
        if (!in_array(Auth::user()->role, ['accountant', 'super_admin', 'sales_manager'])) {
            return response()->json(['success' => false, 'message' => 'غير مصرح لك'], 403);
        }

        $prices = $request->input('prices', []);
        $discounts = $request->input('discounts', []);

        try {
            DB::beginTransaction();

            // تحديث الأسعار
            foreach ($prices as $itemId => $price) {
                $item = OrderItem::find($itemId);
                if ($item) {
                    $item->unit_price = $price;
                    $total = $item->grade1 * $price;
                    if ($item->transaction_type == 'return' || $item->transaction_type == 'sample') {
                        $total = -abs($total);
                    }
                    $item->total = $total;
                    $item->save();
                }
            }

            // تحديث خصومات الإذن
            foreach ($discounts as $orderId => $discount) {
                $order = Order::find($orderId);
                if ($order) {
                    $order->order_discount = $discount;
                    $order->save();

                    // إعادة حساب إجمالي الطلبية
                    $totalAmount = 0;
                    foreach ($order->items as $item) {
                        $itemTotal = $item->grade1 * $item->unit_price;
                        if ($order->order_discount > 0) {
                            $itemTotal = $itemTotal - ($itemTotal * $order->order_discount / 100);
                        }
                        $totalAmount += $itemTotal;
                    }
                    Withdrawal::where('order_id', $order->id)->update(['amount' => $totalAmount]);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'تم تحديث جميع الأسعار بنجاح']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * عرض تقرير الشيكات
     */
    public function cheques(Request $request)
    {
        if (!in_array(Auth::user()->role, ['accountant', 'super_admin', 'sales_manager'])) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }

        $query = Cheque::with('customer')->orderBy('due_date', 'asc');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->from_date) {
            $query->whereDate('due_date', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('due_date', '<=', $request->to_date);
        }

        $cheques = $query->paginate(20);

        $pendingTotal = Cheque::where('status', 'pending')->sum('amount');
        $collectedTotal = Cheque::where('status', 'collected')->sum('amount');
        $returnedTotal = Cheque::where('status', 'returned')->sum('amount');

        return view('accounting.cheques', compact('cheques', 'pendingTotal', 'collectedTotal', 'returnedTotal'));
    }

    /**
     * تسجيل شيك جديد
     */
    public function storeCheque(Request $request)
    {
        if (!in_array(Auth::user()->role, ['accountant', 'super_admin', 'sales_manager'])) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'cheque_number' => 'required|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'order_id' => 'nullable|exists:orders,id',
            'notes' => 'nullable|string',
        ]);

        $cheque = Cheque::create([
            'customer_id' => $validated['customer_id'],
            'order_id' => $validated['order_id'] ?? null,
            'cheque_number' => $validated['cheque_number'],
            'bank_name' => $validated['bank_name'] ?? null,
            'amount' => $validated['amount'],
            'issue_date' => $validated['issue_date'],
            'due_date' => $validated['due_date'],
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('accounting.cheques')->with('success', 'تم تسجيل الشيك بنجاح');
    }

    /**
     * تحديث حالة الشيك (تحصيل/إرجاع)
     */
    public function updateChequeStatus($id, Request $request)
    {
        if (!in_array(Auth::user()->role, ['accountant', 'super_admin', 'sales_manager'])) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }

        $cheque = Cheque::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:collected,returned,cancelled',
            'notes' => 'nullable|string',
        ]);

        $cheque->status = $validated['status'];
        $cheque->notes = $validated['notes'] ?? $cheque->notes;

        if ($validated['status'] == 'collected') {
            $cheque->collected_at = now();
        }

        $cheque->save();

        $statusText = [
            'collected' => 'تم التحصيل',
            'returned' => 'تم الإرجاع',
            'cancelled' => 'تم الإلغاء',
        ];

        return redirect()->route('accounting.cheques')->with('success', 'تم تحديث حالة الشيك: ' . ($statusText[$validated['status']] ?? $validated['status']));
    }
}
