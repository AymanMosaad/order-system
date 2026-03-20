<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الأصناف</title>
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
        .summary {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .summary-item {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .summary-item h4 {
            margin: 0 0 10px 0;
            color: #666;
            font-size: 14px;
        }
        .summary-item p {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
        }
        .btn-print {
            display: inline-block;
            padding: 10px 20px;
            background-color: #17a2b8;
            color: white;
            text-decoration: none;
            border-radius: 3px;
            margin-bottom: 20px;
        }
        .btn-print:hover { background-color: #138496; }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            margin-bottom: 30px;
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
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-top: 30px;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #e9ecef;
            border-right: 4px solid #007bff;
            padding-right: 15px;
        }
        .warning-badge {
            background-color: #f8d7da;
            color: #721c24;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 12px;
        }
        .success-badge {
            background-color: #d4edda;
            color: #155724;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 12px;
        }
        @media print {
            .btn-print { display: none; }
            body { background-color: white; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <h2>📊 تقرير الأصناف والأرصدة</h2>

    <button onclick="window.print()" class="btn-print">🖨️ طباعة</button>

    <div class="summary">
        <div class="summary-item">
            <h4>📦 إجمالي الأصناف</h4>
            <p>{{ $products->count() }}</p>
        </div>
        <div class="summary-item">
            <h4>⚠️ أصناف ناقصة</h4>
            <p>{{ $lowStockProducts->count() }}</p>
        </div>
    </div>

    @if($lowStockProducts->count() > 0)
    <div class="section-title">⚠️ الأصناف الناقصة (تحتاج إعادة طلب)</div>
    <table>
        <thead>
            <tr>
                <th>كود الصنف</th>
                <th>اسم الصنف</th>
                <th>النوع</th>
                <th>الرصيد الحالي</th>
                <th>الحد الأدنى</th>
                <th>الفارق</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lowStockProducts as $product)
            <tr>
                <td><strong>{{ $product['item_code'] }}</strong></td>
                <td>{{ $product['name'] }}</td>
                <td>{{ $product['type'] }}</td>
                <td>
                    <span class="warning-badge">{{ $product['current_stock'] }}</span>
                </td>
                <td>{{ $product['min_stock'] }}</td>
                <td>
                    <strong style="color: red;">
                        {{ $product['min_stock'] - $product['current_stock'] }}
                    </strong>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="section-title">🏆 أكثر الأصناف مبيعاً</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>كود الصنف</th>
                <th>اسم الصنف</th>
                <th>النوع</th>
                <th>إجمالي المبيعات</th>
                <th>الرصيد الحالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topSoldProducts as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $product['item_code'] }}</strong></td>
                <td>{{ $product['name'] }}</td>
                <td>{{ $product['type'] }}</td>
                <td style="font-weight: bold;">{{ $product['total_sold'] }}</td>
                <td>
                    @if($product['is_low'])
                        <span class="warning-badge">{{ $product['current_stock'] }}</span>
                    @else
                        <span class="success-badge">{{ $product['current_stock'] }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">📋 جميع الأصناف</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>كود الصنف</th>
                <th>اسم الصنف</th>
                <th>النوع</th>
                <th>اللون</th>
                <th>المقاس</th>
                <th>الرصيد</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product['item_code'] }}</td>
                <td>{{ $product['name'] }}</td>
                <td>{{ $product['type'] }}</td>
                <td>{{ $product['color'] ?? '-' }}</td>
                <td>{{ $product['size'] ?? '-' }}</td>
                <td>{{ $product['current_stock'] }}</td>
                <td>
                    @if($product['is_low'])
                        <span class="warning-badge">⚠️ ناقص</span>
                    @else
                        <span class="success-badge">✅ متوفر</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

</body>
</html>
