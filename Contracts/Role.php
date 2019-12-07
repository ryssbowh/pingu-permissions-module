<?php

namespace Pingu\Permissions\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Role
{
    /**
     * A role may be given various permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions();

    /**
     * Find a role by its name and guard name.
     *
     * @param string      $name
     * @param string|null $guardName
     *
     * @return \Pingu\Permissions\Contracts\Role
     *
     * @throws \Spatie\Permission\Exceptions\RoleDoesNotExist
     */
    public static function findByName(string $name, $guardName): self;

    /**
     * Find a role by its id and guard name.
     *
     * @param int         $id
     * @param string|null $guardName
     *
     * @return \Pingu\Permissions\Contracts\Role
     *
     * @throws \Pingu\Permission\Exceptions\RoleDoesNotExist
     */
    public static function findById(int $id, $guardName): self;

    /**
     * Find or create a role by its name and guard name.
     *
     * @param string      $name
     * @param string|null $guardName
     *
     * @return \Pingu\Permissions\Contracts\Role
     */
    public static function findOrCreate(string $name, $guardName = null);

    /**
     * Determine if the user may perform the given permission.
     *
     * @param string|\Pingu\Permissions\Contracts\Permission $permission
     *
     * @return bool
     */
    public function hasPermissionTo($permission): bool;
}