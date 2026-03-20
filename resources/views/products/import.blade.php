<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استيراد الأصناف</title>
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
        .muted { color:#666; font-size: 12px; margin-top: 6px; }

        .btn {
            display:inline-block; width:100%;
            padding: 12px 16px; border: none; border-radius: 6px;
            cursor:pointer; font-weight:bold; font-size: 15px;
            text-align:center; text-decoration:none;
        }
        .btn-upload { background:#28a745; color:#fff; }
        .btn-upload:hover { opacity:.9; }
        .btn-template { background:#17a2b8; color:#fff; margin-top:10px; display:inline-block; width:100%; }
        .btn-back { background:#6c757d; color:#fff; margin-top:10px; display:inline-block; width:100%; }

        table { width:100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border:1px solid #ddd; padding: 10px; text-align:right; }
        th { background:#e9ecef; }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <h2>📥 استيراد الأصناف والأرصدة</h2>

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

    {{-- رسائل نجاح عامة (لو رجّعت لهذه الصفحة) --}}
    @if (session('status'))
        <div class="alert alert-success">✅ {{ session('status') }}</div>
    @endif

    <div class="card">
        <div class="info">
            <strong>📋 تعليمات:</strong>
            <ul style="margin: 10px 0; padding-right: 18px;">
                <li>الصيغ المقبولة: <strong>xlsx / csv / xls</strong></li>
                <li>أسماء الأعمدة يجب أن تكون كما في الشيت: <strong>كود الصنف، اسم الصنف، الوحدة، الرصيد</strong></li>
                <li>سيتم تحديث الرصيد فقط للأصناف الموجودة بالفعل في قاعدة البيانات</li>
                <li>يفضّل أن يكون عمود <strong>كود الصنف</strong> نوعه نص (Text) داخل الإكسل للحفاظ على الأصفار في البداية</li>
            </ul>
        </div>

        {{-- فورم الرفع (POST) --}}
        <form method="POST" action="{{ route('products.import') }}" enctype="multipart/form-data">
            @csrf

            <label for="file">اختر ملف Excel أو CSV:</label>
            <input type="file" id="file" name="file" accept=".xlsx,.csv,.xls" required>
            <div class="muted">
                أقصى حجم 50MB • سيتم معالجة الملف ثم تحويلك مع عرض ملخّص النتائج
            </div>

            <div style="margin-top: 14px;">
                <button type="submit" class="btn btn-upload">📤 رفع واستيراد</button>
            </div>
        </form>

        <a href="{{ route('products.downloadTemplate') }}" class="btn btn-template" download>📋 تحميل نموذج (CSV)</a>
        <a href="{{ route('products.index') }}" class="btn btn-back">⬅️ رجوع إلى الأصناف</a>
    </div>

    <div class="card" style="background:#fafafa;">
        <h3 style="margin-top:0;">📝 الأعمدة المتوقعة في الملف:</h3>
        <table>
            <tr>
                <th>اسم العمود</th>
                <th>الوصف</th>
                <th>مثال</th>
            </tr>
            <tr>
                <td>كود الصنف</td>
                <td>معرّف الصنف كما هو مخزن بقاعدة البيانات</td>
                <td>01041200076147</td>
            </tr>
            <tr>
                <td>اسم الصنف</td>
                <td>اسم توضيحي للصنف (اختياري عند التحديث)</td>
                <td>حوائط مط ...</td>
            </tr>
            <tr>
                <td>الوحدة</td>
                <td>مثل: متر / كرتونة … (اختياري)</td>
                <td>متر</td>
            </tr>
            <tr>
                <td>الرصيد</td>
                <td>الكمية الحالية (عدد/متر/…)</td>
                <td>288.72</td>
            </tr>
        </table>
    </div>
</div>

</body>
</html>
