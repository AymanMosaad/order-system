<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>كل الطلبيات - جلوريا للسيراميك</title>
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
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }
        .page-header h1 { margin: 0 0 5px 0; font-size: 24px; }
        .page-header p  { margin: 0; opacity: 0.85; font-size: 14px; }

        /* فلترة */
        .filters-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        /* أزرار */
        .btn-new {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 22px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s;
            white-space: nowrap;
        }
        .btn-new:hover { background-color: #218838; transform: translateY(-2px); }

        .btn-filter {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
        }
        .btn-filter:hover { background: #0056b3; }

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
            min-width: 1000px;
        }

        th, td {
            border: 1px solid #eee;
            padding: 12px;
            text-align: center;
            font-size: 14px;
        }

        th {
            background: #f8f9fa;
            color: #333;
            font-weight: bold;
            border-bottom: 2px solid #dee2e6;
            position: sticky;
            top: 0;
        }

        tr:nth-child(even) { background-color: #fafafa; }
        tr:hover { background-color: #f0f4ff; }

        /* حالة الطلبية */
        .status {
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            display: inline-block;
        }
        .status-new        { background: #d4edda; color: #155724; }
        .status-processing { background: #fff3cd; color: #856404; }
        .status-completed  { background: #d1ecf1; color: #0c5460; }
        .status-cancelled  { background: #f8d7da; color: #721c24; }

        /* أزرار الإجراءات */
        .btn-view {
            display: inline-block;
            background: #e3f2fd;
            color: #007bff;
            padding: 5px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            transition: background 0.2s;
        }
        .btn-view:hover { background: #bbdefb; }

        .btn-edit {
            display: inline-block;
            background: #fff3cd;
            color: #856404;
            padding: 5px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            margin: 0 3px;
        }
        .btn-edit:hover { background: #ffe6a3; }

        .btn-delete {
            display: inline-block;
            background: #f8d7da;
            color: #721c24;
            padding: 5px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
            margin: 0 3px;
        }
        .btn-delete:hover { background: #f1b0b7; }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        .empty-state .icon { font-size: 64px; margin-bottom: 15px; }
        .empty-state p { font-size: 16px; margin: 5px 0; }

        /* Pagination */
        .pagination-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 25px;
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
            display: inline-block;
        }
        .pagination-links .active span {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        .pagination-links a:hover {
            background-color: #007bff;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body { padding: 10px; padding-top: 80px; }
            .page-header { flex-direction: column; text-align: center; }
            .page-header h1 { font-size: 22px; }
            th, td { padding: 8px; font-size: 12px; }
            .btn-view, .btn-edit, .btn-delete { padding: 3px 8px; font-size: 11px; }
            .pagination-custom { flex-direction: column; text-align: center; }
            .pagination-links { justify-content: center; }
        }

        @media (max-width: 576px) {
            .btn-new { padding: 8px 16px; font-size: 12px; }
            .status { padding: 2px 8px; font-size: 10px; }
        }

        /* Alert */
        .alert {
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">

    <div class="page-header">
        <div>
            <h1><i class="fas fa-list-alt"></i> كل الطلبيات</h1>
            <p>إدارة ومتابعة جميع الطلبيات</p>
        </div>
        <a href="{{ route('orders.create') }}" class="btn-new">
            <i class="fas fa-plus-circle"></i> طلبية جديدة
        </a>
    </div>

    <!-- فلاتر البحث -->
    <div class="filters-card">
        <form method="GET" action="{{ route('orders.index') }}" class="row g-3">
            <div class="col-md-4 col-sm-6">
                <label class="form-label"><i class="fas fa-search"></i> بحث</label>
                <input type="text" name="search" class="form-control" placeholder="رقم الطلبية أو العميل" value="{{ request('search') }}">
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="form-label"><i class="fas fa-filter"></i> الحالة</label>
                <select name="status" class="form-select">
                    <option value="">الكل</option>
                    <option value="جديدة" {{ request('status') == 'جديدة' ? 'selected' : '' }}>جديدة</option>
                    <option value="قيد المعالجة" {{ request('status') == 'قيد المعالجة' ? 'selected' : '' }}>قيد المعالجة</option>
                    <option value="مكتملة" {{ request('status') == 'مكتملة' ? 'selected' : '' }}>مكتملة</option>
                    <option value="ملغية" {{ request('status') == 'ملغية' ? 'selected' : '' }}>ملغية</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="form-label"><i class="fas fa-calendar"></i> من تاريخ</label>
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-2 col-sm-6 d-flex align-items-end">
                <button type="submit" class="btn-filter w-100"><i class="fas fa-search"></i> بحث</button>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }}</div>
    @endif

    <div class="table-responsive-custom">
        @if($orders->count() > 0)
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><i class="fas fa-user"></i> الموظف</th>
                            <th><i class="fas fa-user-tie"></i> العميل</th>
                            <th><i class="fas fa-store"></i> التاجر</th>
                            <th><i class="fas fa-hashtag"></i> رقم الإذن</th>
                            <th><i class="fas fa-location-dot"></i> المنطقة</th>
                            <th><i class="fas fa-calendar-day"></i> التاريخ</th>
                            <th><i class="fas fa-chart-simple"></i> الحالة</th>
                            <th><i class="fas fa-cubes"></i> الأصناف</th>
                            <th><i class="fas fa-chart-line"></i> الإجمالي</th>
                            <th><i class="fas fa-cog"></i> الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>{{ $order->user->name ?? '-' }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->trader_name ?? '-' }}</td>
                            <td>{{ $order->order_number ?? '-' }}</td>
                            <td>{{ $order->address ?? '-' }}</td>
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
                            <td>{{ $order->getItemsCount() }}</td>
                            <td>{{ number_format($order->getTotalQuantity()) }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn-view"><i class="fas fa-eye"></i> عرض</a>
                                <!-- التعديل هنا: المدير العام أو مدير المبيعات أو صاحب الطلبية نفسه -->
                                @if(in_array(Auth::user()->role, ['super_admin', 'sales_manager']) || Auth::id() == $order->user_id)
                                    <a href="{{ route('orders.edit', $order->id) }}" class="btn-edit"><i class="fas fa-edit"></i> تعديل</a>
                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" onclick="return confirm('هل أنت متأكد من حذف هذه الطلبية؟')"><i class="fas fa-trash"></i> حذف</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-custom">
                <div class="pagination-info">
                    <i class="fas fa-file-alt"></i> عرض {{ $orders->firstItem() }} - {{ $orders->lastItem() }} من {{ $orders->total() }} طلبية
                </div>
                <div class="pagination-links">
                    {{ $orders->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @else
            <div class="empty-state">
                <div class="icon"><i class="fas fa-inbox"></i></div>
                <p>لا توجد طلبيات حالياً</p>
                <p style="font-size: 14px;">ابدأ بإنشاء أول طلبية</p>
                <a href="{{ route('orders.create') }}" class="btn-new" style="margin-top: 15px;">
                    <i class="fas fa-plus-circle"></i> إنشاء طلبية
                </a>
            </div>
        @endif
    </div>

</div>

</body>
</html>
