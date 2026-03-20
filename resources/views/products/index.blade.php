<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الأصناف</title>
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
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 3px;
            margin-bottom: 20px;
        }
        .btn:hover { background-color: #218838; }
        table {
            width: 100%;
            border-collapse: collapse;
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
        .stock-badge {
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 12px;
        }
        .stock-high { background-color: #d4edda; color: #155724; }
        .stock-low { background-color: #f8d7da; color: #721c24; }
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            margin: 2px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            border-radius: 3px;
        }
        .btn-view { background-color: #17a2b8; color: white; }
        .btn-edit { background-color: #ffc107; color: #333; }
        .btn-delete { background-color: #dc3545; color: white; }
        .btn-sm:hover { opacity: 0.8; }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <h2>📦 الأصناف</h2>

    <a href="{{ route('products.create') }}" class="btn">➕ صنف جديد</a>
    <a href="{{ route('products.report') }}" class="btn" style="background-color: #17a2b8;">📊 التقارير</a>

    @if($products->count() > 0)
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
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td><strong>{{ $product->item_code }}</strong></td>
                    <td>{{ $product->type }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->color ?? '-' }}</td>
                    <td>{{ $product->size ?? '-' }}</td>
                    <td>{{ $product->getCurrentStock() }}</td>
                    <td>{{ $product->stock?->min_stock ?? '-' }}</td>
                    <td>
                        @if($product->isLowStock())
                            <span class="stock-badge stock-low">⚠️ ناقص</span>
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
                            <button type="submit" class="btn-sm btn-delete" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; padding: 40px; color: #666; background-color: white; border-radius: 5px;">
            لا توجد أصناف حالياً
        </p>
    @endif

</div>

</body>
</html>
