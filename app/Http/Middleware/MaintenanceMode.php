<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        // Always allow admins and admin routes through
        if ($request->is('admin*') || $request->is('login') || $request->is('logout')) {
            return $next($request);
        }

        if (Setting::get('site_maintenance', '0') === '1') {
            if (auth()->check() && auth()->user()->is_admin) {
                return $next($request);
            }

            return response()->view('maintenance', [
                'message' => Setting::get('site_maintenance_msg', 'Voltamos em breve!'),
            ], 503);
        }

        return $next($request);
    }
}
