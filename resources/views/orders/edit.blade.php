<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الطلبية</title>
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
        h2, h3 { text-align: center; color: #333; }
        .box {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            background-color: white;
            border-radius: 5px;
        }
        .form-group {
            display: inline-block;
            margin: 10px 5px;
            width: 30%;
        }
        input, select, textarea {
            padding: 8px;
            margin: 5px 0;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-family: Arial;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0,123,255,0.3);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            font-size: 13px;
        }
        th {
            background-color: #333;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn {
            padding: 10px 15px;
            margin: 10px 5px;
            cursor: pointer;
            border: none;
            border-radius: 3px;
            font-size: 14px;
            font-weight: bold;
        }
        .btn-add { background-color: #28a745; color: white; }
        .btn-add:hover { background-color: #218838; }
        .btn-save { background-color: #007bff; color: white; }
        .btn-save:hover { background-color: #0056b3; }
        .btn-delete { background-color: #dc3545; color: white; padding: 5px 10px; }
        .btn-delete:hover { background-color: #c82333; }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        input[type="number"] { width: 100%; }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <h2>✏️ تعديل الطلبية #{{ $order->id }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>خطأ!</strong>
            <ul style="margin: 10px 0; padding-right: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('orders.update', $order->id) }}">
    @csrf

    <div class="box">
        <h3>📋 بيانات الطلبية</h3>

        <div class="form-group">
            <label>اسم العميل <span style="color:red;">*</span></label>
            <input type="text" name="customer_name" value="{{ $order->customer_name }}" required>
        </div>

        <div class="form-group">
            <label>اسم التاجر</label>
            <input type="text" name="trader_name" value="{{ $order->trader_name }}">
        </div>

        <div class="form-group">
            <label>رقم الإذن</label>
            <input type="text" name="order_number" value="{{ $order->order_number }}">
        </div>

        <div class="form-group">
            <label>نوع المخزن</label>
            <select name="warehouse_type">
                <option value="">-- اختر نوع المخزن --</option>
                <option value="محلي" {{ $order->warehouse_type == 'محلي' ? 'selected' : '' }}>محلي</option>
                <option value="معرض بيع" {{ $order->warehouse_type == 'معرض بيع' ? 'selected' : '' }}>معرض بيع</option>
                <option value="تصدير" {{ $order->warehouse_type == 'تصدير' ? 'selected' : '' }}>تصدير</option>
                <option value="عينات" {{ $order->warehouse_type == 'عينات' ? 'selected' : '' }}>عينات</option>
                <option value="ديكور" {{ $order->warehouse_type == 'ديكور' ? 'selected' : '' }}>ديكور</option>
                <option value="احتكار" {{ $order->warehouse_type == 'احتكار' ? 'selected' : '' }}>احتكار</option>
            </select>
        </div>

        <div class="form-group">
            <label>العنوان / المنطقة</label>
            <input type="text" name="address" value="{{ $order->address }}">
        </div>

        <div class="form-group">
            <label>رقم الهاتف</label>
            <input type="text" name="phone" value="{{ $order->phone }}">
        </div>

        <div class="form-group">
            <label>اسم السائق</label>
            <input type="text" name="driver_name" value="{{ $order->driver_name }}">
        </div>

        <div class="form-group">
            <label>التاريخ <span style="color:red;">*</span></label>
            <input type="date" name="date" value="{{ $order->date->format('Y-m-d') }}" required>
        </div>

        <div style="width: 100%;">
            <label>ملاحظات</label>
            <textarea name="notes" style="width: 100%; height: 80px;">{{ $order->notes }}</textarea>
        </div>
    </div>

    <h3>📦 تفاصيل الأصناف</h3>
    <table>
        <thead>
            <tr>
                <th>كود الصنف</th>
                <th>النوع</th>
                <th>اسم الصنف</th>
                <th>اللون</th>
                <th>المقاس</th>
                <th>فرز أول</th>
                <th>فرز ثاني</th>
                <th>فرز ثالث</th>
                <th>الإجمالي</th>
                <th>حذف</th>
            </tr>
        </thead>
        <tbody id="items">
            @foreach($order->items as $index => $item)
            <tr>
                <td>
                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                    <input type="text" name="items[{{ $index }}][item_code]" value="{{ $item->item_code }}" required>
                </td>
                <td><input type="text" name="items[{{ $index }}][type]" value="{{ $item->type }}"></td>
                <td><input type="text" name="items[{{ $index }}][name]" value="{{ $item->name }}"></td>
                <td><input type="text" name="items[{{ $index }}][color]" value="{{ $item->color }}"></td>
                <td><input type="text" name="items[{{ $index }}][size]" value="{{ $item->size }}"></td>
                <td><input type="number" name="items[{{ $index }}][grade1]" value="{{ $item->grade1 }}" min="0" oninput="calcTotal({{ $index }})"></td>
                <td><input type="number" name="items[{{ $index }}][grade2]" value="{{ $item->grade2 }}" min="0" oninput="calcTotal({{ $index }})"></td>
                <td><input type="number" name="items[{{ $index }}][grade3]" value="{{ $item->grade3 }}" min="0" oninput="calcTotal({{ $index }})"></td>
                <td><input type="number" name="items[{{ $index }}][total]" value="{{ $item->total }}" readonly id="total_{{ $index }}"></td>
                <td><button type="button" class="btn btn-delete" onclick="removeRow(this)">✖</button></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button type="button" class="btn btn-add" onclick="addItem()">➕ إضافة صنف</button>
    <br><br>
    <button type="submit" class="btn btn-save">💾 حفظ التعديلات</button>

    </form>

</div>

<script>
let index = {{ count($order->items) }};

function addItem() {
    const tbody = document.getElementById('items');
    const row = document.createElement('tr');

    row.innerHTML = `
        <td><input type="text" name="items[${index}][item_code]" placeholder="كود الصنف" required></td>
        <td><input type="text" name="items[${index}][type]" placeholder="النوع"></td>
        <td><input type="text" name="items[${index}][name]" placeholder="اسم الصنف"></td>
        <td><input type="text" name="items[${index}][color]" placeholder="اللون"></td>
        <td><input type="text" name="items[${index}][size]" placeholder="المقاس"></td>
        <td><input type="number" name="items[${index}][grade1]" value="0" min="0" oninput="calcTotal(${index})"></td>
        <td><input type="number" name="items[${index}][grade2]" value="0" min="0" oninput="calcTotal(${index})"></td>
        <td><input type="number" name="items[${index}][grade3]" value="0" min="0" oninput="calcTotal(${index})"></td>
        <td><input type="number" name="items[${index}][total]" value="0" readonly id="total_${index}"></td>
        <td><button type="button" class="btn btn-delete" onclick="removeRow(this)">✖</button></td>
    `;

    tbody.appendChild(row);
    index++;
}

function removeRow(btn) {
    btn.closest('tr').remove();
}

function calcTotal(i) {
    const g1 = parseInt(document.getElementsByName(`items[${i}][grade1]`)[0]?.value || 0);
    const g2 = parseInt(document.getElementsByName(`items[${i}][grade2]`)[0]?.value || 0);
    const g3 = parseInt(document.getElementsByName(`items[${i}][grade3]`)[0]?.value || 0);
    const total = g1 + g2 + g3;

    const totalInput = document.getElementById(`total_${i}`);
    if (totalInput) {
        totalInput.value = total;
    }
}
</script>

</body>
</html>
