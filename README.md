# Permissions

## v1.2.0

## v1.1.3
- adapted to Core Contracts renaming
- added `EventServiceProvider`
- added caching
- added `EmptyPermissionCache` listener
- added facade `getPermissionableModel` method
- added `PermissionCacheChanged` event
- added docs
- added helper to permission table


## v1.1.2
- removed HasPermission trait from HasRoles, replaced with HasPermissionsThroughRoles
- permission middleware checks the Guest role permission if user is a guest

## v1.0.1 First working version

## TODO
- [ ] make api permissions (guard)

### Permissions

Based on [laravel permission](https://github.com/spatie/laravel-permission). Direct permissions are not in use here, in the sense that it's always the roles that define the permissions. Models cannot hold permissions, only Users and Roles.

Roles have permissions through `HasPermissions` contract, Users have permissions through `HasPermissionsThroughRoles`.

Permissions are cached when using the `Permissions` facade, the cache is emptied when a permission is saved or deleted.

To check permissions, retrieve first the model on which to check it with `Permissions::getPermissionableModel()` which will return a User or the guest Role.

The user id 1 permission checking will always return true. ths is registered on the gate in the service provider.

### Events
- `PermissionCacheChanged` listened by `EmptyPermissionCache`