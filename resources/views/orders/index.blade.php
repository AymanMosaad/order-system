<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كل الطلبيات</title>
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
        h2 { text-align: center; color: #333; margin-bottom: 30px; }
        .btn {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 3px;
            display: inline-block;
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
        .btn-view {
            background-color: #007bff;
            padding: 5px 10px;
            font-size: 12px;
            text-decoration: none;
            color: white;
            border-radius: 3px;
        }
        .btn-view:hover { background-color: #0056b3; }
        .status {
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 12px;
        }
        .status-new { background-color: #d4edda; color: #155724; }
        .status-processing { background-color: #fff3cd; color: #856404; }
        .status-completed { background-color: #d1ecf1; color: #0c5460; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .pagination { text-align: center; margin-top: 20px; }
        .pagination a, .pagination span {
            padding: 5px 10px;
            margin: 0 3px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
        }
        .pagination .active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <h2>📋 كل الطلبيات</h2>

    <a href="{{ route('orders.create') }}" class="btn">➕ طلبية جديدة</a>

    @if($orders->count() > 0)
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
                    <td>{{ $order->id }}</td>
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
                        <a href="{{ route('orders.show', $order->id) }}" class="btn-view">عرض</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {{ $orders->links() }}
        </div>
    @else
        <p style="text-align: center; padding: 40px; color: #666;">لا توجد طلبيات حالياً</p>
    @endif

</div>

</body>
</html>
