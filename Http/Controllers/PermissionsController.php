<?php

namespace Pingu\Permissions\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Permissions, Notify;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Core\Traits\RendersAdminViews;
use Pingu\Permissions\Entities\Permission;
use Pingu\Permissions\Events\PermissionCacheChanged;
use Pingu\User\Entities\Role;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PermissionsController extends BaseController
{
    use RendersAdminViews;

    public function edit(Request $request)
    {
        $roles = Role::where('id', '!=', 1)->get();
        $data = [
            'permissions' => Permissions::getBySection(),
            'patchUri' => route_by_name('permissions.patch')->uri,
            'roles' => $roles,
            'canEdit' => \Auth::user()->hasPermissionTo('edit permissions')
        ];
        return $this->renderAdminView('pages.permissions.edit', 'edit-permissions', $data);
    }

    public function patch(Request $request)
    {
        $post = $request->post();
        if(!isset($post['perms'])) {
            throw new HttpException(422, "'perms' is missing in the post parameters");
        }
        $roles = Role::get();
        $perms = $post['perms'];
        foreach($roles as $role){
            if($role->id == 1) { continue;
            }
            $rolePerms = [];
            if(isset($perms[$role->id])) {
                $rolePerms = array_keys($perms[$role->id]);
            }
            $role->syncPermissions(...$rolePerms);
        }
        event(new PermissionCacheChanged);
        Notify::success("Permissions have been updated");
        return back();
    }
}
