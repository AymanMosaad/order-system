<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>الشيكات - جلوريا للسيراميك</title>
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
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
        }
        .stat-number.pending { color: #ffc107; }
        .stat-number.collected { color: #28a745; }
        .stat-number.returned { color: #dc3545; }

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
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
        }
        .badge-pending { background: #ffc107; color: #212529; }
        .badge-collected { background: #28a745; color: white; }
        .badge-returned { background: #dc3545; color: white; }
        .badge-cancelled { background: #6c757d; color: white; }

        .empty-state {
            text-align: center;
            padding: 60px;
            color: #999;
        }

        @media (max-width: 768px) {
            body { padding: 10px; padding-top: 80px; }
            .stats-grid { grid-template-columns: 1fr; }
            th, td { padding: 6px; font-size: 11px; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-money-check"></i> الشيكات</h1>
        <p>إدارة شيكات العملاء وتتبع تحصيلها</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number pending">{{ number_format($pendingTotal, 2) }}</div>
            <div class="stat-label">شيكات معلقة</div>
        </div>
        <div class="stat-card">
            <div class="stat-number collected">{{ number_format($collectedTotal, 2) }}</div>
            <div class="stat-label">شيكات محصلة</div>
        </div>
        <div class="stat-card">
            <div class="stat-number returned">{{ number_format($returnedTotal, 2) }}</div>
            <div class="stat-label">شيكات مرتجعة</div>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>رقم الشيك</th>
                    <th>العميل</th>
                    <th>البنك</th>
                    <th>المبلغ</th>
                    <th>تاريخ الإصدار</th>
                    <th>تاريخ الاستحقاق</th>
                    <th>الحالة</th>
                    <th>ملاحظات</th>
                </thead>
                <tbody>
                    @forelse($cheques as $index => $cheque)
                     <tr>
                        <td>{{ $cheques->firstItem() + $index }}</td>
                        <td><strong>{{ $cheque->cheque_number }}</strong></td>
                        <td>{{ $cheque->customer->name ?? '-' }}</td>
                        <td>{{ $cheque->bank_name ?? '-' }}</td>
                        <td><strong>{{ number_format($cheque->amount, 2) }}</strong></td>
                        <td>{{ $cheque->issue_date->format('Y-m-d') }}</td>
                        <td>{{ $cheque->due_date->format('Y-m-d') }}</td>
                        <td>
                            @if($cheque->status == 'pending')
                                <span class="badge badge-pending">معلق</span>
                            @elseif($cheque->status == 'collected')
                                <span class="badge badge-collected">محصل</span>
                            @elseif($cheque->status == 'returned')
                                <span class="badge badge-returned">مرتجع</span>
                            @else
                                <span class="badge badge-cancelled">ملغي</span>
                            @endif
                        </td>
                        <td>{{ $cheque->notes ?? '-' }}</td>
                     </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>لا توجد شيكات</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $cheques->appends(request()->query())->links() }}
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('accounting.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right"></i> العودة للوحة
            </a>
        </div>
    </div>

</body>
</html>
