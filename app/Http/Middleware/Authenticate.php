<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            if ($request->routeIs('admin.*') || str_starts_with($request->path(), 'admin')) {
                return route('admin.login');
            }

            return route('login');
        }

        return null;
    }
}
