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
            gap: 8px;
        }

        .nav-link:hover {
            color: #ffc107 !important;
            background: rgba(255,255,255,0.1);
        }

        .nav-link i {
            font-size: 16px;
            width: 20px;
            text-align: center;
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

        /* تنسيق القائمة المنسدلة */
        .dropdown-menu {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 8px 0;
            margin-top: 5px;
            min-width: 240px;
        }

        .dropdown-item {
            color: rgba(255,255,255,0.85) !important;
            padding: 8px 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: rgba(255,255,255,0.1);
            color: #ffc107 !important;
        }

        .dropdown-item i {
            width: 24px;
            font-size: 14px;
            text-align: center;
        }

        .dropdown-divider {
            background-color: rgba(255,255,255,0.1);
            margin: 5px 0;
        }

        .dropdown-header {
            color: #ffc107 !important;
            font-size: 12px;
            padding: 8px 20px;
            font-weight: bold;
        }

        /* ===== تحسينات الموبايل ===== */
        @media (max-width: 768px) {
            body {
                padding-top: 60px;
            }

            .navbar-brand {
                font-size: 1rem;
            }

            .navbar-brand span {
                display: inline-block;
            }

            .navbar-toggler {
                border: none;
                padding: 4px 8px;
            }

            .navbar-toggler:focus {
                box-shadow: none;
            }

            .navbar-collapse {
                background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
                border-radius: 12px;
                margin-top: 12px;
                padding: 8px 0;
                max-height: 80vh;
                overflow-y: auto;
            }

            .nav-link {
                padding: 12px 20px !important;
                margin: 2px 8px;
                border-radius: 10px;
                justify-content: flex-start;
            }

            .nav-link i {
                font-size: 18px;
                width: 28px;
            }

            .nav-item {
                width: 100%;
            }

            .logout-btn {
                margin-top: 8px;
                border-width: 2px;
                justify-content: center;
            }

            .dropdown-menu {
                background: rgba(0,0,0,0.3);
                border: none;
                padding: 0;
                margin: 0;
                position: static !important;
                transform: none !important;
                width: 100%;
            }

            .dropdown-item {
                padding: 12px 20px 12px 40px;
                font-size: 13px;
            }

            .dropdown-toggle::after {
                float: left;
                margin-top: 8px;
            }

            .dropdown-header {
                padding: 10px 20px;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .nav-link {
                padding: 8px 12px !important;
                font-size: 13px;
            }

            .nav-link i {
                font-size: 14px;
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
                    <!-- ===== روابط سريعة للجميع ===== -->
                    @if(Auth::user()->role != 'accountant')
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
                    @endif

                    <!-- ===== روابط للمصنع فقط ===== -->
                    @if(Auth::user()->role == 'factory')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('factory.orders') }}">
                                <i class="fas fa-industry"></i>
                                <span>طلبيات المصنع</span>
                            </a>
                        </li>
                    @endif

                    <!-- ===== روابط الملف الشخصي (معلق مؤقتاً - سيتم تفعيله لاحقاً) ===== -->
                    {{--
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i>
                            <span>حسابي</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.password.edit') }}"><i class="fas fa-key"></i> تغيير كلمة المرور</a></li>
                        </ul>
                    </li>
                    --}}

                    <!-- ========================================== -->
                    <!-- القائمة الأولى: المحاسبة والعملاء والمسحوبات -->
                    <!-- ========================================== -->
                    @if(in_array(Auth::user()->role, ['super_admin', 'accountant']))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="accountingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-chart-line"></i>
                                <span>المحاسبة</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountingDropdown">
                                <li><a class="dropdown-item" href="{{ route('accounting.dashboard') }}"><i class="fas fa-tachometer-alt"></i> لوحة المحاسب</a></li>
                                <li><a class="dropdown-item" href="{{ route('accounting.customers') }}"><i class="fas fa-users"></i> العملاء</a></li>
                                <li><a class="dropdown-item" href="{{ route('accounting.cheques') }}"><i class="fas fa-money-check"></i> الشيكات</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('accounting.customers') }}"><i class="fas fa-hand-holding-usd"></i> مسحوبات العملاء</a></li>
                                @if(Auth::user()->role == 'super_admin')
                                    <li><a class="dropdown-item" href="{{ route('import.withdrawals.form') }}"><i class="fas fa-file-import"></i> استيراد مسحوبات</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    <!-- ========================================== -->
                    <!-- القائمة الثانية: الأصناف والمخزون والتقارير -->
                    <!-- ========================================== -->
                    @if(in_array(Auth::user()->role, ['super_admin', 'sales_manager']))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="productsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-boxes"></i>
                                <span>الأصناف والمخزون</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="productsDropdown">
                                <li><a class="dropdown-item" href="{{ route('products.index') }}"><i class="fas fa-list"></i> قائمة الأصناف</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.products') }}"><i class="fas fa-dollar-sign"></i> إدارة الأسعار</a></li>
                                <li><a class="dropdown-item" href="{{ route('products.stockReport') }}"><i class="fas fa-warehouse"></i> تقرير الرصيد</a></li>
                                <li><a class="dropdown-item" href="{{ route('products.gradeReport') }}"><i class="fas fa-chart-bar"></i> تقرير الفرز</a></li>
                                <li><a class="dropdown-item" href="{{ route('products.stockDashboard') }}"><i class="fas fa-chart-simple"></i> لوحة المخزون</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('stock.check.low') }}"><i class="fas fa-exclamation-triangle"></i> فحص المخزون المنخفض</a></li>
                            </ul>
                        </li>

                        <!-- ===== قائمة منفصلة للتقارير المتقدمة ===== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-chart-line"></i>
                                <span>التقارير</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="reportsDropdown">
                                <li><a class="dropdown-item" href="{{ route('orders.advancedReport') }}"><i class="fas fa-chart-line"></i> تقرير متقدم</a></li>
                                <li><a class="dropdown-item" href="{{ route('orders.report') }}"><i class="fas fa-chart-pie"></i> تقرير المبيعات</a></li>
                                <li><a class="dropdown-item" href="{{ route('products.movementReport') }}"><i class="fas fa-exchange-alt"></i> حركة الأصناف</a></li>
                                <li><a class="dropdown-item" href="{{ route('products.salesSummary') }}"><i class="fas fa-chart-simple"></i> ملخص المبيعات</a></li>
                            </ul>
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

                    <!-- ===== روابط الإدارة للمدير العام فقط ===== -->
                    @if(Auth::user()->role == 'super_admin')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-crown"></i>
                                <span>الإدارة</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> لوحة المدير</a></li>
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fas fa-list-alt"></i> كل الطلبات</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('products.importPage') }}"><i class="fas fa-file-import"></i> استيراد أصناف</a></li>
                                <li><a class="dropdown-item" href="{{ route('products.downloadTemplate') }}"><i class="fas fa-download"></i> تحميل قالب</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="fas fa-users-cog"></i> إدارة المستخدمين</a></li>
                            </ul>
                        </li>
                    @endif

                    <!-- ===== روابط للمحاسب فقط ===== -->
                    @if(Auth::user()->role == 'accountant')
                        <li class="nav-item">
                            <a class="nav-link notifications-icon" href="{{ route('notifications.index') }}">
                                <i class="fas fa-bell"></i>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <span class="notifications-badge">{{ Auth::user()->unreadNotifications->count() }}</span>
                                @endif
                            </a>
                        </li>
                    @endif

                    <!-- ===== زر الخروج ===== -->
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

<script>
    // التأكد من تحميل Bootstrap بشكل صحيح
    document.addEventListener('DOMContentLoaded', function() {
        // إغلاق القائمة للموبايل
        if (window.innerWidth < 768) {
            const navLinks = document.querySelectorAll('.nav-link:not(.dropdown-toggle)');
            const navbarCollapse = document.getElementById('navbarMain');

            navLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                    if (bsCollapse) {
                        bsCollapse.hide();
                    }
                });
            });
        }

        // حل مشكلة القوائم المنسدلة - إعادة تهيئة Bootstrap dropdown
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        dropdownElementList.map(function(dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
