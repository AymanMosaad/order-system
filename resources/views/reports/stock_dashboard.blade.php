<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>لوحة المخزون - جلوريا للسيراميك</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            direction: rtl;
            background-color: #f8f9fa;
            padding: 20px;
            padding-top: 90px;
        }
        .container { max-width: 1400px; margin: 0 auto; }

        .page-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }

        /* كروت الإحصائيات */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
        .stat-number {
            font-size: 32px;
            font-weight: bold;
        }
        .stat-label {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }
        .stat-card.danger .stat-number { color: #dc3545; }
        .stat-card.warning .stat-number { color: #ffc107; }
        .stat-card.success .stat-number { color: #28a745; }

        /* بطاقات الأصناف */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .product-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
            border-right: 4px solid #ddd;
        }
        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .product-card.danger { border-right-color: #dc3545; }
        .product-card.warning { border-right-color: #ffc107; }

        .product-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .product-code {
            font-size: 12px;
            color: #999;
            margin-bottom: 15px;
        }
        .stock-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .stock-label {
            color: #666;
            font-size: 13px;
        }
        .stock-value {
            font-weight: bold;
            font-size: 16px;
        }
        .stock-value.danger { color: #dc3545; }
        .stock-value.warning { color: #ffc107; }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-outline-primary {
            border: 1px solid #007bff;
            color: #007bff;
            background: transparent;
        }
        .btn-outline-primary:hover {
            background: #007bff;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 15px;
            color: #999;
        }

        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin: 25px 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 3px solid #007bff;
            display: inline-block;
        }

        /* تنسيق الفلاتر */
        .filters-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        /* تنسيق مؤشرات الحركة */
        .trend-card {
            background: white;
            padding: 15px 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-right: 4px solid #007bff;
        }
        .trend-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }
        .trend-label {
            font-size: 14px;
            color: #666;
        }
        .trend-value {
            font-size: 18px;
            font-weight: bold;
        }
        .text-danger { color: #dc3545; }
        .text-success { color: #28a745; }
        .text-secondary { color: #6c757d; }

        /* أزرار التصدير */
        .export-buttons {
            display: flex !important;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            justify-content: flex-start;
            visibility: visible !important;
        }
        .btn-excel {
            background: #28a745 !important;
            color: white !important;
            padding: 10px 20px !important;
            border-radius: 8px !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            border: none !important;
            cursor: pointer !important;
        }
        .btn-excel:hover {
            background: #218838 !important;
            color: white !important;
        }
        .btn-pdf {
            background: #dc3545 !important;
            color: white !important;
            padding: 10px 20px !important;
            border-radius: 8px !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            border: none !important;
            cursor: pointer !important;
        }
        .btn-pdf:hover {
            background: #c82333 !important;
            color: white !important;
        }
        .btn-warning {
            background: #ffc107 !important;
            color: #212529 !important;
            padding: 10px 20px !important;
            border-radius: 8px !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            border: none !important;
            cursor: pointer !important;
        }
        .btn-warning:hover {
            background: #e0a800 !important;
            color: #212529 !important;
        }

        @media (max-width: 768px) {
            body { padding: 10px; padding-top: 80px; }
            .products-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .export-buttons { flex-direction: column; }
            .export-buttons .btn-excel,
            .export-buttons .btn-pdf,
            .export-buttons .btn-warning { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-chart-line"></i> لوحة تحكم المخزون</h1>
        <p>ملخص حالة المخزون والأصناف المنخفضة</p>
    </div>

    <!-- ===== فلترة حسب النوع ===== -->
    <div class="filters-card">
        <form method="GET" action="{{ route('products.stockDashboard') }}" class="row g-3">
            <div class="col-md-4 col-sm-12">
                <label class="form-label"><i class="fas fa-tag"></i> نوع الصنف</label>
                <select name="type" class="form-select">
                    <option value="">-- جميع الأنواع --</option>
                    @foreach($types ?? [] as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-sm-12 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> بحث
                </button>
            </div>
            @if(request('type'))
                <div class="col-md-2 col-sm-12 d-flex align-items-end">
                    <a href="{{ route('products.stockDashboard') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-times"></i> إلغاء
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- ===== مؤشرات الحركة (Trend) ===== -->
    @if(isset($trends))
    <div class="trend-card">
        <div class="row">
            <div class="col-md-6">
                <div class="trend-item">
                    <span class="trend-label"><i class="fas fa-chart-line"></i> الأصناف المنخفضة هذا الشهر:</span>
                    <span class="trend-value">{{ $trends['current'] }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="trend-item">
                    <span class="trend-label"><i class="fas fa-calendar-alt"></i> مقارنة بالشهر الماضي:</span>
                    <span class="trend-value
                        @if($trends['trend'] == 'up') text-danger
                        @elseif($trends['trend'] == 'down') text-success
                        @else text-secondary
                        @endif">
                        @if($trends['trend'] == 'up') ↑ زيادة
                        @elseif($trends['trend'] == 'down') ↓ انخفاض
                        @else → ثابت
                        @endif
                        {{ abs($trends['percentage']) }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- ===== كروت الإحصائيات ===== -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ number_format($totalProducts) }}</div>
            <div class="stat-label">إجمالي الأصناف</div>
        </div>
        <div class="stat-card success">
            <div class="stat-number">{{ number_format($healthyCount) }}</div>
            <div class="stat-label">✅ أصناف متوفرة</div>
        </div>
        <div class="stat-card warning">
            <div class="stat-number">{{ number_format($lowStockCount) }}</div>
            <div class="stat-label">⚠️ أصناف منخفضة</div>
        </div>
        <div class="stat-card danger">
            <div class="stat-number">{{ number_format($outOfStockCount) }}</div>
            <div class="stat-label">❌ أصناف منفذة</div>
        </div>
    </div>

    <!-- ===== أزرار التصدير (معدل) ===== -->
    <div class="export-buttons">
        <a href="{{ route('exportLowStockExcel', ['type' => request('type')]) }}" class="btn-excel">
            <i class="fas fa-file-excel"></i> تصدير إلى Excel
        </a>
        <a href="{{ route('exportLowStockPdf', ['type' => request('type')]) }}" class="btn-pdf">
            <i class="fas fa-file-pdf"></i> تصدير إلى PDF
        </a>
        <a href="{{ route('stock.check.low') }}" class="btn-warning">
            <i class="fas fa-sync-alt"></i> فحص المخزون المنخفض الآن
        </a>
    </div>

    <!-- ===== الأصناف المنفذة ===== -->
    @if($outOfStockProducts->count() > 0)
        <div>
            <div class="section-title">
                <i class="fas fa-times-circle" style="color: #dc3545;"></i> أصناف منفذة (رصيد = 0)
            </div>
            <div class="products-grid">
                @foreach($outOfStockProducts as $product)
                    <div class="product-card danger">
                        <div class="product-title">{{ $product->name }}</div>
                        <div class="product-code">كود: {{ $product->item_code }}</div>
                        <div class="stock-row">
                            <span class="stock-label">الرصيد الحالي:</span>
                            <span class="stock-value danger">0</span>
                        </div>
                        <div class="stock-row">
                            <span class="stock-label">الحد الأدنى:</span>
                            <span class="stock-value">{{ $product->stock?->min_stock ?? 50 }}</span>
                        </div>
                        <div class="stock-row">
                            <span class="stock-label">النوع:</span>
                            <span class="stock-value">{{ $product->type ?? '-' }}</span>
                        </div>
                        <div style="margin-top: 15px;">
                            <a href="{{ route('products.show', $product->id) }}" class="btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> عرض التفاصيل
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- ===== الأصناف المنخفضة ===== -->
    @if($lowStockProducts->count() > 0)
        <div>
            <div class="section-title">
                <i class="fas fa-exclamation-triangle" style="color: #ffc107;"></i> أصناف منخفضة (أقل من الحد الأدنى)
            </div>
            <div class="products-grid">
                @foreach($lowStockProducts as $product)
                    @php
                        $currentStock = $product->getCurrentStock();
                        $minStock = $product->stock?->min_stock ?? 50;
                        $shortage = $minStock - $currentStock;
                    @endphp
                    <div class="product-card warning">
                        <div class="product-title">{{ $product->name }}</div>
                        <div class="product-code">كود: {{ $product->item_code }}</div>
                        <div class="stock-row">
                            <span class="stock-label">الرصيد الحالي:</span>
                            <span class="stock-value warning">{{ number_format($currentStock) }}</span>
                        </div>
                        <div class="stock-row">
                            <span class="stock-label">الحد الأدنى:</span>
                            <span class="stock-value">{{ number_format($minStock) }}</span>
                        </div>
                        <div class="stock-row">
                            <span class="stock-label">العجز:</span>
                            <span class="stock-value danger">{{ number_format($shortage) }}</span>
                        </div>
                        <div class="stock-row">
                            <span class="stock-label">النوع:</span>
                            <span class="stock-value">{{ $product->type ?? '-' }}</span>
                        </div>
                        <div style="margin-top: 15px;">
                            <a href="{{ route('products.show', $product->id) }}" class="btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> عرض التفاصيل
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- ===== لو مفيش حاجة ===== -->
    @if($lowStockProducts->count() == 0 && $outOfStockProducts->count() == 0)
        <div class="empty-state">
            <i class="fas fa-check-circle" style="font-size: 64px; color: #28a745;"></i>
            <h3 style="margin-top: 15px;">🎉 المخزون آمن!</h3>
            <p>جميع الأصناف متوفرة بكميات كافية</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary" style="margin-top: 15px;">
                <i class="fas fa-boxes"></i> عرض جميع الأصناف
            </a>
        </div>
    @endif
</div>

</body>
</html>
