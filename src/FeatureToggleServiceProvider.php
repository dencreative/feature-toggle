<?php

namespace CharlGottschalk\FeatureToggle;

use CharlGottschalk\FeatureToggle\Console\AddFeature;
use CharlGottschalk\FeatureToggle\Console\AddRoleToFeature;
use CharlGottschalk\FeatureToggle\Console\DisableFeature;
use CharlGottschalk\FeatureToggle\Console\EnableFeature;
use CharlGottschalk\FeatureToggle\Console\RemoveFeature;
use CharlGottschalk\FeatureToggle\Console\RemoveRoleFromFeature;
use CharlGottschalk\FeatureToggle\Console\ToggleFeature;
use CharlGottschalk\FeatureToggle\Http\Middleware\CheckUserRole;
use CharlGottschalk\FeatureToggle\View\Components\Alert;
use CharlGottschalk\FeatureToggle\View\Components\Button;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class FeatureToggleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('feature', function($app) {
            return new Feature();
        });

        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'features');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->registerViews();
        $this->registerBlade();
        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->registerCommands();
            $this->registerPublish();
        }
    }

    protected function registerBlade() {
        Blade::if('enabled', function ($value, $default = true) {
            return Feature::enabled($value, $default);
        });

        Blade::if('enabledFor', function ($value, $roles = null, $default = true) {
            return Feature::enabledFor($value, $roles, $default);
        });
    }

    protected function registerViews() {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'feature-toggle');
        $this->loadViewComponentsAs('feature-toggle', [
            Button::class,
            Alert::class
        ]);
    }

    protected function registerCommands() {
        $this->commands([
            AddFeature::class,
            AddRoleToFeature::class,
            DisableFeature::class,
            EnableFeature::class,
            RemoveFeature::class,
            RemoveRoleFromFeature::class,
            ToggleFeature::class
        ]);
    }

    protected function registerPublish() {
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('features.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('feature-toggle'),
        ], 'assets');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/feature-toggle'),
        ], 'views');
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    protected function routeConfiguration(): array
    {
        $middleware = [
            'web',
            CheckUserRole::class
        ];
        $configMiddleware = config('features.route.middleware');

        if(!empty($configMiddleware) && is_array($configMiddleware)) {
            array_merge($middleware, $configMiddleware);
        }

        if(!empty($configMiddleware) && is_string($configMiddleware)) {
            $middleware[] = $configMiddleware;
        }

        return [
            'prefix' => config('features.route.prefix'),
            'middleware' => $middleware,
        ];
    }
}
