<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الصنف</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            margin: 0;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container { max-width: 1000px; margin: 0 auto; }
        h2 { text-align: center; color: #333; }
        .box {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .info-item {
            padding: 15px;
            background-color: #f9f9f9;
            border-right: 3px solid #007bff;
            padding-right: 20px;
        }
        .info-item strong { color: #333; display: block; margin-bottom: 5px; }
        .info-item span { color: #666; }
        .stock-alert {
            padding: 15px;
            border-radius: 3px;
            margin-bottom: 20px;
        }
        .stock-alert.warning {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .stock-alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 5px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }
        .btn-edit { background-color: #ffc107; color: #333; }
        .btn-edit:hover { background-color: #ffb300; }
        .btn-delete { background-color: #dc3545; color: white; }
        .btn-delete:hover { background-color: #c82333; }
        .btn-back { background-color: #6c757d; color: white; }
        .btn-back:hover { background-color: #5a6268; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <h2>📦 تفاصيل الصنف: {{ $product->name }}</h2>

    @if($product->isLowStock())
        <div class="stock-alert warning">
            ⚠️ <strong>تنبيه:</strong> الرصيد منخفض جداً! الرصيد الحالي {{ $product->getCurrentStock() }} والحد الأدنى {{ $product->stock?->min_stock }}
        </div>
    @else
        <div class="stock-alert success">
            ✅ الرصيد متوفر بكمية كافية
        </div>
    @endif

    <div class="box">
        <h3>📋 المعلومات الأساسية</h3>
        <div class="info-grid">
            <div class="info-item">
                <strong>كود الصنف:</strong>
                <span>{{ $product->item_code }}</span>
            </div>
            <div class="info-item">
                <strong>النوع:</strong>
                <span>{{ $product->type }}</span>
            </div>
            <div class="info-item">
                <strong>اسم الصنف:</strong>
                <span>{{ $product->name }}</span>
            </div>
            <div class="info-item">
                <strong>اللون:</strong>
                <span>{{ $product->color ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong>المقاس:</strong>
                <span>{{ $product->size ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong>الحالة:</strong>
                <span>{{ $product->is_active ? '✅ فعال' : '❌ معطل' }}</span>
            </div>
        </div>
    </div>

    <div class="box">
        <h3>📊 الرصيد</h3>
        <div class="info-grid">
            <div class="info-item">
                <strong>الرصيد الحالي:</strong>
                <span style="font-size: 24px; font-weight: bold; color: #007bff;">{{ $product->getCurrentStock() }}</span>
            </div>
            <div class="info-item">
                <strong>الحد الأدنى:</strong>
                <span style="font-size: 24px; font-weight: bold; color: #ffc107;">{{ $product->stock?->min_stock ?? '-' }}</span>
            </div>
        </div>
    </div>

    @if($product->orderItems->count() > 0)
    <div class="box">
        <h3>📑 الطلبيات التي تحتوي على هذا الصنف</h3>
        <table>
            <thead>
                <tr>
                    <th>رقم الطلبية</th>
                    <th>العميل</th>
                    <th>الكمية</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->orderItems->take(10) as $item)
                <tr>
                    <td>{{ $item->order->id }}</td>
                    <td>{{ $item->order->customer_name }}</td>
                    <td>{{ $item->total }}</td>
                    <td>{{ $item->order->date->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div style="text-align: center; margin: 20px 0;">
        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-edit">✏️ تعديل</a>
        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-delete" onclick="return confirm('هل أنت متأكد من الحذف؟')">🗑️ حذف</button>
        </form>
        <a href="{{ route('products.index') }}" class="btn btn-back">⬅️ رجوع</a>
    </div>

</div>

</body>
</html>
