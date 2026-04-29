<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        $user = Auth::user();
   
        $adminRole = Role::where('id',$user->role_id)->first();
       
        // التحقق من صلاحيات المستخدم
        if ($adminRole->name=== 'admin') {
            return redirect()->route('admin.dashboard')->with('login_success', 'تم تسجيل الدخول بنجاح');
        } elseif ($adminRole->name === 'teacher') {
            return redirect()->route('teacher.dashboard')->with('login_success', 'تم تسجيل الدخول بنجاح');;
        } elseif ($adminRole->name === 'student') {
            return redirect()->route('student.dashboard')->with('login_success', 'تم تسجيل الدخول بنجاح');;
        } elseif ($adminRole->name === 'parent') {
            return redirect()->route('parent.dashboard')->with('login_success', 'تم تسجيل الدخول بنجاح');;
        }
        return redirect()->intended(RouteServiceProvider::HOME)->with('login_success', 'تم تسجيل الدخول بنجاح');;
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
