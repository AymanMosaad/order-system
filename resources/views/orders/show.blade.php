<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تفاصيل الطلبية #{{ $order->id }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; direction: rtl; margin: 0; background-color: #f5f5f5; padding: 20px; }
        .container { max-width: 1400px; margin: 0 auto; }
        h1, h2 { text-align: center; color: #333; }
        .box {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 15px;
        }
        .info-item {
            background: #f8f9fa;
            padding: 12px 15px;
            border-radius: 6px;
            border-right: 4px solid #007bff;
        }
        .info-item strong { display: block; color: #555; margin-bottom: 4px; }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
            font-size: 14px;
        }
        th {
            background-color: #333;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .total-row { font-weight: bold; background-color: #fff3cd !important; }
        .btn {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
            text-decoration: none;
        }
        .btn-back { background: #6c757d; color: white; }
        .btn-print { background: #17a2b8; color: white; }
        .btn-delete { background: #dc3545; color: white; }
        .btn-edit { background: #ffc107; color: #212529; }
        .delete-form {
            display: inline-block;
            margin: 5px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <h1>📄 تفاصيل الطلبية #{{ $order->id }}</h1>

    {{-- عرض رسائل النجاح أو الخطأ --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-right: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="box">
        <h2>📋 بيانات الطلبية</h2>
        <div class="info-grid">
            <div class="info-item">
                <strong>اسم العميل</strong>
                {{ $order->customer_name }}
            </div>
            <div class="info-item">
                <strong>اسم التاجر</strong>
                {{ $order->trader_name ?? '-' }}
            </div>
            <div class="info-item">
                <strong>رقم الإذن</strong>
                {{ $order->order_number ?? '-' }}
            </div>
            <div class="info-item">
                <strong>نوع المخزن</strong>
                {{ $order->warehouse_type ?? '-' }}
            </div>
            <div class="info-item">
                <strong>رقم الهاتف</strong>
                {{ $order->phone ?? '-' }}
            </div>
            <div class="info-item">
                <strong>العنوان</strong>
                {{ $order->address ?? '-' }}
            </div>
            <div class="info-item">
                <strong>اسم السائق</strong>
                {{ $order->driver_name ?? '-' }}
            </div>
            <div class="info-item">
                <strong>التاريخ</strong>
                {{ $order->date->format('Y-m-d') }}
            </div>
            <div class="info-item">
                <strong>الحالة</strong>
                <span style="color: green; font-weight: bold;">{{ $order->status }}</span>
            </div>
        </div>
    </div>

    <div class="box">
        <h2>📦 الأصناف</h2>
        <table>
            <thead>
                <tr>
                    <th>كود الصنف</th>
                    <th>اسم الصنف</th>
                    <th>الكمية</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->item_code }}</td>
                    <td style="text-align: right;">{{ $item->name }}</td>
                    <td>{{ $item->grade1 }}</td>
                    <td><strong>{{ $item->total }}</strong></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" style="text-align: left; font-weight: bold;">الإجمالي الكلي</td>
                    <td style="font-weight: bold;">{{ $order->items->sum('total') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ route('orders.index') }}" class="btn btn-back">← العودة للطلبيات</a>
        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-edit">✏️ تعديل الطلبية</a>
        <a href="#" onclick="window.print()" class="btn btn-print">🖨️ طباعة</a>

        {{-- نموذج الحذف - هذا هو التعديل المهم --}}
        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="delete-form" onsubmit="return confirm('⚠️ تحذير: هل أنت متأكد من حذف هذه الطلبية؟\nسيتم إعادة الرصيد للمخزن تلقائياً.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-delete" style="background: #dc3545; color: white; border: none; padding: 10px 20px; cursor: pointer;">
                🗑️ حذف الطلبية
            </button>
        </form>
    </div>
</div>

{{-- إضافة JavaScript للتعامل مع الحذف بشكل آمن --}}
<script>
    // منع النقر المزدوج على زر الحذف
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.textContent = '⏳ جاري الحذف...';
            }
        });
    });
</script>

</body>
</html>
