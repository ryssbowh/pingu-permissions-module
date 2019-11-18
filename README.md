# Permissions

## Permissions

Based on [laravel permission](https://github.com/spatie/laravel-permission). Direct permissions are not in use here, in the sense that it's always the roles that define the permissions. Models cannot hold permissions, only Users and Roles.

Roles have permissions through `HasPermissions` contract, Users have permissions through `HasPermissionsThroughRoles`.

Permissions are cached when using the `Permissions` facade, the cache is emptied when a permission is saved or deleted.

To check permissions, retrieve first the model on which to check it with `Permissions::getPermissionableModel()` which will return a User or the guest Role.

The user id 1 permission checking will always return true. this is registered on the gate in the service provider.

## Middlewares

Two middlewares : `permission:name1|name2`, and `role:name1|name2`

## Events
- `PermissionCacheChanged` listened by `EmptyPermissionCache`