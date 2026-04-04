<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // التحقق من أن المستخدم مسجل دخول ودوره محاسب
        if (Auth::check() && Auth::user()->role == 'accountant') {
            return $next($request);
        }

        return redirect()->route('admin.dashboard')->with('error', 'غير مصرح لك - هذه الصفحة للمحاسب فقط');
    }
}
