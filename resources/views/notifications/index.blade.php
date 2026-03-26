<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الإشعارات - جلوريا للسيراميك</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            margin: 0;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container { max-width: 900px; margin: 0 auto; }
        h1 { text-align: center; color: #333; margin-bottom: 30px; }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            font-size: 18px;
            font-weight: bold;
        }
        .card-body {
            padding: 0;
        }

        .notification-item {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            transition: all 0.3s;
            cursor: pointer;
        }
        .notification-item:hover {
            background: #f9f9f9;
        }
        .notification-item.unread {
            background: #e3f2fd;
            border-right: 4px solid #2196f3;
        }
        .notification-item.read {
            background: white;
            opacity: 0.7;
        }
        .notification-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
            font-size: 16px;
        }
        .notification-message {
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .notification-time {
            color: #999;
            font-size: 12px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            text-align: center;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        .empty-state .icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            margin-right: 10px;
        }
        .badge-new {
            background: #dc3545;
            color: white;
        }
        .actions {
            padding: 15px 20px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <h1>🔔 الإشعارات</h1>

    <div class="card">
        <div class="card-header">
            آخر الإشعارات
        </div>
        <div class="card-body">
            @if($notifications->count() > 0)
                @foreach($notifications as $notification)
                    <div class="notification-item {{ $notification->read_at ? 'read' : 'unread' }}"
                         onclick="markAsRead('{{ $notification->id }}')">
                        <div class="notification-title">
                            📋 طلبية جديدة #{{ $notification->data['order_id'] }}
                            @if(!$notification->read_at)
                                <span class="badge badge-new">جديد</span>
                            @endif
                        </div>
                        <div class="notification-message">
                            <strong>{{ $notification->data['user_name'] }}</strong> قام بإنشاء طلبية جديدة
                            <br>
                            العميل: <strong>{{ $notification->data['customer_name'] }}</strong>
                            <br>
                            إجمالي القطع: <strong>{{ number_format($notification->data['total_items']) }}</strong>
                        </div>
                        <div class="notification-time">
                            {{ $notification->created_at->diffForHumans() }}
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="icon">🔕</div>
                    <p>لا توجد إشعارات جديدة</p>
                    <p style="font-size: 14px;">عندما يقوم مستخدم بإنشاء طلبية، ستظهر هنا</p>
                </div>
            @endif
        </div>

        @if($notifications->count() > 0)
            <div class="actions">
                <form action="{{ route('notifications.markAllRead') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">تحديد الكل كمقروء</button>
                </form>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">العودة للوحة التحكم</a>
            </div>
        @endif
    </div>
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
        });
    }
</script>

</body>
</html>
