<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>كشف حساب العميل - جلوريا للسيراميك</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            direction: rtl;
            background-color: #f8f9fa;
            padding: 20px;
            padding-top: 90px;
        }
        .container { max-width: 1400px; margin: 0 auto; }

        .page-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .customer-info {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .info-item {
            background: #f8f9fa;
            padding: 12px 15px;
            border-radius: 10px;
            border-right: 3px solid #007bff;
        }
        .info-label {
            font-size: 12px;
            color: #888;
            display: block;
        }
        .info-value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .summary-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            text-align: center;
        }
        .summary-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
        }
        .summary-box .number {
            font-size: 24px;
            font-weight: bold;
        }
        .summary-box .label {
            font-size: 12px;
            color: #666;
        }
        .summary-box.sale .number { color: #dc3545; }
        .summary-box.return .number { color: #28a745; }
        .summary-box.sample .number { color: #ffc107; }
        .summary-box.total .number { color: #007bff; }

        .table-wrapper {
            background: white;
            border-radius: 15px;
            overflow-x: auto;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #eee;
            padding: 10px;
            text-align: center;
            font-size: 13px;
            vertical-align: middle;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
            position: sticky;
            top: 0;
        }
        .total-row {
            background: #e9ecef;
            font-weight: bold;
        }
        .btn-print {
            background: #17a2b8;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
        }
        .btn-back {
            background: #6c757d;
            color: white;
            text-decoration: none;
            padding: 8px 20px;
            border-radius: 8px;
        }
        .btn-save {
            background: #28a745;
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
        }
        .btn-save-all {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }
        .price-input {
            width: 100px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-align: center;
        }
        .discount-input {
            width: 70px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-align: center;
        }
        .price-form {
            display: inline-flex;
            gap: 5px;
            align-items: center;
        }
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
        }
        .badge-agent { background: #007bff; color: white; }
        .badge-dealer { background: #28a745; color: white; }
        .badge-cash { background: #6c757d; color: white; }
        .badge-first { background: #007bff; color: white; }
        .badge-second { background: #28a745; color: white; }
        .badge-third { background: #fd7e14; color: white; }
        .badge-fourth { background: #dc3545; color: white; }
        .badge-discount { background: #ffc107; color: #212529; }
        .badge-sale { background: #dc3545; color: white; }
        .badge-return { background: #28a745; color: white; }
        .badge-sample { background: #ffc107; color: #212529; }

        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        @media (max-width: 768px) {
            body { padding: 10px; padding-top: 80px; }
            th, td { padding: 6px; font-size: 11px; }
            .price-input { width: 70px; font-size: 10px; }
            .btn-save { padding: 3px 8px; font-size: 10px; }
            .info-grid { grid-template-columns: 1fr; }
            .summary-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-chart-line"></i> كشف حساب العميل</h1>
            <p>تفاصيل المسحوبات والمعاملات</p>
        </div>
        <div>
            <button onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> طباعة</button>
            <a href="{{ route('accounting.customers') }}" class="btn-back"><i class="fas fa-arrow-right"></i> العودة</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="customer-info">
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">كود العميل</span>
                <span class="info-value">{{ $customer->code ?? '-' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">اسم العميل</span>
                <span class="info-value">{{ $customer->name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">نوع العميل</span>
                <span class="info-value">
                    @if($customer->type == 'agent')
                        <span class="badge badge-agent">وكيل</span>
                    @elseif($customer->type == 'dealer')
                        <span class="badge badge-dealer">تاجر</span>
                    @else
                        <span class="badge badge-cash">عميل نقدي</span>
                    @endif
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">نسبة الخصم (عام)</span>
                <span class="info-value">
                    <form method="POST" action="{{ route('accounting.updateDiscount', $customer->id) }}" class="price-form">
                        @csrf
                        @method('PUT')
                        <input type="number" name="discount_rate" step="0.01" value="{{ $customer->discount_rate }}" class="discount-input">
                        <button type="submit" class="btn-save"><i class="fas fa-save"></i> حفظ</button>
                    </form>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">عدد الطلبيات</span>
                <span class="info-value">{{ number_format($totalOrders) }} طلبية</span>
            </div>
        </div>
    </div>

    <!-- ملخص الحساب -->
    <div class="summary-card">
        <div class="summary-grid">
            <div class="summary-box sale">
                <div class="number">{{ number_format($totalAmount - ($totalReturns ?? 0) - ($totalSamples ?? 0), 2) }}</div>
                <div class="label">إجمالي الصرف (على العميل)</div>
            </div>
            <div class="summary-box return">
                <div class="number">{{ number_format($totalReturns ?? 0, 2) }}</div>
                <div class="label">إجمالي الإرتجاعات (لصالح العميل)</div>
            </div>
            <div class="summary-box sample">
                <div class="number">{{ number_format($totalSamples ?? 0, 2) }}</div>
                <div class="label">إجمالي العينات (لصالح العميل)</div>
            </div>
            <div class="summary-box total">
                <div class="number">{{ number_format($totalAmount, 2) }}</div>
                <div class="label">صافي المطلوب (ج.م)</div>
            </div>
        </div>
    </div>

    <!-- زر حفظ الكل -->
    <div class="mb-3 text-center">
        <button type="button" class="btn-save-all" id="saveAllPricesBtn" onclick="saveAllPrices()">
            <i class="fas fa-save"></i> حفظ جميع الأسعار
        </button>
    </div>

    <div class="table-wrapper">
        <form method="POST" action="{{ route('accounting.updateAllPrices', $customer->id) }}" id="allPricesForm">
            @csrf
            @method('PUT')
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 3%">#</th>
                        <th style="width: 8%">التاريخ</th>
                        <th style="width: 8%">رقم الإذن</th>
                        <th style="width: 8%">نوع العملية</th>
                        <th style="width: 10%">كود الصنف</th>
                        <th style="width: 15%">اسم الصنف</th>
                        <th style="width: 7%">المقاس</th>
                        <th style="width: 7%">الفرز</th>
                        <th style="width: 7%">الكمية</th>
                        <th style="width: 10%">السعر</th>
                        <th style="width: 7%">خصم الإذن</th>
                        <th style="width: 10%">الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter = 1; @endphp
                    @forelse($customer->orders as $order)
                        @foreach($order->items as $item)
                            @php
                                $itemTotal = $item->grade1 * $item->unit_price;
                                $finalTotal = $itemTotal;
                                if ($order->order_discount > 0) {
                                    $finalTotal = $itemTotal - ($itemTotal * $order->order_discount / 100);
                                }
                                $transactionTypeText = $item->transaction_type ?? 'sale';
                                $typeClass = $transactionTypeText == 'return' ? 'badge-return' : ($transactionTypeText == 'sample' ? 'badge-sample' : 'badge-sale');
                                $typeName = $transactionTypeText == 'return' ? 'إرتجاع' : ($transactionTypeText == 'sample' ? 'عينة' : 'صرف');
                            @endphp
                         <tr>
                            <td>{{ $counter++ }}</td>
                            <td>{{ $order->date->format('Y-m-d') }}</td>
                            <td>
                                <strong>#{{ $order->order_number ?? $order->id }}</strong>
                                @if($order->order_discount > 0)
                                    <br><span class="badge badge-discount">خصم {{ $order->order_discount }}%</span>
                                @endif
                            </td>
                            <td><span class="badge {{ $typeClass }}">{{ $typeName }}</span></td>
                            <td>{{ $item->item_code }}</td>
                            <td style="text-align: right;">{{ $item->name }}</td>
                            <td>{{ $item->product->size ?? '-' }}</td>
                            <td>
                                @php
                                    $grade = $item->product->grade ?? '';
                                @endphp
                                @if($grade == 'اول' || $grade == 'أول')
                                    <span class="badge badge-first">أول</span>
                                @elseif($grade == 'ثاني' || $grade == 'ثانى')
                                    <span class="badge badge-second">ثاني</span>
                                @elseif($grade == 'ثالث')
                                    <span class="badge badge-third">ثالث</span>
                                @elseif($grade == 'رابع')
                                    <span class="badge badge-fourth">رابع</span>
                                @else
                                    <span class="badge badge-cash">{{ $grade ?: '-' }}</span>
                                @endif
                            </td>
                            <td>{{ number_format($item->grade1, 2) }}</td>
                            <td>
                                <input type="number" name="prices[{{ $item->id }}]" step="0.01" value="{{ $item->unit_price }}" class="price-input">
                            </td>
                            <td>
                                @if($order->order_discount > 0)
                                    <span class="badge badge-discount">{{ $order->order_discount }}%</span>
                                @else
                                    <input type="number" name="discounts[{{ $order->id }}]" step="0.01" value="{{ $order->order_discount }}" class="discount-input">
                                @endif
                            </td>
                            <td>
                                <strong id="total_{{ $item->id }}" style="{{ $transactionTypeText == 'return' || $transactionTypeText == 'sample' ? 'color: #28a745;' : 'color: #dc3545;' }}">
                                    {{ number_format($finalTotal, 2) }}
                                </strong>
                            </td>
                        </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="12" class="text-center">لا توجد معاملات لهذا العميل</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($customer->orders->count() > 0)
                <tfoot>
                    <tr class="total-row">
                        <td colspan="11" style="text-align: left;"><strong>صافي المطلوب</strong></td>
                        <td><strong id="grandTotal">{{ number_format($totalAmount, 2) }}</strong></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </form>
    </div>
</div>

<script>
    // دالة لحفظ جميع الأسعار
    function saveAllPrices() {
        const form = document.getElementById('allPricesForm');
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم تحديث جميع الأسعار بنجاح');
                location.reload();
            } else {
                alert('حدث خطأ: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في الاتصال');
        });
    }

    // تحديث الإجمالي عند تغيير السعر (مباشرة)
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('price-input')) {
            const row = e.target.closest('tr');
            const quantity = parseFloat(row.querySelector('td:nth-child(9)').innerText.replace(/,/g, '')) || 0;
            const newPrice = parseFloat(e.target.value) || 0;
            const total = quantity * newPrice;
            const totalSpan = row.querySelector('td:last-child strong');
            if (totalSpan) {
                totalSpan.innerText = total.toFixed(2);
            }

            // إعادة حساب الإجمالي الكلي
            let grandTotal = 0;
            document.querySelectorAll('#allPricesForm tbody tr').forEach(tr => {
                const totalText = tr.querySelector('td:last-child strong')?.innerText;
                if (totalText) {
                    grandTotal += parseFloat(totalText.replace(/,/g, '')) || 0;
                }
            });
            const grandTotalSpan = document.getElementById('grandTotal');
            if (grandTotalSpan) {
                grandTotalSpan.innerText = grandTotal.toFixed(2);
            }
        }
    });
</script>

</body>
</html>
