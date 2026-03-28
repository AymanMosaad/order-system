<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الطلبية #{{ $order->id }}</title>
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

        .header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }
        .header h1 { margin: 0 0 5px 0; font-size: 24px; }
        .header p  { margin: 0; opacity: 0.85; font-size: 14px; }
        .header-badge {
            background: rgba(255,255,255,0.2);
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 15px;
            font-weight: bold;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #f5576c;
            display: inline-block;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 18px;
        }
        .form-group { display: flex; flex-direction: column; }
        .form-group label {
            font-weight: bold;
            color: #555;
            margin-bottom: 7px;
            font-size: 13px;
        }
        .form-group input, .form-group select, .form-group textarea {
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Tahoma', Arial, sans-serif;
            transition: all 0.3s;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: #f5576c;
            box-shadow: 0 0 0 3px rgba(245,87,108,0.1);
        }
        .form-group textarea { resize: vertical; min-height: 80px; }

        .table-wrapper { overflow-x: auto; margin: 15px 0; }
        table { width: 100%; border-collapse: collapse; min-width: 700px; }
        th, td { border: 1px solid #eee; padding: 11px 12px; text-align: center; font-size: 13px; }
        th {
            background: #f8f9fa;
            color: #333;
            font-weight: bold;
            border-bottom: 2px solid #dee2e6;
        }
        tr:hover { background: #fff5f5; }

        .stock-info { font-size: 12px; font-weight: bold; padding: 4px 8px; border-radius: 5px; }
        .stock-ok      { background: #d4edda; color: #155724; }
        .stock-low     { background: #fff3cd; color: #856404; }
        .stock-danger  { background: #f8d7da; color: #721c24; }

        .btn {
            padding: 10px 22px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            text-decoration: none;
        }
        .btn:hover { transform: translateY(-2px); opacity: 0.9; }
        .btn-add    { background: #28a745; color: white; }
        .btn-save   { background: #f5576c; color: white; width: 100%; justify-content: center; padding: 13px; font-size: 15px; }
        .btn-delete { background: #dc3545; color: white; padding: 6px 12px; font-size: 12px; }

        .alert { padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        @media (max-width: 768px) {
            .header { flex-direction: column; text-align: center; }
            .form-grid { grid-template-columns: 1fr; }
            th, td { padding: 8px; font-size: 12px; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">

    <div class="header">
        <div>
            <h1>✏️ تعديل الطلبية</h1>
            <p>{{ $order->customer_name }} — {{ $order->date->format('Y-m-d') }}</p>
        </div>
        <div class="header-badge"># {{ $order->id }}</div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>❌ خطأ!</strong>
            <ul style="margin: 8px 0; padding-right: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('orders.update', $order->id) }}">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-title">📋 بيانات الطلبية</div>
            <div class="form-grid">
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
                        <option value="محلي"      {{ $order->warehouse_type == 'محلي'      ? 'selected' : '' }}>محلي</option>
                        <option value="معرض بيع"  {{ $order->warehouse_type == 'معرض بيع'  ? 'selected' : '' }}>معرض بيع</option>
                        <option value="تصدير"     {{ $order->warehouse_type == 'تصدير'     ? 'selected' : '' }}>تصدير</option>
                        <option value="عينات"     {{ $order->warehouse_type == 'عينات'     ? 'selected' : '' }}>عينات</option>
                        <option value="ديكور"     {{ $order->warehouse_type == 'ديكور'     ? 'selected' : '' }}>ديكور</option>
                        <option value="احتكار"    {{ $order->warehouse_type == 'احتكار'    ? 'selected' : '' }}>احتكار</option>
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
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>ملاحظات</label>
                    <textarea name="notes">{{ $order->notes }}</textarea>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title">📦 تفاصيل الأصناف</div>
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
                                <input type="text" name="items[{{ $index }}][item_code]" value="{{ $item->item_code }}" readonly
                                       style="background:#f5f5f5; width:100%; padding:8px; border:1px solid #ddd; border-radius:6px;">
                            </td>
                            <td>
                                <input type="text" name="items[{{ $index }}][name]" value="{{ $item->name }}"
                                       class="name-input" list="productNamesList" data-index="{{ $index }}"
                                       style="width:100%; padding:8px; border:1px solid #ddd; border-radius:6px;">
                            </td>
                            <td>
                                <div class="stock-info stock-ok" id="stock_{{ $index }}">
                                    📦 متوفر: {{ $item->product?->getCurrentStock() ?? 0 }}
                                </div>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][quantity]"
                                       value="{{ $item->grade1 ?? 0 }}" min="0"
                                       style="width:100px; text-align:center; padding:8px; border:1px solid #ddd; border-radius:6px;">
                            </td>
                            <td>
                                <button type="button" class="btn btn-delete" onclick="deleteItem(this)">✖</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button type="button" class="btn btn-add" onclick="addItem()" style="margin-top: 10px;">
                ➕ إضافة صنف
            </button>
        </div>

        <button type="submit" class="btn btn-save">💾 حفظ التعديلات</button>
    </form>
</div>

<datalist id="productNamesList">
    @foreach($products as $product)
        <option value="{{ $product->name }}">
    @endforeach
</datalist>

<script>
let itemIndex = {{ count($order->items) }};

function addItem() {
    const tbody = document.getElementById('items');
    const row = document.createElement('tr');
    row.innerHTML = `
        <td><input type="text" name="items[${itemIndex}][item_code]" readonly
                   style="background:#f5f5f5; width:100%; padding:8px; border:1px solid #ddd; border-radius:6px;"></td>
        <td><input type="text" name="items[${itemIndex}][name]" class="name-input"
                   placeholder="اسم الصنف" list="productNamesList" data-index="${itemIndex}"
                   style="width:100%; padding:8px; border:1px solid #ddd; border-radius:6px;"></td>
        <td><div class="stock-info" id="stock_${itemIndex}">---</div></td>
        <td><input type="number" name="items[${itemIndex}][quantity]" value="0" min="0"
                   style="width:100px; text-align:center; padding:8px; border:1px solid #ddd; border-radius:6px;"></td>
        <td><button type="button" class="btn btn-delete" onclick="deleteItem(this)">✖</button></td>
    `;
    tbody.appendChild(row);
    itemIndex++;
}

function handleNameInput(e) {
    if (!e.target.classList.contains('name-input')) return;
    const name = e.target.value.trim();
    if (name.length < 3) return;
    const idx = parseInt(e.target.dataset.index);
    fetch(`/products/get-by-name/${encodeURIComponent(name)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const row = e.target.closest('tr');
                row.querySelector('input[name*="item_code"]').value = data.product.item_code || '';
                const stockDiv = document.getElementById(`stock_${idx}`);
                const s = data.product.stock1 || 0;
                stockDiv.innerHTML = `📦 متوفر: ${s}`;
                stockDiv.className = `stock-info ${s > 50 ? 'stock-ok' : s > 10 ? 'stock-low' : 'stock-danger'}`;
            }
        })
        .catch(err => console.error(err));
}

document.addEventListener('input',  handleNameInput);
document.addEventListener('change', handleNameInput);

function deleteItem(btn) {
    btn.closest('tr').remove();
}
</script>

</body>
</html>
