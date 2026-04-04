<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schedule;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use App\Models\Product;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // قراءة عناوين الأعمدة كما هي بدون تحويل لـ slug
        HeadingRowFormatter::default('none');

        // ===== جدولة فحص المخزون المنخفض =====
        // التحقق كل 6 ساعات (يمكنك تغيير الفترة)
        if ($this->app->runningInConsole()) {
            Schedule::call(function () {
                \Illuminate\Support\Facades\Log::info('بدء فحص المخزون المنخفض...');
                Product::checkAllProductsLowStock();
            })->everySixHours()->name('check-low-stock');

            // أو كل ساعة:
            // })->hourly();

            // أو كل يوم في الصباح:
            // })->dailyAt('08:00');
        }
    }
}
