<?php

namespace Pingu\Permissions\Entities;

use Permissions;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Traits\HasCrudUris;
use Pingu\Permissions\Contracts\Permission as PermissionContract;
use Pingu\Permissions\Events\PermissionCacheChanged;
use Pingu\Permissions\Exceptions\PermissionDoesNotExist;
use Pingu\Permissions\Guard;
use Pingu\User\Entities\Role;
use Pingu\User\Entities\User;

class Permission extends BaseModel implements PermissionContract
{
   
    protected $fillable = ['name', 'guard', 'section'];

    protected $dispatchesEvents = [
        'saved' => PermissionCacheChanged::class,
        'deleted' => PermissionCacheChanged::class
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Find a permission by its name (and optionally guardName).
     *
     * @param string      $name
     * @param string|null $guardName
     *
     * @return \Pingu\Permissions\Contracts\Permission
     */
    public static function findByName(string $name, $guardName = null)
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        return Permissions::getByName($name, $guardName);
    }
    /**
     * Find a permission by its id (and optionally guardName).
     *
     * @param int         $id
     * @param string|null $guardName
     *
     * @return \Pingu\Permissions\Contracts\Permission
     */
    public static function findById(int $id)
    {
        return Permissions::getById($id);
    }
    
    /**
     * Find or create permission by its name (and optionally guardName).
     *
     * @param array $attributes
     *
     * @return \Pingu\Permissions\Contracts\Permission
     */
    public static function findOrCreate(array $attributes)
    {
        $attributes['guard'] = $attributes['guard'] ?? Guard::getDefaultName(static::class);
        $permission = static::where(['name' => $attributes['name'], 'guard' => $attributes['guard']])->first();
        if (!$permission) {
            $permission = static::create($attributes);
        }
        return $permission;
    }

    /**
     * Give default guard name
     *
     * @param  array $attributes
     * @return bool
     */
    public function save(array $options = [])
    {
        $this->attributes['guard'] = $this->attributes['guard'] ?? Guard::getDefaultName(static::class);
        return parent::save($options);
    }

    /**
     * Check if the given role has this permission
     *
     * @param  Role $role
     * @return bool
     */
    public function roleHasPermission(Role $role)
    {
        if($role->id == 1) { return true;
        }
        return Permissions::roleHasPermission($role, $this);
    }

}