<?php 

namespace Pingu\Permissions;

use Illuminate\Contracts\Auth\Access\Gate;
use Pingu\Entities\Permission;

class Permissions
{
	/** @var \Illuminate\Contracts\Auth\Access\Gate */
    protected $gate;

    /** @var \Illuminate\Support\Collection */
    protected $permissions;

	public function __construct(Gate $gate)
    {
        $this->gate = $gate;
        $this->loadPermissions();
    }

    public function loadPermissions()
    {
    	$this->permissions = $this->cache->rememberForever(config('permissions.cache-key'), function() {
            return Permission::all();
        });
    }

    public function flushCache()
    {
        $this->permissions = null;
        $this->cache->forget(config('permissions.cache-key'));
    }

    /**
     * Register the permission check method on the gate.
     *
     * @return bool
     */
    public function registerPermissions(): bool
    {
        $this->gate->before(function (Authorizable $user, string $ability) {
            try {
                if (method_exists($user, 'hasPermissionTo')) {
                    return $user->hasPermissionTo($ability) ?: null;
                }
            } catch (PermissionDoesNotExist $e) {
            }
        });
        return true;
    }

}