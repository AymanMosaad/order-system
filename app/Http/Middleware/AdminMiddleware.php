<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // التحقق من أن المستخدم مسجل دخول وهو مدير أو محاسب
        if (Auth::check() && in_array(Auth::user()->role, ['super_admin', 'sales_manager', 'accountant'])) {
            return $next($request);
        }

        return redirect('/')->with('error', 'غير مصرح لك بالدخول إلى هذه الصفحة');
    }
}
