<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير الأصناف المنخفضة - جلوريا للسيراميك</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Segoe UI', 'Tahoma', Arial, sans-serif;
            direction: rtl;
            padding: 20px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            color: #1e3c72;
        }

        .header p {
            font-size: 12px;
            color: #666;
        }

        .info {
            margin-bottom: 20px;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 5px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #1e3c72;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #999;
        }

        .text-danger {
            color: #dc3545;
            font-weight: bold;
        }

        .text-warning {
            color: #ffc107;
            font-weight: bold;
        }

        @page {
            margin: 1.5cm;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>سيراميكا جلوريا</h1>
    <p>شركة الشرق لصناعة السيراميك - ش.م.م</p>
    <h2 style="margin-top: 15px; font-size: 18px;">تقرير الأصناف المنخفضة</h2>
    <p>تاريخ التقرير: {{ date('Y-m-d') }}</p>
</div>

<div class="info">
    <div class="info-row">
        <span><strong>إجمالي الأصناف المنخفضة:</strong></span>
        <span>{{ $products->count() }} صنف</span>
    </div>
    <div class="info-row">
        <span><strong>تاريخ التقرير:</strong></span>
        <span>{{ now()->format('Y-m-d H:i:s') }}</span>
    </div>
    @if(request('type'))
    <div class="info-row">
        <span><strong>نوع الصنف:</strong></span>
        <span>{{ request('type') }}</span>
    </div>
    @endif
</div>

@if($products->count() > 0)
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>كود الصنف</th>
            <th>اسم الصنف</th>
            <th>النوع</th>
            <th>الرصيد الحالي</th>
            <th>الحد الأدنى</th>
            <th>العجز</th>
            <th>الحالة</th>
        </thead>
        <tbody>
            @foreach($products as $index => $product)
                @php
                    $currentStock = $product->getCurrentStock();
                    $minStock = $product->stock?->min_stock ?? 50;
                    $shortage = $minStock - $currentStock;
                    $status = $currentStock <= 0 ? 'منفذ' : 'منخفض';
                    $statusClass = $currentStock <= 0 ? 'text-danger' : 'text-warning';
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->item_code }}</td>
                    <td style="text-align: right;">{{ $product->name }}</td>
                    <td>{{ $product->type ?? '-' }}</td>
                    <td class="{{ $statusClass }}">{{ number_format($currentStock) }}</td>
                    <td>{{ number_format($minStock) }}</td>
                    <td class="text-danger">{{ number_format($shortage) }}</td>
                    <td class="{{ $statusClass }}">{{ $status }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td colspan="4" style="text-align: left;">الإجمالي</td>
                <td>{{ number_format($products->sum(fn($p) => $p->getCurrentStock())) }}</td>
                <td>{{ number_format($products->sum(fn($p) => $p->stock?->min_stock ?? 50)) }}</td>
                <td>{{ number_format($products->sum(fn($p) => ($p->stock?->min_stock ?? 50) - $p->getCurrentStock())) }}</td>
                <td>-</td>
            </tr>
        </tfoot>
    </table>
@else
    <div style="text-align: center; padding: 50px; color: #28a745;">
        <h3>🎉 لا توجد أصناف منخفضة</h3>
        <p>جميع الأصناف متوفرة بكميات كافية</p>
    </div>
@endif

<div class="footer">
    <p>سيراميكا جلوريا - شركة الشرق لصناعة السيراميك ش.م.م</p>
    <p>هذا التقرير تم إنشاؤه تلقائياً بواسطة النظام | {{ date('Y-m-d H:i:s') }}</p>
</div>

</body>
</html>
