<?php

namespace Unitable\Graham;

use Illuminate\Support\ServiceProvider;
use Unitable\Graham\Console\Commands\CronjobCommand;
use Unitable\Graham\Observers\SubscriptionInvoiceObserver;
use Unitable\Graham\Observers\SubscriptionObserver;
use Unitable\Graham\Subscription\Subscription;
use Unitable\Graham\Subscription\SubscriptionInvoice;

class GrahamServiceProvider extends ServiceProvider {

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'graham');

        $this->app->singleton('graham', function () {
            return new Graham;
        });
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
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
             $this->commands([
                 CronjobCommand::class
             ]);
        }

        $this->loadObservers();
    }

    /**
     * Load any application observers.
     *
     * @return void
     */
    protected function loadObservers() {
        Subscription::observe(SubscriptionObserver::class);
        SubscriptionInvoice::observe(SubscriptionInvoiceObserver::class);
    }

}
