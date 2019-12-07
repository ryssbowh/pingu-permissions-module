<?php

namespace Pingu\Permissions\Traits;

use Illuminate\Support\Collection;
use Permissions;
use Pingu\Permissions\Exceptions\PermissionDoesNotExist;

trait HasPermissionsThroughRoles
{
    /**
     * A model may have multiple direct permissions.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {  
        return Cache::rememberForever(
            config('permissions.cache-key'), function () {
                $out = collect();
                foreach($this->roles as $role){
                    $out = $out->merge($role->permissions);
                }
                return $out; 
            }
        );
    }

    /**
     * Determine if the model may perform the given permission.
     *
     * @param string|int|\Pingu\Permission\Contracts\Permission $permission
     * @param string|null                                       $guardName
     *
     * @return bool
     * @throws PermissionDoesNotExist
     */
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        foreach($this->roles as $role){
            if($role->hasPermissionTo($permission)) { return true;
            }
        }
        return false;
    }

    /**
     * An alias to hasPermissionTo(), but avoids throwing an exception.
     *
     * @param string|int|\Pingu\Permissions\Contracts\Permission $permission
     * @param string|null                                        $guardName
     *
     * @return bool
     */
    public function checkPermissionTo($permission, $guardName = null): bool
    {
        try {
            return $this->hasPermissionTo($permission, $guardName);
        } catch (PermissionDoesNotExist $e) {
            return false;
        }
    }

    /**
     * Determine if the model has any of the given permissions.
     *
     * @param array ...$permissions
     *
     * @return bool
     * @throws \Exception
     */
    public function hasAnyPermission(...$permissions): bool
    {
        if (is_array($permissions[0])) {
            $permissions = $permissions[0];
        }

        foreach ($permissions as $permission) {
            if ($this->checkPermissionTo($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the model has all of the given permissions.
     *
     * @param array ...$permissions
     *
     * @return bool
     * @throws \Exception
     */
    public function hasAllPermissions(...$permissions): bool
    {
        if (is_array($permissions[0])) {
            $permissions = $permissions[0];
        }

        foreach ($permissions as $permission) {
            if (! $this->hasPermissionTo($permission)) {
                return false;
            }
        }

        return true;
    }

    public function getPermissionNames(): Collection
    {
        return $this->permissions->pluck('name');
    }
}
