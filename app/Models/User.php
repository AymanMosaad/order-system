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
        'role',
        'is_admin',
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
            'is_admin' => 'boolean',
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

    // ===== دوال الصلاحيات =====

    /**
     * هل المستخدم مدير عام؟
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin' && $this->is_admin == 1;
    }

    /**
     * هل المستخدم مدير مبيعات؟
     */
    public function isSalesManager(): bool
    {
        return $this->role === 'sales_manager' && $this->is_admin == 1;
    }

    /**
     * هل المستخدم مندوب؟
     */
    public function isSalesRep(): bool
    {
        return $this->role === 'sales_rep' && $this->is_admin == 0;
    }

    /**
     * هل المستخدم مصنع؟
     */
    public function isFactory(): bool
    {
        return $this->role === 'factory' && $this->is_admin == 0;
    }

    /**
     * هل يمكنه رؤية لوحة المدير؟
     */
    public function canViewAdminDashboard(): bool
    {
        return $this->is_admin == 1;
    }

    /**
     * هل يمكنه إدارة المستخدمين؟
     */
    public function canManageUsers(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * هل يمكنه استيراد المنتجات؟
     */
    public function canImportProducts(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * هل يمكنه رؤية كل الطلبيات؟
     */
    public function canViewAllOrders(): bool
    {
        return in_array($this->role, ['super_admin', 'sales_manager']);
    }

    /**
     * هل يمكنه رؤية التقارير؟
     */
    public function canViewReports(): bool
    {
        return in_array($this->role, ['super_admin', 'sales_manager']);
    }
}
