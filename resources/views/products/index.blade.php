<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الأصناف - جلوريا للسيراميك</title>
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
        h2 { text-align: center; color: #333; margin-bottom: 30px; }

        /* فلاتر البحث */
        .filters {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
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
            height: 38px;
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
            height: 38px;
            line-height: 22px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 20px;
            margin-left: 10px;
            border: none;
            cursor: pointer;
        }
        .btn:hover { background-color: #218838; }
        .btn-report { background-color: #17a2b8; }
        .btn-report:hover { background-color: #138496; }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
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

        .stock-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            display: inline-block;
        }
        .stock-high { background-color: #d4edda; color: #155724; }
        .stock-low { background-color: #fff3cd; color: #856404; }
        .stock-out { background-color: #f8d7da; color: #721c24; }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            margin: 2px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
        }
        .btn-view { background-color: #17a2b8; color: white; }
        .btn-edit { background-color: #ffc107; color: #333; }
        .btn-delete { background-color: #dc3545; color: white; }
        .btn-sm:hover { opacity: 0.8; }

        .pagination-container {
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

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }

        @media (max-width: 768px) {
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
            table {
                font-size: 12px;
            }
            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <h2>📦 قائمة الأصناف والأرصدة</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div>
        <a href="{{ route('products.create') }}" class="btn">➕ إضافة صنف جديد</a>
        <a href="{{ route('products.report') }}" class="btn btn-report">📊 تقرير الأصناف</a>
        <a href="{{ route('products.importPage') }}" class="btn" style="background-color: #17a2b8;">⬆️ استيراد من Excel</a>
        <a href="{{ route('products.downloadTemplate') }}" class="btn" style="background-color: #ffc107; color: #333;">⬇️ تحميل قالب</a>
    </div>

    <!-- فلترة وبحث -->
    <div class="filters">
        <form method="GET" action="{{ route('products.index') }}" style="display: flex; flex-wrap: wrap; gap: 15px; width: 100%;">
            <div class="filter-group">
                <label>🔍 بحث</label>
                <input type="text" name="search" placeholder="كود أو اسم الصنف" value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <label>📌 النوع</label>
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
                        <th>النوع</th>
                        <th>اسم الصنف</th>
                        <th>اللون</th>
                        <th>المقاس</th>
                        <th>الرصيد الحالي</th>
                        <th>الحد الأدنى</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td><strong>{{ $product->item_code }}</strong></td>
                        <td>{{ $product->type ?? '-' }}</td>
                        <td style="text-align: right;">{{ $product->name }}</td>
                        <td>{{ $product->color ?? '-' }}</td>
                        <td>{{ $product->size ?? '-' }}</td>
                        <td>
                            @php $stock = $product->stock?->current_stock ?? 0; @endphp
                            {{ number_format($stock) }}
                        </td>
                        <td>{{ $product->stock?->min_stock ?? '-' }}</td>
                        <td>
                            @if($stock <= 0)
                                <span class="stock-badge stock-out">❌ نفد</span>
                            @elseif($product->isLowStock())
                                <span class="stock-badge stock-low">⚠️ منخفض</span>
                            @else
                                <span class="stock-badge stock-high">✅ متوفر</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('products.show', $product->id) }}" class="btn-sm btn-view">عرض</a>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn-sm btn-edit">تعديل</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-sm btn-delete" onclick="return confirm('هل أنت متأكد من حذف هذا الصنف؟')">حذف</button>
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
            <a href="{{ route('products.create') }}" class="btn" style="background-color: #28a745;">➕ أضف صنف جديد</a>
        </div>
    @endif

</div>

</body>
</html>
