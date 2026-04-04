<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>إنشاء طلب جديد - جلوريا للسيراميك</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', 'Tahoma', Arial, sans-serif;
            direction: rtl;
            background-color: #f8f9fa;
            padding: 20px;
            padding-top: 90px;
        }
        .container { max-width: 1400px; margin: 0 auto; }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }
        .page-header h1 { margin: 0 0 10px 0; font-size: 28px; }
        .page-header p { margin: 0; opacity: 0.9; font-size: 14px; }

        .form-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .card-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #007bff;
            display: inline-block;
        }
        .card-title i { margin-left: 8px; color: #007bff; }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            font-weight: bold;
            color: #555;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .form-group input, .form-group select, .form-group textarea {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 14px;
        }
        .required { color: #dc3545; margin-right: 4px; }

        .table-responsive-custom {
            overflow-x: auto;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }
        th, td {
            border: 1px solid #eee;
            padding: 12px;
            text-align: center;
            font-size: 14px;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
        }

        .stock-info {
            font-size: 12px;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 20px;
            display: inline-block;
        }
        .stock-ok { background: #d4edda; color: #155724; }
        .stock-low { background: #fff3cd; color: #856404; }
        .stock-danger { background: #f8d7da; color: #721c24; }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-add { background: #28a745; color: white; width: 100%; }
        .btn-save { background: #007bff; color: white; width: 100%; padding: 14px; font-size: 16px; }
        .btn-delete { background: #dc3545; color: white; padding: 8px 16px; font-size: 13px; border-radius: 6px; cursor: pointer; }

        .alert { padding: 15px 20px; border-radius: 10px; margin-bottom: 20px; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        @media (max-width: 768px) {
            body { padding: 10px; padding-top: 80px; }
            .form-grid { grid-template-columns: 1fr; gap: 15px; }
            .page-header h1 { font-size: 22px; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-plus-circle"></i> إنشاء طلبية جديدة</h1>
        <p>أدخل بيانات الطلبية والأصناف المطلوبة</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong><i class="fas fa-exclamation-triangle"></i> خطأ!</strong>
            <ul style="margin: 10px 0; padding-right: 20px;">
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-times-circle"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
        @csrf

        <!-- بيانات الطلبية -->
        <div class="form-card">
            <div class="card-title"><i class="fas fa-info-circle"></i> بيانات الطلبية</div>
            <div class="form-grid">
                <div class="form-group">
                    <label><i class="fas fa-user-tie"></i> اسم العميل <span class="required">*</span></label>
                    <input type="text" name="customer_name" placeholder="أدخل اسم العميل" required value="{{ old('customer_name') }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-store"></i> اسم التاجر</label>
                    <input type="text" name="trader_name" placeholder="أدخل اسم التاجر" value="{{ old('trader_name') }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-hashtag"></i> رقم الإذن</label>
                    <input type="text" name="order_number" placeholder="رقم الإذن" value="{{ old('order_number') }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-percent"></i> نسبة خصم الإذن (%)</label>
                    <input type="number" name="order_discount" step="0.01" min="0" max="100" placeholder="نسبة الخصم على هذه الطلبية" value="{{ old('order_discount', 0) }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-warehouse"></i> نوع المخزن</label>
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
                    <label><i class="fas fa-location-dot"></i> العنوان / المنطقة</label>
                    <input type="text" name="address" placeholder="العنوان" value="{{ old('address') }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> رقم الهاتف</label>
                    <input type="text" name="phone" placeholder="رقم الهاتف" value="{{ old('phone') }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-truck"></i> اسم السائق</label>
                    <input type="text" name="driver_name" placeholder="اسم السائق" value="{{ old('driver_name') }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-calendar-day"></i> التاريخ <span class="required">*</span></label>
                    <input type="date" name="date" required value="{{ old('date', date('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-pen"></i> ملاحظات</label>
                    <textarea name="notes" placeholder="ملاحظات إضافية">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- تفاصيل الأصناف -->
        <div class="form-card">
            <div class="card-title"><i class="fas fa-cubes"></i> تفاصيل الأصناف</div>

            <div class="table-responsive-custom">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 15%">كود الصنف</th>
                            <th style="width: 35%">اسم الصنف</th>
                            <th style="width: 20%">الرصيد المتوفر</th>
                            <th style="width: 20%">الكمية</th>
                            <th style="width: 10%">حذف</th>
                        </thead>
                        <tbody id="itemsTable"></tbody>
                    </table>
            </div>

            <button type="button" class="btn btn-add" onclick="addItem()">
                <i class="fas fa-plus-circle"></i> إضافة صنف
            </button>
        </div>

        <button type="submit" class="btn btn-save">
            <i class="fas fa-save"></i> حفظ الطلبية
        </button>
    </form>
</div>

<datalist id="productNamesList">
    @foreach($products as $product)
        <option value="{{ $product->name }}">
    @endforeach
</datalist>

<script>
let itemCounter = 0;

function addItem() {
    const idx = itemCounter;
    const tbody = document.getElementById('itemsTable');

    const row = tbody.insertRow();
    row.id = `row_${idx}`;

    row.innerHTML = `
        <td><input type="text" name="items[${idx}][item_code]" id="code_${idx}" readonly style="background:#f5f5f5; width:100%; padding:8px;"></td>
        <td><input type="text" name="items[${idx}][name]" id="name_${idx}" class="product-name" placeholder="اكتب اسم الصنف" list="productNamesList" autocomplete="off" data-idx="${idx}" style="width:100%; padding:8px;"></td>
        <td><div class="stock-info" id="stock_${idx}">---</div></td>
        <td><input type="number" name="items[${idx}][quantity]" id="qty_${idx}" value="0" step="0.01" min="0" style="width:100px; padding:8px;"></td>
        <td><button type="button" class="btn-delete" onclick="deleteItem(${idx})"><i class="fas fa-trash-alt"></i></button></td>
    `;

    const nameInput = document.getElementById(`name_${idx}`);
    nameInput.addEventListener('change', () => fetchProduct(idx));
    nameInput.addEventListener('input', () => fetchProduct(idx));

    const qtyInput = document.getElementById(`qty_${idx}`);
    qtyInput.addEventListener('input', () => checkStock(idx));

    itemCounter++;
}

function fetchProduct(idx) {
    const name = document.getElementById(`name_${idx}`).value;
    if (name.length < 2) return;

    fetch(`/products/get-by-name/${encodeURIComponent(name)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const p = data.product;
                const stockValue = p.stock1 || 0;

                document.getElementById(`code_${idx}`).value = p.item_code || '';

                const stockDiv = document.getElementById(`stock_${idx}`);
                stockDiv.innerHTML = `<i class="fas fa-warehouse"></i> متوفر: ${stockValue}`;
                stockDiv.className = `stock-info ${stockValue > 50 ? 'stock-ok' : stockValue > 10 ? 'stock-low' : 'stock-danger'}`;

                const qty = document.getElementById(`qty_${idx}`);
                if (qty && qty.value > 0) checkStock(idx);
            }
        })
        .catch(err => console.error('Fetch error:', err));
}

function checkStock(idx) {
    const qtyInput = document.getElementById(`qty_${idx}`);
    const stockDiv = document.getElementById(`stock_${idx}`);
    const qty = parseFloat(qtyInput.value) || 0;

    const stockText = stockDiv.innerHTML;
    const stockMatch = stockText.match(/\d+\.?\d*/);

    if (stockMatch) {
        const stock = parseFloat(stockMatch[0]);

        if (qty > stock) {
            stockDiv.className = 'stock-info stock-danger';
            stockDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> متوفر: ${stock} - الكمية أكبر من الرصيد`;
            qtyInput.style.borderColor = '#dc3545';
        } else if (qty > stock * 0.7) {
            stockDiv.className = 'stock-info stock-low';
            stockDiv.innerHTML = `<i class="fas fa-clock"></i> متوفر: ${stock} - الكمية قريبة من الرصيد`;
            qtyInput.style.borderColor = '#ffc107';
        } else {
            stockDiv.className = 'stock-info stock-ok';
            stockDiv.innerHTML = `<i class="fas fa-check-circle"></i> متوفر: ${stock}`;
            qtyInput.style.borderColor = '#ddd';
        }
    }
}

function deleteItem(idx) {
    const row = document.getElementById(`row_${idx}`);
    if (row) row.remove();
}

document.getElementById('orderForm').addEventListener('submit', function(e) {
    const rows = document.querySelectorAll('#itemsTable tr');
    if (rows.length === 0) {
        e.preventDefault();
        alert('⚠️ يجب إضافة صنف واحد على الأقل');
        return;
    }

    let hasValid = false;
    rows.forEach(row => {
        const name = row.querySelector('input[name*="[name]"]')?.value;
        const qty = row.querySelector('input[name*="[quantity]"]')?.value;
        if (name && parseFloat(qty) > 0) hasValid = true;
    });

    if (!hasValid) {
        e.preventDefault();
        alert('⚠️ يجب إضافة صنف واحد على الأقل مع تحديد اسم الصنف والكمية');
    }
});
</script>

</body>
</html>
