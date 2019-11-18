<?php

namespace Pingu\Permissions\Middleware;

use Closure, Notify;
use Pingu\Permissions\Exceptions\UnauthorizedException;
use Pingu\User\Entities\Role;

class PermissionMiddleware
{
    public function redirect($request)
    {
        Notify::warning('Please login to access this page');
        session(['redirect' => $request->path()]);
        return redirect()->route('user.login');
    }

    public function handle($request, Closure $next, $permission)
    {
        $permissionsArray = is_array($permission)
            ? $permission
            : explode('|', $permission);

        $model = \Permissions::getPermissionableModel();

        $permissions = array_map(
            function ($name) use ($model) {
                return \Permissions::getByName($name, $model->guard);
            }, $permissionsArray
        );
        
        if (!$model->hasAllPermissions($permissions)) {
            if ($request->ajax()) {
                throw UnauthorizedException::forPermissions($permissionsArray);
            }
            return $this->redirect($request);
        }

        return $next($request);
    }
}
