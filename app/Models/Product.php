<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
}
