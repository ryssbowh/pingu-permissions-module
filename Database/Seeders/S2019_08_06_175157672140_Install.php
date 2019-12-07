<?php

use Pingu\Core\Seeding\MigratableSeeder;
use Pingu\Core\Seeding\DisableForeignKeysTrait;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Permissions\Entities\Permission;
use Pingu\User\Entities\Role;

class S2019_08_06_175157672140_Install extends MigratableSeeder
{
    use DisableForeignKeysTrait;

    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $edit = Permission::create(['name' => 'edit permissions', 'section' => 'Permissions', 'helper' => 'Warning : do not give this permissions to untrusted roles']);
        $view = Permission::create(['name' => 'view permissions', 'section' => 'Permissions']);
        $admin = Role::find(4);
        $admin->givePermissionTo([$view, $edit]);
        $item = MenuItem::create(
            [
            'name' => 'Permissions',
            'active' => true,
            'url' => 'permissions.admin.edit',
            'deletable' => 0,
            'permission_id' => $view->id,
            ], 'admin-menu', 'admin-menu.users'
        );
    }

    /**
     * Reverts the database seeder.
     */
    public function down(): void
    {
        // Remove your data
    }
}
