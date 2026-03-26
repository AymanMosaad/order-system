<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewOrderNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // إرسال إيميل وتخزين في قاعدة البيانات
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('طلبية جديدة رقم #' . $this->order->id)
            ->greeting('مرحباً ' . $notifiable->name)
            ->line('تم إنشاء طلبية جديدة بواسطة: ' . $this->order->user->name)
            ->line('اسم العميل: ' . $this->order->customer_name)
            ->line('التاريخ: ' . $this->order->date->format('Y-m-d'))
            ->line('إجمالي القطع: ' . $this->order->items->sum('total'))
            ->action('عرض الطلبية', url('/orders/show/' . $this->order->id))
            ->line('شكراً لاستخدامك النظام!');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'user_name' => $this->order->user->name,
            'customer_name' => $this->order->customer_name,
            'total_items' => $this->order->items->sum('total'),
            'created_at' => $this->order->created_at,
        ];
    }
}
