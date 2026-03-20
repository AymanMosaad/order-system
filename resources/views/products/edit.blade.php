<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الصنف</title>
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
            margin-right: 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        .btn-save {
            background-color: #007bff;
            color: white;
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
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <h2>✏️ تعديل الصنف</h2>

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
        <form method="POST" action="{{ route('products.update', $product->id) }}">
        @csrf

        <div class="form-group">
            <label>كود الصنف</label>
            <input type="text" name="item_code" value="{{ $product->item_code }}" required>
        </div>

        <div class="form-group">
            <label>النوع</label>
            <select name="type" required>
                <option value="حوائط جلوريا" {{ $product->type == 'حوائط جلوريا' ? 'selected' : '' }}>حوائط جلوريا</option>
                <option value="حوائط ايكو" {{ $product->type == 'حوائط ايكو' ? 'selected' : '' }}>حوائط ايكو</option>
                <option value="ارضيات ايكو" {{ $product->type == 'ارضيات ايكو' ? 'selected' : '' }}>ارضيات ايكو</option>
                <option value="ارضيات HDC" {{ $product->type == 'ارضيات HDC' ? 'selected' : '' }}>ارضيات HDC</option>
                <option value="ارضيات UGC" {{ $product->type == 'ارضيات UGC' ? 'selected' : '' }}>ارضيات UGC</option>
                <option value="PORSLIM" {{ $product->type == 'PORSLIM' ? 'selected' : '' }}>PORSLIM</option>
                <option value="SUPER GLOSSY" {{ $product->type == 'SUPER GLOSSY' ? 'selected' : '' }}>SUPER GLOSSY</option>
            </select>
        </div>

        <div class="form-group">
            <label>اسم الصنف</label>
            <input type="text" name="name" value="{{ $product->name }}" required>
        </div>

        <div class="form-group">
            <label>اللون</label>
            <input type="text" name="color" value="{{ $product->color }}">
        </div>

        <div class="form-group">
            <label>المقاس</label>
            <input type="text" name="size" value="{{ $product->size }}">
        </div>

        <div class="form-group">
            <label>الحد الأدنى للتنبيه</label>
            <input type="number" name="min_stock" value="{{ $product->stock?->min_stock ?? 50 }}" min="0" required>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" {{ $product->is_active ? 'checked' : '' }}>
                الصنف فعال
            </label>
        </div>

        <button type="submit" class="btn btn-save">💾 حفظ التعديلات</button>
        <a href="{{ route('products.show', $product->id) }}" class="btn" style="background-color: #6c757d; color: white;">⬅️ إلغاء</a>

        </form>
    </div>

</div>

</body>
</html>
