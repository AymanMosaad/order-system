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
        'total',
        'unit_price',
        'discount_rate',
        'discount_amount',
        'transaction_type', // إضافة نوع العملية (صرف/إرتجاع/عينة)
    ];

    protected $casts = [
        'grade1' => 'decimal:2',
        'grade2' => 'decimal:2',
        'grade3' => 'decimal:2',
        'total' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'discount_rate' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    // ===== العلاقات =====

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ===== دوال مساعدة =====

    public function calculateTotal(): float
    {
        $quantity = ($this->grade1 ?? 0) + ($this->grade2 ?? 0) + ($this->grade3 ?? 0);
        $price = $this->unit_price ?? $this->product?->price ?? 0;
        $discountAmount = $this->discount_amount ?? 0;

        $this->total = ($quantity * $price) - $discountAmount;
        return $this->total;
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->calculateTotal();
        });
    }

    public function getAllCodes(): array
    {
        return array_filter([
            $this->item_code,
            $this->item_code2,
            $this->item_code3
        ]);
    }

    public function getCodesCount(): int
    {
        return count($this->getAllCodes());
    }

    public function getDetailedNameAttribute(): string
    {
        $codes = $this->getAllCodes();
        $codeString = implode(' | ', $codes);
        return "{$this->name} - {$this->color} ({$this->size}) - {$codeString}";
    }

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

    /**
     * الحصول على نوع العملية نصياً
     */
    public function getTransactionTypeTextAttribute(): string
    {
        $types = [
            'sale' => 'صرف',
            'return' => 'إرتجاع',
            'sample' => 'عينة',
            'discount' => 'خصم'
        ];
        return $types[$this->transaction_type] ?? 'صرف';
    }

    /**
     * الحصول على قيمة العملية (مدين/دائن)
     */
    public function getTransactionValueAttribute(): float
    {
        if ($this->transaction_type == 'return' || $this->transaction_type == 'sample') {
            return -abs($this->total); // سالب (لصالح العميل)
        }
        return abs($this->total); // موجب (على العميل)
    }
}
