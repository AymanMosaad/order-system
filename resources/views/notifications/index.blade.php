<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الإشعارات - جلوريا للسيراميك</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            direction: rtl;
            background-color: #f8f9fa;
            padding: 20px;
            padding-top: 80px;
        }
        .container { max-width: 1000px; margin: 0 auto; }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }

        .notification-item {
            background: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-right: 4px solid #007bff;
            transition: all 0.3s;
            cursor: pointer;
        }
        .notification-item:hover {
            transform: translateX(-3px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .notification-item.unread {
            background: #e3f2fd;
            border-right-color: #ffc107;
        }
        .notification-icon {
            width: 40px;
            height: 40px;
            background: #007bff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            color: white;
        }
        .notification-content {
            flex: 1;
        }
        .notification-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            font-size: 16px;
        }
        .notification-message {
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .notification-time {
            font-size: 12px;
            color: #999;
            margin-top: 8px;
        }
        .badge-new {
            background: #dc3545;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            margin-right: 10px;
        }
        .btn-mark-read {
            background: none;
            border: none;
            color: #007bff;
            cursor: pointer;
            font-size: 12px;
            margin-right: 10px;
        }
        .btn-mark-read:hover {
            text-decoration: underline;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            color: #999;
        }
        .empty-state .icon {
            font-size: 64px;
            margin-bottom: 15px;
        }

        .actions {
            padding: 15px 20px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
            border-radius: 12px;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            body { padding-top: 70px; }
            .notification-item { padding: 15px; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-bell"></i> الإشعارات</h1>
        <p>جميع الإشعارات الواردة</p>
    </div>

    @if($notifications->count() > 0)
        @foreach($notifications as $notification)
            @php
                $notificationType = $notification->type;
                $data = $notification->data;
                $isUnread = is_null($notification->read_at);
            @endphp
            <div class="notification-item {{ $isUnread ? 'unread' : '' }}"
                 onclick="markAsRead('{{ $notification->id }}')">
                <div style="display: flex; align-items: flex-start;">
                    <div class="notification-icon">
                        @if($notificationType == 'App\Notifications\NewOrderNotification')
                            <i class="fas fa-file-alt"></i>
                        @elseif($notificationType == 'App\Notifications\OrderStatusChangedNotification')
                            <i class="fas fa-industry"></i>
                        @else
                            <i class="fas fa-bell"></i>
                        @endif
                    </div>
                    <div class="notification-content">
                        @if($notificationType == 'App\Notifications\NewOrderNotification')
                            <div class="notification-title">
                                <i class="fas fa-file-alt"></i> طلبية جديدة #{{ $data['order_id'] ?? 'N/A' }}
                                @if($isUnread)
                                    <span class="badge-new">جديد</span>
                                @endif
                            </div>
                            <div class="notification-message">
                                <strong>{{ $data['user_name'] ?? 'مستخدم' }}</strong> قام بإنشاء طلبية جديدة
                                <br>
                                العميل: <strong>{{ $data['customer_name'] ?? '-' }}</strong>
                                <br>
                                إجمالي القطع: <strong>{{ number_format($data['total_items'] ?? 0) }}</strong>
                            </div>
                        @elseif($notificationType == 'App\Notifications\OrderStatusChangedNotification')
                            <div class="notification-title">
                                <i class="fas fa-industry"></i> طلبية #{{ $data['order_id'] ?? 'N/A' }} - تحديث حالة
                                @if($isUnread)
                                    <span class="badge-new">جديد</span>
                                @endif
                            </div>
                            <div class="notification-message">
                                <i class="fas fa-user-tie"></i> العميل: <strong>{{ $data['customer_name'] ?? '-' }}</strong>
                                <br>
                                <i class="fas fa-exchange-alt"></i> تغيرت الحالة من
                                <strong>{{ $data['old_status'] ?? '-' }}</strong> إلى
                                <strong>{{ $data['new_status'] ?? '-' }}</strong>
                                @if(isset($data['factory_notes']) && $data['factory_notes'])
                                    <br>
                                    <i class="fas fa-pen"></i> ملاحظات المصنع: {{ $data['factory_notes'] }}
                                @endif
                            </div>
                        @endif
                        <div class="notification-time">
                            <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                            @if($isUnread)
                                <button class="btn-mark-read" onclick="event.stopPropagation(); markAsRead('{{ $notification->id }}')">
                                    <i class="fas fa-check"></i> تحديد كمقروء
                                </button>
                            @else
                                <span class="text-muted">✓ مقروءة</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="actions">
            <form action="{{ route('notifications.markAllRead') }}" method="POST" style="display: inline-block;">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check-double"></i> تحديد الكل كمقروء
                </button>
            </form>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> العودة للوحة التحكم
            </a>
        </div>

        <div class="mt-3 text-center">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="icon"><i class="fas fa-bell-slash"></i></div>
            <p>لا توجد إشعارات</p>
            <p style="font-size: 14px;">عندما يتم إنشاء طلبيات جديدة أو تغيير حالتها، ستظهر هنا</p>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary mt-3">العودة للوحة التحكم</a>
        </div>
    @endif
</div>

<script>
    function markAsRead(notificationId) {
        fetch('{{ route('notifications.markRead') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: notificationId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>

</body>
</html>
