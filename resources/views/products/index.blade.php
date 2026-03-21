@extends('layouts.navbar')

@section('content')
<div class="container mt-4">
    <h2>قائمة الأصناف والأرصدة</h2>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if(session('import_errors'))
        <div class="alert alert-danger">
            <h5>الأخطاء:</h5>
            <ul>
                @foreach(session('import_errors') as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>كود الصنف</th>
                <th>الاسم</th>
                <th>النوع</th>
                <th>اللون</th>
                <th>المقاس</th>
                <th>الرصيد الحالي</th>
                <th>الحد الأدنى</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->item_code }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->type }}</td>
                <td>{{ $product->color }}</td>
                <td>{{ $product->size }}</td>
                <td>
                    <strong>{{ $product->stock?->current_stock ?? 0 }}</strong>
                </td>
                <td>{{ $product->stock?->min_stock ?? 0 }}</td>
                <td>
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">عرض</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
