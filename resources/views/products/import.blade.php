<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استيراد الأصناف - جلوريا للسيراميك</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            margin: 0;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container { max-width: 720px; margin: 0 auto; }
        h2 { text-align: center; color: #333; margin: 12px 0 20px; }

        .card {
            background-color: white;
            padding: 24px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
            margin-bottom: 18px;
        }
        .alert {
            padding: 12px 14px;
            border-radius: 6px;
            margin-bottom: 14px;
            border: 1px solid transparent;
        }
        .alert-danger { background:#f8d7da; color:#721c24; border-color:#f5c6cb; }
        .alert-success { background:#d4edda; color:#155724; border-color:#c3e6cb; }
        .alert-warning { background:#fff3cd; color:#856404; border-color:#ffeeba; }
        .alert-info { background:#d1ecf1; color:#0c5460; border-color:#bee5eb; }

        .info {
            background:#d1ecf1; color:#0c5460; border:1px solid #bee5eb;
            padding: 12px 14px; border-radius:6px; margin-bottom:12px;
        }
        label { display:block; margin:10px 0 8px; font-weight:bold; color:#333; }
        input[type="file"] {
            width: 100%;
            padding: 14px;
            border: 2px dashed #007bff;
            border-radius: 6px;
            background: #fafcff;
            cursor: pointer;
        }
        input[type="file"]:hover {
            border-color: #28a745;
            background: #f0fff0;
        }
        .muted { color:#666; font-size: 12px; margin-top: 6px; }

        .btn {
            display:inline-block; width:100%;
            padding: 12px 16px; border: none; border-radius: 6px;
            cursor:pointer; font-weight:bold; font-size: 15px;
            text-align:center; text-decoration:none;
            transition: all 0.3s;
        }
        .btn-upload { background:#28a745; color:#fff; }
        .btn-upload:hover { background:#218838; transform: translateY(-1px); }
        .btn-template { background:#17a2b8; color:#fff; margin-top:10px; display:inline-block; width:100%; }
        .btn-template:hover { background:#138496; transform: translateY(-1px); }
        .btn-back { background:#6c757d; color:#fff; margin-top:10px; display:inline-block; width:100%; }
        .btn-back:hover { background:#5a6268; transform: translateY(-1px); }

        table { width:100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border:1px solid #ddd; padding: 10px; text-align:right; }
        th { background:#e9ecef; font-weight:bold; }

        .summary-box {
            background: #e8f5e9;
            border-right: 4px solid #28a745;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .error-box {
            background: #ffebee;
            border-right: 4px solid #dc3545;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 4px;
            max-height: 200px;
            overflow-y: auto;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success { background: #28a745; color: white; }
        .badge-danger { background: #dc3545; color: white; }
        .badge-info { background: #17a2b8; color: white; }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <h2>📥 استيراد الأصناف والأرصدة</h2>

    {{-- التحقق من صلاحيات المدير --}}
    @if(auth()->user()->is_admin != 1)
        <div class="alert alert-danger">
            <strong>⛔ غير مصرح!</strong>
            <p>هذه الصفحة متاحة للمديرين فقط. لا تملك الصلاحية للوصول إلى هذه الصفحة.</p>
            <a href="{{ route('orders.userDashboard') }}" style="color: #721c24; text-decoration: underline;">العودة للوحة التحكم</a>
        </div>
    @else

    {{-- أخطاء الفاليديشن --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>❌ خطأ!</strong>
            <ul style="margin: 10px 0; padding-right: 18px;">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- رسائل نجاح عامة --}}
    @if (session('success'))
        <div class="alert alert-success">
            <strong>✅ {{ session('success') }}</strong>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            <strong>❌ {{ session('error') }}</strong>
        </div>
    @endif

    {{-- عرض ملخص الاستيراد --}}
    @if(session('import_summary'))
        <div class="summary-box">
            <strong>📊 ملخص عملية الاستيراد:</strong>
            <ul style="margin: 10px 0; padding-right: 18px;">
                <li>✅ تم استيراد: <strong>{{ session('import_summary')['success'] }}</strong> صنف بنجاح</li>
                <li>❌ فشل استيراد: <strong>{{ session('import_summary')['failed'] }}</strong> صنف</li>
                <li>📄 إجمالي المعالجة: <strong>{{ session('import_summary')['processed'] }}</strong> صف</li>
            </ul>
        </div>
    @endif

    {{-- عرض أخطاء الاستيراد التفصيلية --}}
    @if(session('import_errors') && count(session('import_errors')) > 0)
        <div class="error-box">
            <strong>⚠️ الأخطاء التفصيلية:</strong>
            <ul style="margin: 10px 0; padding-right: 18px; max-height: 150px; overflow-y: auto;">
                @foreach(session('import_errors') as $err)
                    <li style="color: #dc3545;">• {{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="info">
    <strong>📋 تعليمات الاستيراد:</strong>
    <ul style="margin: 10px 0; padding-right: 18px;">
        <li>📁 الصيغ المقبولة: <strong>xlsx / csv / xls</strong></li>
        <li>📊 أسماء الأعمدة يجب أن تكون كما في الشيت:
            <strong>كود الصنف، اسم الصنف، الوحدة، الرصيد</strong>
        </li>
        <li>🔄 سيتم تحديث الرصيد للأصناف الموجودة، وإنشاء الأصناف الجديدة تلقائياً</li>
        <li>🔢 يفضّل أن يكون عمود <strong>كود الصنف</strong> نوعه نص (Text) داخل الإكسل للحفاظ على الأصفار في البداية</li>
    </ul>
</div>

        {{-- فورم الرفع (POST) --}}
        <form method="POST" action="{{ route('products.import') }}" enctype="multipart/form-data">
            @csrf

            <label for="file">📂 اختر ملف Excel أو CSV:</label>
            <input type="file" id="file" name="file" accept=".xlsx,.csv,.xls" required>
            <div class="muted">
                ⚡ أقصى حجم 50MB • سيتم معالجة الملف ثم تحويلك مع عرض ملخّص النتائج
            </div>

            <div style="margin-top: 14px;">
                <button type="submit" class="btn btn-upload">📤 رفع واستيراد</button>
            </div>
        </form>

        <a href="{{ route('products.downloadTemplate') }}" class="btn btn-template" download>📋 تحميل قالب (Excel/CSV)</a>
        <a href="{{ route('products.index') }}" class="btn btn-back">⬅️ رجوع إلى الأصناف</a>
    </div>

    <div class="card" style="background:#fafafa;">
        <h3 style="margin-top:0;">📝 الأعمدة المتوقعة في الملف:</h3>
        <table style="width:100%;">
            <thead>
                <tr style="background:#e9ecef;">
                    <th style="padding:10px;">اسم العمود</th>
                    <th style="padding:10px;">الوصف</th>
                    <th style="padding:10px;">مثال</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding:8px;"><strong>كود الصنف</strong></td>
                    <td style="padding:8px;">معرّف الصنف (إجباري)</td>
                    <td style="padding:8px; direction:ltr;">01041200076147</td>
                </tr>
                <tr style="background:#f5f5f5;">
                    <td style="padding:8px;"><strong>اسم الصنف</strong></td>
                    <td style="padding:8px;">اسم توضيحي للصنف (إجباري)</td>
                    <td style="padding:8px;">حوائط مطفي 60×60</td>
                </tr>
                <tr>
                    <td style="padding:8px;"><strong>الوحدة</strong></td>
                    <td style="padding:8px;">نوع الوحدة (اختياري)</td>
                    <td style="padding:8px;">متر / كرتونة / قطعة</td>
                </tr>
                <tr style="background:#f5f5f5;">
                    <td style="padding:8px;"><strong>الرصيد</strong></td>
                    <td style="padding:8px;">الكمية الحالية (إجباري)</td>
                    <td style="padding:8px;">288.72</td>
                </tr>
                <tr>
                    <td style="padding:8px;"><strong>السعر</strong></td>
                    <td style="padding:8px;">سعر الوحدة (اختياري)</td>
                    <td style="padding:8px;">50.00</td>
                </tr>
            </tbody>
        </table>

        <div class="alert alert-info" style="margin-top: 15px;">
            <strong>💡 ملاحظة:</strong> يمكنك تحميل القالب أولاً، ثم تعبئة البيانات فيه، ثم رفعه مرة أخرى.
        </div>
    </div>

    @endif
</div>

<script>
    // تحسين تجربة المستخدم عند رفع الملف
    document.getElementById('file')?.addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        if (fileName) {
            const fileSize = (e.target.files[0].size / 1024 / 1024).toFixed(2);
            console.log(`تم اختيار الملف: ${fileName} (${fileSize} MB)`);
        }
    });
</script>

</body>
</html>
