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
        .filter-group {
            display: inline-block;
            margin: 0 10px 10px 0;
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

        .table-responsive-custom {
            background: white;
            border-radius: 12px;
            overflow-x: auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background: #333;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) { background: #f9f9f9; }
        tr:hover { background: #f0f0f0; }

        .stock-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 11px;
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

        @media (max-width: 768px) {
            body { padding: 10px; padding-top: 80px; }
            .filter-group { display: block; margin: 10px 0; }
            .filter-group input, .filter-group select { width: 100%; }
            .btn-filter, .btn-reset { width: 100%; margin-top: 10px; }
            .btn-custom { display: block; width: 100%; margin-bottom: 10px; text-align: center; }
            table { font-size: 12px; }
            th, td { padding: 8px; }
            .pagination-custom { flex-direction: column; text-align: center; }
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

        <div>
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

        <!-- فلترة وبحث -->
        <div class="filters-card">
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

        @if($products->count() > 0)
            <div class="table-responsive-custom">
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
    @endif
    <!-- ===== نهاية فحص الصلاحية ===== -->
</div>

</body>
</html>
