<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCanManageKeuangan
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check() || ! Auth::user()->canManageKeuangan()) {
            abort(403, 'Anda tidak memiliki izin untuk mengelola keuangan.');
        }

        return $next($request);
    }
}
