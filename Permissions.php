<?php 

namespace Pingu\Permissions;

use Cache, Schema;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Str;
use Pingu\Content\Entities\ContentType;
use Pingu\Permissions\Entities\Permission;
use Pingu\Permissions\Exceptions\PermissionDoesNotExist;
use Pingu\User\Entities\Role;

class Permissions
{
    /** @var \Illuminate\Contracts\Auth\Access\Gate */
    protected $gate;
    protected $guestRole;

    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
        if (Schema::hasTable('roles')) {
            $this->guestRole = Role::find(config('user.guestRole'));
        }
    }

    /**
     * Get the entity on which to check permissions
     * can be the user if logged in or the guest role
     * 
     * @return User|Role
     */
    public function getPermissionableModel()
    {
        $model = \Auth::user();
        if (!$model) {
            return $this->guestRole;
        }
        return $model;
    }

    public function resolvePermissionable($permissionable)
    {
        if (is_null($permissionable)) {
            return $this->getPermissionableModel();
        }
        return $permissionable;
    }

    public function guestRole()
    {
        return $this->guestRole;
    }

    protected function resolveCache()
    {
        return Cache::rememberForever(config('permissions.cache-key'), function() {
            return Permission::get();
        });
    }

    public function flushCache()
    {
        Cache::forget(config('permissions.cache-key'));
    }

    /**
     * Register the permission check method on the gate.
     *
     * @return bool
     */
    public function registerPermissions(): bool
    {
        $this->gate->after(function (Authorizable $user, string $ability) {
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
    public function getPermissions(array $arguments = [])
    {
        $permissions = $this->resolveCache();
        foreach($arguments as $attr => $value){
            $permissions = $permissions->where($attr, $value);
        }
        return $permissions;
    }

    /**
     * Get one permission by name
     *
     * @param string $name
     * @throws  PermissionDoesNotExist
     * @return Permission
     */
    public function getByName(string $name, string $guard)
    {
        $perm = $this->getPermissions(['name' => $name, 'guard' => $guard])->first();
        if(!$perm){
            throw PermissionDoesNotExist::name($name, $guard);
        }
        return $perm;
    }

    /**
     * Get one permission by id
     * 
     * @param int $id
     * 
     * @throws PermissionDoesNotExist
     * @return Permission
     */
    public function getById(int $id)
    {
        $perm = $this->getPermissions(['id' => $id])->first();
        if (!$perm) {
            throw PermissionDoesNotExist::withId($id);
        }
        return $perm;
    }

    /**
     * Get permissions gropped by secton
     * @return Collection
     */
    public function getBySection()
    {
        return $this->resolveCache()->groupBy('section');
    }

}