<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>تقرير الرصيد - جلوريا للسيراميك</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', 'Tahoma', Arial, sans-serif;
            direction: rtl;
            margin: 0;
            background-color: #f8f9fa;
            padding: 20px;
            padding-top: 90px;
        }
        .container { max-width: 1200px; margin: 0 auto; }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .filters {
            background: white;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.05);
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 15px;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        .filter-group label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
            font-size: 12px;
        }
        .filter-group input, .filter-group select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            min-width: 180px;
        }
        .btn-filter {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            height: 40px;
        }
        .btn-filter:hover {
            background: #0056b3;
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
            font-size: 14px;
            height: 40px;
            line-height: 24px;
        }
        .btn-reset:hover {
            background: #5a6268;
        }

        .summary {
            background: white;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.05);
        }
        .summary-item {
            text-align: center;
            flex: 1;
        }
        .summary-label {
            font-size: 13px;
            color: #666;
        }
        .summary-value {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }

        .table-wrapper {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 5px rgba(0,0,0,0.05);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #eee;
            padding: 12px 15px;
            text-align: center;
        }
        th {
            background: #f8f9fa;
            color: #333;
            font-weight: bold;
            border-bottom: 2px solid #dee2e6;
            font-size: 14px;
        }
        tr:hover {
            background: #f5f5f5;
        }
        .size-name {
            font-weight: bold;
            color: #333;
        }
        .quantity {
            font-size: 16px;
            font-weight: bold;
            color: #28a745;
        }
        .size-missing {
            background-color: #fff3cd;
        }
        .size-missing .size-name {
            color: #856404;
        }
        .size-missing .quantity {
            color: #856404;
        }

        .btn-print {
            background: #17a2b8;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .btn-print:hover {
            background: #138496;
        }

        .no-data {
            text-align: center;
            padding: 50px;
            color: #999;
        }

        @media (max-width: 768px) {
            body { padding: 10px; padding-top: 80px; }
            .filters {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-group input, .filter-group select {
                width: 100%;
            }
            .btn-filter, .btn-reset {
                width: 100%;
            }
            .summary {
                flex-direction: column;
                gap: 10px;
            }
            th, td {
                padding: 8px 10px;
                font-size: 13px;
            }
        }

        @media print {
            .filters, .btn-print, .navbar-custom { display: none; }
            body { background: white; padding: 0; }
            .table-wrapper { box-shadow: none; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="header">
        <h1><i class="fas fa-chart-simple"></i> تقرير الرصيد حسب المقاس</h1>
        <p>ملخص الأرصدة المخزنية لجميع المقاسات</p>
    </div>

    <button onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> طباعة التقرير</button>

    <div class="filters">
        <form method="GET" action="{{ route('products.stockReport') }}" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end; width: 100%;">
            <div class="filter-group">
                <label><i class="fas fa-search"></i> بحث</label>
                <input type="text" name="search" placeholder="كود أو اسم الصنف" value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <label><i class="fas fa-tag"></i> النوع</label>
                <select name="type">
                    <option value="">-- جميع الأنواع --</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label><i class="fas fa-ruler"></i> المقاس</label>
                <select name="size">
                    <option value="">-- جميع المقاسات --</option>
                    @foreach($sizes as $size)
                        <option value="{{ $size }}" {{ request('size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-filter"><i class="fas fa-search"></i> بحث</button>
            <a href="{{ route('products.stockReport') }}" class="btn-reset"><i class="fas fa-times"></i> إلغاء</a>
        </form>
    </div>

    @php
        // تجميع الرصيد حسب المقاس (مع فصل المقاسات الفارغة)
        $sizeTotals = [];
        $missingSizeTotal = 0;
        $missingSizeCount = 0;

        foreach ($products as $product) {
            $size = $product->size ?? null;
            $quantity = $product->stock->current_stock ?? 0;

            // تخطي المنتجات بدون رصيد
            if ($quantity <= 0) continue;

            // إذا كان المقاس فارغ أو غير محدد
            if (empty($size) || $size == '' || $size == 'غير محدد') {
                $missingSizeTotal += $quantity;
                $missingSizeCount++;
                continue;
            }

            if (!isset($sizeTotals[$size])) {
                $sizeTotals[$size] = 0;
            }
            $sizeTotals[$size] += $quantity;
        }

        // ترتيب المقاسات حسب الحجم
        $sortedSizes = array_keys($sizeTotals);
        usort($sortedSizes, function($a, $b) {
            // ترتيب المقاسات بالأرقام
            preg_match('/(\d+)/', $a, $aNum);
            preg_match('/(\d+)/', $b, $bNum);
            $aVal = isset($aNum[1]) ? (int)$aNum[1] : 0;
            $bVal = isset($bNum[1]) ? (int)$bNum[1] : 0;
            if ($aVal == $bVal) return strcmp($a, $b);
            return $aVal - $bVal;
        });

        $totalStock = array_sum($sizeTotals) + $missingSizeTotal;
    @endphp

    @if(request('size') && request('size') != '')
        <div style="background: #e3f2fd; padding: 10px 15px; border-radius: 8px; margin-bottom: 15px; text-align: center;">
            <i class="fas fa-filter"></i> تم التصفية حسب المقاس: <strong>{{ request('size') }}</strong>
            @if(request('type'))
                - النوع: <strong>{{ request('type') }}</strong>
            @endif
            @if(request('search'))
                - البحث: <strong>{{ request('search') }}</strong>
            @endif
        </div>
    @endif

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label"><i class="fas fa-boxes"></i> إجمالي الأصناف</div>
            <div class="summary-value">{{ number_format($products->where('stock.current_stock', '>', 0)->count()) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label"><i class="fas fa-coins"></i> إجمالي الرصيد</div>
            <div class="summary-value">{{ number_format($totalStock) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label"><i class="fas fa-ruler-combined"></i> عدد المقاسات</div>
            <div class="summary-value">{{ count($sizeTotals) }}</div>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 20%;">#</th>
                    <th style="width: 50%;"><i class="fas fa-ruler"></i> المقاس</th>
                    <th style="width: 30%;"><i class="fas fa-cubes"></i> الكمية</th>
                </thead>
                <tbody>
                    @forelse($sortedSizes as $index => $size)
                        @php $total = $sizeTotals[$size]; @endphp
                        @if($total > 0)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="size-name">{{ $size }}</td>
                            <td class="quantity">{{ number_format($total) }} <span style="font-size: 12px; color: #666;">قطعة</span></td>
                        </tr>
                        @endif
                    @empty
                        @if($missingSizeTotal == 0)
                        <tr>
                            <td colspan="3" class="no-data"><i class="fas fa-inbox"></i> لا توجد بيانات متاحة</td>
                        </tr>
                        @endif
                    @endforelse

                    <!-- عرض الأصناف بدون مقاس -->
                    @if($missingSizeTotal > 0)
                    <tr class="size-missing">
                        <td>{{ count($sizeTotals) + 1 }}</td>
                        <td class="size-name"><i class="fas fa-exclamation-triangle"></i> بدون مقاس</td>
                        <td class="quantity">{{ number_format($missingSizeTotal) }} <span style="font-size: 12px;">قطعة</span></td>
                    </tr>
                    @endif
                </tbody>
                @if($totalStock > 0)
                <tfoot>
                    <tr style="background: #e9ecef; font-weight: bold;">
                        <td colspan="2" style="text-align: left;"><i class="fas fa-calculator"></i> الإجمالي الكلي</td>
                        <td class="quantity">{{ number_format($totalStock) }} قطعة</td>
                    </tr>
                </tfoot>
                @endif
            </table>
    </div>
</div>

</body>
</html>
