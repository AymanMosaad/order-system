<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductStock extends Model
{
    protected $table = 'product_stocks';

    protected $fillable = [
        'product_id',
        'current_stock',
        'min_stock',
    ];

    protected $casts = [
        'current_stock' => 'decimal:2', // مهم لعَرض الكسور
        'min_stock'     => 'integer',
    ];

    // العلاقات
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // دوال مساعدة
    public function isLowStock(): bool
    {
        return (float)$this->current_stock < (int)$this->min_stock;
    }

    public function decreaseStock(float|int $quantity): bool
    {
        $quantity = (float) $quantity;
        if ((float)$this->current_stock >= $quantity) {
            $this->current_stock = (float)$this->current_stock - $quantity;
            $this->save();
            return true;
        }
        return false;
    }

    public function increaseStock(float|int $quantity): bool
    {
        $this->current_stock = (float)$this->current_stock + (float)$quantity;
        $this->save();
        return true;
    }

    public function setStock(float|int $quantity): void
    {
        $this->current_stock = (float) $quantity;
        $this->save();
    }
}
