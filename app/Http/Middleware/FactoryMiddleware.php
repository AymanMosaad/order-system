<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FactoryMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (Auth::user()->role == 'factory' || Auth::user()->role == 'super_admin') {
            return $next($request);
        }

        return redirect('/')->with('error', 'غير مصرح لك بالدخول - هذه الصفحة مخصصة للمصنع');
    }
}
