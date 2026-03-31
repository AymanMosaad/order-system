<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>إضافة صنف جديد - جلوريا للسيراميك</title>
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
        .container { max-width: 700px; margin: 0 auto; }

        /* هيدر الصفحة */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }
        .page-header h1 {
            margin: 0 0 5px 0;
            font-size: 24px;
        }
        .page-header p {
            margin: 0;
            opacity: 0.85;
            font-size: 14px;
        }

        /* كارد الفورم */
        .form-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        /* حقول الإدخال */
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
            font-size: 14px;
        }
        label i {
            margin-left: 6px;
            color: #007bff;
        }
        input, select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            font-size: 14px;
            transition: all 0.3s;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        }
        input:hover, select:hover {
            border-color: #007bff;
        }

        /* زر الحفظ */
        .btn-save {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            transition: all 0.3s;
            margin-top: 10px;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,123,255,0.3);
        }

        /* تنبيهات الأخطاء */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-danger ul {
            margin: 10px 0 0 0;
            padding-right: 20px;
        }
        .required {
            color: #dc3545;
            margin-right: 3px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body { padding: 10px; padding-top: 80px; }
            .form-card { padding: 20px; }
            .page-header h1 { font-size: 20px; }
            .page-header p { font-size: 12px; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-plus-circle"></i> إضافة صنف جديد</h1>
        <p>أدخل بيانات الصنف الجديد</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong><i class="fas fa-exclamation-triangle"></i> خطأ!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-times-circle"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <form method="POST" action="{{ route('products.store') }}">
            @csrf

            <div class="form-group">
                <label><i class="fas fa-barcode"></i> كود الصنف <span class="required">*</span></label>
                <input type="text" name="item_code" placeholder="مثال: GLR001" required value="{{ old('item_code') }}">
            </div>

            <div class="form-group">
                <label><i class="fas fa-tag"></i> النوع <span class="required">*</span></label>
                <select name="type" required>
                    <option value="">-- اختر النوع --</option>
                    <option value="حوائط جلوريا" {{ old('type') == 'حوائط جلوريا' ? 'selected' : '' }}>حوائط جلوريا</option>
                    <option value="حوائط ايكو" {{ old('type') == 'حوائط ايكو' ? 'selected' : '' }}>حوائط ايكو</option>
                    <option value="أرضيات جلوريا" {{ old('type') == 'أرضيات جلوريا' ? 'selected' : '' }}>أرضيات جلوريا</option>
                    <option value="أرضيات ايكو" {{ old('type') == 'أرضيات ايكو' ? 'selected' : '' }}>أرضيات ايكو</option>
                    <option value="HDC" {{ old('type') == 'HDC' ? 'selected' : '' }}>HDC</option>
                    <option value="UGC" {{ old('type') == 'UGC' ? 'selected' : '' }}>UGC</option>
                    <option value="بورسل" {{ old('type') == 'بورسل' ? 'selected' : '' }}>بورسل</option>
                    <option value="PORSLIM" {{ old('type') == 'PORSLIM' ? 'selected' : '' }}>PORSLIM</option>
                    <option value="SUPER GLOSSY" {{ old('type') == 'SUPER GLOSSY' ? 'selected' : '' }}>SUPER GLOSSY</option>
                </select>
            </div>

            <div class="form-group">
                <label><i class="fas fa-font"></i> اسم الصنف <span class="required">*</span></label>
                <input type="text" name="name" placeholder="اسم الصنف الكامل" required value="{{ old('name') }}">
            </div>

            <div class="form-group">
                <label><i class="fas fa-palette"></i> اللون</label>
                <input type="text" name="color" placeholder="اللون" value="{{ old('color') }}">
            </div>

            <div class="form-group">
                <label><i class="fas fa-ruler"></i> المقاس</label>
                <input type="text" name="size" placeholder="المقاس" value="{{ old('size') }}">
            </div>

            <div class="form-group">
                <label><i class="fas fa-warehouse"></i> الرصيد الحالي <span class="required">*</span></label>
                <input type="number" name="current_stock" placeholder="0" min="0" step="0.01" required value="{{ old('current_stock', 0) }}">
            </div>

            <div class="form-group">
                <label><i class="fas fa-bell"></i> الحد الأدنى للتنبيه <span class="required">*</span></label>
                <input type="number" name="min_stock" placeholder="50" min="0" required value="{{ old('min_stock', 50) }}">
            </div>

            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> حفظ الصنف
            </button>
        </form>
    </div>
</div>

</body>
</html>
