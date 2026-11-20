<?php

namespace App\Http\Controllers\Platform\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        if (Auth::guard('platform_admin')->check()) {
            return redirect()->route('platform.dashboard');
        }

        return view('platform.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::guard('platform_admin')->attempt($credentials, $remember)) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password tidak sesuai.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $admin = Auth::guard('platform_admin')->user();

        if ($admin->status !== 'active') {
            Auth::guard('platform_admin')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Akun Platform Admin tidak aktif.',
            ]);
        }

        $admin->forceFill([
            'last_login_at' => now(),
        ])->save();

        return redirect()->intended(
            route('platform.dashboard')
        );
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('platform_admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('platform.login');
    }
}
