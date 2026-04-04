<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>الأصناف - جلوريا للسيراميك</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', 'Tahoma', Arial, sans-serif;
            direction: rtl;
            background-color: #f8f9fa;
            padding: 20px;
            padding-top: 90px;
        }
        .container { max-width: 1400px; margin: 0 auto; }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }
        .page-header h1 { margin: 0 0 5px 0; font-size: 24px; }
        .page-header p { margin: 0; opacity: 0.85; font-size: 14px; }

        /* فلاتر البحث */
        .filters-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        /* زر تصفية للموبايل */
        .filter-toggle-btn {
            display: none;
            width: 100%;
            background: #007bff;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 15px;
            font-weight: bold;
            text-align: center;
        }

        .filter-group {
            display: inline-block;
            margin: 0 10px 10px 0;
        }
        .filter-group label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
            font-size: 12px;
            display: block;
        }
        .filter-group input, .filter-group select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            min-width: 180px;
        }
        .btn-filter {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-reset {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-custom {
            display: inline-block;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s;
            margin-left: 10px;
            margin-bottom: 20px;
        }
        .btn-success { background: #28a745; color: white; }
        .btn-info { background: #17a2b8; color: white; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }

        /* ===== تصميم الجدول للشاشات الكبيرة ===== */
        .desktop-table {
            background: white;
            border-radius: 12px;
            overflow-x: auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .desktop-table table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }
        .desktop-table th, .desktop-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        .desktop-table th {
            background: #333;
            color: white;
            font-weight: bold;
        }
        .desktop-table tr:nth-child(even) { background: #f9f9f9; }
        .desktop-table tr:hover { background: #f0f0f0; }

        /* ===== تصميم البطاقات للموبايل ===== */
        .mobile-cards {
            display: none;
            gap: 15px;
            flex-direction: column;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            padding: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: all 0.3s;
            border-right: 4px solid #667eea;
        }

        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
        }

        .product-code {
            font-size: 16px;
            font-weight: bold;
            color: #667eea;
            background: #f0f4ff;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .product-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .card-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .card-label {
            min-width: 90px;
            font-weight: bold;
            color: #666;
            font-size: 13px;
        }

        .card-value {
            flex: 1;
            color: #333;
            font-size: 14px;
            word-break: break-word;
        }

        .card-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            padding-top: 12px;
            border-top: 1px solid #eee;
            flex-wrap: wrap;
        }

        .card-actions .btn-custom {
            flex: 1;
            text-align: center;
            margin: 0;
            padding: 8px 12px;
        }

        .stock-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            display: inline-block;
        }
        .stock-high { background: #d4edda; color: #155724; }
        .stock-low { background: #fff3cd; color: #856404; }
        .stock-out { background: #f8d7da; color: #721c24; }

        .pagination-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 20px;
            padding: 15px;
            background: white;
            border-radius: 8px;
        }
        .pagination-links {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        .pagination-links a, .pagination-links span {
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            border: 1px solid #dee2e6;
            background: white;
            color: #007bff;
        }
        .pagination-links .active span {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .alert { padding: 12px 15px; border-radius: 8px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }

        /* رسالة منع الوصول */
        .access-denied {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .access-denied .icon { font-size: 64px; color: #dc3545; margin-bottom: 20px; }
        .access-denied h3 { color: #dc3545; margin-bottom: 15px; }
        .access-denied p { color: #666; margin-bottom: 20px; }

        /* مجموعة الأزرار */
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .action-buttons .btn-custom {
            margin: 0;
        }

        @media (max-width: 768px) {
            body { padding: 10px; padding-top: 80px; }
            .page-header h1 { font-size: 20px; }

            /* إخفاء الجدول وإظهار البطاقات */
            .desktop-table { display: none; }
            .mobile-cards { display: flex; }

            /* إظهار زر التصفية */
            .filter-toggle-btn { display: block; }

            /* إخفاء الفلاتر بشكل افتراضي */
            .filters-card {
                display: none;
            }

            .filters-card.show {
                display: block;
            }

            .filter-group {
                display: block;
                margin: 10px 0;
            }
            .filter-group input, .filter-group select {
                width: 100%;
                min-width: auto;
            }
            .btn-filter, .btn-reset {
                width: 100%;
                margin-top: 10px;
                text-align: center;
            }
            .action-buttons {
                flex-direction: column;
            }
            .action-buttons .btn-custom {
                width: 100%;
                text-align: center;
            }
            .pagination-custom {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            .card-label { min-width: 80px; }
        }

        @media (max-width: 480px) {
            .product-name { font-size: 16px; }
            .card-label { font-size: 12px; min-width: 70px; }
            .card-value { font-size: 13px; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <!-- ===== فحص الصلاحية: المدير العام أو مدير المبيعات فقط ===== -->
    @if(!in_array(Auth::user()->role, ['super_admin', 'sales_manager']))
        <div class="access-denied">
            <div class="icon"><i class="fas fa-lock"></i></div>
            <h3>غير مصرح بالدخول</h3>
            <p>عذراً، هذه الصفحة مخصصة للمدير العام ومدير المبيعات فقط.</p>
            <a href="{{ route('orders.userDashboard') }}" class="btn-custom btn-info">
                <i class="fas fa-arrow-right"></i> العودة للوحة التحكم
            </a>
        </div>
    @else
        <!-- المحتوى الأصلي يبدأ هنا -->
        <div class="page-header">
            <h1><i class="fas fa-boxes"></i> قائمة الأصناف والأرصدة</h1>
            <p>إدارة وتتبع جميع الأصناف والمخزون</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }}</div>
        @endif

        <div class="action-buttons">
            <!-- إضافة صنف جديد: للمدير العام ومدير المبيعات -->
            <a href="{{ route('products.create') }}" class="btn-custom btn-success"><i class="fas fa-plus-circle"></i> إضافة صنف جديد</a>

            <!-- تقرير الأصناف: للمدير العام ومدير المبيعات -->
            <a href="{{ route('products.report') }}" class="btn-custom btn-info"><i class="fas fa-chart-line"></i> تقرير الأصناف</a>

            <!-- استيراد وتحميل قالب: للمدير العام فقط -->
            @if(Auth::user()->role == 'super_admin')
                <a href="{{ route('products.importPage') }}" class="btn-custom btn-info"><i class="fas fa-file-import"></i> استيراد من Excel</a>
                <a href="{{ route('products.downloadTemplate') }}" class="btn-custom btn-warning"><i class="fas fa-download"></i> تحميل قالب</a>
            @endif
        </div>

        <!-- زر تصفية للموبايل -->
        <button class="filter-toggle-btn" onclick="toggleFilters()">
            <i class="fas fa-filter"></i> تصفية وبحث
        </button>

        <!-- فلترة وبحث -->
        <div class="filters-card" id="filtersCard">
            <form method="GET" action="{{ route('products.index') }}" style="display: flex; flex-wrap: wrap; gap: 15px; width: 100%; align-items: flex-end;">
                <div class="filter-group">
                    <label><i class="fas fa-search"></i> بحث</label>
                    <input type="text" name="search" placeholder="كود أو اسم الصنف" value="{{ request('search') }}">
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-tag"></i> النوع</label>
                    <select name="type">
                        <option value="">-- جميع الأنواع --</option>
                        @if(isset($types) && $types->count() > 0)
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <button type="submit" class="btn-filter"><i class="fas fa-search"></i> بحث</button>
                <a href="{{ route('products.index') }}" class="btn-reset"><i class="fas fa-times"></i> إلغاء</a>
            </form>
        </div>

        <!-- عرض الجدول للشاشات الكبيرة -->
        <div class="desktop-table">
            @if($products->count() > 0)
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>كود الصنف</th>
                                <th>اسم الصنف</th>
                                <th>الرصيد الحالي</th>
                                <th>الحد الأدنى</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td><strong>{{ $product->item_code }}</strong></td>
                                <td style="text-align: right;">{{ $product->name }}</td>
                                <td>
                                    @php $stock = $product->stock?->current_stock ?? 0; @endphp
                                    {{ number_format($stock, 2) }}
                                </td>
                                <td>{{ $product->stock?->min_stock ?? '-' }}</td>
                                <td>
                                    @if($stock <= 0)
                                        <span class="stock-badge stock-out"><i class="fas fa-times-circle"></i> نفد</span>
                                    @elseif($product->isLowStock())
                                        <span class="stock-badge stock-low"><i class="fas fa-exclamation-triangle"></i> منخفض</span>
                                    @else
                                        <span class="stock-badge stock-high"><i class="fas fa-check-circle"></i> متوفر</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('products.show', $product->id) }}" class="btn-custom btn-info btn-sm"><i class="fas fa-eye"></i> عرض</a>
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn-custom btn-warning btn-sm"><i class="fas fa-edit"></i> تعديل</a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-custom btn-sm" style="background: #dc3545; color: white; border: none; border-radius: 6px; padding: 5px 10px;" onclick="return confirm('هل أنت متأكد من حذف هذا الصنف؟')"><i class="fas fa-trash-alt"></i> حذف</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination-custom">
                    <div class="pagination-info">
                        <i class="fas fa-file-alt"></i> عرض {{ $products->firstItem() }} - {{ $products->lastItem() }} من {{ $products->total() }} صنف
                    </div>
                    <div class="pagination-links">
                        {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-inbox"></i>
                    <p>لا توجد أصناف تطابق معايير البحث</p>
                    <a href="{{ route('products.create') }}" class="btn-custom btn-success"><i class="fas fa-plus-circle"></i> أضف صنف جديد</a>
                </div>
            @endif
        </div>

        <!-- عرض البطاقات للموبايل -->
        <div class="mobile-cards">
            @if($products->count() > 0)
                @foreach($products as $product)
                    @php $stock = $product->stock?->current_stock ?? 0; @endphp
                    <div class="product-card">
                        <div class="card-header">
                            <span class="product-code"><i class="fas fa-barcode"></i> {{ $product->item_code }}</span>
                            @if($stock <= 0)
                                <span class="stock-badge stock-out"><i class="fas fa-times-circle"></i> نفد</span>
                            @elseif($product->isLowStock())
                                <span class="stock-badge stock-low"><i class="fas fa-exclamation-triangle"></i> منخفض</span>
                            @else
                                <span class="stock-badge stock-high"><i class="fas fa-check-circle"></i> متوفر</span>
                            @endif
                        </div>

                        <div class="product-name">{{ $product->name }}</div>

                        <div class="card-row">
                            <div class="card-label"><i class="fas fa-database"></i> الرصيد الحالي:</div>
                            <div class="card-value">
                                <strong>{{ number_format($stock, 2) }}</strong>
                                @if($product->stock?->min_stock)
                                    <small class="text-muted">(الحد الأدنى: {{ $product->stock->min_stock }})</small>
                                @endif
                            </div>
                        </div>

                        @if($product->type)
                        <div class="card-row">
                            <div class="card-label"><i class="fas fa-tag"></i> النوع:</div>
                            <div class="card-value">{{ $product->type }}</div>
                        </div>
                        @endif

                        @if($product->unit)
                        <div class="card-row">
                            <div class="card-label"><i class="fas fa-balance-scale"></i> الوحدة:</div>
                            <div class="card-value">{{ $product->unit }}</div>
                        </div>
                        @endif

                        <div class="card-actions">
                            <a href="{{ route('products.show', $product->id) }}" class="btn-custom btn-info btn-sm"><i class="fas fa-eye"></i> عرض</a>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn-custom btn-warning btn-sm"><i class="fas fa-edit"></i> تعديل</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="flex: 1;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-custom btn-sm" style="width: 100%; background: #dc3545; color: white; border: none; border-radius: 6px; padding: 8px 12px;" onclick="return confirm('هل أنت متأكد من حذف هذا الصنف؟')"><i class="fas fa-trash-alt"></i> حذف</button>
                            </form>
                        </div>
                    </div>
                @endforeach

                <div class="pagination-custom">
                    <div class="pagination-info">
                        <i class="fas fa-file-alt"></i> عرض {{ $products->firstItem() }} - {{ $products->lastItem() }} من {{ $products->total() }} صنف
                    </div>
                    <div class="pagination-links">
                        {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-inbox"></i>
                    <p>لا توجد أصناف تطابق معايير البحث</p>
                    <a href="{{ route('products.create') }}" class="btn-custom btn-success"><i class="fas fa-plus-circle"></i> أضف صنف جديد</a>
                </div>
            @endif
        </div>
    @endif
    <!-- ===== نهاية فحص الصلاحية ===== -->
</div>

<script>
    function toggleFilters() {
        const filtersCard = document.getElementById('filtersCard');
        filtersCard.classList.toggle('show');
    }

    // إغلاق الفلاتر تلقائياً عند الضغط على زر البحث في الموبايل
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.querySelector('#filtersCard form');
        if (filterForm) {
            filterForm.addEventListener('submit', function() {
                if (window.innerWidth < 768) {
                    setTimeout(function() {
                        document.getElementById('filtersCard').classList.remove('show');
                    }, 100);
                }
            });
        }
    });
</script>

</body>
</html>
