<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دخول</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container { max-width: 400px; width: 100%; }
        .box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
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
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: Arial;
            font-size: 14px;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }
        .btn {
            width: 100%;
            padding: 12px;
            background-color: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn:hover { background-color: #5568d3; }
        .error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }
        .alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .links {
            text-align: center;
            margin-top: 20px;
        }
        .links a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }
        .links a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <div class="box">
        <h2>🔐 دخول</h2>

        @if ($errors->any())
            <div class="alert">
                <strong>خطأ!</strong>
                <ul style="margin: 10px 0; padding-right: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" placeholder="example@test.com" required value="{{ old('email') }}">
            @if ($errors->has('email'))
                <span class="error">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <div class="form-group">
            <label>كلمة المرور</label>
            <input type="password" name="password" placeholder="••••••••" required>
            @if ($errors->has('password'))
                <span class="error">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="remember"> تذكرني
            </label>
        </div>

        <button type="submit" class="btn">دخول</button>

        </form>

        <div class="links">
            <p>ليس لديك حساب؟ <a href="{{ route('register') }}">إنشاء حساب جديد</a></p>
        </div>
    </div>
</div>

</body>
</html>
