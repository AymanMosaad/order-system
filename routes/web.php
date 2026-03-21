<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Auth routes (من Breeze أو Jetstream لو موجود)
require __DIR__.'/auth.php';

// Protected routes (تحتاج login)
Route::middleware(['auth'])->group(function () {

    // Dashboard الافتراضي
    Route::get('/dashboard', function () {
        return view('dashboard'); // أو user_dashboard.blade.php لو عايز تغيره
    })->name('dashboard');

    // Products Routes (CRUD + Import + Report)
    Route::resource('products', ProductController::class);
    Route::get('products/import', [ProductController::class, 'importPage'])->name('products.importPage');
    Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
    Route::get('products/report', [ProductController::class, 'report'])->name('products.report');

    // Orders Routes (CRUD + أي actions إضافية)
    Route::resource('orders', OrderController::class);
    // لو عندك action إضافي زي "complete" أو "deliver" ضيفه هنا بعدين
    // مثال: Route::post('orders/{order}/deliver', [OrderController::class, 'deliver'])->name('orders.deliver');
});
