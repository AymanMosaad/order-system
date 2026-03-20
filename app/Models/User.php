<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ===== العلاقات =====

    /**
     * الطلبيات الخاصة بهذا الموظف
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // ===== دوال مساعدة =====

    /**
     * عدد الطلبيات الخاصة به
     */
    public function getOrdersCount(): int
    {
        return $this->orders()->count();
    }

    /**
     * إجمالي الكميات الخاصة به
     */
    public function getTotalQuantity(): int
    {
        return $this->orders()
            ->with('items')
            ->get()
            ->sum(function ($order) {
                return $order->items->sum('total');
            });
    }

    /**
     * آخر طلبية أدخلها
     */
    public function getLastOrder()
    {
        return $this->orders()->latest()->first();
    }

    /**
     * عدد الطلبيات الجديدة
     */
    public function getNewOrdersCount(): int
    {
        return $this->orders()->where('status', 'جديدة')->count();
    }
}
