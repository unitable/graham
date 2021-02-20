<?php

namespace Unitable\Graham;

use Illuminate\Support\ServiceProvider;
use Unitable\Graham\Engines\Hosted\HostedEngine;

class GrahamServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'graham');

        // Register the main class to use with the facade
        $this->app->singleton('graham', function () {
            return new Graham;
        });

        $this->app->singleton(HostedEngine::class, function() {
            return new HostedEngine();
        });
    }

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'graham');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'graham');
         $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('graham.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/graham'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/graham'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/graham'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

}
