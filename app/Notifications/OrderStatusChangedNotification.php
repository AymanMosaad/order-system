<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusChangedNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $oldStatus;

    public function __construct(Order $order, $oldStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'customer_name' => $this->order->customer_name,
            'old_status' => $this->oldStatus,
            'new_status' => $this->order->status,
            'factory_notes' => $this->order->factory_notes,
            'message' => 'تم تغيير حالة الطلبية #' . $this->order->id . ' من ' . $this->oldStatus . ' إلى ' . $this->order->status,
            'type' => 'order_status_changed'
        ];
    }
}
