<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقرير الرئيسي - جلوريا للسيراميك</title>
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

        .filters {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .filter-group {
            display: inline-block;
            margin: 0 10px 10px 0;
        }
        .filter-group label {
            font-weight: bold;
            color: #555;
            margin-left: 8px;
        }
        .filter-group input, .filter-group select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }
        .btn-filter {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
        }
        .btn-reset {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            margin: 10px 0;
        }
        .stat-label {
            color: #666;
            font-size: 14px;
        }

        .section-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .section-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #007bff;
            display: inline-block;
        }

        .type-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .type-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s;
        }
        .type-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .type-name {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .type-quantity {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #eee;
            padding: 12px;
            text-align: center;
        }
        th {
            background: #f8f9fa;
            color: #333;
            font-weight: bold;
        }

        .btn-print {
            background: #17a2b8;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .filter-group { display: block; margin: 10px 0; }
            .filter-group input, .filter-group select { width: 100%; }
            .type-grid { grid-template-columns: 1fr; }
        }

        @media print {
            .filters, .btn-print, .navbar { display: none; }
            body { background: white; padding: 0; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="header">
        <h1>📊 التقرير الرئيسي للمبيعات</h1>
        <p>نظرة شاملة على المبيعات حسب نوع الصنف ونوع المخزن</p>
    </div>

    <button onclick="window.print()" class="btn-print">🖨️ طباعة التقرير</button>

    <div class="filters">
        <form method="GET" action="{{ route('orders.report') }}">
            <div class="filter-group">
                <label>من تاريخ:</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}">
            </div>
            <div class="filter-group">
                <label>إلى تاريخ:</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}">
            </div>
            <div class="filter-group">
                <label>نوع المخزن:</label>
                <select name="warehouse_type">
                    <option value="">-- الكل --</option>
                    <option value="محلي" {{ request('warehouse_type') == 'محلي' ? 'selected' : '' }}>محلي</option>
                    <option value="تصدير" {{ request('warehouse_type') == 'تصدير' ? 'selected' : '' }}>تصدير</option>
                    <option value="معرض بيع" {{ request('warehouse_type') == 'معرض بيع' ? 'selected' : '' }}>معرض بيع</option>
                    <option value="احتكار" {{ request('warehouse_type') == 'احتكار' ? 'selected' : '' }}>احتكار</option>
                </select>
            </div>
            <button type="submit" class="btn-filter">🔍 بحث</button>
            <a href="{{ route('orders.report') }}" class="btn-reset">🗑️ إلغاء</a>
        </form>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">📋 إجمالي الطلبيات</div>
            <div class="stat-value">{{ number_format($totalOrders) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">📦 إجمالي القطع</div>
            <div class="stat-value">{{ number_format($totalQuantity) }}</div>
        </div>
    </div>

    <!-- تقرير حسب نوع الصنف -->
    <div class="section-card">
        <div class="section-title">🏷️ المبيعات حسب نوع الصنف</div>
        <div class="type-grid">
            @foreach($typeSales as $type => $quantity)
                @if($quantity > 0 || $loop->first)
                <div class="type-card">
                    <div class="type-name">{{ $type }}</div>
                    <div class="type-quantity">{{ number_format($quantity) }}</div>
                </div>
                @endif
            @endforeach
        </div>
    </div>

    <!-- تقرير حسب نوع المخزن -->
    <div class="section-card">
        <div class="section-title">🏭 المبيعات حسب نوع المخزن</div>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>نوع المخزن</th>
                        <th>عدد الطلبيات</th>
                        <th>إجمالي القطع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($warehouseStats as $warehouse => $data)
                    <tr>
                        <td><strong>{{ $warehouse }}</strong></td>
                        <td>{{ $data['orders_count'] }}</td>
                        <td>{{ number_format($data['total_quantity']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
