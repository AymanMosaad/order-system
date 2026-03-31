<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (Auth::user()->role == 'super_admin') {
            return $next($request);
        }

        return redirect('/')->with('error', 'غير مصرح لك بالدخول - هذه الصفحة مخصصة للمدير العام فقط');
    }
}
