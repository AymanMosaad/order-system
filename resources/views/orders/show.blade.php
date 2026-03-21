@extends('layouts.navbar')

@section('content')
<div class="container mt-5">
    <h2>تفاصيل الطلبية #{{ $order->id }}</h2>

    <p><strong>التاريخ:</strong> {{ $order->date->format('Y-m-d') }}</p>
    <p><strong>الحالة:</strong> {{ $order->status }}</p>
    <p><strong>ملاحظات:</strong> {{ $order->notes ?? 'لا توجد' }}</p>

    <h4>الأصناف في الطلبية</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>كود أول</th>
                <th>كود ثاني</th>
                <th>كود ثالث</th>
                <th>النوع</th>
                <th>الاسم</th>
                <th>اللون</th>
                <th>المقاس</th>
                <th>فرز 1</th>
                <th>فرز 2</th>
                <th>فرز 3</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->item_code ?? '-' }}</td>
                <td>{{ $item->item_code2 ?? '-' }}</td>
                <td>{{ $item->item_code3 ?? '-' }}</td>
                <td>{{ $item->type ?? '-' }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->color ?? '-' }}</td>
                <td>{{ $item->size ?? '-' }}</td>
                <td>{{ $item->grade1 ?? 0 }}</td>
                <td>{{ $item->grade2 ?? 0 }}</td>
                <td>{{ $item->grade3 ?? 0 }}</td>
                <td><strong>{{ $item->total ?? 0 }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p class="mt-3"><strong>إجمالي الكميات:</strong> {{ $order->getTotalQuantity() }}</p>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary">رجوع</a>
    @if(auth()->id() === $order->user_id)
        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning">تعديل</a>
    @endif
</div>
@endsection
