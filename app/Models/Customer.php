<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'code',
        'name',
        'type',
        'phone',
        'address',
        'discount_rate',
        'balance',
        'credit_limit',
        'notes'
    ];

    protected $casts = [
        'discount_rate' => 'decimal:2',
        'balance' => 'decimal:2',
        'credit_limit' => 'decimal:2',
    ];

    /**
     * العلاقة مع الطلبيات
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * العلاقة مع الشيكات
     */
    public function cheques(): HasMany
    {
        return $this->hasMany(Cheque::class);
    }

    /**
     * إجمالي المسحوبات
     */
    public function getTotalWithdrawalsAttribute()
    {
        return $this->orders->sum(function($order) {
            return $order->items->sum('total');
        });
    }
    public function withdrawals()
{
    return $this->hasMany(Withdrawal::class);
}

    /**
     * إجمالي الشيكات المعلقة
     */
    public function getPendingChequesTotalAttribute()
    {
        return $this->cheques()->where('status', 'pending')->sum('amount');
    }
}
