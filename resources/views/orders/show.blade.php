<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تفاصيل الطلبية #{{ $order->id }} - جلوريا للسيراميك</title>
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
            padding: 25px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }
        .page-header h1 { margin: 0 0 5px 0; font-size: 24px; }
        .page-header p { margin: 0; opacity: 0.85; font-size: 14px; }
        .page-badge {
            background: rgba(255,255,255,0.2);
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 15px;
            font-weight: bold;
        }

        .info-card {
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
        .card-title i {
            margin-left: 8px;
            color: #007bff;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 12px;
        }
        .info-item {
            background: #f8f9fa;
            padding: 14px 16px;
            border-radius: 10px;
            border-right: 4px solid #667eea;
            transition: all 0.2s;
        }
        .info-item:hover {
            background: #f0f4ff;
            transform: translateX(-2px);
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

        .desktop-table {
            overflow-x: auto;
            margin-top: 10px;
        }
        .desktop-table table {
            width: 100%;
            border-collapse: collapse;
            min-width: 500px;
        }
        .desktop-table th, .desktop-table td {
            border: 1px solid #eee;
            padding: 12px;
            text-align: center;
            font-size: 14px;
        }
        .desktop-table th {
            background: #f8f9fa;
            color: #333;
            font-weight: bold;
            border-bottom: 2px solid #dee2e6;
        }
        .desktop-table tr:hover { background: #f9f9ff; }

        .mobile-items {
            display: none;
            gap: 12px;
            flex-direction: column;
            margin-top: 15px;
        }

        .item-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
            border-right: 3px solid #007bff;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
        }

        .item-number {
            font-weight: bold;
            color: #007bff;
            font-size: 14px;
        }

        .item-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .item-label {
            min-width: 70px;
            font-weight: bold;
            color: #666;
            font-size: 12px;
        }

        .item-value {
            flex: 1;
            color: #333;
            font-size: 14px;
        }

        .item-total {
            background: #fff3cd;
            padding: 8px 12px;
            border-radius: 8px;
            margin-top: 10px;
            text-align: center;
            font-weight: bold;
        }

        .mobile-total-card {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            margin-top: 10px;
        }

        .mobile-total-card .total-label {
            font-size: 14px;
            color: #856404;
            margin-bottom: 5px;
        }

        .mobile-total-card .total-value {
            font-size: 22px;
            font-weight: bold;
            color: #856404;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }
        .status-new { background: #d4edda; color: #155724; }
        .status-processing { background: #fff3cd; color: #856404; }
        .status-completed { background: #d1ecf1; color: #0c5460; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .status-factory { background: #17a2b8; color: white; }

        .total-row {
            font-weight: bold;
            background: linear-gradient(135deg, #fff3cd, #ffeaa7) !important;
        }
        .total-row td { font-size: 15px; }

        .discount-badge {
            background: #ffc107;
            color: #212529;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

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
            justify-content: center;
            gap: 7px;
            text-decoration: none;
        }
        .btn:hover { transform: translateY(-2px); opacity: 0.9; }
        .btn-back    { background: #6c757d; color: white; }
        .btn-edit    { background: #ffc107; color: #212529; }
        .btn-print   { background: #17a2b8; color: white; }
        .btn-delete  { background: #dc3545; color: white; }
        .btn-factory { background: #fd7e14; color: white; }
        .btn-success-disabled { background: #28a745; color: white; cursor: default; }

        .alert { padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger  { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        @media print {
            body { padding: 0 !important; margin: 0 !important; background: white; }
            .navbar-custom, .btn, .actions, .page-badge, .btn-print, .btn-back, .btn-edit, .btn-delete, .btn-factory { display: none !important; }
            .page-header { background: #333 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; margin-bottom: 20px; padding: 15px; }
            .container { width: 100% !important; max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
            .info-card { break-inside: avoid; page-break-inside: avoid; box-shadow: none; border: 1px solid #ddd; margin-bottom: 15px; }
            .desktop-table table { width: 100% !important; break-inside: avoid; page-break-inside: avoid; }
            .mobile-items { display: none !important; }
        }

        @media (max-width: 768px) {
            body { padding: 10px; padding-top: 80px; }
            .page-header { flex-direction: column; text-align: center; }
            .page-header h1 { font-size: 22px; }
            .btn { padding: 10px 18px; font-size: 13px; min-width: 100px; }
            .desktop-table { display: none; }
            .mobile-items { display: flex; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">

    <div class="page-header">
        <div>
            <h1><i class="fas fa-file-alt"></i> تفاصيل الطلبية</h1>
            <p>{{ $order->customer_name }} — {{ $order->date->format('Y-m-d') }}</p>
        </div>
        <div class="page-badge"><i class="fas fa-hashtag"></i> {{ $order->id }}</div>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }}</div>
    @endif

    <div class="info-card">
        <div class="card-title"><i class="fas fa-info-circle"></i> بيانات الطلبية</div>
        <div class="info-grid">
            <div class="info-item">
                <strong><i class="fas fa-user-tie"></i> اسم العميل</strong>
                <span>{{ $order->customer_name }}</span>
            </div>
            <div class="info-item">
                <strong><i class="fas fa-store"></i> اسم التاجر</strong>
                <span>{{ $order->trader_name ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong><i class="fas fa-hashtag"></i> رقم الإذن</strong>
                <span>{{ $order->order_number ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong><i class="fas fa-percent"></i> خصم الطلبية</strong>
                <span>
                    @if($order->order_discount > 0)
                        <span class="discount-badge">{{ $order->order_discount }}% خصم</span>
                    @else
                        -
                    @endif
                </span>
            </div>
            <div class="info-item">
                <strong><i class="fas fa-warehouse"></i> نوع المخزن</strong>
                <span>{{ $order->warehouse_type ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong><i class="fas fa-phone"></i> رقم الهاتف</strong>
                <span>{{ $order->phone ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong><i class="fas fa-location-dot"></i> العنوان</strong>
                <span>{{ $order->address ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong><i class="fas fa-truck"></i> اسم السائق</strong>
                <span>{{ $order->driver_name ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong><i class="fas fa-calendar-day"></i> التاريخ</strong>
                <span>{{ $order->date->format('Y-m-d') }}</span>
            </div>
            <div class="info-item">
                <strong><i class="fas fa-chart-simple"></i> الحالة</strong>
                @php
                    $statusClass = 'status-new';
                    if($order->status == 'قيد المعالجة') $statusClass = 'status-processing';
                    elseif($order->status == 'مكتملة') $statusClass = 'status-completed';
                    elseif($order->status == 'ملغية') $statusClass = 'status-cancelled';
                    elseif($order->status == 'مرسلة للمصنع') $statusClass = 'status-factory';
                @endphp
                <span class="status-badge {{ $statusClass }}">{{ $order->status }}</span>
            </div>
            @if($order->notes)
            <div class="info-item" style="grid-column: 1 / -1;">
                <strong><i class="fas fa-pen"></i> ملاحظات</strong>
                <span>{{ $order->notes }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- الأصناف -->
    <div class="info-card">
        <div class="card-title"><i class="fas fa-cubes"></i> الأصناف</div>

        @php
            $orderTotal = 0;
            foreach($order->items as $item) {
                $itemTotal = $item->grade1 * $item->unit_price;
                if ($order->order_discount > 0) {
                    $itemTotal = $itemTotal - ($itemTotal * $order->order_discount / 100);
                }
                $orderTotal += $itemTotal;
            }
        @endphp

        <div class="desktop-table">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><i class="fas fa-barcode"></i> كود الصنف</th>
                        <th><i class="fas fa-tag"></i> اسم الصنف</th>
                        <th><i class="fas fa-weight-hanging"></i> الكمية</th>
                        <th><i class="fas fa-chart-line"></i> السعر</th>
                        <th><i class="fas fa-chart-line"></i> الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $i => $item)
                        @php
                            $itemTotal = $item->grade1 * $item->unit_price;
                            $displayTotal = $itemTotal;
                            if ($order->order_discount > 0) {
                                $displayTotal = $itemTotal - ($itemTotal * $order->order_discount / 100);
                            }
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item->item_code }}</td>
                            <td style="text-align: right;">{{ $item->name }}</td>
                            <td>{{ number_format($item->grade1, 2) }}</td>
                            <td>{{ number_format($item->unit_price, 2) }}</td>
                            <td><strong>{{ number_format($displayTotal, 2) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="5" style="text-align: left; font-weight: bold;"><i class="fas fa-calculator"></i> الإجمالي الكلي</td>
                        <td style="font-weight: bold;">{{ number_format($orderTotal, 2) }}</td>
                    </tr>
                    @if($order->order_discount > 0)
                    <tr class="total-row">
                        <td colspan="5" style="text-align: left; font-weight: bold;"><i class="fas fa-tag"></i> قيمة الخصم ({{ $order->order_discount }}%)</td>
                        <td style="font-weight: bold; color: #dc3545;">- {{ number_format(($order->items->sum(function($item) { return $item->grade1 * $item->unit_price; }) * $order->order_discount / 100), 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="5" style="text-align: left; font-weight: bold;"><i class="fas fa-money-bill-wave"></i> الإجمالي بعد الخصم</td>
                        <td style="font-weight: bold; color: #28a745;">{{ number_format($orderTotal, 2) }}</td>
                    </tr>
                    @endif
                </tfoot>
            </table>
        </div>

        <div class="mobile-items">
            @foreach($order->items as $i => $item)
                @php
                    $itemTotal = $item->grade1 * $item->unit_price;
                    $displayTotal = $itemTotal;
                    if ($order->order_discount > 0) {
                        $displayTotal = $itemTotal - ($itemTotal * $order->order_discount / 100);
                    }
                @endphp
                <div class="item-card">
                    <div class="item-header">
                        <span class="item-number"><i class="fas fa-cube"></i> صنف #{{ $i + 1 }}</span>
                    </div>
                    <div class="item-row">
                        <div class="item-label"><i class="fas fa-barcode"></i> الكود:</div>
                        <div class="item-value">{{ $item->item_code }}</div>
                    </div>
                    <div class="item-row">
                        <div class="item-label"><i class="fas fa-tag"></i> الاسم:</div>
                        <div class="item-value">{{ $item->name }}</div>
                    </div>
                    <div class="item-row">
                        <div class="item-label"><i class="fas fa-weight-hanging"></i> الكمية:</div>
                        <div class="item-value">{{ number_format($item->grade1, 2) }}</div>
                    </div>
                    <div class="item-row">
                        <div class="item-label"><i class="fas fa-chart-line"></i> السعر:</div>
                        <div class="item-value">{{ number_format($item->unit_price, 2) }}</div>
                    </div>
                    <div class="item-total">
                        <i class="fas fa-chart-line"></i> الإجمالي: <strong>{{ number_format($displayTotal, 2) }}</strong>
                    </div>
                </div>
            @endforeach

            <div class="mobile-total-card">
                <div class="total-label"><i class="fas fa-calculator"></i> الإجمالي الكلي للطلبية</div>
                <div class="total-value">{{ number_format($orderTotal, 2) }}</div>
                @if($order->order_discount > 0)
                    <div class="total-label" style="margin-top: 10px;"><i class="fas fa-tag"></i> الخصم ({{ $order->order_discount }}%)</div>
                    <div class="total-value" style="color: #dc3545;">- {{ number_format(($order->items->sum(function($item) { return $item->grade1 * $item->unit_price; }) * $order->order_discount / 100), 2) }}</div>
                    <div class="total-label" style="margin-top: 10px;"><i class="fas fa-money-bill-wave"></i> الإجمالي بعد الخصم</div>
                    <div class="total-value" style="color: #28a745;">{{ number_format($orderTotal, 2) }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- الأزرار -->
    <div class="info-card">
        <div class="actions">
            <a href="{{ route('orders.index') }}" class="btn btn-back"><i class="fas fa-arrow-right"></i> العودة للطلبيات</a>
            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-edit"><i class="fas fa-edit"></i> تعديل</a>
            <a href="{{ route('orders.invoice', $order->id) }}" class="btn btn-print" target="_blank">
                <i class="fas fa-print"></i> فاتورة
            </a>
            @if(in_array(Auth::user()->role, ['super_admin', 'sales_manager']) && !$order->sent_to_factory && $order->status != 'مرسلة للمصنع')
                <form action="{{ route('orders.sendToFactory', $order->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-factory" onclick="return confirm('هل أنت متأكد من إرسال هذه الطلبية للمصنع؟')">
                        <i class="fas fa-industry"></i> إرسال للمصنع
                    </button>
                </form>
            @endif
            @if($order->sent_to_factory || $order->status == 'مرسلة للمصنع')
                <span class="btn btn-success-disabled">
                    <i class="fas fa-check-circle"></i> تم الإرسال للمصنع
                    @if($order->sent_to_factory_at)
                        <br><small>{{ \Carbon\Carbon::parse($order->sent_to_factory_at)->format('Y-m-d H:i') }}</small>
                    @endif
                </span>
            @endif
            <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                  onsubmit="return confirm('⚠️ هل أنت متأكد من حذف هذه الطلبية؟\nسيتم إعادة الرصيد للمخزن تلقائياً.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-delete"><i class="fas fa-trash-alt"></i> حذف</button>
            </form>
        </div>
    </div>

</div>

</body>
</html>
