<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>تقرير الفرز - جلوريا للسيراميك</title>
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
        .page-header h1 { margin: 0 0 5px 0; font-size: 24px; }
        .page-header p { margin: 0; opacity: 0.85; font-size: 14px; }

        .report-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow-x: auto;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #007bff;
            display: inline-block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px 12px;
            text-align: center;
            font-size: 13px;
        }
        th {
            background: #f8f9fa;
            color: #333;
            font-weight: bold;
            position: sticky;
            top: 0;
        }
        tr:hover {
            background: #f5f5f5;
        }
        .quantity {
            font-weight: bold;
        }
        .quantity-first { color: #007bff; }
        .quantity-second { color: #28a745; }
        .quantity-third { color: #fd7e14; }
        .quantity-fourth { color: #dc3545; }
        .quantity-total { color: #6c757d; font-weight: bold; }

        .summary-row {
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
            margin-bottom: 20px;
        }
        .btn-print:hover { background: #138496; }

        @media (max-width: 768px) {
            body { padding: 10px; padding-top: 80px; }
            th, td { padding: 6px 8px; font-size: 11px; }
            .page-header h1 { font-size: 18px; }
        }

        @media print {
            .btn-print, .navbar-custom { display: none; }
            body { background: white; padding: 0; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-chart-bar"></i> تقرير الرصيد حسب الفرز</h1>
        <p>توزيع الأرصدة على الأفراز (أول - ثاني - ثالث - رابع) - بالمتر</p>
    </div>

    <button onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> طباعة التقرير</button>

    @php
        // تجميع البيانات في مصفوفة واحدة
        $allSizes = [];
        $gradesList = ['أول', 'ثاني', 'ثالث', 'رابع'];

        foreach ($gradesList as $grade) {
            $products = \App\Models\Product::with('stock')
                ->where('grade', $grade)
                ->where('is_active', true)
                ->get();

            foreach ($products as $product) {
                $size = $product->size ?? 'غير محدد';
                $quantity = $product->stock->current_stock ?? 0;

                if (!isset($allSizes[$size])) {
                    $allSizes[$size] = [
                        'أول' => 0,
                        'ثاني' => 0,
                        'ثالث' => 0,
                        'رابع' => 0,
                        'total' => 0
                    ];
                }
                $allSizes[$size][$grade] += $quantity;
                $allSizes[$size]['total'] += $quantity;
            }
        }

        // ترتيب المقاسات
        ksort($allSizes);

        // حساب الإجماليات لكل فرز
        $gradeTotals = ['أول' => 0, 'ثاني' => 0, 'ثالث' => 0, 'رابع' => 0, 'total' => 0];
        foreach ($allSizes as $size => $data) {
            $gradeTotals['أول'] += $data['أول'];
            $gradeTotals['ثاني'] += $data['ثاني'];
            $gradeTotals['ثالث'] += $data['ثالث'];
            $gradeTotals['رابع'] += $data['رابع'];
            $gradeTotals['total'] += $data['total'];
        }
    @endphp

    <div class="report-card">
        <div class="section-title"><i class="fas fa-table"></i> توزيع الأرصدة حسب المقاس والفرز (بالمتر)</div>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المقاس</th>
                        <th style="background: #007bff; color: white;">أول (متر)</th>
                        <th style="background: #28a745; color: white;">ثاني (متر)</th>
                        <th style="background: #fd7e14; color: white;">ثالث (متر)</th>
                        <th style="background: #dc3545; color: white;">رابع (متر)</th>
                        <th style="background: #6c757d; color: white;">الإجمالي (متر)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter = 1; @endphp
                    @foreach($allSizes as $size => $data)
                        @if($data['total'] > 0)
                        <tr>
                            <td>{{ $counter++ }}</td>
                            <td><strong>{{ $size }}</strong></td>
                            <td class="quantity quantity-first">{{ number_format($data['أول'], 2) }}</td>
                            <td class="quantity quantity-second">{{ number_format($data['ثاني'], 2) }}</td>
                            <td class="quantity quantity-third">{{ number_format($data['ثالث'], 2) }}</td>
                            <td class="quantity quantity-fourth">{{ number_format($data['رابع'], 2) }}</td>
                            <td class="quantity quantity-total">{{ number_format($data['total'], 2) }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="summary-row">
                        <td colspan="2"><strong>الإجمالي الكلي</strong></td>
                        <td class="quantity-first"><strong>{{ number_format($gradeTotals['أول'], 2) }}</strong></td>
                        <td class="quantity-second"><strong>{{ number_format($gradeTotals['ثاني'], 2) }}</strong></td>
                        <td class="quantity-third"><strong>{{ number_format($gradeTotals['ثالث'], 2) }}</strong></td>
                        <td class="quantity-fourth"><strong>{{ number_format($gradeTotals['رابع'], 2) }}</strong></td>
                        <td class="quantity-total"><strong>{{ number_format($gradeTotals['total'], 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- ملخص النسب -->
    <div class="report-card">
        <div class="section-title"><i class="fas fa-chart-pie"></i> ملخص النسب المئوية</div>
        <div style="overflow-x: auto;">
            <div class="row">
                @foreach($gradesList as $grade)
                    @php
                        $percentage = $gradeTotals['total'] > 0 ? ($gradeTotals[$grade] / $gradeTotals['total']) * 100 : 0;
                    @endphp
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card text-center p-3">
                            <h5>{{ $grade }}</h5>
                            <div class="progress mb-2" style="height: 25px;">
                                <div class="progress-bar bg-{{ $grade == 'أول' ? 'primary' : ($grade == 'ثاني' ? 'success' : ($grade == 'ثالث' ? 'warning' : 'danger')) }}"
                                     style="width: {{ $percentage }}%">
                                    {{ round($percentage, 1) }}%
                                </div>
                            </div>
                            <p class="mb-0"><strong>{{ number_format($gradeTotals[$grade], 2) }}</strong> متر</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="report-card">
        <div class="section-title"><i class="fas fa-chart-simple"></i> إحصائيات سريعة (بالمتر)</div>
        <div class="row">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center p-3 bg-primary text-white">
                    <h5>أول</h5>
                    <h3>{{ number_format($gradeTotals['أول'], 2) }}</h3>
                    <small>متر</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center p-3 bg-success text-white">
                    <h5>ثاني</h5>
                    <h3>{{ number_format($gradeTotals['ثاني'], 2) }}</h3>
                    <small>متر</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center p-3 bg-warning text-dark">
                    <h5>ثالث</h5>
                    <h3>{{ number_format($gradeTotals['ثالث'], 2) }}</h3>
                    <small>متر</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center p-3 bg-danger text-white">
                    <h5>رابع</h5>
                    <h3>{{ number_format($gradeTotals['رابع'], 2) }}</h3>
                    <small>متر</small>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
