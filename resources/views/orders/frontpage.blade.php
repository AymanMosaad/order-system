<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial; direction: rtl; margin: 20px; }
        h1 { text-align: center; margin-bottom: 20px; }
        .stats { display: flex; gap: 20px; margin-bottom: 20px; }
        .stat { flex: 1; background-color: #f0f0f0; padding: 15px; border-radius: 5px; text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #333; color: white; }
        a { text-decoration: none; color: white; background-color: #333; padding: 5px 10px; border-radius: 3px; }
    </style>
</head>
<body>
@include('layouts.navbar')
<h1>📊 لوحة التحكم الرئيسية</h1>

<div class="stats">
    <div class="stat">
        <h3>عدد الطلبات</h3>
        <p>{{ $totalOrders }}</p>
    </div>
    <div class="stat">
        <h3>إجمالي الكميات</h3>
        <p>{{ $totalItems }}</p>
    </div>
</div>

<h2>آخر الطلبات</h2>
<table>
    <thead>
        <tr>
            <th>رقم الإذن</th>
            <th>اسم العميل</th>
            <th>التاريخ</th>
            <th>عدد الأصناف</th>
            <th>الإجمالي</th>
            <th>عرض</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->order_number ?? '-' }}</td>
            <td>{{ $order->customer_name }}</td>
            <td>{{ $order->date }}</td>
            <td>{{ $order->items->count() }}</td>
            <td>{{ $order->items->sum('total') }}</td>
            <td><a href="{{ route('orders.show', $order->id) }}">عرض</a></td>
        </tr>
        @endforeach
    </tbody>
</table>

<br>
<a href="{{ route('orders.create') }}">➕ إنشاء طلب جديد</a>

</body>
</html>
