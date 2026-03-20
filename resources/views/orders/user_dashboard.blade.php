<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            margin: 0;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container { max-width: 1200px; margin: 0 auto; }
        h2, h3 { text-align: center; color: #333; }
        .stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 30px 0;
        }
        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-box h3 { margin-top: 0; color: #007bff; }
        .stat-box p { font-size: 28px; font-weight: bold; color: #333; }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 3px;
            margin-bottom: 20px;
        }
        .btn:hover { background-color: #218838; }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #333;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f0f0f0; }
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            margin: 2px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            border-radius: 3px;
        }
        .btn-edit {
            background-color: #ffc107;
            color: #333;
        }
        .btn-edit:hover { background-color: #ffb300; }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        .btn-delete:hover { background-color: #c82333; }
        .btn-view {
            background-color: #17a2b8;
            color: white;
        }
        .btn-view:hover { background-color: #138496; }
        .status {
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 12px;
        }
        .status-new { background-color: #d4edda; color: #155724; }
        .status-processing { background-color: #fff3cd; color: #856404; }
        .status-completed { background-color: #d1ecf1; color: #0c5460; }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <h2>👤 لوحة التحكم - {{ auth()->user()->name }}</h2>

    <div class="stats">
        <div class="stat-box">
            <h3>📋 الطلبيات</h3>
            <p>{{ $totalOrders }}</p>
        </div>
        <div class="stat-box">
            <h3>📦 إجمالي الكميات</h3>
            <p>{{ $totalItems }}</p>
        </div>
    </div>

    <a href="{{ route('orders.create') }}" class="btn">➕ طلبية جديدة</a>

    <h3>📊 طلباتك</h3>

    @if($orders->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>ر��م الإذن</th>
                    <th>العميل</th>
                    <th>نوع المخزن</th>
                    <th>التاريخ</th>
                    <th>الأصناف</th>
                    <th>الكمية</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->order_number ?? '-' }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->warehouse_type ?? '-' }}</td>
                    <td>{{ $order->date->format('Y-m-d') }}</td>
                    <td>{{ $order->getItemsCount() }}</td>
                    <td>{{ $order->getTotalQuantity() }}</td>
                    <td>
                        <span class="status status-{{ str_replace('ة', '', str_replace('ا', '', strtolower($order->status))) }}">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('orders.show', $order->id) }}" class="btn-sm btn-view">عرض</a>
                        <a href="{{ route('orders.edit', $order->id) }}" class="btn-sm btn-edit">تعديل</a>
                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-sm btn-delete" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="text-align: center; margin-top: 20px;">
            {{ $orders->links() }}
        </div>
    @else
        <p style="text-align: center; padding: 40px; color: #666; background-color: white; border-radius: 5px;">
            لا توجد طلبيات حالياً. <a href="{{ route('orders.create') }}">إنشاء طلبية جديدة</a>
        </p>
    @endif

</div>

</body>
</html>
