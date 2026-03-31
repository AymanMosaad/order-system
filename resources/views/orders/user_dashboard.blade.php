<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>لوحة التحكم - جلوريا للسيراميك</title>
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

        /* Header */
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            text-align: center;
        }
        .dashboard-header h1 { margin: 0 0 10px 0; font-size: 28px; }
        .dashboard-header p { margin: 0; opacity: 0.9; font-size: 16px; }

        /* رسالة ترحيب حسب الدور */
        .role-welcome {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            font-size: 14px;
        }
        .role-welcome.sales-rep { background: #e3f2fd; border-right: 4px solid #007bff; color: #0056b3; }
        .role-welcome.factory { background: #fff3e0; border-right: 4px solid #ff9800; color: #e65100; }
        .role-welcome.manager { background: #e8f5e9; border-right: 4px solid #4caf50; color: #1b5e20; }

        /* كروت الإحصائيات */
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
        .stat-icon { font-size: 48px; margin-bottom: 10px; }
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            margin: 10px 0;
        }
        .stat-label { color: #666; font-size: 14px; font-weight: 500; }

        /* أزرار */
        .btn-custom {
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
        .btn-custom:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        .btn-success { background-color: #28a745; }
        .btn-success:hover { background-color: #218838; }

        /* جدول */
        .table-responsive-custom {
            background: white;
            border-radius: 15px;
            overflow-x: auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
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

        tr:hover { background: #f5f5f5; }

        /* حالة الطلبية */
        .status {
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 11px;
            display: inline-block;
        }
        .status-new { background: #d4edda; color: #155724; }
        .status-processing { background: #fff3cd; color: #856404; }
        .status-completed { background: #d1ecf1; color: #0c5460; }
        .status-cancelled { background: #f8d7da; color: #721c24; }

        .view-link {
            color: #007bff;
            text-decoration: none;
            padding: 5px 10px;
            background: #e3f2fd;
            border-radius: 5px;
            font-size: 13px;
            transition: all 0.2s;
        }
        .view-link:hover { background: #bbdefb; }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #999;
        }
        .empty-state .icon { font-size: 64px; margin-bottom: 15px; }

        /* Pagination */
        .pagination-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
            padding: 15px;
            background: white;
            border-radius: 15px;
        }

        .pagination-links {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .pagination-links a, .pagination-links span {
            padding: 6px 12px;
            margin: 0 2px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
            border-radius: 6px;
        }
        .pagination-links .active span {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body { padding: 10px; padding-top: 80px; }
            .stats-grid { grid-template-columns: 1fr; gap: 15px; }
            .dashboard-header h1 { font-size: 22px; }
            .dashboard-header p { font-size: 14px; }
            table { font-size: 12px; }
            th, td { padding: 8px; }
            .btn-custom { padding: 8px 15px; font-size: 12px; }
            .view-link { padding: 3px 8px; font-size: 11px; }
            .pagination-custom { flex-direction: column; text-align: center; }
            .pagination-links { justify-content: center; }
        }

        @media (max-width: 480px) {
            .stat-number { font-size: 24px; }
            .stat-icon { font-size: 36px; }
        }

        @media print {
            .btn-custom, .dashboard-header { display: none; }
            body { background: white; padding: 0; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="dashboard-header">
        <h1><i class="fas fa-chalkboard-user"></i> لوحة التحكم الرئيسية</h1>
        <p>مرحباً {{ Auth::user()->name }}، هذه نظرة عامة على طلبياتك</p>
    </div>

    <!-- ===== رسالة ترحيب حسب الدور (جديدة) ===== -->
    @if(Auth::user()->role == 'sales_rep')
        <div class="role-welcome sales-rep">
            <i class="fas fa-user-check"></i> مرحباً {{ Auth::user()->name }}، يمكنك إنشاء طلبيات جديدة ومتابعة طلبياتك الحالية.
        </div>
    @elseif(Auth::user()->role == 'factory')
        <div class="role-welcome factory">
            <i class="fas fa-industry"></i> مرحباً {{ Auth::user()->name }}، يمكنك متابعة الطلبيات المرسلة للمصنع وتحديث حالتها من <a href="{{ route('factory.orders') }}" style="color: #e65100; font-weight: bold;">هنا</a>.
        </div>
    @elseif(in_array(Auth::user()->role, ['super_admin', 'sales_manager']))
        <div class="role-welcome manager">
            <i class="fas fa-chart-line"></i> مرحباً {{ Auth::user()->name }}، يمكنك الوصول إلى التقارير المتقدمة وإدارة جميع الطلبيات من <a href="{{ route('admin.dashboard') }}" style="color: #1b5e20; font-weight: bold;">لوحة المدير</a>.
        </div>
    @endif
    <!-- ===== نهاية الإضافة ===== -->

    <div class="stats-grid">
        <div class="stat-card" onclick="window.location='{{ route('orders.userDashboard') }}'">
            <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat-number">{{ number_format($totalOrders) }}</div>
            <div class="stat-label">إجمالي الطلبيات</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-cubes"></i></div>
            <div class="stat-number">{{ number_format($totalItems) }}</div>
            <div class="stat-label">إجمالي الكميات</div>
        </div>
    </div>

    <div class="table-responsive-custom">
        <div style="padding: 15px; border-bottom: 1px solid #eee;">
            <h3 class="box-title" style="margin: 0;"><i class="fas fa-clock"></i> آخر الطلبيات</h3>
        </div>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> رقم الإذن</th>
                        <th><i class="fas fa-user-tie"></i> اسم العميل</th>
                        <th><i class="fas fa-calendar-day"></i> التاريخ</th>
                        <th><i class="fas fa-chart-simple"></i> الحالة</th>
                        <th><i class="fas fa-cubes"></i> عدد الأصناف</th>
                        <th><i class="fas fa-chart-line"></i> الإجمالي</th>
                        <th><i class="fas fa-cog"></i> الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number ?? '#' . $order->id }}</strong></td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->date->format('Y-m-d') }}</td>
                        <td>
                            @php
                                $statusClass = 'status-new';
                                if($order->status == 'قيد المعالجة') $statusClass = 'status-processing';
                                elseif($order->status == 'مكتملة') $statusClass = 'status-completed';
                                elseif($order->status == 'ملغية') $statusClass = 'status-cancelled';
                            @endphp
                            <span class="status {{ $statusClass }}">{{ $order->status }}</span>
                        </td>
                        <td>{{ $order->items->count() }}</td>
                        <td>{{ number_format($order->items->sum('total')) }}</td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="view-link"><i class="fas fa-eye"></i> عرض</a>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="icon"><i class="fas fa-inbox"></i></div>
                                    <p>لا توجد طلبيات حتى الآن</p>
                                    <a href="{{ route('orders.create') }}" class="btn-custom btn-success" style="margin-top: 15px;">
                                        <i class="fas fa-plus-circle"></i> إنشاء طلب جديد
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->count() > 0)
        <div class="pagination-custom">
            <div class="pagination-info">
                <i class="fas fa-file-alt"></i> عرض {{ $orders->firstItem() }} - {{ $orders->lastItem() }} من {{ $orders->total() }} طلبية
            </div>
            <div class="pagination-links">
                {{ $orders->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @endif

        <div style="text-align: center; padding: 15px; border-top: 1px solid #eee;">
            <a href="{{ route('orders.create') }}" class="btn-custom btn-success"><i class="fas fa-plus-circle"></i> إنشاء طلب جديد</a>
            <a href="{{ route('orders.userDashboard') }}" class="btn-custom"><i class="fas fa-clipboard-list"></i> كل طلبياتي</a>
        </div>
    </div>
</div>

</body>
</html>
