<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Penggunaan di routes:
     *   ->middleware('role:super_admin,admin_pp')
     *   ->middleware('role:super_admin,admin_pwm,admin_pdm,admin_pcm,admin_prm,admin_masjid')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!$request->user()->aktif) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['login' => 'Akun Anda tidak aktif.']);
        }

        if (!empty($roles) && !in_array($request->user()->role, $roles)) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
