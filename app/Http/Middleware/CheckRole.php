<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check() || !in_array(auth()->user()->role->name, $roles)) {
            // إعادة التوجيه للصفحة السابقة مع رسالة خطأ
            return redirect()->back()->with('error', 'ليس لديك صلاحية الدخول.');
        }

        return $next($request);
    }
}
