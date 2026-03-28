<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير متقدم - جلوريا للسيراميك</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Tahoma', Arial, sans-serif;
            direction: rtl;
            margin: 0;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container { max-width: 1600px; margin: 0 auto; }

        .report-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }
        .report-header h1 { margin: 0 0 10px 0; font-size: 28px; }
        .report-header p  { margin: 0; opacity: 0.9; }

        .filters {
            background: white;
            padding: 20px 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }
        .filter-group { display: flex; flex-direction: column; gap: 6px; }
        .filter-group label { font-weight: bold; color: #555; font-size: 13px; }
        .filter-group input, .filter-group select {
            padding: 9px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Tahoma', Arial, sans-serif;
            min-width: 155px;
        }
        .btn-filter {
            background: #007bff; color: white;
            border: none; padding: 9px 22px;
            border-radius: 8px; cursor: pointer;
            font-size: 14px; font-weight: bold;
            transition: all 0.3s; white-space: nowrap;
        }
        .btn-filter:hover { background: #0056b3; transform: translateY(-1px); }
        .btn-reset {
            background: #6c757d; color: white;
            border: none; padding: 9px 22px;
            border-radius: 8px; cursor: pointer;
            font-size: 14px; font-weight: bold;
            text-decoration: none; display: inline-block;
            transition: all 0.3s; white-space: nowrap;
        }
        .btn-reset:hover { background: #545b62; }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.07);
            transition: transform 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-icon  { font-size: 40px; margin-bottom: 10px; }
        .stat-value { font-size: 32px; font-weight: bold; color: #007bff; margin: 10px 0; }
        .stat-label { color: #666; font-size: 14px; }

        .section-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
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

        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #eee; padding: 12px; text-align: center; font-size: 14px; }
        th {
            background: #f8f9fa;
            color: #333;
            font-weight: bold;
            border-bottom: 2px solid #dee2e6;
        }
        tr:hover { background: #f5f5f5; }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger  { background: #f8d7da; color: #721c24; }
        .badge-info    { background: #d1ecf1; color: #0c5460; }

        .total-row { background: #e9ecef !important; font-weight: bold; }

        .progress-bar-wrap {
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            height: 22px;
        }
        .progress-bar-fill {
            background: linear-gradient(90deg, #007bff, #667eea);
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 11px;
            font-weight: bold;
            min-width: 30px;
            border-radius: 10px;
        }

        .search-bar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .search-bar input {
            padding: 8px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Tahoma', Arial, sans-serif;
            width: 260px;
        }

        .btn-print {
            background: #17a2b8; color: white;
            border: none; padding: 10px 22px;
            border-radius: 8px; cursor: pointer;
            font-size: 14px; font-weight: bold;
            margin-bottom: 20px; transition: all 0.3s;
        }
        .btn-print:hover { background: #138496; transform: translateY(-1px); }

        @media (max-width: 768px) {
            .filter-row { flex-direction: column; }
            .filter-group input, .filter-group select { min-width: 100%; }
            .search-bar input { width: 100%; }
            th, td { padding: 8px; font-size: 12px; }
        }

        @media print {
            .filters, .btn-print, .search-bar button { display: none; }
            body { background: white; padding: 0; }
            .stat-card { break-inside: avoid; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="report-header">
        <h1>📊 التقرير المتقدم</h1>
        <p>نظرة شاملة على أداء الطلبيات والمبيعات</p>
    </div>

    <button onclick="window.print()" class="btn-print">🖨️ طباعة التقرير</button>

    <div class="filters">
        <form method="GET" action="{{ route('orders.advancedReport') }}">
            <div class="filter-row">
                <div class="filter-group">
                    <label>من تاريخ:</label>
                    <input type="date" name="from_date" value="{{ $fromDate ?? '' }}">
                </div>
                <div class="filter-group">
                    <label>إلى تاريخ:</label>
                    <input type="date" name="to_date" value="{{ $toDate ?? '' }}">
                </div>
                <div class="filter-group">
                    <label>نوع المخزن:</label>
                    <select name="warehouse_type">
                        <option value="">الكل</option>
                        <option value="محلي"     {{ request('warehouse_type') == 'محلي'     ? 'selected' : '' }}>محلي</option>
                        <option value="معرض بيع" {{ request('warehouse_type') == 'معرض بيع' ? 'selected' : '' }}>معرض بيع</option>
                        <option value="تصدير"    {{ request('warehouse_type') == 'تصدير'    ? 'selected' : '' }}>تصدير</option>
                    </select>
                </div>
                <button type="submit" class="btn-filter">🔍 بحث</button>
            </div>
        </form>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📋</div>
            <div class="stat-value">{{ number_format($totalOrders) }}</div>
            <div class="stat-label">إجمالي الطلبيات</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-value">{{ number_format($totalQuantity) }}</div>
            <div class="stat-label">إجمالي القطع</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-value">{{ $activeUsers }}</div>
            <div class="stat-label">المستخدمين النشطين</div>
        </div>
    </div>

    <!-- تقرير حسب نوع الصنف -->
    <div class="section-card">
        <div class="section-title">🏷️ التقرير حسب نوع الصنف</div>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>نوع الصنف</th>
                        <th>عدد الطلبيات</th>
                        <th>إجمالي الكمية</th>
                        <th>النسبة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($typeStats as $type => $data)
                    <tr>
                        <td><strong>{{ $type }}</strong></td>
                        <td>{{ $data['orders_count'] }}</td>
                        <td>{{ number_format($data['total_quantity']) }}</td>
                        <td style="min-width: 120px;">
                            @php
                                $percentage = $totalQuantity > 0
                                    ? ($data['total_quantity'] / $totalQuantity) * 100
                                    : 0;
                            @endphp
                            <div class="progress-bar-wrap">
                                <div class="progress-bar-fill" style="width: {{ max($percentage, 5) }}%;">
                                    {{ round($percentage, 1) }}%
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- تقرير حسب مندوب المبيعات -->
    <div class="section-card">
        <div class="section-title">👤 تقرير حسب مندوب المبيعات</div>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>اسم المندوب</th>
                        <th>عدد الطلبيات</th>
                        <th>إجمالي القطع</th>
                        <th>متوسط الطلبية</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userStats as $user)
                    <tr>
                        <td><strong>{{ $user['name'] }}</strong></td>
                        <td>{{ $user['orders_count'] }}</td>
                        <td>{{ number_format($user['total_quantity']) }}</td>
                        <td>{{ number_format($user['orders_count'] > 0 ? $user['total_quantity'] / $user['orders_count'] : 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td>الإجمالي الكلي</td>
                        <td>{{ $totalOrders }}</td>
                        <td>{{ number_format($totalQuantity) }}</td>
                        <td>{{ number_format($totalOrders > 0 ? $totalQuantity / $totalOrders : 0) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- تقرير تفصيلي بالأصناف مع فلترة وبحث -->
    <div class="section-card">
        <div class="section-title">📋 التقرير التفصيلي للأصناف</div>

        <div class="search-bar">
            <form method="GET" action="{{ route('orders.advancedReport') }}" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center; width: 100%;">
                <input type="hidden" name="from_date"       value="{{ $fromDate ?? '' }}">
                <input type="hidden" name="to_date"         value="{{ $toDate ?? '' }}">
                <input type="hidden" name="warehouse_type"  value="{{ request('warehouse_type') }}">
                <input type="text"
                       name="product_search"
                       placeholder="🔍 بحث بكود أو اسم الصنف"
                       value="{{ request('product_search') }}">
                <button type="submit" class="btn-filter">🔍 بحث في الأصناف</button>
                @if(request('product_search'))
                    <a href="{{ route('orders.advancedReport', array_filter(['from_date' => $fromDate, 'to_date' => $toDate, 'warehouse_type' => request('warehouse_type')])) }}"
                       class="btn-reset">🗑️ إلغاء</a>
                @endif
            </form>
        </div>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>كود الصنف</th>
                        <th>اسم الصنف</th>
                        <th>الكمية المباعة</th>
                        <th>عدد الطلبيات</th>
                        <th>الرصيد الحالي</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $filteredProducts = $productStats;
                        if(request('product_search')) {
                            $search = request('product_search');
                            $filteredProducts = array_filter($productStats, function($product) use ($search) {
                                return stripos($product['item_code'], $search) !== false
                                    || stripos($product['name'], $search) !== false;
                            });
                        }
                    @endphp
                    @forelse($filteredProducts as $product)
                    <tr>
                        <td><strong>{{ $product['item_code'] }}</strong></td>
                        <td style="text-align: right;">{{ $product['name'] }}</td>
                        <td><strong>{{ number_format($product['total_sold']) }}</strong></td>
                        <td>{{ $product['orders_count'] }}</td>
                        <td>
                            @if($product['current_stock'] <= 0)
                                <span class="badge badge-danger">نفد</span>
                            @elseif($product['current_stock'] < 50)
                                <span class="badge badge-warning">{{ number_format($product['current_stock']) }}</span>
                            @else
                                <span class="badge badge-success">{{ number_format($product['current_stock']) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($product['is_low'])
                                <span class="badge badge-danger">⚠️ منخفض</span>
                            @else
                                <span class="badge badge-success">✅ متوفر</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 30px; color: #999;">
                            لا توجد نتائج تطابق معايير البحث
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
