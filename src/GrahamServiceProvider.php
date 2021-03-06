<?php

namespace Unitable\Graham;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Unitable\Graham\Console\Commands\CronjobCommand;
use Unitable\Graham\Contracts\CurrencyResolver as Currency;
use Unitable\Graham\Observers\SubscriptionInvoiceObserver;
use Unitable\Graham\Observers\SubscriptionObserver;
use Unitable\Graham\Subscription\Subscription;
use Unitable\Graham\Subscription\SubscriptionInvoice;
use Unitable\Graham\Support\CurrencyResolver;

class GrahamServiceProvider extends ServiceProvider {

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(__DIR__.'/../config/graham.php', 'graham');

        $this->app->singleton('graham', function($app) {
            return new Graham($app);
        });
        $this->app->singletonIf(Currency::class, function() {
            return new CurrencyResolver;
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
        // $this->loadRoutesFrom(__DIR__ . '/routes/graham.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/graham.php' => config_path('graham.php'),
            ], 'graham-config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/graham'),
            ], 'graham-views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/graham'),
            ], 'graham-assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/graham'),
            ], 'graham-lang');*/

            // Registering package commands.
             $this->commands([
                 CronjobCommand::class
             ]);
        }

        $this->registerEvents();
        $this->registerObservers();
    }

    /**
     * Register any application events.
     *
     * @return void
     */
    protected function registerEvents() {
        Event::listen(Events\SubscriptionInvoiceCreated::class, Listeners\StartProcessingInvoice::class);
    }

    /**
     * Register any application observers.
     *
     * @return void
     */
    protected function registerObservers() {
        Subscription::observe(SubscriptionObserver::class);
        SubscriptionInvoice::observe(SubscriptionInvoiceObserver::class);
    }

}
