<?php

namespace Unitable\Graham\Engines\Hosted;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Unitable\Graham\Engines\Hosted\Listeners\ProcessSubscription;
use Unitable\Graham\Events\SubscriptionCreated;

class HostedEngineServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton(HostedEngine::class, function() {
            return new HostedEngine();
        });

        $this->registerEvents();
    }

    /**
     * Register any application events.
     *
     * @return void
     */
    protected function registerEvents() {
        Event::listen(SubscriptionCreated::class, ProcessSubscription::class);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {

    }

}
