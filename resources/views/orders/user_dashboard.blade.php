<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - جلوريا للسيراميك</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Tahoma', Arial, sans-serif;
            direction: rtl;
            margin: 0;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container { max-width: 1400px; margin: 0 auto; }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            text-align: center;
        }
        .header h1 { margin: 0 0 10px 0; font-size: 28px; }
        .header p { margin: 0; opacity: 0.9; font-size: 16px; }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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

        .box {
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

        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #eee; padding: 12px; text-align: center; }
        th {
            background: #f8f9fa;
            color: #333;
            font-weight: bold;
            border-bottom: 2px solid #dee2e6;
        }
        tr:hover { background: #f5f5f5; }

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
        .btn-success { background-color: #28a745; }
        .btn-success:hover { background-color: #218838; }

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

        @media (max-width: 768px) {
            body { padding: 10px; }
            .stats-grid { grid-template-columns: 1fr; gap: 15px; }
            table { font-size: 12px; }
            th, td { padding: 8px; }
            .btn { padding: 8px 15px; font-size: 12px; }
        }

        @media print {
            .btn, .header { display: none; }
            body { background: white; padding: 0; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="header">
        <h1>📊 لوحة التحكم الرئيسية</h1>
        <p>مرحباً {{ Auth::user()->name }}، هذه نظرة عامة على طلبياتك</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card" onclick="window.location='{{ route('orders.index') }}'">
            <div class="stat-icon">📋</div>
            <div class="stat-number">{{ number_format($totalOrders) }}</div>
            <div class="stat-label">إجمالي الطلبيات</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-number">{{ number_format($totalItems) }}</div>
            <div class="stat-label">إجمالي الكميات</div>
        </div>
    </div>

    <div class="box">
        <div class="box-title">🕒 آخر الطلبيات</div>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>رقم الإذن</th>
                        <th>اسم العميل</th>
                        <th>التاريخ</th>
                        <th>عدد الأصناف</th>
                        <th>الإجمالي</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number ?? '-' }}</strong></td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->date }}</td>
                        <td>{{ $order->items->count() }}</td>
                        <td>{{ number_format($order->items->sum('total')) }}</td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="view-link">👁️ عرض</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="icon">📭</div>
                                <p>لا توجد طلبيات حتى الآن</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ route('orders.create') }}" class="btn btn-success">➕ إنشاء طلب جديد</a>
            <a href="{{ route('orders.index') }}" class="btn">📋 كل طلبياتي</a>
        </div>
    </div>
</div>

</body>
</html>
