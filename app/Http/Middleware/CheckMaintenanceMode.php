<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SystemSetting;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $maintenance = SystemSetting::getValue('system_maintenance_mode', false);
        $user = Auth::user();

        if ($maintenance && (!$user || !$user->isSuperAdmin())) {
            return response()->view('maintenance');
        }

        return $next($request);
    }
}
