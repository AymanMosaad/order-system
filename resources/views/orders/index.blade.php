<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كل الطلبيات - جلوريا للسيراميك</title>
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
            padding: 25px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }
        .header h1 { margin: 0 0 5px 0; font-size: 24px; }
        .header p  { margin: 0; opacity: 0.85; font-size: 14px; }

        .card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .btn-new {
            display: inline-flex;
            align-items: center;
            gap: 7px;
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

        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #eee; padding: 12px; text-align: center; font-size: 14px; }
        th {
            background: #f8f9fa;
            color: #333;
            font-weight: bold;
            border-bottom: 2px solid #dee2e6;
        }
        tr:nth-child(even) { background-color: #fafafa; }
        tr:hover { background-color: #f0f4ff; }

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

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        .empty-state .icon { font-size: 64px; margin-bottom: 15px; }
        .empty-state p { font-size: 16px; margin: 5px 0; }

        .pagination { text-align: center; margin-top: 25px; }
        .pagination a, .pagination span {
            padding: 6px 12px;
            margin: 0 3px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
            border-radius: 6px;
            display: inline-block;
        }
        .pagination .active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        @media (max-width: 768px) {
            .header { flex-direction: column; text-align: center; }
            th, td { padding: 8px; font-size: 12px; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">

    <div class="header">
        <div>
            <h1>📋 كل الطلبيات</h1>
            <p>إدارة ومتابعة جميع الطلبيات</p>
        </div>
        <a href="{{ route('orders.create') }}" class="btn-new">➕ طلبية جديدة</a>
    </div>

    <div class="card">
        @if($orders->count() > 0)
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الموظف</th>
                            <th>اسم العميل</th>
                            <th>اسم التاجر</th>
                            <th>رقم الإذن</th>
                            <th>المنطقة</th>
                            <th>التاريخ</th>
                            <th>الحالة</th>
                            <th>الأصناف</th>
                            <th>الإجمالي</th>
                            <th>الإجراءات</th>
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
                                <span class="status status-{{ str_replace('ة', '', str_replace('ا', '', strtolower($order->status))) }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>{{ $order->getItemsCount() }}</td>
                            <td>{{ $order->getTotalQuantity() }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn-view">👁️ عرض</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $orders->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="icon">📭</div>
                <p>لا توجد طلبيات حالياً</p>
                <p style="font-size: 14px;">ابدأ بإنشاء أول طلبية</p>
                <a href="{{ route('orders.create') }}" class="btn-new" style="margin-top: 15px;">➕ إنشاء طلبية</a>
            </div>
        @endif
    </div>

</div>

</body>
</html>
