<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صنف جديد</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            margin: 0;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container { max-width: 600px; margin: 0 auto; }
        h2 { text-align: center; color: #333; }
        .box {
            background-color: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-family: Arial;
            font-size: 14px;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0,123,255,0.3);
        }
        .btn {
            padding: 12px 24px;
            margin-top: 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        .btn-save {
            background-color: #007bff;
            color: white;
            width: 100%;
        }
        .btn-save:hover { background-color: #0056b3; }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .required { color: red; }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <h2>➕ إضافة صنف جديد</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>خطأ!</strong>
            <ul style="margin: 10px 0; padding-right: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="box">
        <form method="POST" action="{{ route('products.store') }}">
        @csrf

        <div class="form-group">
            <label>كود الصنف <span class="required">*</span></label>
            <input type="text" name="item_code" placeholder="مثال: GLR001" required value="{{ old('item_code') }}">
        </div>

        <div class="form-group">
            <label>النوع <span class="required">*</span></label>
            <select name="type" required>
                <option value="">-- اختر النوع --</option>
                <option value="حوائط جلوريا" {{ old('type') == 'حوائط جلوريا' ? 'selected' : '' }}>حوائط جلوريا</option>
                <option value="حوائط ايكو" {{ old('type') == 'حوائط ايكو' ? 'selected' : '' }}>حوائط ايكو</option>
                <option value="ارضيات ايكو" {{ old('type') == 'ارضيات ايكو' ? 'selected' : '' }}>ارضيات ايكو</option>
                <option value="ارضيات HDC" {{ old('type') == 'ارضيات HDC' ? 'selected' : '' }}>ارضيات HDC</option>
                <option value="ارضيات UGC" {{ old('type') == 'ارضيات UGC' ? 'selected' : '' }}>ارضيات UGC</option>
                <option value="PORSLIM" {{ old('type') == 'PORSLIM' ? 'selected' : '' }}>PORSLIM</option>
                <option value="SUPER GLOSSY" {{ old('type') == 'SUPER GLOSSY' ? 'selected' : '' }}>SUPER GLOSSY</option>
            </select>
        </div>

        <div class="form-group">
            <label>اسم الصنف <span class="required">*</span></label>
            <input type="text" name="name" placeholder="اسم الصنف الكامل" required value="{{ old('name') }}">
        </div>

        <div class="form-group">
            <label>اللون</label>
            <input type="text" name="color" placeholder="اللون" value="{{ old('color') }}">
        </div>

        <div class="form-group">
            <label>المقاس</label>
            <input type="text" name="size" placeholder="المقاس" value="{{ old('size') }}">
        </div>

        <div class="form-group">
            <label>الرصيد الحالي <span class="required">*</span></label>
            <input type="number" name="current_stock" placeholder="0" min="0" required value="{{ old('current_stock', 0) }}">
        </div>

        <div class="form-group">
            <label>الحد الأدنى للتنبيه <span class="required">*</span></label>
            <input type="number" name="min_stock" placeholder="50" min="0" required value="{{ old('min_stock', 50) }}">
        </div>

        <button type="submit" class="btn btn-save">💾 حفظ الصنف</button>

        </form>
    </div>

</div>

</body>
</html>
