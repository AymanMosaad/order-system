<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>استيراد المسحوبات - جلوريا للسيراميك</title>
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

        .upload-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            text-align: center;
            margin-bottom: 30px;
        }

        .drop-zone {
            border: 2px dashed #ddd;
            border-radius: 15px;
            padding: 40px;
            margin: 20px 0;
            transition: all 0.3s;
            cursor: pointer;
        }
        .drop-zone:hover {
            border-color: #007bff;
            background: #f8f9fa;
        }
        .drop-zone.dragover {
            border-color: #28a745;
            background: #e8f5e9;
        }

        .info-box {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: right;
        }

        .filters-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .filter-group {
            display: inline-block;
            margin-left: 15px;
            margin-bottom: 10px;
        }

        .filter-group label {
            font-weight: bold;
            color: #555;
            font-size: 12px;
            display: block;
            margin-bottom: 5px;
        }

        .filter-group input, .filter-group select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            min-width: 180px;
        }

        .btn-filter {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-reset {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .table-responsive {
            overflow-x: auto;
            border-radius: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            border: 1px solid #eee;
            padding: 12px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background: #f8f9fa;
            font-weight: bold;
            position: sticky;
            top: 0;
        }

        .btn-sm {
            padding: 4px 10px;
            font-size: 12px;
            border-radius: 6px;
        }

        .price-input {
            width: 100px;
            padding: 6px;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-align: center;
        }

        .discount-input {
            width: 70px;
            padding: 6px;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-align: center;
        }

        .summary-box {
            background: #e8f5e9;
            border-right: 4px solid #28a745;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .error-list {
            background: #f8d7da;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            max-height: 300px;
            overflow-y: auto;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-success { background: #28a745; color: white; }
        .badge-warning { background: #ffc107; color: #212529; }
        .badge-danger { background: #dc3545; color: white; }

        .pagination-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 20px;
            padding: 15px;
            background: white;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            body { padding: 10px; padding-top: 80px; }
            .filter-group { display: block; margin-left: 0; }
            .filter-group input, .filter-group select { width: 100%; }
            th, td { font-size: 12px; padding: 8px; }
            .price-input, .discount-input { width: 70px; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-file-import"></i> استيراد المسحوبات</h1>
        <p>رفع ملف Excel الخاص بمسحوبات العملاء</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('import_errors'))
        <div class="error-list">
            <h5><i class="fas fa-exclamation-triangle"></i> الأخطاء:</h5>
            <ul>
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ===== نموذج رفع الملف ===== -->
    <div class="upload-card">
        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <strong>تعليمات الملف:</strong>
            <ul class="mt-2 mb-0">
                <li>يجب أن يكون الملف بصيغة Excel (.xlsx, .xls)</li>
                <li>الترتيب: طبيعة الاذن | رقم الاذن | التاريخ | كود العميل | اسم العميل | كود الصنف | اسم الصنف | الوحدة | الكمية | القيمة | نوع | مجموعة | فرز | مقاس | موديل | لون | المخزن</li>
                <li>العمود A: طبيعة الاذن (صرف مبيعات محليه، صرف مبيعات تصدير، إلخ)</li>
                <li>العمود E: اسم العميل</li>
                <li>العمود F: كود الصنف</li>
                <li>العمود I: الكمية</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('import.withdrawals') }}" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <div class="drop-zone" id="dropZone">
                <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: #007bff;"></i>
                <p class="mt-3">اسحب وأفلت الملف هنا أو اضغط للاختيار</p>
                <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv" style="display: none;">
                <button type="button" class="btn btn-primary mt-3" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-folder-open"></i> اختيار ملف
                </button>
                <div id="fileName" class="mt-2 text-muted"></div>
            </div>
            <button type="submit" class="btn btn-success btn-lg mt-3" id="submitBtn" disabled>
                <i class="fas fa-upload"></i> بدء الاستيراد
            </button>
        </form>
    </div>

    <!-- ===== تقرير المسحوبات بعد الاستيراد ===== -->
    @if(isset($withdrawals) && count($withdrawals) > 0)
        <div class="summary-box">
            <h5><i class="fas fa-chart-line"></i> ملخص المسحوبات</h5>
            <div class="row">
                <div class="col-md-3">
                    <strong>إجمالي المسحوبات:</strong> {{ number_format($totalWithdrawals, 2) }} ج.م
                </div>
                <div class="col-md-3">
                    <strong>عدد العملاء:</strong> {{ $customersCount }}
                </div>
                <div class="col-md-3">
                    <strong>عدد العناصر:</strong> {{ $itemsCount }}
                </div>
            </div>
        </div>

        <!-- ===== فلتر البحث ===== -->
        <div class="filters-card">
            <h5><i class="fas fa-filter"></i> فلترة المسحوبات</h5>
            <form method="GET" action="{{ route('import.withdrawals.form') }}">
                <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                    <div class="filter-group">
                        <label>اسم العميل</label>
                        <input type="text" name="customer_name" placeholder="بحث باسم العميل" value="{{ request('customer_name') }}">
                    </div>
                    <div class="filter-group">
                        <label>كود الصنف</label>
                        <input type="text" name="item_code" placeholder="بحث بكود الصنف" value="{{ request('item_code') }}">
                    </div>
                    <div class="filter-group">
                        <label>المقاس</label>
                        <input type="text" name="size" placeholder="بحث بالمقاس" value="{{ request('size') }}">
                    </div>
                    <div class="filter-group">
                        <label>الفرز</label>
                        <select name="grade">
                            <option value="">-- الكل --</option>
                            <option value="اول" {{ request('grade') == 'اول' ? 'selected' : '' }}>أول</option>
                            <option value="ثاني" {{ request('grade') == 'ثاني' ? 'selected' : '' }}>ثاني</option>
                            <option value="ثالث" {{ request('grade') == 'ثالث' ? 'selected' : '' }}>ثالث</option>
                            <option value="رابع" {{ request('grade') == 'رابع' ? 'selected' : '' }}>رابع</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn-filter"><i class="fas fa-search"></i> بحث</button>
                        <a href="{{ route('import.withdrawals.form') }}" class="btn-reset"><i class="fas fa-times"></i> إلغاء</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- ===== جدول المسحوبات ===== -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>التاريخ</th>
                        <th>رقم الإذن</th>
                        <th>اسم العميل</th>
                        <th>كود الصنف</th>
                        <th>اسم الصنف</th>
                        <th>المقاس</th>
                        <th>الفرز</th>
                        <th>الكمية</th>
                        <th>سعر الوحدة</th>
                        <th>نسبة الخصم</th>
                        <th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withdrawals as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['date'] }}</td>
                        <td>#{{ $item['order_number'] }}</td>
                        <td>{{ $item['customer_name'] }}</td>
                        <td>{{ $item['item_code'] }}</td>
                        <td style="text-align: right;">{{ $item['item_name'] }}</td>
                        <td>{{ $item['size'] ?? '-' }}</td>
                        <td>
                            @if($item['grade'] == 'اول')
                                <span class="badge badge-success">أول</span>
                            @elseif($item['grade'] == 'ثاني')
                                <span class="badge badge-warning">ثاني</span>
                            @elseif($item['grade'] == 'ثالث')
                                <span class="badge badge-danger">ثالث</span>
                            @else
                                {{ $item['grade'] ?? '-' }}
                            @endif
                        </td>
                        <td>{{ number_format($item['quantity'], 2) }}</td>
                        <td>
                            <form method="POST" action="{{ route('import.withdrawals.updatePrice', $item['order_item_id']) }}" style="display: inline-flex; gap: 5px;">
                                @csrf
                                @method('PUT')
                                <input type="number" name="price" step="0.01" value="{{ $item['unit_price'] }}" class="price-input">
                                <button type="submit" class="btn-sm btn-primary">تحديث</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('import.withdrawals.updateDiscount', $item['order_item_id']) }}" style="display: inline-flex; gap: 5px;">
                                @csrf
                                @method('PUT')
                                <input type="number" name="discount_rate" step="0.01" value="{{ $item['discount_rate'] }}" class="discount-input">
                                <button type="submit" class="btn-sm btn-primary">تحديث</button>
                            </form>
                        </td>
                        <td><strong>{{ number_format($item['total'], 2) }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(isset($withdrawals) && $withdrawals instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="pagination-custom">
                <div>{{ $withdrawals->firstItem() }} - {{ $withdrawals->lastItem() }} من {{ $withdrawals->total() }}</div>
                <div>{{ $withdrawals->links('pagination::bootstrap-4') }}</div>
            </div>
        @endif
    @endif

    <div class="text-center mt-4">
        <a href="{{ route('accounting.customers') }}" class="btn btn-secondary">
            <i class="fas fa-users"></i> عرض العملاء
        </a>
        <a href="{{ route('products.index') }}" class="btn btn-info">
            <i class="fas fa-boxes"></i> إدارة الأصناف
        </a>
    </div>
</div>

<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const fileName = document.getElementById('fileName');
    const submitBtn = document.getElementById('submitBtn');

    dropZone.addEventListener('click', () => fileInput.click());
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });
    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length) {
            fileInput.files = files;
            updateFileName(files[0].name);
        }
    });
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length) {
            updateFileName(e.target.files[0].name);
        }
    });

    function updateFileName(name) {
        fileName.innerHTML = `<i class="fas fa-file-excel"></i> ${name}`;
        submitBtn.disabled = false;
    }
</script>

</body>
</html>
