<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء طلب جديد</title>
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
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .quantity-input { width: 120px; text-align: center; }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <h2>🏭 إنشاء طلبية جديدة</h2>

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

    <form method="POST" action="{{ route('orders.store') }}">
    @csrf

    <div class="box">
        <h3>📋 بيانات الطلبية</h3>
        <div class="form-group">
            <label>اسم العميل <span style="color:red;">*</span></label>
            <input type="text" name="customer_name" placeholder="اسم العميل" required value="{{ old('customer_name') }}">
        </div>
        <div class="form-group">
            <label>اسم التاجر</label>
            <input type="text" name="trader_name" placeholder="اسم التاجر" value="{{ old('trader_name') }}">
        </div>
        <div class="form-group">
            <label>رقم الإذن</label>
            <input type="text" name="order_number" placeholder="رقم الإذن" value="{{ old('order_number') }}">
        </div>
        <div class="form-group">
            <label>نوع المخزن</label>
            <select name="warehouse_type">
                <option value="">-- اختر نوع المخزن --</option>
                <option value="محلي" {{ old('warehouse_type') == 'محلي' ? 'selected' : '' }}>محلي</option>
                <option value="معرض بيع" {{ old('warehouse_type') == 'معرض بيع' ? 'selected' : '' }}>معرض بيع</option>
                <option value="تصدير" {{ old('warehouse_type') == 'تصدير' ? 'selected' : '' }}>تصدير</option>
                <option value="عينات" {{ old('warehouse_type') == 'عينات' ? 'selected' : '' }}>عينات</option>
                <option value="ديكور" {{ old('warehouse_type') == 'ديكور' ? 'selected' : '' }}>ديكور</option>
                <option value="احتكار" {{ old('warehouse_type') == 'احتكار' ? 'selected' : '' }}>احتكار</option>
            </select>
        </div>
        <div class="form-group">
            <label>العنوان / المنطقة</label>
            <input type="text" name="address" placeholder="العنوان" value="{{ old('address') }}">
        </div>
        <div class="form-group">
            <label>رقم الهاتف</label>
            <input type="text" name="phone" placeholder="رقم الهاتف" value="{{ old('phone') }}">
        </div>
        <div class="form-group">
            <label>اسم السائق</label>
            <input type="text" name="driver_name" placeholder="اسم السائق" value="{{ old('driver_name') }}">
        </div>
        <div class="form-group">
            <label>التاريخ <span style="color:red;">*</span></label>
            <input type="date" name="date" required value="{{ old('date', date('Y-m-d')) }}">
        </div>
        <div style="width: 100%;">
            <label>ملاحظات</label>
            <textarea name="notes" placeholder="ملاحظات إضافية" style="width: 100%; height: 80px;">{{ old('notes') }}</textarea>
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
            <tbody id="items"></tbody>
        </table>
    </div>

    <button type="button" class="btn btn-add" onclick="addItem()">➕ إضافة صنف</button>
    <br><br>
    <button type="submit" class="btn btn-save">💾 حفظ الطلبية</button>
    </form>
</div>

<script>
let itemIndex = 0;

function addItem() {
    const tbody = document.getElementById('items');
    const row = document.createElement('tr');

    row.innerHTML = `
        <tr><input type="text" name="items[${itemIndex}][item_code]" readonly style="background:#f5f5f5;">\n
        <td><input type="text" name="items[${itemIndex}][name]" class="name-input" placeholder="اكتب اسم الصنف" list="productNamesList" autocomplete="off">\n
        <td><div class="stock-info" id="stock_${itemIndex}">---</div>\n
        <td><input type="number" name="items[${itemIndex}][quantity]" class="quantity-input" value="0" step="0.01" min="0" oninput="checkStock(${itemIndex})">\n
        <td><button type="button" class="btn btn-delete" onclick="deleteItem(this)">✖</button>\n
    `;

    tbody.appendChild(row);
    itemIndex++;
}

function checkStock(index) {
    const quantityInput = document.querySelector(`input[name="items[${index}][quantity]"]`);
    const stockDiv = document.getElementById(`stock_${index}`);

    if (stockDiv && stockDiv.innerHTML !== '---') {
        const stockText = stockDiv.innerHTML;
        const stockMatch = stockText.match(/متوفر:\s*([\d.]+)/);

        if (stockMatch && quantityInput) {
            const stock = parseFloat(stockMatch[1]);
            const quantity = parseFloat(quantityInput.value) || 0;

            if (quantity > stock) {
                stockDiv.style.backgroundColor = '#f8d7da';
                stockDiv.style.color = '#721c24';
                stockDiv.innerHTML = `⚠️ متوفر: ${stock} - الكمية أكبر من الرصيد`;
                quantityInput.style.borderColor = '#dc3545';
            } else if (quantity > stock * 0.7) {
                stockDiv.style.backgroundColor = '#fff3cd';
                stockDiv.style.color = '#856404';
                stockDiv.innerHTML = `⚠️ متوفر: ${stock} - الكمية قريبة من الرصيد`;
                quantityInput.style.borderColor = '#ffc107';
            } else {
                stockDiv.style.backgroundColor = '#d4edda';
                stockDiv.style.color = '#155724';
                stockDiv.innerHTML = `✅ متوفر: ${stock}`;
                quantityInput.style.borderColor = '#ccc';
            }
        }
    }
}

document.addEventListener('input', function(e) {
    if (e.target.classList.contains('name-input')) {
        const name = e.target.value.trim();
        if (name.length < 2) return;

        const rowIndex = parseInt(e.target.name.match(/\d+/)[0]);

        fetch(`/products/get-by-name/${encodeURIComponent(name)}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const p = data.product;
                    const row = e.target.closest('tr');

                    row.querySelector('input[name*="item_code"]').value = p.item_code || '';
                    const stockDiv = document.getElementById(`stock_${rowIndex}`);
                    const stockValue = p.stock1 || 0;
                    stockDiv.innerHTML = `📦 متوفر: ${stockValue}`;
                    stockDiv.className = `stock-info ${stockValue > 50 ? 'stock-ok' : stockValue > 10 ? 'stock-low' : 'stock-danger'}`;

                    // التحقق من الكمية إذا كانت موجودة
                    const quantityInput = row.querySelector('input[name*="quantity"]');
                    if (quantityInput && quantityInput.value > 0) {
                        checkStock(rowIndex);
                    }
                }
            })
            .catch(err => console.error('Fetch error:', err));
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
