<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلبيات المصنع - جلوريا للسيراميك</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            direction: rtl;
            background-color: #f8f9fa;
            padding: 20px;
            padding-top: 80px;
        }
        .container { max-width: 1400px; margin: 0 auto; }

        .page-header {
            background: linear-gradient(135deg, #fd7e14 0%, #dc3545 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-new { background: #28a745; color: white; }
        .status-loading { background: #ffc107; color: #212529; }
        .status-loaded { background: #17a2b8; color: white; }
        .status-delayed { background: #fd7e14; color: white; }
        .status-cancelled { background: #dc3545; color: white; }

        .btn-status {
            padding: 5px 10px;
            margin: 2px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }
        .btn-loading { background: #ffc107; color: #212529; }
        .btn-loaded { background: #17a2b8; color: white; }
        .btn-delayed { background: #fd7e14; color: white; }
        .btn-cancel { background: #dc3545; color: white; }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            vertical-align: middle;
        }
        th {
            background: #333;
            color: white;
        }

        @media (max-width: 768px) {
            body { padding-top: 70px; }
            table { font-size: 12px; }
            th, td { padding: 6px; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-industry"></i> طلبيات المصنع</h1>
        <p>إدارة ومتابعة الطلبيات الواردة للمصنع</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>العميل</th>
                    <th>المندوب</th>
                    <th>التاريخ</th>
                    <th>الكمية</th>
                    <th>الحالة</th>
                    <th>ملاحظات المصنع</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td><strong>#{{ $order->id }}</strong></td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->user->name ?? '-' }}</td>
                    <td>{{ $order->date->format('Y-m-d') }}</td>
                    <td>{{ number_format($order->items->sum('total')) }} متر</td>
                    <td>
                        @php
                            $statusClass = 'status-new';
                            if($order->status == 'تحت التحميل') $statusClass = 'status-loading';
                            elseif($order->status == 'تم التحميل') $statusClass = 'status-loaded';
                            elseif($order->status == 'مؤجلة') $statusClass = 'status-delayed';
                            elseif($order->status == 'ملغية') $statusClass = 'status-cancelled';
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ $order->status }}</span>
                    </td>
                    <td>
                        <!-- التعديل هنا: استخدام URL مباشر بدلاً من route -->
                        <form action="/factory/order/{{ $order->id }}/status" method="POST" style="display: inline-block;">
                            @csrf
                            <input type="text" name="factory_notes" placeholder="ملاحظة" class="form-control form-control-sm" style="width: 120px; display: inline-block;">
                            <select name="status" class="form-select form-select-sm" style="width: 110px; display: inline-block;">
                                <option value="جديدة" {{ $order->status == 'جديدة' ? 'selected' : '' }}>جديدة</option>
                                <option value="تحت التحميل" {{ $order->status == 'تحت التحميل' ? 'selected' : '' }}>تحت التحميل</option>
                                <option value="تم التحميل" {{ $order->status == 'تم التحميل' ? 'selected' : '' }}>تم التحميل</option>
                                <option value="مؤجلة" {{ $order->status == 'مؤجلة' ? 'selected' : '' }}>مؤجلة</option>
                                <option value="ملغية" {{ $order->status == 'ملغية' ? 'selected' : '' }}>ملغية</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">تحديث</button>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">عرض</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $orders->links() }}
    </div>
</div>

</body>
</html>
