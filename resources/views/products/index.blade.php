<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الأصناف - جلوريا للسيراميك</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            font-size: 20px;
            font-weight: bold;
        }
        .card-body { padding: 20px; }

        .filters {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .filter-group {
            display: inline-block;
            margin: 0 10px 10px 0;
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
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        .btn-success { background: #28a745; color: white; }
        .btn-info { background: #17a2b8; color: white; }
        .btn-warning { background: #ffc107; color: #212529; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
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

        .stock-low { color: #ff9800; font-weight: bold; }
        .stock-out { color: #f44336; font-weight: bold; }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-warning { background: #ffc107; color: #212529; }
        .badge-danger { background: #dc3545; color: white; }
        .badge-success { background: #28a745; color: white; }

        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 25px;
            padding: 15px;
            background: #f8f9fa;
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
        }
        .pagination-links a {
            background: white;
            color: #007bff;
            border: 1px solid #dee2e6;
        }
        .pagination-links .active span {
            background: #007bff;
            color: white;
        }
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }

        @media (max-width: 768px) {
            .filter-group { display: block; margin: 10px 0; }
            .filter-group input, .filter-group select { width: 100%; }
            table { font-size: 12px; }
            th, td { padding: 8px; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="card">
        <div class="card-header">
            📦 قائمة الأصناف والأرصدة
        </div>
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px;">
                <a href="{{ route('products.create') }}" class="btn-custom btn-success">➕ إضافة صنف جديد</a>
                <a href="{{ route('products.importPage') }}" class="btn-custom btn-info">⬆️ استيراد من Excel</a>
                <a href="{{ route('products.downloadTemplate') }}" class="btn-custom btn-warning">⬇️ تحميل قالب</a>
            </div>

            <!-- فلترة وبحث -->
            <div class="filters">
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="filter-group">
                        <input type="text" name="search" placeholder="🔍 بحث بكود أو اسم الصنف" value="{{ request('search') }}">
                    </div>
                    <div class="filter-group">
                        <select name="type">
                            <option value="">-- جميع الأنواع --</option>
                            @if(isset($types) && $types->count() > 0)
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <button type="submit" class="btn-filter">🔍 بحث</button>
                    <a href="{{ route('products.index') }}" class="btn-reset">🗑️ إلغاء</a>
                </form>
            </div>

            @if($products->count() > 0)
                <div style="overflow-x: auto;">
                     <table>
                        <thead>
                             <tr>
                                <th>#</th>
                                <th>كود الصنف</th>
                                <th>اسم الصنف</th>
                                <th>الرصيد الحالي</th>
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
                                    <span class="@if($stock <= 0) stock-out @elseif($stock < 50) stock-low @endif">
                                        {{ number_format($stock) }}
                                    </span>
                                    @if($stock <= 0)
                                        <span class="badge badge-danger">نفد</span>
                                    @elseif($stock < 50)
                                        <span class="badge badge-warning">منخفض</span>
                                    @endif
                                 </td>
                                 <td>
                                    <span class="badge badge-{{ $product->is_active ? 'success' : 'secondary' }}">
                                        {{ $product->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                 </td>
                                 <td>
                                    <a href="{{ route('products.show', $product->id) }}" class="btn-custom btn-info" style="padding: 5px 10px; font-size: 12px;">عرض</a>
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn-custom btn-warning" style="padding: 5px 10px; font-size: 12px;">تعديل</a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-custom" style="background: #dc3545; color: white; padding: 5px 10px; font-size: 12px; border: none; border-radius: 6px;" onclick="return confirm('هل أنت متأكد من حذف هذا الصنف؟')">حذف</button>
                                    </form>
                                 </td>
                             </tr>
                            @endforeach
                        </tbody>
                     </table>
                </div>

                <div class="pagination-container">
                    <div class="pagination-info">
                        📄 عرض {{ $products->firstItem() }} - {{ $products->lastItem() }} من {{ $products->total() }} صنف
                    </div>
                    <div class="pagination-links">
                        {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @else
                <div class="alert alert-info text-center">
                    <p>لا توجد أصناف تطابق معايير البحث</p>
                    <a href="{{ route('products.create') }}" class="btn-custom btn-success">➕ أضف صنف جديد</a>
                </div>
            @endif
        </div>
    </div>
</div>

</body>
</html>
