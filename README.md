# Permissions

## TOTO

## v1.1.2
- removed HasPermission trait from HasRoles, replaced with HasPermissionsThroughRoles
- permission middleware checks the Guest role permission if user is a guest

## v1.0.1 First working version

## TODO
- [ ] fix api permissions (guard)

## Permissions

most of [laravel permission](https://github.com/spatie/laravel-permission) has been ported here. Direct permissions are not in use here, in the sense that it's always the roles that define the permissions.

The user id 1 permission checking will always return true.