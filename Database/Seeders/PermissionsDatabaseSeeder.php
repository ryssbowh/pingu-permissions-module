<?php

namespace Pingu\Permissions\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Permissions\Entities\Permission;

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
        	$perm = Permission::create(['name' => 'edit permissions', 'section' => 'Permissions']);
        	$menu = Menu::findByName('admin-menu');
        	$users = MenuItem::where(['name' => 'Users'])->first();
        	$item = new MenuItem;
        	$item->name = 'Permissions';
        	$item->active = true;
        	$item->url = 'permissions.admin.edit';
            $item->permission_id = $perm->id;
        	$item->parent()->associate($users);
        	$item->menu()->associate($menu);
        	$item->save();
        }
    }
}
