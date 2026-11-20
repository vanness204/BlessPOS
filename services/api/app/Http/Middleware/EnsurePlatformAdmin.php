<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlatformAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth('platform_admin')->check()) {
            return redirect()->route('platform.login');
        }

        if (auth('platform_admin')->user()->status !== 'active') {
            auth('platform_admin')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('platform.login')
                ->withErrors([
                    'email' => 'Akun Platform Admin tidak aktif.',
                ]);
        }

        return $next($request);
    }
}
