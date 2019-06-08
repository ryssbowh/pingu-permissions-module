<?php

namespace Pingu\Permissions\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Permissions\Entities\Permission;
use Pingu\User\Entities\Role;

class PermissionsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$permission = Permission::where(['name' => 'edit permissions'])->first();
    	if(!$permission){
        	$perm = Permission::create(['name' => 'edit permissions', 'section' => 'Permissions', 'helper' => 'Warning : do not give this permissions to untrusted roles']);
            $admin = Role::find(4);
            $admin->givePermissionTo($perm);
        	$menu = Menu::findByName('admin-menu');
        	$users = MenuItem::findByName('admin-menu.users');
        	$item = MenuItem::create([
        	    'name' => 'Permissions',
                'active' => true,
        	    'url' => 'permissions.admin.edit',
                'deletable' => 0,
                'permission_id' => $perm->id,
            ], $menu, $users);
        }
    }
}
