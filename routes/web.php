<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

// ===========================
// Auth Routes
// ===========================
Route::middleware('guest')->group(function () {
    Route::get('login', fn() => view('auth.login'))->name('login');
    Route::post('login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);

    Route::get('register', fn() => view('auth.register'))->name('register');
    Route::post('register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// ===========================
// الصفحة الرئيسية
// ===========================
Route::get('/', [OrderController::class, 'frontPage'])->name('orders.frontPage');

// ===========================
// الطلبيات - عام (بدون Auth)
// ===========================
Route::prefix('orders')->name('orders.')->group(function() {
    Route::get('index', [OrderController::class, 'index'])->name('index');
    Route::get('show/{id}', [OrderController::class, 'show'])->name('show');
    Route::get('report', [OrderController::class, 'frontPage'])->name('report');
});

// ===========================
// الطلبيات - محمية بـ Auth
// ===========================
Route::middleware('auth')->prefix('orders')->name('orders.')->group(function () {
    // لوحة تحكم المستخدم
    Route::get('user-dashboard', [OrderController::class, 'userDashboard'])->name('userDashboard');

    // إنشاء طلب جديد
    Route::get('create', [OrderController::class, 'create'])->name('create');
    Route::post('store', [OrderController::class, 'store'])->name('store');

    // تعديل طلب
    Route::get('edit/{id}', [OrderController::class, 'edit'])->name('edit');
    Route::post('update/{id}', [OrderController::class, 'update'])->name('update');

    // حذف طلب
    Route::delete('delete/{id}', [OrderController::class, 'destroy'])->name('destroy');
});

// ===========================
// الأصناف - عام (بدون Auth)
// ===========================
Route::prefix('products')->name('products.')->group(function() {
    Route::get('index', [ProductController::class, 'index'])->name('index');
    Route::get('show/{id}', [ProductController::class, 'show'])->name('show');
    Route::get('report', [ProductController::class, 'report'])->name('report');
});

// ===========================
// الأصناف - محمية بـ Auth
// ===========================
Route::middleware('auth')->prefix('products')->name('products.')->group(function() {
    // إنشاء صنف جديد
    Route::get('create', [ProductController::class, 'create'])->name('create');
    Route::post('store', [ProductController::class, 'store'])->name('store');

    // تعديل صنف
    Route::get('{id}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::post('{id}/update', [ProductController::class, 'update'])->name('update');

    // حذف صنف
    Route::delete('{id}/delete', [ProductController::class, 'destroy'])->name('destroy');

    // تعديل الرصيد
    Route::post('{id}/adjust-stock', [ProductController::class, 'adjustStock'])->name('adjustStock');

    // صفحة الاستيراد (GET) + تنفيذ الاستيراد (POST)
    Route::get('import-page', [ProductController::class, 'importPage'])->name('importPage');
    Route::post('import', [ProductController::class, 'import'])->name('import');

    // تحميل القالب
    Route::get('download-template', [ProductController::class, 'downloadTemplate'])->name('downloadTemplate');

    // ✅ حماية إضافية: أي GET على /products/import يرجّعك لصفحة الرفع
    Route::get('import', function () {
        return redirect()->route('products.importPage');
    });

    // ────────────────────────────────────────────────
// تعديلات إضافية / تصحيح للروتات (إذا كانت ناقصة أو تحتاج تحديث)
// يمكن حذفها لو مش محتاجها
// ────────────────────────────────────────────────

Route::middleware('auth')->group(function () {

    // لو الـ resource مش موجودة بالكامل، ممكن نضيفها هنا
    // لكن بما إن عندك روتات يدوية لكل action، ممكن ما نحتاجهاش دلوقتي

    // مثال: لو عايز تضيف action جديد لتغيير حالة الطلبية لاحقًا
    // Route::post('orders/{order}/change-status', [OrderController::class, 'changeStatus'])->name('orders.changeStatus');

});
});
