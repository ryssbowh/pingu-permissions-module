<?php

namespace Pingu\Permissions\Console;

use Illuminate\Console\Command;
use Pingu\Permissions\Contracts\Permission;
use Pingu\Permissions\Contracts\Role;

class CreateRole extends Command
{
    protected $signature = 'permissions:create-role
        {name : The name of the role}
        {guard? : The name of the guard}
        {permissions? : A list of permissions to assign to the role, separated by | }';

    protected $description = 'Create a role';

    public function handle()
    {
        $role = Role::findOrCreate($this->argument('name'), $this->argument('guard'));

        $role->givePermissionTo($this->makePermissions($this->argument('permissions')));

        $this->info("Role `{$role->name}` created");
    }

    protected function makePermissions($string = null)
    {
        if (empty($string)) {
            return;
        }

        $permissions = explode('|', $string);

        $models = [];

        foreach ($permissions as $permission) {
            $models[] = Permission::findOrCreate(['name' => trim($permission), 'guard' => $this->argument('guard')]);
        }

        return collect($models);
    }
}
