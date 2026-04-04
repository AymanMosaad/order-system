<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cheque extends Model
{
    protected $table = 'cheques';

    protected $fillable = [
        'customer_id',
        'order_id',
        'cheque_number',
        'bank_name',
        'amount',
        'issue_date',
        'due_date',
        'status',
        'notes',
        'collected_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'issue_date' => 'date',
        'due_date' => 'date',
        'collected_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCollected(): bool
    {
        return $this->status === 'collected';
    }

    public function isReturned(): bool
    {
        return $this->status === 'returned';
    }
}
