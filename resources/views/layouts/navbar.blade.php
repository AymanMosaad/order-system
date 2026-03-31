<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="theme-color" content="#333">
    <title>جلوريا للسيراميك - @yield('title', 'نظام إدارة الطلبيات')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', 'Tahoma', 'Arial', sans-serif;
            direction: rtl;
            background-color: #f8f9fa;
            padding-top: 70px;
        }

        /* تخصيص الـ Navbar */
        .navbar-custom {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 10px 0;
        }

        .navbar-brand {
            font-size: 1.3rem;
            font-weight: bold;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-brand:hover {
            color: #ffc107 !important;
        }

        .nav-link {
            color: rgba(255,255,255,0.85) !important;
            font-size: 14px;
            padding: 8px 16px !important;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-link:hover {
            color: #ffc107 !important;
            background: rgba(255,255,255,0.1);
        }

        .nav-link i {
            font-size: 16px;
        }

        /* روابط المدير */
        .admin-link {
            border-right: 2px solid #ffc107;
            margin-right: 5px;
        }

        /* زر الخروج */
        .logout-btn {
            background: rgba(220,53,69,0.2);
            border: 1px solid #dc3545;
            color: #ff6b6b !important;
        }

        .logout-btn:hover {
            background: #dc3545;
            color: white !important;
        }

        /* أيقونة الإشعارات */
        .notifications-icon {
            position: relative;
        }

        .notifications-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
            min-width: 18px;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding-top: 60px;
            }

            .nav-link {
                padding: 10px 16px !important;
                justify-content: center;
            }

            .admin-link {
                border-right: none;
                border-top: 2px solid #ffc107;
                margin-top: 5px;
                padding-top: 10px !important;
            }

            .navbar-brand {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container-fluid">
        <!-- الشعار -->
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-tile"></i>
            <span>جلوريا للسيراميك</span>
        </a>

        <!-- زر القائمة للموبايل -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars" style="color: white; font-size: 24px;"></i>
        </button>

        <!-- روابط الـ Navbar -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                @auth
                    <!-- ===== روابط مشتركة للجميع (كل المستخدمين) ===== -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orders.create') }}">
                            <i class="fas fa-plus-circle"></i>
                            <span>طلب جديد</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orders.userDashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>لوحتي</span>
                        </a>
                    </li>

                    <!-- ===== روابط للمصنع فقط (factory) ===== -->
                    @if(Auth::user()->role == 'factory')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('factory.orders') }}">
                                <i class="fas fa-industry"></i>
                                <span>طلبيات المصنع</span>
                            </a>
                        </li>
                    @endif

                    <!-- ===== روابط للمدير العام ومدير المبيعات ===== -->
                    @if(in_array(Auth::user()->role, ['super_admin', 'sales_manager']))
                        <li class="nav-item">
                            <a class="nav-link admin-link" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-crown"></i>
                                <span>لوحة المدير</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('orders.index') }}">
                                <i class="fas fa-list-alt"></i>
                                <span>كل الطلبات</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.index') }}">
                                <i class="fas fa-boxes"></i>
                                <span>الأصناف</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('orders.advancedReport') }}">
                                <i class="fas fa-chart-line"></i>
                                <span>تقرير متقدم</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('orders.report') }}">
                                <i class="fas fa-chart-pie"></i>
                                <span>تقرير المبيعات</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.stockReport') }}">
                                <i class="fas fa-warehouse"></i>
                                <span>تقرير الرصيد</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.gradeReport') }}">
                                <i class="fas fa-chart-bar"></i>
                                <span>تقرير الفرز</span>
                            </a>
                        </li>

                        <!-- أيقونة الإشعارات -->
                        <li class="nav-item">
                            <a class="nav-link notifications-icon" href="{{ route('notifications.index') }}">
                                <i class="fas fa-bell"></i>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <span class="notifications-badge">{{ Auth::user()->unreadNotifications->count() }}</span>
                                @endif
                            </a>
                        </li>
                    @endif

                    <!-- ===== روابط للمدير العام فقط (super_admin) ===== -->
                    @if(Auth::user()->role == 'super_admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.importPage') }}">
                                <i class="fas fa-file-import"></i>
                                <span>استيراد</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.downloadTemplate') }}">
                                <i class="fas fa-download"></i>
                                <span>قالب</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.users.index') }}">
                                <i class="fas fa-users-cog"></i>
                                <span>إدارة المستخدمين</span>
                            </a>
                        </li>
                    @endif

                    <!-- ===== زر الخروج المعدل (للجميع) ===== -->
                    <li class="nav-item">
                        <a href="{{ route('logout.get') }}" class="nav-link logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> خروج
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @else
                    <!-- ===== روابط لغير المسجلين ===== -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>دخول</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fas fa-user-plus"></i>
                            <span>تسجيل</span>
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
