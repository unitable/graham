<?php

namespace Unitable\Graham\Engines\Hosted;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Unitable\Graham\Engines\Hosted\Jobs;
use Unitable\Graham\Engines\Hosted\Listeners;
use Unitable\Graham\Events;

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
        Event::listen(Events\CronjobFired::class, function() {
            Jobs\CreateRenewalInvoices::dispatch();
            Jobs\ProcessEndedSubscriptionsPeriods::dispatch();
            Jobs\CancelEndedSubscriptions::dispatch();
            Jobs\CancelIncompleteSubscriptions::dispatch();
        });
        Event::listen(Events\SubscriptionCreated::class, Listeners\StartProcessingSubscription::class);
        Event::listen(Events\SubscriptionInvoicePaid::class, Listeners\RenewIncompleteSubscription::class);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        //
    }

}
