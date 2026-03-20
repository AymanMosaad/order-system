<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Product
 *
 * الخصائص الشائعة:
 * - item_code (string)  : مُعرّف الصنف
 * - type      (string)  : نوع الصنف
 * - name      (string)  : اسم الصنف
 * - color     (string?) : اللون
 * - size      (string?) : المقاس
 * - is_active (bool)    : فعّال/غير فعّال
 */
class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'item_code',
        'type',
        'name',
        'color',
        'size',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ========== العلاقات ==========

    /**
     * رصيد المخزون الخاص بهذا المنتج (سجل واحد)
     */
    public function stock(): HasOne
    {
        // المفتاح الافتراضي foreign key = product_id في جدول product_stocks
        return $this->hasOne(ProductStock::class);
    }

    /**
     * عناصر الطلب المرتبطة بهذا المنتج
     */
    public function orderItems(): HasMany
    {
        // المفتاح الافتراضي foreign key = product_id في جدول order_items
        return $this->hasMany(OrderItem::class);
    }

    // ========== دوال مساعدة (عرض/حساب) ==========

    /**
     * الرصيد الحالي بصيغة عشريّة (يدعم الكسور)
     */
    public function getCurrentStock(): float
    {
        // لو مفيش سجل رصيد، رجّع 0.0
        return (float) ($this->stock?->current_stock ?? 0.0);
    }

    /**
     * هل الرصيد الحالي أقل من الحدّ الأدنى؟
     */
    public function isLowStock(): bool
    {
        $min = (int) ($this->stock?->min_stock ?? 50);
        return $this->getCurrentStock() < $min;
    }

    /**
     * خصم من الرصيد (يدعم العشري). لو مفيش سجلّ رصيد، بيرجّع false.
     */
    public function decreaseStock(float|int $quantity): bool
    {
        if (!$this->stock) {
            return false;
        }
        return $this->stock->decreaseStock($quantity);
    }

    /**
     * زيادة الرصيد (يدعم العشري). لو مفيش سجلّ رصيد، بينشئه أولًا ثم يزود.
     */
    public function increaseStock(float|int $quantity): bool
    {
        $this->ensureStockRow();
        return $this->stock->increaseStock($quantity);
    }

    /**
     * تعيين الرصيد مباشرة على علاقة المخزون.
     * ينشئ سجلّ الرصيد لو مش موجود.
     */
    public function setStockOnRelation(float|int $quantity): void
    {
        $this->ensureStockRow();
        // لو عندك method setStock في ProductStock هيتم استدعاؤها
        if (method_exists($this->stock, 'setStock')) {
            $this->stock->setStock($quantity);
        } else {
            $this->stock->update(['current_stock' => (float) $quantity]);
        }
    }

    /**
     * اسم كامل للعرض (يتفادى الـ null)
     */
    public function getFullNameAttribute(): string
    {
        $parts = array_filter([
            $this->name,
            $this->color,
            $this->size ? "({$this->size})" : null,
        ]);

        return implode(' - ', $parts);
    }

    // ========== أدوات داخلية ==========

    /**
     * تأكيد وجود سجلّ رصيد؛ إن لم يوجد يتم إنشاؤه بقيم افتراضية.
     */
    protected function ensureStockRow(): void
    {
        if (!$this->stock) {
            $this->stock()->create([
                'current_stock' => 0,
                'min_stock'     => 50,
            ]);
            // عمل refresh لتوفّر $this->stock مباشرة
            $this->load('stock');
        }
    }
}
