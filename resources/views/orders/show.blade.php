<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تفاصيل الطلبية #{{ $order->id }}</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .header-info h1 { margin: 0 0 5px 0; font-size: 24px; }
        .header-info p { margin: 0; opacity: 0.85; font-size: 14px; }
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
            border-bottom: 3px solid #007bff;
            display: inline-block;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 12px;
        }
        .info-item {
            background: #f8f9fa;
            padding: 14px 16px;
            border-radius: 10px;
            border-right: 4px solid #667eea;
        }
        .info-item strong {
            display: block;
            color: #888;
            font-size: 12px;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-item span { color: #333; font-size: 15px; font-weight: 600; }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            background: #d4edda;
            color: #155724;
        }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #eee; padding: 12px; text-align: center; font-size: 14px; }
        th {
            background: #f8f9fa;
            color: #333;
            font-weight: bold;
            border-bottom: 2px solid #dee2e6;
        }
        tr:hover { background: #f9f9ff; }
        td:nth-child(2) { text-align: right; }

        .total-row {
            font-weight: bold;
            background: linear-gradient(135deg, #fff3cd, #ffeaa7) !important;
        }
        .total-row td { font-size: 15px; }

        .actions {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .btn {
            padding: 11px 22px;
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
        .btn-back    { background: #6c757d; color: white; }
        .btn-edit    { background: #ffc107; color: #212529; }
        .btn-print   { background: #17a2b8; color: white; }
        .btn-delete  { background: #dc3545; color: white; }

        .alert { padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger  { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        @media (max-width: 768px) {
            .header { flex-direction: column; text-align: center; }
            th, td { padding: 8px; font-size: 12px; }
            .btn { padding: 9px 15px; font-size: 13px; }
        }

        @media print {
            .actions, .header-badge { display: none; }
            body { background: white; padding: 0; }
            .header { background: #333 !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">

    <div class="header">
        <div class="header-info">
            <h1>📄 تفاصيل الطلبية</h1>
            <p>{{ $order->customer_name }} — {{ $order->date->format('Y-m-d') }}</p>
        </div>
        <div class="header-badge"># {{ $order->id }}</div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">❌ {{ session('error') }}</div>
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

    <div class="card">
        <div class="card-title">📋 بيانات الطلبية</div>
        <div class="info-grid">
            <div class="info-item">
                <strong>اسم العميل</strong>
                <span>{{ $order->customer_name }}</span>
            </div>
            <div class="info-item">
                <strong>اسم التاجر</strong>
                <span>{{ $order->trader_name ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong>رقم الإذن</strong>
                <span>{{ $order->order_number ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong>نوع المخزن</strong>
                <span>{{ $order->warehouse_type ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong>رقم الهاتف</strong>
                <span>{{ $order->phone ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong>العنوان</strong>
                <span>{{ $order->address ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong>اسم السائق</strong>
                <span>{{ $order->driver_name ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong>التاريخ</strong>
                <span>{{ $order->date->format('Y-m-d') }}</span>
            </div>
            <div class="info-item">
                <strong>الحالة</strong>
                <span class="status-badge">{{ $order->status }}</span>
            </div>
            @if($order->notes)
            <div class="info-item" style="grid-column: 1 / -1;">
                <strong>ملاحظات</strong>
                <span>{{ $order->notes }}</span>
            </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-title">📦 الأصناف</div>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>كود الصنف</th>
                        <th>اسم الصنف</th>
                        <th>الكمية</th>
                        <th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->item_code }}</td>
                        <td style="text-align: right;">{{ $item->name }}</td>
                        <td>{{ number_format($item->grade1) }}</td>
                        <td><strong>{{ number_format($item->total) }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="4" style="text-align: left; font-weight: bold;">🔢 الإجمالي الكلي</td>
                        <td style="font-weight: bold;">{{ number_format($order->items->sum('total')) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="actions">
            <a href="{{ route('orders.index') }}" class="btn btn-back">← العودة للطلبيات</a>
            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-edit">✏️ تعديل</a>
            <a href="#" onclick="window.print()" class="btn btn-print">🖨️ طباعة</a>
            <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                  onsubmit="return confirm('⚠️ هل أنت متأكد من حذف هذه الطلبية؟\nسيتم إعادة الرصيد للمخزن تلقائياً.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-delete">🗑️ حذف</button>
            </form>
        </div>
    </div>

</div>

<script>
    document.querySelectorAll('.delete-form, form[action*="destroy"]').forEach(form => {
        form.addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            if (btn) { btn.disabled = true; btn.textContent = '⏳ جاري الحذف...'; }
        });
    });
</script>

</body>
</html>
