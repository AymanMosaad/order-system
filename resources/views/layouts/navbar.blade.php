<!-- Navigation Bar -->
<nav style="background-color:#333; color:white; padding:15px; font-family:Arial; direction:rtl;">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <!-- الشعار / العنوان -->
        <div style="font-weight:bold; font-size:18px;">
            <a href="{{ route('home') }}" style="color:white; text-decoration:none;">🏭 جلوريا للسيراميك والبورسلين</a>
        </div>

        <!-- الروابط -->
        <div style="display:flex; gap:15px; align-items:center; flex-wrap:wrap;">
            @auth
                <!-- روابط مشتركة للجميع (المستخدم العادي والمدير) -->
                <a href="{{ route('orders.create') }}" style="color:white; text-decoration:none;">➕ طلب جديد</a>
                <a href="{{ route('orders.userDashboard') }}" style="color:white; text-decoration:none;">👤 لوحتي</a>

                <!-- روابط خاصة بالمدير فقط -->
                @if(Auth::user()->is_admin == 1)
                    <a href="{{ route('admin.dashboard') }}" style="color:white; text-decoration:none;">👑 لوحة المدير</a>
                    <a href="{{ route('orders.index') }}" style="color:white; text-decoration:none;">📋 كل الطلبات</a>
                    <a href="{{ route('products.index') }}" style="color:white; text-decoration:none;">📦 الأصناف</a>
                    <a href="{{ route('orders.advancedReport') }}" style="color:white; text-decoration:none;">📊 تقرير متقدم</a>
                    <a href="{{ route('orders.report') }}" style="color:white; text-decoration:none;">📈 تقرير الأصناف</a>
                    <a href="{{ route('products.stockReport') }}" style="color:white; text-decoration:none;">📊 تقرير الرصيد</a>
                    <a href="{{ route('products.importPage') }}" style="color:white; text-decoration:none;">⬆️ استيراد</a>
                    <a href="{{ route('products.downloadTemplate') }}" style="color:white; text-decoration:none;">⬇️ قالب</a>

                    <!-- ========== أيقونة الإشعارات ========== -->
                    <div style="position: relative; display: inline-block;">
                        <a href="{{ route('notifications.index') }}" style="color:white; text-decoration:none; position: relative;">
                            🔔
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <span style="position: absolute; top: -8px; right: -8px; background: #dc3545; color: white; border-radius: 50%; padding: 2px 6px; font-size: 10px; font-weight: bold;">
                                    {{ Auth::user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </a>
                    </div>
                @endif

                <!-- تسجيل الخروج (POST) -->
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit"
                            style="background:none; border:1px solid #fff; border-radius:4px; padding:6px 10px; color:white; cursor:pointer; font-size:14px;">
                        🚪 خروج
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" style="color:white; text-decoration:none;">🔐 دخول</a>
                <a href="{{ route('register') }}" style="color:white; text-decoration:none;">✍️ تسجيل</a>
            @endauth
        </div>
    </div>
</nav>
<hr style="margin:0;">
