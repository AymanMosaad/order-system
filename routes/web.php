<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Auth;

// ===========================
// Auth Routes
// ===========================
Route::middleware('guest')->group(function () {
    Route::get('login', fn() => view('auth.login'))->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('register', fn() => view('auth.register'))->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout.get');
});

// ===========================
// الصفحة الرئيسية
// ===========================
Route::get('/', function () {
    if (Auth::check()) {
        if (in_array(Auth::user()->role, ['super_admin', 'sales_manager'])) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('orders.userDashboard');
    }
    return view('home');
})->name('home');

// ===========================
// الطلبيات
// ===========================
Route::middleware('auth')->prefix('orders')->name('orders.')->group(function() {
    Route::get('user-dashboard', [OrderController::class, 'userDashboard'])->name('userDashboard');
    Route::get('create', [OrderController::class, 'create'])->name('create');
    Route::post('store', [OrderController::class, 'store'])->name('store');
    Route::get('show/{id}', [OrderController::class, 'show'])->name('show');
    Route::get('edit/{id}', [OrderController::class, 'edit'])->name('edit');
    Route::put('update/{id}', [OrderController::class, 'update'])->name('update');
    Route::delete('delete/{id}', [OrderController::class, 'destroy'])->name('destroy');

    // طباعة الفاتورة
    Route::get('invoice/{order}', [OrderController::class, 'printInvoice'])->name('invoice');

    // إرسال للمصنع
    Route::post('send-to-factory/{id}', [OrderController::class, 'sendToFactory'])->name('sendToFactory');

    Route::middleware('admin')->group(function() {
        Route::get('index', [OrderController::class, 'index'])->name('index');
        Route::get('report', [OrderController::class, 'frontPage'])->name('report');
        Route::get('advanced-report', [OrderController::class, 'advancedReport'])->name('advancedReport');
    });
});

// ===========================
// الأصناف
// ===========================
Route::prefix('products')->name('products.')->group(function() {
    Route::get('get-by-name/{name}', [ProductController::class, 'getByName'])->name('getByName');
});

Route::middleware('auth')->prefix('products')->name('products.')->group(function() {
    Route::get('show/{id}', [ProductController::class, 'show'])->name('show');

    Route::middleware('admin')->group(function() {
        Route::get('index', [ProductController::class, 'index'])->name('index');
        Route::get('report', [ProductController::class, 'report'])->name('report');
        Route::get('create', [ProductController::class, 'create'])->name('create');
        Route::post('store', [ProductController::class, 'store'])->name('store');
        Route::get('{id}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('{id}/update', [ProductController::class, 'update'])->name('update');
        Route::delete('{id}/delete', [ProductController::class, 'destroy'])->name('destroy');
        Route::post('{id}/adjust-stock', [ProductController::class, 'adjustStock'])->name('adjustStock');
        Route::get('import-page', [ProductController::class, 'importPage'])->name('importPage');
        Route::post('import', [ProductController::class, 'import'])->name('import');
        Route::get('download-template', [ProductController::class, 'downloadTemplate'])->name('downloadTemplate');
        Route::get('stock-report', [ProductController::class, 'stockReport'])->name('stockReport');
        Route::get('grade-report', [ProductController::class, 'gradeReport'])->name('gradeReport');

        // ===== تقارير حركة الأصناف =====
        Route::get('movement-report', [ProductController::class, 'movementReport'])->name('movementReport');
        Route::get('sales-summary', [ProductController::class, 'salesSummary'])->name('salesSummary');

        // ===== لوحة المخزون =====
        Route::get('stock-dashboard', [ProductController::class, 'stockDashboard'])->name('stockDashboard');
    });
});

// ===== تصدير الأصناف المنخفضة (خارج المجموعة) =====
Route::middleware(['auth', 'admin'])->get('/products/export-low-stock-excel', [App\Http\Controllers\ProductController::class, 'exportLowStockExcel'])->name('exportLowStockExcel');
Route::middleware(['auth', 'admin'])->get('/products/export-low-stock-pdf', [App\Http\Controllers\ProductController::class, 'exportLowStockPdf'])->name('exportLowStockPdf');

// ===========================
// لوحة تحكم المدير
// ===========================
Route::middleware(['auth', 'admin'])->get('/admin/dashboard', [OrderController::class, 'adminDashboard'])->name('admin.dashboard');

// ===========================
// فحص المخزون المنخفض (يدوي)
// ===========================
Route::middleware(['auth', 'admin'])->get('/stock/check-low', function() {
    try {
        \App\Models\Product::checkAllProductsLowStock();

        $lowStockCount = \App\Models\Product::with('stock')
            ->where('is_active', true)
            ->get()
            ->filter(fn($p) => $p->isLowStock())
            ->count();

        if ($lowStockCount > 0) {
            return redirect()->route('admin.dashboard')
                ->with('success', "✅ تم فحص المخزون المنخفض. تم إرسال إشعارات لـ {$lowStockCount} صنف منخفض.");
        } else {
            return redirect()->route('admin.dashboard')
                ->with('info', "✅ تم فحص المخزون المنخفض. لا توجد أصناف منخفضة.");
        }
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('خطأ في فحص المخزون: ' . $e->getMessage());
        return redirect()->route('admin.dashboard')
            ->with('error', '❌ حدث خطأ أثناء فحص المخزون: ' . $e->getMessage());
    }
})->name('stock.check.low');

// ===========================
// Routes المصنع
// ===========================
Route::middleware(['auth'])->prefix('factory')->name('factory.')->group(function() {
    Route::get('/orders', [OrderController::class, 'factoryOrders'])->name('orders');
    Route::post('/order/{id}/status', [OrderController::class, 'updateOrderStatus'])->name('updateStatus');
});

// ===========================
// Routes إدارة المستخدمين (للمدير العام فقط)
// ===========================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function() {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

// ===========================
// إدارة المنتجات للمدير (صفحة بسيطة لتعديل الأسعار)
// ===========================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function() {
    Route::get('/products', [ProductController::class, 'adminProducts'])->name('products');
    Route::put('/products/{id}', [ProductController::class, 'updateProduct'])->name('products.update');
});

// ===========================
// Routes الإشعارات
// ===========================
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function() {
    Route::get('/', function() {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        return view('notifications.index', ['notifications' => $notifications]);
    })->name('index');

    Route::post('/mark-read', function() {
        $notification = auth()->user()->notifications()->where('id', request('id'))->first();
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    })->name('markRead');

    Route::post('/mark-all-read', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'تم تحديد جميع الإشعارات كمقروءة');
    })->name('markAllRead');
});

// ===========================
// Routes المحاسبة
// ===========================
Route::middleware(['auth'])->prefix('accounting')->name('accounting.')->group(function() {
    Route::get('/dashboard', [AccountingController::class, 'dashboard'])->name('dashboard');
    Route::get('/customers', [AccountingController::class, 'customers'])->name('customers');
    Route::get('/customer-statement/{id}', [AccountingController::class, 'customerStatement'])->name('customer.statement');
    Route::get('/customer-withdrawals/{id}', [AccountingController::class, 'customerWithdrawals'])->name('customer.withdrawals');
    Route::get('/cheques', [AccountingController::class, 'cheques'])->name('cheques');
    Route::post('/cheques', [AccountingController::class, 'storeCheque'])->name('cheques.store');
    Route::put('/cheques/{id}/status', [AccountingController::class, 'updateChequeStatus'])->name('cheques.update-status');
    // تحديث نسبة خصم العميل
    Route::put('/update-discount/{id}', [AccountingController::class, 'updateDiscount'])->name('updateDiscount');
    // تحديث سعر منتج في كشف الحساب
    Route::put('/update-item-price/{id}', [AccountingController::class, 'updateItemPrice'])->name('updateItemPrice');
});

// ===========================
// Routes استيراد المسحوبات (للمدير العام فقط)
// ===========================
Route::middleware(['auth', 'admin'])->prefix('import')->name('import.')->group(function() {
    Route::get('/withdrawals', [ImportController::class, 'showForm'])->name('withdrawals.form');
    Route::post('/withdrawals', [ImportController::class, 'importWithdrawals'])->name('withdrawals');
});

// تحديث السعر والخصم في المسحوبات
Route::put('/withdrawals/update-price/{id}', [ImportController::class, 'updatePrice'])->name('import.withdrawals.updatePrice');
Route::put('/withdrawals/update-discount/{id}', [ImportController::class, 'updateDiscount'])->name('import.withdrawals.updateDiscount');

// تحديث نسبة خصم العميل
Route::put('/accounting/update-discount/{id}', [AccountingController::class, 'updateDiscount'])->name('accounting.updateDiscount');

// تحديث سعر منتج في كشف الحساب
Route::put('/accounting/update-item-price/{id}', [AccountingController::class, 'updateItemPrice'])->name('accounting.updateItemPrice');


// تحديث خصم الإذن
Route::put('/accounting/update-order-discount/{id}', [AccountingController::class, 'updateOrderDiscount'])->name('accounting.updateOrderDiscount');

// تحديث جميع الأسعار دفعة واحدة
Route::put('/accounting/update-all-prices/{id}', [AccountingController::class, 'updateAllPrices'])->name('accounting.updateAllPrices');

// تحديث جميع الأسعار دفعة واحدة


// ===========================
// تغيير كلمة المرور
// ===========================
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function() {
    Route::get('/password', [App\Http\Controllers\ProfileController::class, 'editPassword'])->name('password.edit');
    Route::put('/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password.update');
});
