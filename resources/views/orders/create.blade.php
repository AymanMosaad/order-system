<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء طلب جديد - جلوريا للسيراميك</title>
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

        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        .header p {
            margin: 0;
            opacity: 0.9;
            font-size: 14px;
        }

        /* Cards */
        .card {
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

        /* Form Groups */
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
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        }
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        .required {
            color: #dc3545;
            margin-right: 4px;
        }

        /* Table */
        .table-wrapper {
            overflow-x: auto;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }
        th, td {
            border: 1px solid #eee;
            padding: 12px;
            text-align: center;
            font-size: 14px;
        }
        th {
            background: #f8f9fa;
            color: #333;
            font-weight: bold;
            border-bottom: 2px solid #dee2e6;
        }
        tr:hover {
            background: #f5f5f5;
        }

        /* Stock Info */
        .stock-info {
            font-size: 12px;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 20px;
            display: inline-block;
        }
        .stock-ok {
            background: #d4edda;
            color: #155724;
        }
        .stock-low {
            background: #fff3cd;
            color: #856404;
        }
        .stock-danger {
            background: #f8d7da;
            color: #721c24;
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-add {
            background: #28a745;
            color: white;
        }
        .btn-add:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        .btn-save {
            background: #007bff;
            color: white;
            width: 100%;
            justify-content: center;
            padding: 12px;
            font-size: 16px;
        }
        .btn-save:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }
        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 5px 12px;
            font-size: 12px;
        }
        .btn-delete:hover {
            background: #c82333;
        }

        /* Alerts */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        /* Quantity Input */
        .quantity-input {
            width: 100px;
            text-align: center;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            th, td {
                padding: 8px;
                font-size: 12px;
            }
            .quantity-input {
                width: 70px;
            }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="header">
        <h1>🏭 إنشاء طلبية جديدة</h1>
        <p>أدخل بيانات الطلبية والأصناف المطلوبة</p>
    </div>

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

    {{-- [تعديل 1] أضفنا id="orderForm" للفورم --}}
    <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
        @csrf

        <!-- بيانات الطلبية -->
        <div class="card">
            <div class="card-title">📋 بيانات الطلبية</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>اسم العميل <span class="required">*</span></label>
                    <input type="text" name="customer_name" placeholder="أدخل اسم العميل" required value="{{ old('customer_name') }}">
                </div>
                <div class="form-group">
                    <label>اسم التاجر</label>
                    <input type="text" name="trader_name" placeholder="أدخل اسم التاجر" value="{{ old('trader_name') }}">
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
                    <label>التاريخ <span class="required">*</span></label>
                    <input type="date" name="date" required value="{{ old('date', date('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label>ملاحظات</label>
                    <textarea name="notes" placeholder="ملاحظات إضافية">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- تفاصيل الأصناف -->
        <div class="card">
            <div class="card-title">📦 تفاصيل الأصناف</div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 15%">كود الصنف</th>
                            <th style="width: 35%">اسم الصنف</th>
                            <th style="width: 20%">الرصيد المتوفر</th>
                            <th style="width: 20%">الكمية</th>
                            <th style="width: 10%">حذف</th>
                        </thead>
                        <tbody id="items"></tbody>
                    </table>
                </div>

                <button type="button" class="btn btn-add" onclick="addItem()">
                    ➕ إضافة صنف
                </button>
            </div>

            <button type="submit" class="btn btn-save">
                💾 حفظ الطلبية
            </button>
        </form>
    </div>

    <script>
    let itemIndex = 0;

    function addItem() {
        const tbody = document.getElementById('items');
        const row = document.createElement('tr');

        {{-- [تعديل 2] أضفنا data-index للـ name input عشان نعرف رقم الصف بسهولة --}}
        row.innerHTML = `
            <td><input type="text" name="items[${itemIndex}][item_code]" readonly style="background:#f5f5f5; width:100%; padding:8px; border:1px solid #ddd; border-radius:6px;"></td>
            <td><input type="text" name="items[${itemIndex}][name]" class="name-input" placeholder="اكتب اسم الصنف" list="productNamesList" autocomplete="off" data-index="${itemIndex}" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:6px;"></td>
            <td><div class="stock-info" id="stock_${itemIndex}" style="display:inline-block;">---</div></td>
            <td><input type="number" name="items[${itemIndex}][quantity]" class="quantity-input" value="0" step="0.01" min="0" oninput="checkStock(${itemIndex})" style="width:100px; text-align:center; padding:8px; border:1px solid #ddd; border-radius:6px;"></td>
            <td><button type="button" class="btn btn-delete" onclick="deleteItem(this)">✖</button></td>
        `;

        tbody.appendChild(row);
        itemIndex++;
    }

    function checkStock(index) {
        const quantityInput = document.querySelector(`input[name="items[${index}][quantity]"]`);
        const stockDiv = document.getElementById(`stock_${index}`);

        if (stockDiv && stockDiv.innerHTML !== '---') {
            const stockText = stockDiv.innerHTML;
            const stockMatch = stockText.match(/\d+\.?\d*/);

            if (stockMatch && quantityInput) {
                const stock = parseFloat(stockMatch[0]);
                const quantity = parseFloat(quantityInput.value) || 0;

                if (quantity > stock) {
                    stockDiv.className = 'stock-info stock-danger';
                    stockDiv.innerHTML = `⚠️ متوفر: ${stock} - الكمية أكبر من الرصيد`;
                    quantityInput.style.borderColor = '#dc3545';
                } else if (quantity > stock * 0.7) {
                    stockDiv.className = 'stock-info stock-low';
                    stockDiv.innerHTML = `⚠️ متوفر: ${stock} - الكمية قريبة من الرصيد`;
                    quantityInput.style.borderColor = '#ffc107';
                } else {
                    stockDiv.className = 'stock-info stock-ok';
                    stockDiv.innerHTML = `✅ متوفر: ${stock}`;
                    quantityInput.style.borderColor = '#ddd';
                }
            }
        }
    }

    {{-- [تعديل 3] أضفنا 'change' مع 'input' عشان يشتغل لما المستخدم يختار من القائمة ويطلع --}}
    function handleNameInput(e) {
        if (!e.target.classList.contains('name-input')) return;

        const name = e.target.value.trim();
        if (name.length < 2) return;

        const rowIndex = parseInt(e.target.dataset.index);

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

                    const quantityInput = row.querySelector('input[name*="quantity"]');
                    if (quantityInput && quantityInput.value > 0) {
                        checkStock(rowIndex);
                    }
                }
            })
            .catch(err => console.error('Fetch error:', err));
    }

    document.addEventListener('input',  handleNameInput);
    document.addEventListener('change', handleNameInput);

    function deleteItem(btn) {
        btn.closest('tr').remove();
    }

    {{-- [تعديل 4] حذف الصفوف الفاضية قبل الإرسال تلقائياً --}}
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        document.querySelectorAll('#items tr').forEach(function(row) {
            const code = row.querySelector('input[name*="item_code"]')?.value?.trim();
            const name = row.querySelector('input[name*="[name]"]')?.value?.trim();
            if (!code && !name) {
                row.remove();
            }
        });

        if (document.querySelectorAll('#items tr').length === 0) {
            e.preventDefault();
            alert('يجب إضافة صنف واحد على الأقل');
        }
    });
    </script>

    <datalist id="productNamesList">
        @foreach($products as $product)
            <option value="{{ $product->name }}">
        @endforeach
    </datalist>

</body>
</html>
