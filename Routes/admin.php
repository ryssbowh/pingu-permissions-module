<?php

use Pingu\Permissions\Entities\Permission;

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group prefixed with admin which
| contains the "web" middleware group and the permission middleware "can:access admin area".
|
*/

Route::get('permissions', ['uses' => 'PermissionsController@edit'])
    ->name('permissions.admin.edit')
    ->middleware('permission:view permissions');

Route::patch('permissions', ['uses' => 'PermissionsController@patch'])
    ->name('permissions.patch')
    ->middleware('permission:edit permissions');