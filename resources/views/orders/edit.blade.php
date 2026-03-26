<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الطلبية #{{ $order->id }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; direction: rtl; margin: 0; background-color: #f5f5f5; padding: 20px; }
        .container { max-width: 1600px; margin: 0 auto; }
        h2, h3 { text-align: center; color: #333; }
        .box { border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; background-color: white; border-radius: 5px; }
        .form-group { display: inline-block; margin: 10px 5px; width: 30%; }
        input, select, textarea {
            padding: 8px; margin: 5px 0; width: 100%; border: 1px solid #ccc; border-radius: 3px; font-size: 13px;
        }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #007bff; box-shadow: 0 0 5px rgba(0,123,255,0.3); }
        .table-wrapper { overflow-x: auto; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; background-color: white; min-width: 1000px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; font-size: 12px; }
        th { background-color: #333; color: white; font-weight: bold; position: sticky; top: 0; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .stock-info { font-size: 11px; font-weight: bold; padding: 3px 6px; border-radius: 3px; margin-top: 3px; }
        .stock-ok { background:#d4edda; color:#155724; }
        .stock-low { background:#fff3cd; color:#856404; }
        .stock-danger { background:#f8d7da; color:#721c24; }
        .btn { padding: 10px 15px; margin: 10px 5px; cursor: pointer; border: none; border-radius: 3px; font-size: 14px; font-weight: bold; }
        .btn-add { background-color: #28a745; color: white; }
        .btn-save { background-color: #007bff; color: white; width: 100%; }
        .btn-delete { background-color: #dc3545; color: white; padding: 5px 10px; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <h2>✏️ تعديل الطلبية #{{ $order->id }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>❌ خطأ!</strong>
            <ul style="margin: 10px 0; padding-right: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('orders.update', $order->id) }}">
    @csrf
    @method('PUT')

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

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>كود الصنف</th>
                    <th>اسم الصنف</th>
                    <th>الرصيد المتوفر</th>
                    <th>الكمية</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody id="items">
                @foreach($order->items as $index => $item)
                <tr>
                    <td>
                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                        <input type="text" name="items[{{ $index }}][item_code]" value="{{ $item->item_code }}" readonly>
                    </td>
                    <td>
                        <input type="text" name="items[{{ $index }}][name]" value="{{ $item->name }}" class="name-input">
                    </td>
                    <td>
                        <div class="stock-info stock-ok" id="stock_{{ $index }}">
                            متوفر: {{ $item->product?->getCurrentStock() ?? 0 }}
                        </div>
                    </td>
                    <td>
                        <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->grade1 ?? 0 }}" min="0">
                    </td>
                    <td><button type="button" class="btn btn-delete" onclick="deleteItem(this)">✖</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <button type="button" class="btn btn-add" onclick="addItem()">➕ إضافة صنف</button>
    <br><br>
    <button type="submit" class="btn btn-save">💾 حفظ التعديلات</button>
    </form>
</div>

<script>
let itemIndex = {{ count($order->items) }};

function addItem() {
    const tbody = document.getElementById('items');
    const row = document.createElement('tr');

    row.innerHTML = `
        <td><input type="text" name="items[${itemIndex}][item_code]" readonly></td>
        <td><input type="text" name="items[${itemIndex}][name]" class="name-input" placeholder="اسم الصنف" list="productNamesList"></td>
        <td><div class="stock-info" id="stock_${itemIndex}">---</div></td>
        <td><input type="number" name="items[${itemIndex}][quantity]" value="0" min="0"></td>
        <td><button type="button" class="btn btn-delete" onclick="deleteItem(this)">✖</button></td>
    `;

    tbody.appendChild(row);
    itemIndex++;
}

document.addEventListener('input', function(e) {
    if (e.target.classList.contains('name-input')) {
        const name = e.target.value.trim();
        if (name.length < 3) return;

        const rowIndex = parseInt(e.target.name.match(/\d+/)[0]);

        fetch(`/products/get-by-name/${encodeURIComponent(name)}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const p = data.product;
                    const row = e.target.closest('tr');

                    row.querySelector('input[name*="name"]').value = p.name;
                    row.querySelector('input[name*="item_code"]').value = p.item_code || '';
                    const stockDiv = document.getElementById(`stock_${rowIndex}`);
                    stockDiv.innerHTML = `متوفر: ${p.stock1 || 0}`;
                    stockDiv.className = `stock-info ${p.stock1 > 50 ? 'stock-ok' : p.stock1 > 10 ? 'stock-low' : 'stock-danger'}`;
                }
            });
    }
});

function deleteItem(btn) {
    btn.closest('tr').remove();
}
</script>

<datalist id="productNamesList">
    @foreach($products as $product)
        <option value="{{ $product->name }}">
    @endforeach
</datalist>

</body>
</html>
