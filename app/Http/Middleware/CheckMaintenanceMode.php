<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mode maintenance dari Admin > Settings: toko ditutup untuk pengunjung,
 * tapi admin tetap bisa login dan membuka panel admin.
 */
class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $enabled = (bool) Setting::get('maintenance_mode', false);
        } catch (\Throwable $e) {
            $enabled = false;
        }

        if (!$enabled || $request->user()?->is_admin) {
            return $next($request);
        }

        if ($request->is('admin', 'admin/*', 'login', 'logout', 'forgot-password', 'reset-password*', 'up')) {
            return $next($request);
        }

        abort(503, 'Toko sedang dalam perbaikan. Silakan kembali beberapa saat lagi.');
    }
}
