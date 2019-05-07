<?php

namespace Pingu\Permissions\Console;

use Illuminate\Console\Command;
use Pingu\Permissions\Contracts\Permission;

class CreatePermission extends Command
{
    protected $signature = 'permission:create-permission 
                {name : The name of the permission} 
                {guard? : The name of the guard}';

    protected $description = 'Create a permission';

    public function handle()
    {
        $permissionClass = app(Permission::class);

        $permission = $permissionClass::findOrCreate($this->argument('name'), $this->argument('guard'));

        $this->info("Permission `{$permission->name}` created");
    }
}
