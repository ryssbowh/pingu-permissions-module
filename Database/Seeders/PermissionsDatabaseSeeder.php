<?php

namespace Pingu\Permissions\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
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
        Permission::findOrCreate(['name' => 'manage permissions', 'section' => 'Permissions']);
    }
}
