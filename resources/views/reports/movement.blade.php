<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>تقرير حركة الأصناف - جلوريا للسيراميك</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }

        .filters-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .summary-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .summary-number {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
        }

        .table-wrapper {
            background: white;
            border-radius: 12px;
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
        }

        th {
            background: #f8f9fa;
            font-weight: bold;
            position: sticky;
            top: 0;
        }

        .btn-print {
            background: #17a2b8;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            body { padding: 10px; padding-top: 80px; }
            th, td { padding: 6px; font-size: 11px; }
            .summary-number { font-size: 20px; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-chart-line"></i> تقرير حركة الأصناف</h1>
        <p>سجل تفصيلي لجميع عمليات البيع</p>
    </div>

    <button onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> طباعة</button>

    <!-- فلاتر البحث -->
    <div class="filters-card">
        <form method="GET" action="{{ route('products.movementReport') }}" class="row g-3">
            <div class="col-md-3 col-sm-6">
                <label class="form-label"><i class="fas fa-box"></i> الصنف</label>
                <select name="product_id" class="form-select">
                    <option value="">-- جميع الأصناف --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->item_code }} - {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <label class="form-label"><i class="fas fa-calendar"></i> من تاريخ</label>
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-2 col-sm-6">
                <label class="form-label"><i class="fas fa-calendar"></i> إلى تاريخ</label>
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="form-label"><i class="fas fa-user"></i> المندوب</label>
                <select name="user_id" class="form-select">
                    <option value="">-- جميع المندوبين --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-sm-12 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> بحث</button>
            </div>
        </form>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="summary-number">{{ number_format($totalQuantity, 2) }}</div>
            <div>إجمالي الكمية (متر)</div>
        </div>
        <div class="summary-card">
            <div class="summary-number">{{ number_format($totalValue, 2) }}</div>
            <div>إجمالي القيمة</div>
        </div>
        <div class="summary-card">
            <div class="summary-number">{{ number_format($totalOrders) }}</div>
            <div>عدد الطلبيات</div>
        </div>
    </div>

    <!-- جدول الحركة -->
    <div class="table-wrapper">
        <table class="table table-bordered">
            <thead>
                 <tr>
                    <th>#</th>
                    <th>التاريخ</th>
                    <th>رقم الطلبية</th>
                    <th>العميل</th>
                    <th>المندوب</th>
                    <th>كود الصنف</th>
                    <th>اسم الصنف</th>
                    <th>الكمية (متر)</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $index => $item)
                <tr>
                    <td>{{ $movements->firstItem() + $index }}</td>
                    <td>{{ $item->order?->date?->format('Y-m-d') ?? '-' }}</td>
                    <td>#{{ $item->order_id }}</td>
                    <td>{{ $item->order?->customer_name ?? '-' }}</td>
                    <td>{{ $item->order?->user?->name ?? '-' }}</td>
                    <td>{{ $item->item_code }}</td>
                    <td style="text-align: right;">{{ $item->name }}</td>
                    <td>{{ number_format($item->grade1, 2) }}</td>
                    <td><strong>{{ number_format($item->total, 2) }}</strong></td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 50px;">
                        <i class="fas fa-inbox"></i> لا توجد بيانات
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $movements->appends(request()->query())->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
