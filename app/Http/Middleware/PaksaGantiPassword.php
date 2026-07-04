<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Paksa user dengan wajib_ganti_password mengganti password sebelum akses panel.
 */
class PaksaGantiPassword
{
    /** @var list<string> */
    private const ROUTE_DIIZINKAN = [
        'panel.pengaturan.index',
        'panel.pengaturan.akun',
        'logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user?->wajib_ganti_password) {
            return $next($request);
        }

        if ($request->routeIs(self::ROUTE_DIIZINKAN)) {
            return $next($request);
        }

        return redirect()
            ->route('panel.pengaturan.index')
            ->with('warning', 'Silakan ganti password Anda terlebih dahulu sebelum melanjutkan.');
    }
}
