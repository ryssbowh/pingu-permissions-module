<?php

namespace Pingu\Permissions\Console;

use Illuminate\Console\Command;
use Pingu\Permissions\Entities\Permission;

class CreatePermission extends Command
{
    protected $signature = 'permission:create-permission 
                {name : The name of the permission} 
                {guard? : The name of the guard}
                {section? : The name of the section}';

    protected $description = 'Create a permission';

    public function handle()
    {
        $permission = Permission::findOrCreate(['name' => $this->argument('name'), 'guard' => $this->argument('guard'), 'section' => $this->argument('section')]);

        $this->info("Permission `{$permission->name}` created");
    }
}
