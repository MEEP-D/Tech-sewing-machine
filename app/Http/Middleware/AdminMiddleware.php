<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Filament::auth()->user() ?? auth()->user();

        if (! $user || ! $user->is_admin) {
            abort(403, 'Bạn không có quyền truy cập trang quản trị.');
        }

        return $next($request);
    }
}
