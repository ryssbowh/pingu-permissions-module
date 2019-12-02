<?php

namespace Pingu\Permissions\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Pingu\Menu\Events\MenuCacheChanged;
use Pingu\Menu\Events\MenuItemCacheChanged;
use Pingu\Menu\Listeners\EmptyMenuCache;
use Pingu\Menu\Listeners\EmptyMenuItemCache;
use Pingu\Permissions\Events\PermissionCacheChanged;
use Pingu\Permissions\Listeners\EmptyPermissionCache;
use Pingu\User\Events\RoleCreated;
use Pingu\User\Events\RoleDeleted;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PermissionCacheChanged::class => [
            EmptyPermissionCache::class
        ],
        RoleCreated::class => [
            EmptyPermissionCache::class
        ],
        RoleDeleted::class => [
            EmptyPermissionCache::class
        ]
    ];
}