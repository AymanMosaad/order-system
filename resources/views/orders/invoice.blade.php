<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة الطلبية #{{ $order->id }} - سيراميكا جلوريا</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Tahoma', 'Arial', sans-serif;
            direction: rtl;
            background: #fff;
            padding: 0;
            margin: 0;
            font-size: 13px;
        }

        .invoice-container {
            max-width: 100%;
            margin: 0 auto;
            background: white;
            padding: 15px 20px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .invoice-container {
                padding: 0;
                margin: 0;
            }
            .print-button {
                display: none;
            }
            table, tr, td, th, tbody, thead, tfoot {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            .items-table {
                page-break-inside: avoid;
                break-inside: avoid;
            }
            .items-table tbody tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }
            @page {
                margin: 0.5cm;
                size: auto;
            }
            body {
                margin: 0;
                padding: 0;
            }
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eee;
        }

        .logo-section {
            text-align: center;
            order: 2;
        }

        .logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            color: white;
            font-size: 28px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }

        .company-sub {
            font-size: 11px;
            color: #666;
        }

        .invoice-title {
            text-align: right;
            order: 1;
        }

        .invoice-number {
            font-size: 13px;
            color: #666;
            margin-bottom: 3px;
        }

        .order-info {
            background: #f8f9fa;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 12px;
        }

        .info-item {
            display: flex;
            align-items: baseline;
            gap: 8px;
            background: white;
            padding: 6px 12px;
            border-radius: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .info-label {
            font-size: 11px;
            color: #888;
            font-weight: normal;
        }

        .info-value {
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }

        .info-item.status-item {
            background: #e9ecef;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 12px;
        }

        .items-table th {
            background: #333;
            color: white;
            padding: 8px 6px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #444;
        }

        .items-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: center;
        }

        .items-table tr:hover {
            background: #f9f9f9;
        }

        .totals {
            margin-top: 15px;
            text-align: left;
        }

        .total-row {
            display: flex;
            justify-content: flex-end;
            padding: 8px 0;
            border-top: 1px solid #eee;
        }

        .total-label {
            font-weight: bold;
            width: 120px;
            text-align: right;
            font-size: 14px;
        }

        .total-value {
            font-weight: bold;
            width: 130px;
            text-align: left;
            font-size: 16px;
            color: #28a745;
        }

        .footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #eee;
            text-align: center;
            font-size: 11px;
        }

        .signature {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 15px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .signature-line {
            text-align: center;
            flex: 1;
            min-width: 120px;
        }

        .signature-line .line {
            border-top: 1px solid #333;
            margin-top: 25px;
            padding-top: 5px;
            font-size: 11px;
        }

        .print-button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 15px;
            display: inline-block;
        }

        .print-button:hover {
            background: #0056b3;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-new { background: #d4edda; color: #155724; }
        .status-processing { background: #fff3cd; color: #856404; }
        .status-completed { background: #d1ecf1; color: #0c5460; }
        .status-cancelled { background: #f8d7da; color: #721c24; }

        .notes-section {
            margin-top: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 11px;
        }

        .discount-badge {
            background: #ffc107;
            color: #212529;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }

        @media (max-width: 768px) {
            .invoice-container {
                padding: 10px;
            }
            .header {
                flex-direction: column;
                text-align: center;
            }
            .invoice-title {
                text-align: center;
                order: 1;
            }
            .logo-section {
                order: 2;
            }
            .order-info {
                gap: 8px;
            }
            .info-item {
                flex: 1 1 calc(50% - 8px);
                min-width: 140px;
            }
            .items-table th, .items-table td {
                padding: 4px;
                font-size: 10px;
            }
            .total-row {
                justify-content: center;
            }
            .signature {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }
        }

        @media print {
            .info-item {
                flex: none;
                padding: 4px 10px;
            }
            .signature {
                flex-direction: row;
            }
        }
    </style>
</head>
<body>

<div class="invoice-container">
    <!-- زر الطباعة -->
    <div class="no-print" style="text-align: center; margin-bottom: 15px;">
        <button onclick="window.print()" class="print-button">
            🖨️ طباعة الفاتورة
        </button>
    </div>

    <!-- ===== رأس الفاتورة ===== -->
    <div class="header">
        <div class="invoice-title">
            <div class="invoice-number">رقم الفاتورة: INV-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>
            <div class="invoice-number">التاريخ: {{ $order->date->format('d-m-Y') }}</div>
        </div>
        <div class="logo-section">
            <div class="logo">
                🧱
            </div>
            <div class="company-name">سيراميكا جلوريا</div>
            <div class="company-sub">شركة الشرق لصناعة السيراميك</div>
            <div class="company-sub">ش.م.م</div>
        </div>
    </div>

    <!-- ===== معلومات العميل والطلب ===== -->
    <div class="order-info">
        <div class="info-item">
            <span class="info-label">👤 العميل:</span>
            <span class="info-value">{{ $order->customer_name }}</span>
        </div>

        @if($order->order_number)
        <div class="info-item">
            <span class="info-label">🔢 رقم الإذن:</span>
            <span class="info-value">{{ $order->order_number }}</span>
        </div>
        @endif

        @if($order->trader_name)
        <div class="info-item">
            <span class="info-label">🏪 التاجر:</span>
            <span class="info-value">{{ $order->trader_name }}</span>
        </div>
        @endif

        @if($order->phone)
        <div class="info-item">
            <span class="info-label">📞 الهاتف:</span>
            <span class="info-value">{{ $order->phone }}</span>
        </div>
        @endif

        @if($order->address)
        <div class="info-item">
            <span class="info-label">📍 العنوان:</span>
            <span class="info-value">{{ $order->address }}</span>
        </div>
        @endif

        <div class="info-item">
            <span class="info-label">👨‍💼 المندوب:</span>
            <span class="info-value">{{ $order->user->name ?? '-' }}</span>
        </div>

        <div class="info-item status-item">
            <span class="info-label">📊 الحالة:</span>
            <span class="info-value">
                @php
                    $statusClass = 'status-new';
                    if($order->status == 'قيد المعالجة') $statusClass = 'status-processing';
                    elseif($order->status == 'مكتملة') $statusClass = 'status-completed';
                    elseif($order->status == 'ملغية') $statusClass = 'status-cancelled';
                @endphp
                <span class="status-badge {{ $statusClass }}">{{ $order->status }}</span>
            </span>
        </div>
    </div>

    <!-- جدول الأصناف -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 20%">كود الصنف</th>
                <th style="width: 40%">اسم الصنف</th>
                <th style="width: 15%">الكمية (متر)</th>
                <th style="width: 20%">الإجمالي</th>
            </thead>
        <tbody>
            @php
                $subtotal = 0;
            @endphp
            @foreach($order->items as $i => $item)
                @php
                    $itemTotal = $item->total;
                    $subtotal += $itemTotal;
                @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->item_code }}</td>
                <td style="text-align: right;">{{ $item->name }}</td>
                <td>{{ number_format($item->grade1, 2) }}</td>
                <td><strong>{{ number_format($itemTotal, 2) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- الإجمالي -->
    <div class="totals">
        <div class="total-row">
            <span class="total-label">إجمالي المبلغ:</span>
            <span class="total-value">{{ number_format($subtotal, 2) }} ج.م</span>
        </div>
        @if($order->order_discount > 0)
        <div class="total-row">
            <span class="total-label">خصم الطلبية ({{ $order->order_discount }}%):</span>
            <span class="total-value" style="color: #dc3545;">- {{ number_format(($subtotal * $order->order_discount / 100), 2) }} ج.م</span>
        </div>
        <div class="total-row">
            <span class="total-label">الإجمالي بعد الخصم:</span>
            <span class="total-value">{{ number_format($order->total_amount ?? ($subtotal - ($subtotal * $order->order_discount / 100)), 2) }} ج.م</span>
        </div>
        @else
        <div class="total-row">
            <span class="total-label">الإجمالي الكلي:</span>
            <span class="total-value">{{ number_format($order->total_amount ?? $subtotal, 2) }} ج.م</span>
        </div>
        @endif
    </div>

    <!-- ملاحظات -->
    @if($order->notes)
    <div class="notes-section">
        <strong>📝 ملاحظات:</strong>
        <p style="margin-top: 5px; color: #666;">{{ $order->notes }}</p>
    </div>
    @endif

    <!-- ===== التذييل - التوقيعات ===== -->
    <div class="footer">
        <div class="signature">
            <div class="signature-line">
                <div class="line"></div>
                <span>توقيع المستلم</span>
            </div>
            <div class="signature-line">
                <div class="line"></div>
                <span>توقيع المندوب</span>
            </div>
            <div class="signature-line">
                <div class="line"></div>
                <span>ختم الشركة</span>
            </div>
        </div>
        <div style="margin-top: 15px; font-size: 10px; color: #999;">
            سيراميكا جلوريا - شركة الشرق لصناعة السيراميك ش.م.م<br>
            جميع الحقوق محفوظة © {{ date('Y') }}
        </div>
    </div>
</div>

<script>
    // الطباعة التلقائية عند التحميل
    window.onload = function() {
        setTimeout(function() {
            window.print();
        }, 500);
    };
</script>

</body>
</html>
