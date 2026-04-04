<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>العملاء - جلوريا للسيراميك</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            direction: rtl;
            background-color: #f8f9fa;
            padding: 20px;
            padding-top: 90px;
        }
        .container { max-width: 1400px; margin: 0 auto; }

        .page-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
        }
        .stat-label {
            color: #666;
            font-size: 13px;
        }

        .filters-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .filter-group {
            display: inline-block;
            margin-left: 15px;
            margin-bottom: 10px;
        }

        .filter-group label {
            font-weight: bold;
            color: #555;
            font-size: 12px;
            display: block;
            margin-bottom: 5px;
        }

        .filter-group input, .filter-group select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            min-width: 180px;
        }

        .btn-filter {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-reset {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .table-wrapper {
            background: white;
            border-radius: 15px;
            overflow-x: auto;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #eee;
            padding: 12px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background: #f8f9fa;
            font-weight: bold;
            position: sticky;
            top: 0;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-agent { background: #007bff; color: white; }
        .badge-dealer { background: #28a745; color: white; }
        .badge-cash { background: #6c757d; color: white; }

        .btn-sm {
            padding: 5px 12px;
            font-size: 12px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-primary { background: #007bff; color: white; }
        .btn-primary:hover { background: #0056b3; color: white; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-warning:hover { background: #e0a800; color: #212529; }

        .discount-form {
            display: inline-flex;
            gap: 5px;
            align-items: center;
        }
        .discount-input {
            width: 70px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-align: center;
        }

        .pagination-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 20px;
            padding: 15px;
            background: white;
            border-radius: 8px;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        @media (max-width: 768px) {
            body { padding: 10px; padding-top: 80px; }
            .filter-group { display: block; margin-left: 0; }
            .filter-group input, .filter-group select { width: 100%; }
            th, td { padding: 8px; font-size: 12px; }
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-users"></i> قائمة العملاء</h1>
        <p>إدارة العملاء والوكلاء والتجار - مسحوبات العملاء</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ number_format($totalCustomers) }}</div>
            <div class="stat-label">إجمالي العملاء</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($totalWithdrawals, 2) }}</div>
            <div class="stat-label">إجمالي المسحوبات (ج.م)</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($totalAgents) }}</div>
            <div class="stat-label">الوكلاء</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($totalDealers) }}</div>
            <div class="stat-label">التجار</div>
        </div>
    </div>

    <!-- ===== فلترة وبحث ===== -->
    <div class="filters-card">
        <h5><i class="fas fa-filter"></i> فلترة العملاء</h5>
        <form method="GET" action="{{ route('accounting.customers') }}">
            <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                <div class="filter-group">
                    <label>اسم العميل</label>
                    <input type="text" name="customer_name" placeholder="بحث باسم العميل" value="{{ request('customer_name') }}">
                </div>
                <div class="filter-group">
                    <label>نوع العميل</label>
                    <select name="type">
                        <option value="">-- الكل --</option>
                        <option value="agent" {{ request('type') == 'agent' ? 'selected' : '' }}>وكيل</option>
                        <option value="dealer" {{ request('type') == 'dealer' ? 'selected' : '' }}>تاجر</option>
                        <option value="cash" {{ request('type') == 'cash' ? 'selected' : '' }}>عميل نقدي</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>المقاس</label>
                    <select name="size">
                        <option value="">-- جميع المقاسات --</option>
                        @foreach($sizes as $size)
                            <option value="{{ $size }}" {{ request('size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn-filter"><i class="fas fa-search"></i> بحث</button>
                    <a href="{{ route('accounting.customers') }}" class="btn-reset"><i class="fas fa-times"></i> إلغاء</a>
                </div>
            </div>
        </form>
    </div>

    <div class="table-wrapper">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 15%">كود العميل</th>
                    <th style="width: 30%">اسم العميل</th>
                    <th style="width: 10%">نوع العميل</th>
                    <th style="width: 10%">نسبة الخصم</th>
                    <th style="width: 15%">إجمالي المسحوبات (ج.م)</th>
                    <th style="width: 15%">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $index => $customer)
                <tr>
                    <td>{{ $customers->firstItem() + $index }}</td>
                    <td><strong>{{ $customer->code ?? '-' }}</strong></td>
                    <td style="text-align: right;">{{ $customer->name }}</td>
                    <td>
                        @if($customer->type == 'agent')
                            <span class="badge badge-agent">وكيل</span>
                        @elseif($customer->type == 'dealer')
                            <span class="badge badge-dealer">تاجر</span>
                        @else
                            <span class="badge badge-cash">عميل نقدي</span>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('accounting.updateDiscount', $customer->id) }}" class="discount-form">
                            @csrf
                            @method('PUT')
                            <input type="number" name="discount_rate" step="0.01" value="{{ $customer->discount_rate }}" class="discount-input" style="width: 70px;">
                            <button type="submit" class="btn-sm btn-warning"><i class="fas fa-save"></i></button>
                        </form>
                    </td>
                    <td>
                        <strong class="text-primary">{{ number_format($customer->total_withdrawals, 2) }}</strong>
                    </td>
                    <td>
                        <a href="{{ route('accounting.customer.statement', $customer->id) }}" class="btn-sm btn-primary">
                            <i class="fas fa-chart-line"></i> كشف حساب
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">لا توجد بيانات عملاء</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($customers->hasPages())
        <div class="pagination-custom">
            <div>{{ $customers->firstItem() }} - {{ $customers->lastItem() }} من {{ $customers->total() }}</div>
            <div>{{ $customers->links('pagination::bootstrap-4') }}</div>
        </div>
    @endif

    <div class="text-center mt-4">
        <a href="{{ route('accounting.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة للوحة
        </a>
    </div>
</div>

</body>
</html>
