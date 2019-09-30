<?php

namespace Pingu\Permissions\Providers;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Routing\Router;
use Pingu\Core\Support\ModuleServiceProvider;
use Pingu\Permissions\Console\{CacheReset,CreatePermission,CreateRole,Show};
use Pingu\Permissions\Middleware\PermissionMiddleware;
use Pingu\Permissions\Middleware\RoleMiddleware;
use Pingu\Permissions\Permissions;

class PermissionsServiceProvider extends ModuleServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    protected $routeMiddlewares = [
        'permission' => PermissionMiddleware::class,
        'role' => RoleMiddleware::class
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(Permissions $permissions, Router $router)
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'permissions');
        $this->registerFactories();
        $this->registerCommands();
        $this->registerRouteMiddlewares($router);

        $permissions->registerPermissions();

        $this->app->singleton('permissions.permissions', function ($app) use ($permissions) {
            return $permissions;
        });

        /**
         * Grant all access to God role
         */
        \Gate::before(function ($user, $ability) {
            if ($user->hasRole(1)) {
                return true;
            }
        });
    }

    public function registerRouteMiddlewares(Router $kernel)
    {
        foreach($this->routeMiddlewares as $name => $middleware){
            $kernel->aliasMiddleware($name, $middleware);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'permissions'
        );
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/permissions');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'permissions');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'permissions');
        }
    }

    /**
     * Register an additional directory of factories.
     * 
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    public function registerCommands()
    {
        $this->commands([
            CacheReset::class,
            CreateRole::class,
            CreatePermission::class,
            Show::class,
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
