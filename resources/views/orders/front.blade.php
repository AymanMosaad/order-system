<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقارير</title>
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
        h3 { color: #007bff; margin-top: 30px; padding-bottom: 10px; border-bottom: 2px solid #007bff; }
        .summary-box {
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
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
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
        .warehouse-title {
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
        .empty-message {
            text-align: center;
            padding: 60px 20px;
            color: #999;
            font-size: 18px;
            background-color: white;
            border-radius: 5px;
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
        @media print {
            .btn-print { display: none; }
            body { background-color: white; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <h2>🏭 جلوريا للسيراميك والبورسلين - تقرير الطلبات</h2>

    <button onclick="window.print()" class="btn-print">🖨️ طباعة</button>

    @if($totalOrders > 0)
        <div class="summary-box">
            <div class="summary-item">
                <h4>📋 إجمالي الطلبات</h4>
                <p>{{ $totalOrders }}</p>
            </div>
            <div class="summary-item">
                <h4>📦 إجمالي الأصناف</h4>
                <p>{{ $totalItems }}</p>
            </div>
        </div>

        @forelse($typeStats as $warehouse => $stats)
            @if($stats['orders_count'] > 0 && $stats['total_items'] > 0)
                <div class="warehouse-title">{{ $warehouse }}</div>

                <table>
                    <thead>
                        <tr>
                            <th>عدد الطلبات</th>
                            <th>إجمالي الأصناف</th>
                            <th>حوائط جلوريا</th>
                            <th>حوائط ايكو</th>
                            <th>ارضيات ايكو</th>
                            <th>ارضيات HDC</th>
                            <th>ارضيات UGC</th>
                            <th>PORSLIM</th>
                            <th>SUPER GLOSSY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>{{ $stats['orders_count'] }}</strong></td>
                            <td><strong>{{ $stats['total_items'] }}</strong></td>
                            <td>{{ $stats['by_type']['حوائط جلوريا'] ?? 0 }}</td>
                            <td>{{ $stats['by_type']['حوائط ايكو'] ?? 0 }}</td>
                            <td>{{ $stats['by_type']['ارضيات ايكو'] ?? 0 }}</td>
                            <td>{{ $stats['by_type']['ارضيات HDC'] ?? 0 }}</td>
                            <td>{{ $stats['by_type']['ارضيات UGC'] ?? 0 }}</td>
                            <td>{{ $stats['by_type']['PORSLIM'] ?? 0 }}</td>
                            <td>{{ $stats['by_type']['SUPER GLOSSY'] ?? 0 }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif
        @empty
            <div class="empty-message">
                📊 لا توجد بيانات لعرضها حالياً
            </div>
        @endforelse

    @else
        <div class="empty-message">
            📊 لا توجد طلبيات في النظام حالياً
        </div>
    @endif

</div>

</body>
</html>
