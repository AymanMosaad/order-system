<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء طلب جديد</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            margin: 0;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container { max-width: 1600px; margin: 0 auto; }
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
            font-size: 13px;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0,123,255,0.3);
        }
        .table-wrapper {
            overflow-x: auto;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            min-width: 1400px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 12px;
        }
        th {
            background-color: #333;
            color: white;
            font-weight: bold;
            position: sticky;
            top: 0;
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
        .btn-save { background-color: #007bff; color: white; width: 100%; }
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
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        input[type="number"] { width: 100%; }
        .section-header {
            background-color: #e8e8e8;
            padding: 8px;
            font-weight: bold;
            text-align: center;
            color: #333;
            border: 1px solid #ddd;
        }
        .codes-header { background-color: #d4edda; }
        .info-header { background-color: #d1ecf1; }
        .grades-header { background-color: #fff3cd; }
        .total-header { background-color: #e7d4f5; }
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

    @if(session('success'))
        <div class="alert alert-success">
            ✅ {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('orders.store') }}" enctype="multipart/form-data">
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
                    <!-- الأكواد الثلاثة -->
                    <th colspan="3" class="codes-header">🔢 الأكواد</th>

                    <!-- البيانات الأساسية -->
                    <th colspan="4" class="info-header">📋 البيانات الأساسية</th>

                    <!-- الفرزات -->
                    <th colspan="3" class="grades-header">📊 الفرزات</th>

                    <!-- الإجمالي والحذف -->
                    <th colspan="2" class="total-header">📈 النتيجة</th>
                </tr>
                <tr>
                    <th>كود الفرز الأول</th>
                    <th>كود الفرز الثاني</th>
                    <th>كود الفرز الثالث</th>

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
                <!-- الأصناف ستضاف هنا -->
            </tbody>
        </table>
    </div>

    <button type="button" class="btn btn-add" onclick="addItem()">➕ إضافة صنف</button>
    <br><br>
    <button type="submit" class="btn btn-save">💾 حفظ الطلبية</button>

    </form>

</div>

<script>
let itemIndex = 0;
const productTypes = [
    'حوائط جلوريا',
    'حوائط ايكو',
    'ارضيات ايكو',
    'ارضيات HDC',
    'ارضيات UGC',
    'PORSLIM',
    'SUPER GLOSSY'
];

const productCodes = @json($products->pluck('item_code')->toArray());

function addItem() {
    const tbody = document.getElementById('items');
    const row = document.createElement('tr');

    let typeOptions = '<option value="">اختر النوع</option>';
    productTypes.forEach(type => {
        typeOptions += `<option value="${type}">${type}</option>`;
    });

    row.innerHTML = `
        <td><input type="text" name="items[${itemIndex}][item_code]" placeholder="01041200076147" required list="productCodesList"></td>
        <td><input type="text" name="items[${itemIndex}][item_code2]" placeholder="01042200076147" list="productCodesList"></td>
        <td><input type="text" name="items[${itemIndex}][item_code3]" placeholder="01043200076147" list="productCodesList"></td>

        <td><select name="items[${itemIndex}][type]">${typeOptions}</select></td>
        <td><input type="text" name="items[${itemIndex}][name]" placeholder="اسم الصنف"></td>
        <td><input type="text" name="items[${itemIndex}][color]" placeholder="اللون"></td>
        <td><input type="text" name="items[${itemIndex}][size]" placeholder="المقاس"></td>

        <td><input type="number" name="items[${itemIndex}][grade1]" value="0" min="0" oninput="calcTotal(${itemIndex})"></td>
        <td><input type="number" name="items[${itemIndex}][grade2]" value="0" min="0" oninput="calcTotal(${itemIndex})"></td>
        <td><input type="number" name="items[${itemIndex}][grade3]" value="0" min="0" oninput="calcTotal(${itemIndex})"></td>

        <td><input type="number" name="items[${itemIndex}][total]" value="0" readonly id="total_${itemIndex}" style="background-color: #fff3cd; font-weight: bold;"></td>
        <td><button type="button" class="btn btn-delete" onclick="deleteItem(this)">✖</button></td>
    `;

    tbody.appendChild(row);
    itemIndex++;
}

function calcTotal(index) {
    const g1 = parseInt(document.getElementsByName(`items[${index}][grade1]`)[0]?.value || 0);
    const g2 = parseInt(document.getElementsByName(`items[${index}][grade2]`)[0]?.value || 0);
    const g3 = parseInt(document.getElementsByName(`items[${index}][grade3]`)[0]?.value || 0);
    const total = g1 + g2 + g3;

    const totalInput = document.getElementById(`total_${index}`);
    if (totalInput) {
        totalInput.value = total;
    }
}

function deleteItem(btn) {
    btn.closest('tr').remove();
}
</script>

<!-- Datalist للأكواد -->
<datalist id="productCodesList">
    @foreach($products as $product)
        <option value="{{ $product->item_code }}">
    @endforeach
</datalist>

</body>
</html>
