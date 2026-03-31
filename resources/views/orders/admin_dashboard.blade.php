<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>لوحة تحكم المدير - جلوريا للسيراميك</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', 'Tahoma', Arial, sans-serif;
            direction: rtl;
            background-color: #f8f9fa;
            padding: 20px;
            padding-top: 90px;
        }
        .container { max-width: 1400px; margin: 0 auto; }

        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            text-align: center;
        }
        .dashboard-header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        .dashboard-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 16px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
            cursor: pointer;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        .stat-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            margin: 10px 0;
        }
        .stat-label {
            color: #666;
            font-size: 14px;
            font-weight: 500;
        }

        .info-box {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .box-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #007bff;
            display: inline-block;
        }
        .box-title i {
            margin-left: 8px;
            color: #007bff;
        }

        .table-responsive-custom {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            min-width: 700px;
        }
        th, td {
            border: 1px solid #eee;
            padding: 12px;
            text-align: center;
        }
        th {
            background: #f8f9fa;
            color: #333;
            font-weight: bold;
            border-bottom: 2px solid #dee2e6;
        }
        tr:hover {
            background: #f5f5f5;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 10px 5px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        .btn-success {
            background-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .btn-info {
            background-color: #17a2b8;
        }
        .btn-info:hover {
            background-color: #138496;
        }

        .notification-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            background: #e3f2fd;
            margin-bottom: 10px;
            border-radius: 8px;
            border-right: 4px solid #2196f3;
            transition: all 0.3s;
        }
        .notification-item:hover {
            background: #d1e7ff;
        }
        .notification-item strong {
            color: #333;
            font-size: 15px;
        }
        .notification-time {
            font-size: 11px;
            color: #999;
            margin-top: 8px;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #999;
        }
        .empty-state .icon {
            font-size: 64px;
            margin-bottom: 15px;
        }

        .view-link {
            color: #007bff;
            text-decoration: none;
            padding: 5px 10px;
            background: #e3f2fd;
            border-radius: 5px;
            font-size: 13px;
            transition: all 0.2s;
        }
        .view-link:hover {
            background: #bbdefb;
        }

        @media (max-width: 768px) {
            body { padding: 10px; padding-top: 80px; }
            .stats-grid { grid-template-columns: 1fr; gap: 15px; }
            .dashboard-header h1 { font-size: 22px; }
            .dashboard-header p { font-size: 14px; }
            table { font-size: 12px; }
            th, td { padding: 8px; }
            .btn { padding: 8px 15px; font-size: 12px; }
            .box-title { font-size: 18px; }
            .stat-number { font-size: 24px; }
            .stat-icon { font-size: 36px; }
        }

        @media (max-width: 576px) {
            .notification-item { padding: 10px; }
            .notification-item strong { font-size: 13px; }
        }

        @media print {
            .btn, .navbar-custom, .dashboard-header { display: none; }
            body { background: white; padding: 0; }
            .stat-card { break-inside: avoid; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="dashboard-header">
        <h1><i class="fas fa-crown"></i> لوحة تحكم المدير</h1>
        <p>مرحباً {{ Auth::user()->name }}، هذه نظرة عامة على النظام</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card" onclick="window.location='{{ route('orders.index') }}'">
            <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat-number">{{ number_format($totalOrders) }}</div>
            <div class="stat-label">إجمالي الطلبيات</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-number">{{ number_format($totalUsers) }}</div>
            <div class="stat-label">عدد المستخدمين</div>
        </div>
        <div class="stat-card" onclick="window.location='{{ route('products.index') }}'">
            <div class="stat-icon"><i class="fas fa-boxes"></i></div>
            <div class="stat-number">{{ number_format($totalProducts) }}</div>
            <div class="stat-label">المنتجات</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
            <div class="stat-number">{{ number_format($totalRevenue) }}</div>
            <div class="stat-label">إجمالي المبيعات</div>
        </div>
    </div>

    <div class="info-box">
        <div class="box-title"><i class="fas fa-bell"></i> آخر الإشعارات</div>
        @if($unreadNotifications->count() > 0)
            @foreach($unreadNotifications->take(5) as $notification)
                <div class="notification-item">
                    @if($notification->type == 'App\Notifications\NewOrderNotification')
                        <strong><i class="fas fa-file-alt"></i> طلبية جديدة #{{ $notification->data['order_id'] ?? 'N/A' }}</strong>
                        <br>
                        <i class="fas fa-user"></i> بواسطة: <strong>{{ $notification->data['user_name'] ?? 'مستخدم' }}</strong>
                        <br>
                        <i class="fas fa-user-tie"></i> العميل: {{ $notification->data['customer_name'] ?? '-' }}
                        <br>
                        <i class="fas fa-cubes"></i> إجمالي القطع: <strong>{{ number_format($notification->data['total_items'] ?? 0) }}</strong>
                    @elseif($notification->type == 'App\Notifications\OrderStatusChangedNotification')
                        <strong><i class="fas fa-industry"></i> طلبية #{{ $notification->data['order_id'] ?? 'N/A' }} - تحديث حالة</strong>
                        <br>
                        <i class="fas fa-user-tie"></i> العميل: {{ $notification->data['customer_name'] ?? '-' }}
                        <br>
                        <i class="fas fa-exchange-alt"></i> تغيرت الحالة من
                        <strong>{{ $notification->data['old_status'] ?? '-' }}</strong> إلى
                        <strong>{{ $notification->data['new_status'] ?? '-' }}</strong>
                        @if(isset($notification->data['factory_notes']) && $notification->data['factory_notes'])
                            <br>
                            <i class="fas fa-pen"></i> ملاحظات المصنع: {{ $notification->data['factory_notes'] }}
                        @endif
                    @endif
                    <div class="notification-time">
                        <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                    </div>
                </div>
            @endforeach
            <div style="text-align: center; margin-top: 15px;">
                <a href="{{ route('notifications.index') }}" class="btn btn-info"><i class="fas fa-envelope-open-text"></i> عرض كل الإشعارات ({{ $unreadNotifications->count() }} غير مقروء)</a>
                <form action="{{ route('notifications.markAllRead') }}" method="POST" style="display: inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-success"><i class="fas fa-check-double"></i> تحديد الكل كمقروء</button>
                </form>
            </div>
        @else
            <div class="empty-state">
                <div class="icon"><i class="fas fa-bell-slash"></i></div>
                <p>لا توجد إشعارات جديدة</p>
                <p style="font-size: 12px;">عندما يقوم مستخدم بإنشاء طلبية، ستظهر هنا</p>
            </div>
        @endif
    </div>

    <div class="info-box">
        <div class="box-title"><i class="fas fa-clock"></i> أحدث الطلبيات</div>
        <div class="table-responsive-custom">
             <table class="table table-bordered">
                <thead>
                      <tr>
                        <th><i class="fas fa-hashtag"></i> #</th>
                        <th><i class="fas fa-user-tie"></i> العميل</th>
                        <th><i class="fas fa-user"></i> المستخدم</th>
                        <th><i class="fas fa-calendar-day"></i> التاريخ</th>
                        <th><i class="fas fa-chart-line"></i> الإجمالي</th>
                        <th><i class="fas fa-cog"></i> الإجراءات</th>
                       </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                         <tr>
                             <td><strong>#{{ $order->id }}</strong></td>
                             <td>{{ $order->customer_name }}</td>
                             <td>{{ $order->user->name ?? '-' }}</td>
                             <td>{{ $order->date->format('Y-m-d') }}</td>
                             <td>{{ number_format($order->items->sum('total')) }}</td>
                             <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="view-link"><i class="fas fa-eye"></i> عرض</a>
                             </td>
                         </tr>
                    @empty
                         <tr>
                            <td colspan="6" style="text-align: center; padding: 40px;">
                                <i class="fas fa-inbox"></i> لا توجد طلبيات حتى الآن
                            </td>
                         </tr>
                    @endforelse
                </tbody>
             </table>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ route('orders.index') }}" class="btn"><i class="fas fa-list-alt"></i> عرض كل الطلبيات</a>
            <a href="{{ route('orders.advancedReport') }}" class="btn btn-success"><i class="fas fa-chart-line"></i> تقرير متقدم</a>
            <a href="{{ route('products.report') }}" class="btn btn-info"><i class="fas fa-chart-pie"></i> تقرير الأصناف</a>
        </div>
    </div>
</div>

</body>
</html>
