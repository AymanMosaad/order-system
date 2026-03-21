<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الطلبية</title>
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
        h2 { text-align: center; color: #333; }
        .box {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            background-color: white;
            border-radius: 5px;
        }
        .info-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }
        .info-item {
            padding: 10px;
            background-color: #f9f9f9;
            border-right: 3px solid #007bff;
            padding-right: 15px;
        }
        .info-item strong { color: #333; }
        .info-item span { color: #666; display: block; margin-top: 5px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
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
        .btn {
            padding: 10px 20px;
            margin: 10px 5px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }
        .btn-edit { background-color: #ffc107; color: #333; }
        .btn-edit:hover { background-color: #ffb300; }
        .btn-delete { background-color: #dc3545; color: white; }
        .btn-delete:hover { background-color: #c82333; }
        .btn-back { background-color: #6c757d; color: white; }
        .btn-back:hover { background-color: #5a6268; }
        .btn-print { background-color: #17a2b8; color: white; }
        .btn-print:hover { background-color: #138496; }
        .actions { text-align: center; margin: 20px 0; }
        @media print {
            .actions, .btn-print, nav { display: none; }
            body { background-color: white; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <h2>📄 تفاصيل الطلبية #{{ $order->id }}</h2>

    <div class="box">
        <div class="info-row">
            <div class="info-item">
                <strong>اسم العميل:</strong>
                <span>{{ $order->customer_name }}</span>
            </div>
            <div class="info-item">
                <strong>اسم التاجر:</strong>
                <span>{{ $order->trader_name ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong>رقم الإذن:</strong>
                <span>{{ $order->order_number ?? '-' }}</span>
            </div>
        </div>

        <div class="info-row">
            <div class="info-item">
                <strong>نوع المخزن:</strong>
                <span>{{ $order->warehouse_type ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong>المنط��ة:</strong>
                <span>{{ $order->address ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong>رقم الهاتف:</strong>
                <span>{{ $order->phone ?? '-' }}</span>
            </div>
        </div>

        <div class="info-row">
            <div class="info-item">
                <strong>اسم السائق:</strong>
                <span>{{ $order->driver_name ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong>التاريخ:</strong>
                <span>{{ $order->date->format('Y-m-d') }}</span>
            </div>
            <div class="info-item">
                <strong>الحالة:</strong>
                <span>{{ $order->status }}</span>
            </div>
        </div>

        @if($order->notes)
        <div class="info-row">
            <div class="info-item" style="grid-column: 1/-1;">
                <strong>ملاحظات:</strong>
                <span>{{ $order->notes }}</span>
            </div>
        </div>
        @endif
    </div>

    <h3>📦 الأصناف</h3>
    <table>
        <thead>
            <tr>
                <th>كود الصنف</th>
                <th>النوع</th>
                <th>اسم الصنف</th>
                <th>اللون</th>
                <th>المقاس</th>
                <th>فرز أول</th>
                <th>فرز ثاني</th>
                <th>فرز ثالث</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @forelse($order->items as $item)
            <tr>
                <td>{{ $item->item_code }}</td>
                <td>{{ $item->type ?? '-' }}</td>
                <td>{{ $item->name ?? '-' }}</td>
                <td>{{ $item->color ?? '-' }}</td>
                <td>{{ $item->size ?? '-' }}</td>
                <td>{{ $item->grade1 ?? 0 }}</td>
                <td>{{ $item->grade2 ?? 0 }}</td>
                <td>{{ $item->grade3 ?? 0 }}</td>
                <td><strong>{{ $item->total ?? 0 }}</strong></td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="color: #999;">لا توجد أصناف في هذه الطلبية</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f0f0f0;">
                <td colspan="8" style="text-align: right; font-weight: bold;">الإجمالي:</td>
                <td style="font-weight: bold;">{{ $order->getTotalQuantity() }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="actions">
        @auth
            @if(Auth::id() === $order->user_id)
                <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-edit">✏️ تعديل</a>
                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delete" onclick="return confirm('هل أنت متأكد من الحذف؟')">🗑️ حذف</button>
                </form>
            @endif
        @endauth

        <button onclick="window.print()" class="btn btn-print">🖨️ طباعة</button>
        <a href="{{ route('orders.index') }}" class="btn btn-back">⬅️ رجوع</a>
    </div>

</div>

</body>
</html>
