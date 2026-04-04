<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class LowStockNotification extends Notification
{
    use Queueable;

    protected $product;
    protected $currentStock;
    protected $minStock;

    /**
     * Create a new notification instance.
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
        $this->currentStock = $product->getCurrentStock();
        $this->minStock = $product->stock?->min_stock ?? 50;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail']; // يمكن إضافة 'mail' لاحقاً
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        $shortage = $this->minStock - $this->currentStock;

        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'product_code' => $this->product->item_code,
            'current_stock' => $this->currentStock,
            'min_stock' => $this->minStock,
            'shortage' => $shortage,
            'message' => "⚠️ تحذير: المنتج {$this->product->name} (كود: {$this->product->item_code}) وصل إلى رصيد منخفض! الرصيد الحالي: {$this->currentStock}، الحد الأدنى: {$this->minStock}، النقص: {$shortage}",
            'type' => 'low_stock',
            'url' => route('products.show', $this->product->id),
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $shortage = $this->minStock - $this->currentStock;

        return (new MailMessage)
            ->subject('⚠️ تحذير: مخزون منخفض - ' . $this->product->name)
            ->line('نظام إدارة المخزون - جلوريا للسيراميك')
            ->line('تم رصد مخزون منخفض للمنتج التالي:')
            ->line('**اسم المنتج:** ' . $this->product->name)
            ->line('**كود المنتج:** ' . $this->product->item_code)
            ->line('**الرصيد الحالي:** ' . $this->currentStock)
            ->line('**الحد الأدنى:** ' . $this->minStock)
            ->line('**العجز:** ' . $shortage)
            ->action('عرض المنتج', route('products.show', $this->product->id))
            ->line('يرجى التكرم بإعادة الطلب للمنتج.');
    }
}
