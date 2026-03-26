<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // التحقق من أن المستخدم مسجل دخول وهو مدير
        if (Auth::check() && Auth::user()->is_admin == 1) {
            return $next($request);
        }

        // إذا كان مستخدم عادي، يرجع للصفحة الرئيسية مع رسالة
        return redirect('/')->with('error', 'غير مصرح لك بالدخول إلى هذه الصفحة');
    }
}
