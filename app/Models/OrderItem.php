<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'item_code',
        'item_code2',
        'item_code3',
        'type',
        'name',
        'color',
        'size',
        'grade1',
        'grade2',
        'grade3',
        'total'
    ];

    protected $casts = [
        'grade1' => 'integer',
        'grade2' => 'integer',
        'grade3' => 'integer',
        'total' => 'integer',
    ];

    // ===== العلاقات =====

    /**
     * الطلبية الخاصة بهذا الصنف
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * المنتج الخاص بهذا الصنف
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ===== دوال مساعدة =====

    /**
     * حساب الإجمالي تلقائي
     */
    public function calculateTotal(): int
    {
        $this->total = ($this->grade1 ?? 0) + ($this->grade2 ?? 0) + ($this->grade3 ?? 0);
        return $this->total;
    }

    /**
     * تحديث الإجمالي قبل الحفظ
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->calculateTotal();
        });
    }

    /**
     * جميع الأكواد الثلاثة
     */
    public function getAllCodes(): array
    {
        return array_filter([
            $this->item_code,
            $this->item_code2,
            $this->item_code3
        ]);
    }

    /**
     * عدد الأكواد
     */
    public function getCodesCount(): int
    {
        return count($this->getAllCodes());
    }

    /**
     * اسم مفصل للصنف (يشمل الأكواد الثلاثة)
     */
    public function getDetailedNameAttribute(): string
    {
        $codes = $this->getAllCodes();
        $codeString = implode(' | ', $codes);
        return "{$this->name} - {$this->color} ({$this->size}) - {$codeString}";
    }

    /**
     * معلومات الفرز الثلاثي
     */
    public function getGradesInfo(): array
    {
        return [
            'grade1_code' => $this->item_code,
            'grade1_qty' => $this->grade1,
            'grade2_code' => $this->item_code2,
            'grade2_qty' => $this->grade2,
            'grade3_code' => $this->item_code3,
            'grade3_qty' => $this->grade3,
            'total' => $this->total
        ];
    }
}
