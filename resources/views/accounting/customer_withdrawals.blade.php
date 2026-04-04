<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مسحوبات العميل - جلوريا للسيراميك</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Arial, sans-serif; direction: rtl; background: #f8f9fa; padding: 20px; padding-top: 90px; }
        .container { max-width: 1400px; margin: 0 auto; }
        .page-header { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px; text-align: center; }
        .info-card { background: white; border-radius: 15px; padding: 20px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .info-row { display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 10px; }
        .info-label { font-weight: bold; min-width: 120px; color: #555; }
        .info-value { color: #333; }
        .total-box { background: #e3f2fd; padding: 15px; border-radius: 10px; text-align: center; margin-bottom: 20px; }
        .total-number { font-size: 28px; font-weight: bold; color: #007bff; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 15px; overflow: hidden; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: center; vertical-align: middle; }
        th { background: #333; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; }
        .btn-print { background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 8px; }
        @media (max-width: 768px) { th, td { font-size: 12px; padding: 8px; } }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-hand-holding-usd"></i> مسحوبات العميل</h1>
        <p>سجل جميع المسحوبات والمنتجات المسحوبة</p>
    </div>

    <div class="info-card">
        <div class="info-row">
            <div class="info-label"><i class="fas fa-user"></i> اسم العميل:</div>
            <div class="info-value"><strong>{{ $customer->name }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label"><i class="fas fa-tag"></i> نوع العميل:</div>
            <div class="info-value">
                @if($customer->type == 'agent') وكيل
                @elseif($customer->type == 'dealer') تاجر
                @else عميل نقدي @endif
            </div>
        </div>
        <div class="info-row">
            <div class="info-label"><i class="fas fa-percent"></i> نسبة الخصم:</div>
            <div class="info-value">{{ number_format($customer->discount_rate, 2) }}%</div>
        </div>
        <div class="info-row">
            <div class="info-label"><i class="fas fa-phone"></i> الهاتف:</div>
            <div class="info-value">{{ $customer->phone ?? '-' }}</div>
        </div>
    </div>

    <div class="total-box">
        <div class="row">
            <div class="col-md-4">
                <div class="total-number">{{ number_format($totalQuantity, 2) }}</div>
                <div>إجمالي الكميات (متر)</div>
            </div>
            <div class="col-md-4">
                <div class="total-number">{{ number_format($totalAmount, 2) }}</div>
                <div>إجمالي المسحوبات (جنيه)</div>
            </div>
            <div class="col-md-4">
                <div class="total-number">{{ $withdrawals ? count($withdrawals) : 0 }}</div>
                <div>عدد العناصر المسحوبة</div>
            </div>
        </div>
    </div>

    @if($withdrawals && count($withdrawals) > 0)
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>التاريخ</th>
                        <th>رقم الإذن</th>
                        <th>كود الصنف</th>
                        <th>اسم الصنف</th>
                        <th>النوع</th>
                        <th>المجموعة</th>
                        <th>الفرز</th>
                        <th>المقاس</th>
                        <th>الموديل</th>
                        <th>اللون</th>
                        <th>الكمية</th>
                        <th>سعر الوحدة</th>
                        <th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withdrawals as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->date }}</td>
                        <td><strong>#{{ $item->order_number }}</strong></td>
                        <td>{{ $item->item_code }}</td>
                        <td style="text-align: right;">{{ $item->item_name }}</td>
                        <td>{{ $item->product_type ?? '-' }}</td>
                        <td>{{ $item->product_group ?? '-' }}</td>
                        <td>
                            <span class="badge" style="background: #17a2b8; color: white;">{{ $item->grade ?? '-' }}</span>
                        </td>
                        <td>{{ $item->size ?? '-' }}</td>
                        <td>{{ $item->model ?? '-' }}</td>
                        <td>{{ $item->color ?? '-' }}</td>
                        <td><strong>{{ number_format($item->quantity, 2) }}</strong></td>
                        <td>{{ number_format($item->unit_price, 2) }}</td>
                        <td><strong>{{ number_format($item->total, 2) }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="fas fa-inbox"></i> لا توجد مسحوبات لهذا العميل
        </div>
    @endif

    <div class="text-center mt-4">
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print"></i> طباعة التقرير
        </button>
        <a href="{{ route('accounting.customers') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة للعملاء
        </a>
    </div>
</div>

</body>
</html>
