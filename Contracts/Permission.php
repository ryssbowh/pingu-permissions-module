<?php

namespace Pingu\Permissions\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Permission
{
    /**
     * A permission can be applied to roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles();

    /**
     * Find a permission by its name.
     *
     * @param string      $name
     * @param string|null $guardName
     *
     * @return Permission
     */
    public static function findByName(string $name, $guardName = null);

    /**
     * Find a permission by its id.
     *
     * @param int $id
     *
     * @return Permission
     */
    public static function findById(int $id);

    /**
     * Find or Create a permission by its name and guard name.
     *
     * @param string      $name
     * @param string|null $guardName
     *
     * @return Permission
     */
    public static function findOrCreate(array $attributes);
}