<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'customer_id',           // أضفنا هذا الحقل
        'customer_name',
        'trader_name',
        'order_number',
        'warehouse_type',
        'address',
        'phone',
        'notes',
        'driver_name',
        'date',
        'status',
        'reference_number',
        'sent_to_factory',
        'sent_to_factory_at',
        'factory_notes',
        'order_discount',      // ✅ أضف هذا
        'total_amount',        // ✅ أضف هذا
    ];

    protected $casts = [
        'date' => 'date',
        'sent_to_factory_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'order_discount' => 'decimal:2',   // ✅ أضف هذا
        'total_amount' => 'decimal:2',     // ✅ أضف هذا
    ];

    // ===== العلاقات =====

    /**
     * الموظف اللي أدخل الطلبية
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * الأصناف في الطلبية
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * العميل (للمحاسبة)
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    // ===== دوال مساعدة =====

    /**
     * إجمالي الكميات
     */
    public function getTotalQuantity(): int
    {
        return $this->items->sum('total');
    }

    /**
     * عدد الأصناف
     */
    public function getItemsCount(): int
    {
        return $this->items->count();
    }

    /**
     * إجمالي الأصناف المختلفة
     */
    public function getUniqueItemsCount(): int
    {
        return $this->items->distinct('product_id')->count();
    }

    /**
     * هل الطلبية مكتملة؟
     */
    public function isCompleted(): bool
    {
        return $this->status === 'مكتملة';
    }

    /**
     * هل الطلبية جديدة؟
     */
    public function isNew(): bool
    {
        return $this->status === 'جديدة';
    }

    /**
     * تغيير الحالة
     */
    public function updateStatus(string $newStatus): bool
    {
        $validStatuses = ['جديدة', 'معالجة', 'مكتملة', 'ملغاة'];

        if (in_array($newStatus, $validStatuses)) {
            $this->status = $newStatus;
            $this->save();
            return true;
        }

        return false;
    }
}
