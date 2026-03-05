<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    /**
     * Allow only super admin (user ID 1 in admin guard).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = auth('admin')->user();

        if (!$admin || (int) $admin->id !== 1) {
            abort(403, 'Only super admin can access this module.');
        }

        return $next($request);
    }
}
