<?php

namespace Pingu\Permissions\Providers;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Routing\Router;
use Pingu\Core\Support\ModuleServiceProvider;
use Pingu\Permissions\Console\{CacheReset,CreatePermission,CreateRole,Show};
use Pingu\Permissions\Middleware\PermissionMiddleware;
use Pingu\Permissions\Middleware\RoleMiddleware;
use Pingu\Permissions\Permissions as PermissionsClass;

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
    public function boot(Router $router)
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->loadModuleViewsFrom(__DIR__ . '/../Resources/views', 'permissions');
        $this->registerFactories();
        $this->registerCommands();
        $this->registerRouteMiddlewares($router);
        $this->addBladeDirectives();
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
        $this->app->singleton('permissions.permissions', PermissionsClass::class);
    }

    protected function addBladeDirectives()
    {
        \Blade::directive(
            'ifperm', function ($expression) {

                $code = "<?php if(\Permissions::getPermissionableModel()->hasPermissionTo($expression)): ?>";

                return $code;
            }
        );

        \Blade::directive(
            'endifperm', function () {
                return '<?php endif ?>';
            }
        );
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
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('permissions.php')
        ], 'permissions-config');
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
        $this->commands(
            [
            CacheReset::class,
            CreateRole::class,
            CreatePermission::class,
            Show::class,
            ]
        );
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
