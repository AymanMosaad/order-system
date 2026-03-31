<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
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

    // إضافة Route إرسال للمصنع
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
    });
});

// ===========================
// لوحة تحكم المدير
// ===========================
Route::middleware(['auth', 'admin'])->get('/admin/dashboard', [OrderController::class, 'adminDashboard'])->name('admin.dashboard');

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
