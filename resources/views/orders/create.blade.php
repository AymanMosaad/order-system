@extends('layouts.navbar')

@section('content')
<div class="container mt-5">
    <h2 class="text-center">إنشاء طلبية جديدة</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        <div class="row mb-4">
            <div class="col-md-6">
                <label class="form-label">التاريخ</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', now()->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
            </div>
        </div>

        <h4>الأصناف في الطلبية</h4>
        <table class="table table-bordered" id="items-table">
            <thead class="table-dark">
                <tr>
                    <th>كود أول</th>
                    <th>كود ثاني</th>
                    <th>كود ثالث</th>
                    <th>النوع</th>
                    <th>اسم الصنف</th>
                    <th>اللون</th>
                    <th>المقاس</th>
                    <th>فرز 1</th>
                    <th>فرز 2</th>
                    <th>فرز 3</th>
                    <th>الإجمالي</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody>
                <!-- الصف الأول الافتراضي -->
                <tr>
                    <td><input type="text" name="items[0][item_code]" class="form-control" required></td>
                    <td><input type="text" name="items[0][item_code2]" class="form-control"></td>
                    <td><input type="text" name="items[0][item_code3]" class="form-control"></td>
                    <td><input type="text" name="items[0][type]" class="form-control"></td>
                    <td><input type="text" name="items[0][name]" class="form-control" required></td>
                    <td><input type="text" name="items[0][color]" class="form-control"></td>
                    <td><input type="text" name="items[0][size]" class="form-control"></td>
                    <td><input type="number" name="items[0][grade1]" class="form-control" min="0" value="0"></td>
                    <td><input type="number" name="items[0][grade2]" class="form-control" min="0" value="0"></td>
                    <td><input type="number" name="items[0][grade3]" class="form-control" min="0" value="0"></td>
                    <td><input type="number" name="items[0][total]" class="form-control" readonly></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row">حذف</button></td>
                </tr>
            </tbody>
        </table>

        <button type="button" id="add-row" class="btn btn-success mb-3">➕ إضافة صنف جديد</button>

        <div class="text-end">
            <button type="submit" class="btn btn-primary btn-lg">💾 حفظ الطلبية</button>
        </div>
    </form>
</div>

<script>
let rowIndex = 1;

document.getElementById('add-row').addEventListener('click', function() {
    const table = document.querySelector('#items-table tbody');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td><input type="text" name="items[${rowIndex}][item_code]" class="form-control" required></td>
        <td><input type="text" name="items[${rowIndex}][item_code2]" class="form-control"></td>
        <td><input type="text" name="items[${rowIndex}][item_code3]" class="form-control"></td>
        <td><input type="text" name="items[${rowIndex}][type]" class="form-control"></td>
        <td><input type="text" name="items[${rowIndex}][name]" class="form-control" required></td>
        <td><input type="text" name="items[${rowIndex}][color]" class="form-control"></td>
        <td><input type="text" name="items[${rowIndex}][size]" class="form-control"></td>
        <td><input type="number" name="items[${rowIndex}][grade1]" class="form-control" min="0" value="0"></td>
        <td><input type="number" name="items[${rowIndex}][grade2]" class="form-control" min="0" value="0"></td>
        <td><input type="number" name="items[${rowIndex}][grade3]" class="form-control" min="0" value="0"></td>
        <td><input type="number" name="items[${rowIndex}][total]" class="form-control" readonly></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-row">حذف</button></td>
    `;
    table.appendChild(newRow);
    rowIndex++;
});

// حذف الصف
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
    }
});
</script>
@endsection
