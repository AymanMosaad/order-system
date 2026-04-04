<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'item_code',
        'type',
        'name',
        'color',
        'size',
        'price',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function stock(): HasOne
    {
        return $this->hasOne(ProductStock::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getCurrentStock(): float
    {
        return (float) ($this->stock?->current_stock ?? 0.0);
    }

    public function isLowStock(): bool
    {
        $min = (int) ($this->stock?->min_stock ?? 50);
        return $this->getCurrentStock() < $min;
    }

    public function decreaseStock(float|int $quantity): bool
    {
        if (!$this->stock) {
            return false;
        }
        return $this->stock->decreaseStock($quantity);
    }

    public function increaseStock(float|int $quantity): bool
    {
        $this->ensureStockRow();
        return $this->stock->increaseStock($quantity);
    }

    public function setStockOnRelation(float|int $quantity): void
    {
        $this->ensureStockRow();
        if (method_exists($this->stock, 'setStock')) {
            $this->stock->setStock($quantity);
        } else {
            $this->stock->update(['current_stock' => (float) $quantity]);
        }
    }

    public function getFullNameAttribute(): string
    {
        $parts = array_filter([
            $this->name,
            $this->color,
            $this->size ? "({$this->size})" : null,
        ]);
        return implode(' - ', $parts);
    }

    protected function ensureStockRow(): void
    {
        if (!$this->stock) {
            $this->stock()->create([
                'current_stock' => 0,
                'min_stock'     => 50,
            ]);
            $this->load('stock');
        }
    }

    // ========================
    // دوال التحذير من المخزون المنخفض
    // ========================

    /**
     * التحقق من المخزون المنخفض وإرسال إشعار (مع منع التكرار)
     */
    public function checkAndNotifyLowStock(): void
    {
        if ($this->isLowStock()) {
            // إرسال إشعار للمديرين
            $admins = \App\Models\User::whereIn('role', ['super_admin', 'sales_manager'])->get();

            foreach ($admins as $admin) {
                // منع تكرار الإشعارات (مرة كل 24 ساعة)
                $lastNotification = $admin->notifications()
                    ->where('type', 'App\Notifications\LowStockNotification')
                    ->where('data->product_id', $this->id)
                    ->where('created_at', '>=', now()->subHours(24))
                    ->first();

                if (!$lastNotification) {
                    try {
                        $admin->notify(new \App\Notifications\LowStockNotification($this));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('فشل إرسال إشعار للمدير: ' . $e->getMessage());
                    }
                }
            }

            // تسجيل في جدول stock_alerts
            if (class_exists(\App\Models\StockAlert::class)) {
                try {
                    \App\Models\StockAlert::updateOrCreate(
                        ['product_id' => $this->id, 'is_resolved' => false],
                        [
                            'current_stock' => $this->getCurrentStock(),
                            'min_stock' => $this->stock?->min_stock ?? 50,
                        ]
                    );
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('فشل تسجيل التحذير: ' . $e->getMessage());
                }
            }

            \Illuminate\Support\Facades\Log::info('⚠️ تم إرسال تحذير للمخزون المنخفض للصنف: ' . $this->name . ' (الرصيد: ' . $this->getCurrentStock() . ')');
        }
    }

    /**
     * التحقق من جميع الأصناف وإرسال إشعارات (مع منع التشغيل المتكرر)
     */
    public static function checkAllProductsLowStock(): void
    {
        // ✅ منع التشغيل المتكرر (مرة كل ساعة)
        $cacheKey = 'low_stock_check_last_run';
        $lastRun = Cache::get($cacheKey);

        if ($lastRun && $lastRun > now()->subHour()) {
            \Illuminate\Support\Facades\Log::info('تم تخطي فحص المخزون المنخفض (آخر فحص كان قبل أقل من ساعة)');
            return;
        }

        Cache::put($cacheKey, now(), 3600); // تخزين لمدة ساعة

        try {
            $products = self::with('stock')
                ->where('is_active', true)
                ->get();

            $count = 0;
            foreach ($products as $product) {
                if ($product->isLowStock()) {
                    $product->checkAndNotifyLowStock();
                    $count++;
                }
            }

            \Illuminate\Support\Facades\Log::info('✅ تم فحص المخزون المنخفض لـ ' . $products->count() . ' صنف، تم إرسال ' . $count . ' تحذير');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('خطأ في فحص المخزون المنخفض: ' . $e->getMessage());
        }
    }
}
