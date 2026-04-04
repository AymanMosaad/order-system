<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>لوحة المحاسب - جلوريا للسيراميك</title>
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
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
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

        .info-box {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .box-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #007bff;
            display: inline-block;
        }

        .table-wrapper {
            overflow-x: auto;
            margin-top: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #eee;
            padding: 10px;
            text-align: center;
            font-size: 13px;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
        }
        tr:hover {
            background: #f5f5f5;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 6px;
            text-decoration: none;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background: #0056b3;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        @media (max-width: 768px) {
            body { padding: 10px; padding-top: 80px; }
            .stats-grid { grid-template-columns: 1fr; gap: 15px; }
            .stat-number { font-size: 22px; }
            th, td { padding: 8px; font-size: 11px; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-chart-line"></i> لوحة المحاسب</h1>
        <p>نظرة عامة على المعاملات المالية والعملاء</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ number_format($totalCustomers) }}</div>
            <div class="stat-label">إجمالي العملاء</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($totalWithdrawals, 2) }}</div>
            <div class="stat-label">إجمالي المسحوبات (متر)</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($pendingCheques, 2) }}</div>
            <div class="stat-label">شيكات معلقة</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($totalOrders) }}</div>
            <div class="stat-label">إجمالي الطلبيات</div>
        </div>
    </div>

    <div class="info-box">
        <div class="box-title"><i class="fas fa-trophy"></i> أكبر 5 عملاء</div>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 10%">#</th>
                        <th style="width: 60%">اسم العميل</th>
                        <th style="width: 30%">إجمالي المسحوبات</th>
                    </thead>
                <tbody>
                    @foreach($topCustomers as $index => $customer)
                    <tr>
                        <td>{{ $index + 1 }} </td>
                        <td><strong>{{ $customer->name }}</strong> </td>
                        <td>{{ number_format($customer->total_withdrawals, 2) }} </td>
                    </tr>
                    @endforeach
                </tbody>
             </table
        </div>
    </div>

    <div class="info-box">
        <div class="box-title"><i class="fas fa-clock"></i> آخر الطلبيات</div>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 15%">#</th>
                        <th style="width: 25%">التاريخ</th>
                        <th style="width: 40%">العميل</th>
                        <th style="width: 20%">الإجمالي</th>
                    </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                     <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->date->format('Y-m-d') }}</td>
                        <td style="text-align: right;">
                            @if($order->customer)
                                <a href="{{ route('accounting.customer.statement', $order->customer->id) }}" class="btn-sm btn-primary">
                                    <i class="fas fa-user"></i> {{ $order->customer->name }}
                                </a>
                            @else
                                <span class="badge bg-secondary">عميل نقدي</span>
                            @endif
                        </td>
                        <td><strong>{{ number_format($order->items->sum('total'), 2) }}</strong></td>
                     </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>لا توجد طلبيات بعد</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="text-center mt-3">
            <a href="{{ route('accounting.customers') }}" class="btn btn-primary">
                <i class="fas fa-users"></i> عرض جميع العملاء
            </a>
            <a href="{{ route('accounting.cheques') }}" class="btn btn-secondary">
                <i class="fas fa-money-check"></i> عرض الشيكات
            </a>
        </div>
    </div>
</div>

</body>
</html>
