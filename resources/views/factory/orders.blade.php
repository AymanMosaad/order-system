<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>طلبيات المصنع - جلوريا للسيراميك</title>
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
        .container { max-width: 1400px; margin: 0 auto; }

        .page-header {
            background: linear-gradient(135deg, #fd7e14 0%, #dc3545 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }
        .page-header h1 { margin: 0 0 10px 0; font-size: 24px; }
        .page-header p { margin: 0; opacity: 0.9; font-size: 14px; }

        /* حالة الطلبية */
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-new { background: #28a745; color: white; }
        .status-loading { background: #ffc107; color: #212529; }
        .status-loaded { background: #17a2b8; color: white; }
        .status-delayed { background: #fd7e14; color: white; }
        .status-cancelled { background: #dc3545; color: white; }

        /* أزرار */
        .btn-status {
            padding: 8px 12px;
            margin: 2px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.2s;
        }
        .btn-primary {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
        }
        .btn-primary:hover { background: #0056b3; }

        .btn-info {
            background: #17a2b8;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-info:hover { background: #138496; }

        /* ===== تصميم الجدول للشاشات الكبيرة ===== */
        .desktop-table {
            background: white;
            border-radius: 12px;
            overflow-x: auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .desktop-table table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }
        .desktop-table th, .desktop-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
            vertical-align: middle;
        }
        .desktop-table th {
            background: #333;
            color: white;
            font-weight: bold;
        }
        .desktop-table tr:hover {
            background: #f5f5f5;
        }

        /* ===== تصميم البطاقات للموبايل ===== */
        .mobile-cards {
            display: none;
            gap: 15px;
            flex-direction: column;
        }

        .order-card {
            background: white;
            border-radius: 15px;
            padding: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: all 0.3s;
            border-right: 4px solid #fd7e14;
        }

        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
        }

        .order-id {
            font-size: 16px;
            font-weight: bold;
            color: #fd7e14;
            background: #fff3e0;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .card-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .card-label {
            min-width: 80px;
            font-weight: bold;
            color: #666;
            font-size: 13px;
        }

        .card-value {
            flex: 1;
            color: #333;
            font-size: 14px;
            word-break: break-word;
        }

        /* نموذج تحديث الحالة للموبايل */
        .status-update-form {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 12px;
            margin-top: 12px;
            border: 1px solid #eee;
        }

        .status-update-form select,
        .status-update-form input,
        .status-update-form button {
            width: 100%;
            margin-bottom: 8px;
        }

        .status-update-form select,
        .status-update-form input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        .status-update-form button {
            margin-bottom: 0;
            padding: 10px;
        }

        .card-actions {
            display: flex;
            gap: 10px;
            margin-top: 12px;
        }

        .card-actions .btn-info {
            flex: 1;
            text-align: center;
            padding: 10px;
        }

        /* تحسينات عامة */
        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* فلترة */
        .filter-toggle-btn {
            display: none;
            width: 100%;
            background: #fd7e14;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 15px;
            font-weight: bold;
            text-align: center;
        }

        .filters-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .pagination-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
            padding: 15px;
            background: white;
            border-radius: 12px;
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
            background-color: #fd7e14;
            color: white;
            border-color: #fd7e14;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body { padding: 10px; padding-top: 70px; }
            .page-header h1 { font-size: 20px; }
            .page-header p { font-size: 12px; }

            /* إخفاء الجدول وإظهار البطاقات */
            .desktop-table { display: none; }
            .mobile-cards { display: flex; }

            /* إظهار زر التصفية */
            .filter-toggle-btn { display: block; }

            /* إخفاء الفلاتر بشكل افتراضي */
            .filters-card {
                display: none;
            }

            .filters-card.show {
                display: block;
            }

            .pagination-custom {
                flex-direction: column;
                text-align: center;
            }
            .pagination-links { justify-content: center; }
        }

        @media (max-width: 480px) {
            .card-label { min-width: 70px; font-size: 12px; }
            .card-value { font-size: 13px; }
            .order-id { font-size: 14px; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-industry"></i> طلبيات المصنع</h1>
        <p>إدارة ومتابعة الطلبيات الواردة للمصنع</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }}</div>
    @endif

    <!-- زر تصفية للموبايل -->
    <button class="filter-toggle-btn" onclick="toggleFilters()">
        <i class="fas fa-filter"></i> تصفية وبحث
    </button>

    <!-- فلاتر البحث -->
    <div class="filters-card" id="filtersCard">
        <form method="GET" action="{{ route('factory.orders') }}" class="row g-3">
            <div class="col-md-4 col-sm-12">
                <label class="form-label"><i class="fas fa-search"></i> بحث</label>
                <input type="text" name="search" class="form-control" placeholder="رقم الطلبية أو العميل" value="{{ request('search') }}">
            </div>
            <div class="col-md-3 col-sm-12">
                <label class="form-label"><i class="fas fa-filter"></i> الحالة</label>
                <select name="status" class="form-select">
                    <option value="">الكل</option>
                    <option value="جديدة" {{ request('status') == 'جديدة' ? 'selected' : '' }}>جديدة</option>
                    <option value="تحت التحميل" {{ request('status') == 'تحت التحميل' ? 'selected' : '' }}>تحت التحميل</option>
                    <option value="تم التحميل" {{ request('status') == 'تم التحميل' ? 'selected' : '' }}>تم التحميل</option>
                    <option value="مؤجلة" {{ request('status') == 'مؤجلة' ? 'selected' : '' }}>مؤجلة</option>
                    <option value="ملغية" {{ request('status') == 'ملغية' ? 'selected' : '' }}>ملغية</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-12">
                <label class="form-label"><i class="fas fa-calendar"></i> من تاريخ</label>
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-2 col-sm-12 d-flex align-items-end">
                <button type="submit" class="btn-primary w-100"><i class="fas fa-search"></i> بحث</button>
            </div>
        </form>
    </div>

    <!-- عرض الجدول للشاشات الكبيرة -->
    <div class="desktop-table">
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العميل</th>
                        <th>المندوب</th>
                        <th>التاريخ</th>
                        <th>الكمية</th>
                        <th>الحالة</th>
                        <th>ملاحظات المصنع</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->user->name ?? '-' }}</td>
                        <td>{{ $order->date->format('Y-m-d') }}</td>
                        <td>{{ number_format($order->items->sum('total')) }} متر</td>
                        <td>
                            @php
                                $statusClass = 'status-new';
                                if($order->status == 'تحت التحميل') $statusClass = 'status-loading';
                                elseif($order->status == 'تم التحميل') $statusClass = 'status-loaded';
                                elseif($order->status == 'مؤجلة') $statusClass = 'status-delayed';
                                elseif($order->status == 'ملغية') $statusClass = 'status-cancelled';
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $order->status }}</span>
                        </td>
                        <td>
                            <form action="/factory/order/{{ $order->id }}/status" method="POST" style="display: flex; gap: 5px; flex-wrap: wrap;">
                                @csrf
                                <input type="text" name="factory_notes" placeholder="ملاحظة" class="form-control form-control-sm" style="width: 120px; display: inline-block;" value="{{ $order->factory_notes }}">
                                <select name="status" class="form-select form-select-sm" style="width: 110px; display: inline-block;">
                                    <option value="جديدة" {{ $order->status == 'جديدة' ? 'selected' : '' }}>جديدة</option>
                                    <option value="تحت التحميل" {{ $order->status == 'تحت التحميل' ? 'selected' : '' }}>تحت التحميل</option>
                                    <option value="تم التحميل" {{ $order->status == 'تم التحميل' ? 'selected' : '' }}>تم التحميل</option>
                                    <option value="مؤجلة" {{ $order->status == 'مؤجلة' ? 'selected' : '' }}>مؤجلة</option>
                                    <option value="ملغية" {{ $order->status == 'ملغية' ? 'selected' : '' }}>ملغية</option>
                                </select>
                                <button type="submit" class="btn-primary btn-sm">تحديث</button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn-info btn-sm" style="padding: 5px 12px; text-decoration: none; border-radius: 5px;"><i class="fas fa-eye"></i> عرض</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- عرض البطاقات للموبايل -->
    <div class="mobile-cards">
        @foreach($orders as $order)
            @php
                $statusClass = 'status-new';
                if($order->status == 'تحت التحميل') $statusClass = 'status-loading';
                elseif($order->status == 'تم التحميل') $statusClass = 'status-loaded';
                elseif($order->status == 'مؤجلة') $statusClass = 'status-delayed';
                elseif($order->status == 'ملغية') $statusClass = 'status-cancelled';
            @endphp
            <div class="order-card">
                <div class="card-header">
                    <span class="order-id"><i class="fas fa-hashtag"></i> طلبية #{{ $order->id }}</span>
                    <span class="status-badge {{ $statusClass }}">{{ $order->status }}</span>
                </div>

                <div class="card-row">
                    <div class="card-label"><i class="fas fa-user-tie"></i> العميل:</div>
                    <div class="card-value">{{ $order->customer_name }}</div>
                </div>

                <div class="card-row">
                    <div class="card-label"><i class="fas fa-user"></i> المندوب:</div>
                    <div class="card-value">{{ $order->user->name ?? '-' }}</div>
                </div>

                <div class="card-row">
                    <div class="card-label"><i class="fas fa-calendar-day"></i> التاريخ:</div>
                    <div class="card-value">{{ $order->date->format('Y-m-d') }}</div>
                </div>

                <div class="card-row">
                    <div class="card-label"><i class="fas fa-cubes"></i> الكمية:</div>
                    <div class="card-value"><strong>{{ number_format($order->items->sum('total')) }} متر</strong></div>
                </div>

                <!-- نموذج تحديث الحالة للموبايل -->
                <div class="status-update-form">
                    <form action="/factory/order/{{ $order->id }}/status" method="POST">
                        @csrf
                        <select name="status" class="form-select">
                            <option value="جديدة" {{ $order->status == 'جديدة' ? 'selected' : '' }}>جديدة</option>
                            <option value="تحت التحميل" {{ $order->status == 'تحت التحميل' ? 'selected' : '' }}>تحت التحميل</option>
                            <option value="تم التحميل" {{ $order->status == 'تم التحميل' ? 'selected' : '' }}>تم التحميل</option>
                            <option value="مؤجلة" {{ $order->status == 'مؤجلة' ? 'selected' : '' }}>مؤجلة</option>
                            <option value="ملغية" {{ $order->status == 'ملغية' ? 'selected' : '' }}>ملغية</option>
                        </select>
                        <input type="text" name="factory_notes" placeholder="ملاحظات المصنع (اختياري)" class="form-control" value="{{ $order->factory_notes }}">
                        <button type="submit" class="btn-primary"><i class="fas fa-save"></i> تحديث الحالة</button>
                    </form>
                </div>

                <div class="card-actions">
                    <a href="{{ route('orders.show', $order->id) }}" class="btn-info"><i class="fas fa-eye"></i> عرض التفاصيل</a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- روابط التصفح -->
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
</div>

<script>
    function toggleFilters() {
        const filtersCard = document.getElementById('filtersCard');
        filtersCard.classList.toggle('show');
    }

    // إغلاق الفلاتر تلقائياً عند الضغط على زر البحث في الموبايل
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.querySelector('#filtersCard form');
        if (filterForm) {
            filterForm.addEventListener('submit', function() {
                if (window.innerWidth < 768) {
                    setTimeout(function() {
                        document.getElementById('filtersCard').classList.remove('show');
                    }, 100);
                }
            });
        }
    });
</script>

</body>
</html>
