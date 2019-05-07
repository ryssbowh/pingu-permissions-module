<?php 

namespace Pingu\Permissions;

use Cache;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Gate;
use Pingu\Permissions\Entities\Permission;

class Permissions
{
	/** @var \Illuminate\Contracts\Auth\Access\Gate */
    protected $gate;

    /** @var \Illuminate\Support\Collection */
    protected $permissions;

	public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    public function flushCache()
    {
        $this->permissions = null;
        Cache::forget(config('permissions.cache-key'));
    }

    /**
     * Register the permission check method on the gate.
     *
     * @return bool
     */
    public function registerPermissions(): bool
    {
        $this->gate->before(function (Authorizable $user, string $ability) {
            if($user->id == 1) return true;

            try {
                if (method_exists($user, 'hasPermissionTo')) {
                    return $user->hasPermissionTo($ability) ?: null;
                }
            } catch (PermissionDoesNotExist $e) {
            }
        });
        return true;
    }

    /**
     * Get the permissions based on the passed params.
     *
     * @param array $params
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPermissions(array $params = [])
    {
        if ($this->permissions === null) {
            $this->permissions = Cache::rememberForever(config('permissions.cache-key'), function() {
                return Permission::get();
            });
        }

        $permissions = clone $this->permissions;

        foreach ($params as $attr => $value) {
            $permissions = $permissions->where($attr, $value);
        }

        return $permissions;
    }

}