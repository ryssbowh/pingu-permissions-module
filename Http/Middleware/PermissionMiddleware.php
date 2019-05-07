<?php

namespace Pingu\Permissions\Middleware;

use Closure, Notify;
use Pingu\Permissions\Exceptions\UnauthorizedException;

class PermissionMiddleware
{
    public function handle($request, Closure $next, $permission)
    {
        if (app('auth')->guest()) {
            Notify::warning('Please login to access this page');
            session(['redirect' => $request->path()]);
            return redirect()->route('user.login');
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $permission) {
            if (app('auth')->user()->can($permission)) {
                return $next($request);
            }
        }

        throw UnauthorizedException::forPermissions($permissions);
    }
}
