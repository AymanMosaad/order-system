<!-- Navigation Bar -->
<nav style="background-color:#333; color:white; padding:15px; font-family:Arial; direction:rtl;">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <!-- الشعار / العنوان -->
        <div style="font-weight:bold; font-size:18px;">
            🏭 جلوريا للسيراميك والبورسلين
        </div>

        <!-- الروابط -->
        <div style="display:flex; gap:15px; align-items:center; flex-wrap:wrap;">
            @auth
                <!-- الطلبات -->
                <a href="{{ route('orders.index') }}" style="color:white; text-decoration:none;">📋 كل الطلبات</a>
                <a href="{{ route('orders.create') }}" style="color:white; text-decoration:none;">➕ طلب جديد</a>
                <a href="{{ route('products.index') }}" style="color:white; text-decoration:none;">📦 الأصناف</a>
                <a href="{{ route('orders.report') }}" style="color:white; text-decoration:none;">📊 التقارير</a>

                <!-- الاستيراد والقالب -->
                <a href="{{ route('products.importPage') }}" style="color:white; text-decoration:none;">⬆️ استيراد</a>
                <a href="{{ route('products.downloadTemplate') }}" style="color:white; text-decoration:none;">⬇️ قالب</a>

                <!-- لوحة المستخدم (إن كانت موجودة عندك) -->
                <a href="{{ route('orders.userDashboard') }}" style="color:white; text-decoration:none;">👤 لوحتي</a>

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
