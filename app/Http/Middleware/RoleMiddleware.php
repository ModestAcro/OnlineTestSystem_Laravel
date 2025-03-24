<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Если пользователь не администратор
        if (Auth::guard('admin')->check() && $request->route()->getPrefix() != 'admin') {
            // Выход из системы
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Перенаправление на страницу логина
            return redirect('/login');
        }

        // Если авторизован как студент, тоже проверяем
        if (Auth::guard('student')->check() && $request->route()->getPrefix() != 'student') {
            // Выход из системы
            Auth::guard('student')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Перенаправление на страницу логина
            return redirect('/login');
        }

        return $next($request);
    }
}
