<?php

namespace Pingu\Permissions\Providers;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\ServiceProvider;
use Pingu\Permissions\Console\{CacheReset,CreatePermission,CreateRole,Show};

class PermissionsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'permissions');
        $this->registerFactories();
        $this->registerCommands();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('permissions.php'),
        ], 'config');
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
        if ($this->app->runningInConsole()) {
            $this->commands([
                CacheReset::class,
                CreateRole::class,
                CreatePermission::class,
                Show::class,
            ]);
        }
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
