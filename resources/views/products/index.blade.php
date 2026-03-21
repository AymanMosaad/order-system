@extends('layouts.navbar')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">قائمة الأصناف والأرصدة</h2>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <a href="{{ route('products.importPage') }}" class="btn btn-primary mb-3">استيراد من إكسل</a>

    @if($products->isEmpty())
        <p class="text-center alert alert-warning">لا توجد أصناف حالياً. جرب تشغل الـ seeder أو أنشئ صنف يدوي.</p>
    @else
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>كود الصنف</th>
                    <th>الاسم</th>
                    <th>النوع</th>
                    <th>اللون</th>
                    <th>المقاس</th>
                    <th>الرصيد الحالي</th>
                    <th>الحد الأدنى</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td><strong>{{ $product->item_code }}</strong></td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->type }}</td>
                    <td>{{ $product->color ?? '-' }}</td>
                    <td>{{ $product->size ?? '-' }}</td>
                    <td class="fw-bold">{{ $product->stock?->current_stock ?? 0 }}</td>
                    <td>{{ $product->stock?->min_stock ?? 0 }}</td>
                    <td>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">عرض</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
