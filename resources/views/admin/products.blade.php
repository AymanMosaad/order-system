<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>إدارة المنتجات - جلوريا للسيراميك</title>
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

        .page-header h1 { margin: 0 0 5px 0; font-size: 24px; }
        .page-header p { margin: 0; opacity: 0.85; font-size: 14px; }

        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-bottom: none;
        }

        .card-header h3 { margin: 0; font-size: 20px; }
        .card-header p { margin: 5px 0 0; opacity: 0.9; font-size: 13px; }

        .card-body { padding: 20px; }

        /* تنسيق الفلتر */
        .filter-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #e9ecef;
        }

        .filter-title {
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
            border-right: 3px solid #007bff;
            padding-right: 10px;
        }

        .filter-group {
            display: inline-block;
            margin-left: 15px;
            margin-bottom: 10px;
        }

        .filter-group label {
            font-weight: bold;
            color: #555;
            font-size: 12px;
            display: block;
            margin-bottom: 5px;
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
            margin-top: 20px;
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
            margin-top: 20px;
        }

        .btn-filter-small {
            background: #28a745;
            color: white;
            border: none;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
        }

        .table-responsive {
            overflow-x: auto;
            border-radius: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #eee;
            padding: 12px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background: #f8f9fa;
            font-weight: bold;
            position: sticky;
            top: 0;
        }

        .form-control-sm {
            width: 100%;
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 13px;
        }

        .btn-sm {
            padding: 5px 12px;
            font-size: 12px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-primary { background: #007bff; color: white; border: none; }
        .btn-primary:hover { background: #0056b3; color: white; }

        .btn-secondary { background: #6c757d; color: white; border: none; }
        .btn-secondary:hover { background: #5a6268; color: white; }

        .btn-info { background: #17a2b8; color: white; border: none; }
        .btn-info:hover { background: #138496; color: white; }

        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }

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

        .badge-grade {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }
        .grade-اول { background: #ffc107; color: #856404; }
        .grade-ثاني { background: #17a2b8; color: white; }
        .grade-ثالث { background: #28a745; color: white; }
        .grade-رابع { background: #fd7e14; color: white; }

        @media (max-width: 768px) {
            body { padding: 10px; padding-top: 80px; }
            th, td { padding: 8px; font-size: 12px; }
            .card-header h3 { font-size: 18px; }
            .btn-sm { padding: 4px 8px; font-size: 11px; }
            .form-control-sm { font-size: 11px; }
            .filter-group { display: block; margin-left: 0; }
            .filter-group input, .filter-group select { width: 100%; min-width: auto; }
            .btn-filter, .btn-reset { width: 100%; margin-top: 10px; text-align: center; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-dollar-sign"></i> إدارة أسعار المنتجات</h1>
        <p>تعديل أسعار المنتجات وأسمائها - التحديث يؤثر تلقائياً على حسابات المسحوبات</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-boxes"></i> قائمة المنتجات</h3>
            <p>يمكنك تعديل الاسم أو السعر مباشرة ثم الضغط على حفظ</p>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                </div>
            @endif

            <!-- ===== فلتر البحث المتقدم ===== -->
            <div class="filter-card">
                <div class="filter-title">
                    <i class="fas fa-filter"></i> فلترة وبحث
                </div>
                <form method="GET" action="{{ route('admin.products') }}">
                    <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                        <div class="filter-group">
                            <label><i class="fas fa-barcode"></i> كود المنتج</label>
                            <input type="text" name="item_code" placeholder="أدخل كود المنتج" value="{{ request('item_code') }}">
                        </div>
                        <div class="filter-group">
                            <label><i class="fas fa-tag"></i> اسم المنتج</label>
                            <input type="text" name="name" placeholder="أدخل اسم المنتج" value="{{ request('name') }}">
                        </div>
                        <div class="filter-group">
                            <label><i class="fas fa-layer-group"></i> النوع</label>
                            <select name="type">
                                <option value="">-- الكل --</option>
                                <option value="حوائط" {{ request('type') == 'حوائط' ? 'selected' : '' }}>حوائط</option>
                                <option value="أرضيات" {{ request('type') == 'أرضيات' ? 'selected' : '' }}>أرضيات</option>
                                <option value="سيراميك" {{ request('type') == 'سيراميك' ? 'selected' : '' }}>سيراميك</option>
                                <option value="بورسلان" {{ request('type') == 'بورسلان' ? 'selected' : '' }}>بورسلان</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><i class="fas fa-star"></i> الفرز</label>
                            <select name="grade">
                                <option value="">-- الكل --</option>
                                <option value="اول" {{ request('grade') == 'اول' ? 'selected' : '' }}>أول</option>
                                <option value="ثاني" {{ request('grade') == 'ثاني' ? 'selected' : '' }}>ثاني</option>
                                <option value="ثالث" {{ request('grade') == 'ثالث' ? 'selected' : '' }}>ثالث</option>
                                <option value="رابع" {{ request('grade') == 'رابع' ? 'selected' : '' }}>رابع</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><i class="fas fa-arrows-alt"></i> المقاس</label>
                            <input type="text" name="size" placeholder="مثال: 32×64" value="{{ request('size') }}">
                        </div>
                        <div class="filter-group">
                            <label><i class="fas fa-palette"></i> اللون</label>
                            <input type="text" name="color" placeholder="اللون" value="{{ request('color') }}">
                        </div>
                        <div>
                            <button type="submit" class="btn-filter">
                                <i class="fas fa-search"></i> بحث
                            </button>
                            <a href="{{ route('admin.products') }}" class="btn-reset">
                                <i class="fas fa-times"></i> إلغاء
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 10%">كود المنتج</th>
                            <th style="width: 25%">اسم المنتج</th>
                            <th style="width: 8%">النوع</th>
                            <th style="width: 6%">الفرز</th>
                            <th style="width: 8%">المقاس</th>
                            <th style="width: 8%">اللون</th>
                            <th style="width: 8%">السعر الحالي</th>
                            <th style="width: 12%">تعديل السعر</th>
                            <th style="width: 10%">إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <form method="POST" action="{{ route('admin.products.update', $product->id) }}">
                                @csrf
                                @method('PUT')
                                <td><strong>{{ $product->item_code }}</strong></td>
                                <td style="text-align: right;">
                                    <input type="text" name="name" value="{{ $product->name }}" class="form-control-sm" style="width: 100%;">
                                </td>
                                <td>{{ $product->type ?? '-' }}</td>
                                <td>
                                    @if($product->grade)
                                        <span class="badge-grade grade-{{ $product->grade }}">{{ $product->grade }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $product->size ?? '-' }}</td>
                                <td>{{ $product->color ?? '-' }}</td>
                                <td>
                                    <strong class="text-primary">{{ number_format($product->price, 2) }}</strong>
                                </td>
                                <td>
                                    <input type="number" name="price" step="0.01" value="{{ $product->price }}" class="form-control-sm" style="width: 100px;">
                                </td>
                                <td>
                                    <button type="submit" class="btn-sm btn-primary">
                                        <i class="fas fa-save"></i> حفظ
                                    </button>
                                </td>
                            </form>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    <i class="fas fa-inbox"></i> لا توجد منتجات تطابق معايير البحث
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="pagination-custom">
                    <div class="pagination-info">
                        <i class="fas fa-file-alt"></i> عرض {{ $products->firstItem() }} - {{ $products->lastItem() }} من {{ $products->total() }} منتج
                    </div>
                    <div class="pagination-links">
                        {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('accounting.customers') }}" class="btn btn-secondary">
            <i class="fas fa-users"></i> العودة للعملاء
        </a>
        <a href="{{ route('accounting.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-chart-line"></i> لوحة المحاسبة
        </a>
        <a href="{{ route('products.index') }}" class="btn btn-info">
            <i class="fas fa-boxes"></i> إدارة الأصناف المتقدمة
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
