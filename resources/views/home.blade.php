<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جلوريا للسيراميك والبورسلين</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Tahoma', Arial, sans-serif;
            direction: rtl;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
        }

        /* خلفية متحركة */
        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" opacity="0.1"><path fill="white" d="M20,20 L30,20 L25,30 Z M50,50 L60,50 L55,60 Z M80,80 L90,80 L85,90 Z"/></svg>');
            background-size: 30px;
            pointer-events: none;
        }

        .container {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .hero-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 100%;
            text-align: center;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-image {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px;
        }

        .hero-image h1 {
            color: white;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .hero-image p {
            color: rgba(255,255,255,0.9);
            font-size: 16px;
        }

        .hero-content {
            padding: 40px 30px;
        }

        .hero-content h2 {
            color: #333;
            margin-bottom: 15px;
            font-size: 24px;
        }

        .hero-content p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102,126,234,0.4);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102,126,234,0.5);
        }

        .btn-register {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-register:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .feature {
            text-align: center;
        }

        .feature-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .feature h4 {
            color: #333;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .feature p {
            font-size: 12px;
            color: #999;
            margin: 0;
        }

        @media (max-width: 480px) {
            .hero-image { padding: 30px; }
            .hero-content { padding: 30px 20px; }
            .btn { padding: 10px 20px; font-size: 14px; }
            .hero-image h1 { font-size: 24px; }
        }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>
    <div class="container">
        <div class="hero-card">
            <div class="hero-image">
                <h1>🏭 جلوريا</h1>
                <p>للسيراميك والبورسلين</p>
            </div>
            <div class="hero-content">
                <h2>مرحباً بك في نظام إدارة الطلبيات</h2>
                <p>نظام متكامل لإدارة الطلبيات والمخزون، يقدم تقارير دقيقة وإحصائيات لحظية لمتابعة الأداء والمبيعات.</p>

                <div class="btn-group">
                    <a href="{{ route('login') }}" class="btn btn-login">🔐 تسجيل الدخول</a>
                    <a href="{{ route('register') }}" class="btn btn-register">✍️ إنشاء حساب جديد</a>
                </div>

                <div class="features">
                    <div class="feature">
                        <div class="feature-icon">📦</div>
                        <h4>إدارة المخزون</h4>
                        <p>تتبع دقيق للأرصدة</p>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">📊</div>
                        <h4>تقارير فورية</h4>
                        <p>تحليل المبيعات</p>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">🔔</div>
                        <h4>إشعارات ذكية</h4>
                        <p>تنبيهات بالأصناف الناقصة</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
