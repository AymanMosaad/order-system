@extends('layouts.navbar')

@section('content')
<div class="container mt-5">
    <h2>تعديل الطلبية #{{ $order->id }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-4">
            <div class="col-md-6">
                <label>التاريخ</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', $order->date->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-6">
                <label>ملاحظات</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes', $order->notes) }}</textarea>
            </div>
        </div>

        <h4>الأصناف</h4>
        <table class="table table-bordered" id="items-table">
            <thead class="table-dark">
                <tr>
                    <th>كود أول</th><th>كود ثاني</th><th>كود ثالث</th>
                    <th>النوع</th><th>اسم الصنف</th><th>اللون</th><th>المقاس</th>
                    <th>فرز 1</th><th>فرز 2</th><th>فرز 3</th><th>الإجمالي</th><th>حذف</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr>
                    <td><input type="text" name="items[{{ $index }}][item_code]" class="form-control" value="{{ $item->item_code }}" required></td>
                    <td><input type="text" name="items[{{ $index }}][item_code2]" class="form-control" value="{{ $item->item_code2 }}"></td>
                    <td><input type="text" name="items[{{ $index }}][item_code3]" class="form-control" value="{{ $item->item_code3 }}"></td>
                    <td><input type="text" name="items[{{ $index }}][type]" class="form-control" value="{{ $item->type }}"></td>
                    <td><input type="text" name="items[{{ $index }}][name]" class="form-control" value="{{ $item->name }}" required></td>
                    <td><input type="text" name="items[{{ $index }}][color]" class="form-control" value="{{ $item->color }}"></td>
                    <td><input type="text" name="items[{{ $index }}][size]" class="form-control" value="{{ $item->size }}"></td>
                    <td><input type="number" name="items[{{ $index }}][grade1]" class="form-control" value="{{ $item->grade1 ?? 0 }}"></td>
                    <td><input type="number" name="items[{{ $index }}][grade2]" class="form-control" value="{{ $item->grade2 ?? 0 }}"></td>
                    <td><input type="number" name="items[{{ $index }}][grade3]" class="form-control" value="{{ $item->grade3 ?? 0 }}"></td>
                    <td><input type="number" name="items[{{ $index }}][total]" class="form-control" value="{{ $item->total ?? 0 }}" readonly></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row">حذف</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="button" id="add-row" class="btn btn-success mb-3">➕ إضافة صنف</button>

        <div class="text-end">
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">إلغاء</a>
            <button type="submit" class="btn btn-warning btn-lg">💾 حفظ التعديلات</button>
        </div>
    </form>
</div>

<!-- نفس الـ JavaScript اللي في create.blade.php (انسخه هنا أو ضعه في layout) -->
<script>
    // نفس كود الـ add-row و remove-row الموجود في create.blade.php
    // (انسخه من فوق عشان يشتغل)
</script>
@endsection
