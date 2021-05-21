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
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        $this->registerEvents();
    }

    /**
     * Register any application events.
     *
     * @return void
     */
    protected function registerEvents() {
        Event::listen(Events\CronjobFired::class, function() {
            /* 1º */ Jobs\CreateRenewalInvoices::dispatch();
            /* 2º */ Jobs\CancelEndedSubscriptions::dispatch();
            /* 3º */ Jobs\ProcessExpiredSubscriptionsIntents::dispatch();
            /* 4º */ Jobs\ProcessEndedSubscriptionsPeriods::dispatch();
            /* 5º */ Jobs\CancelIncompleteSubscriptions::dispatch();
        });
        Event::listen(Events\SubscriptionCreated::class, Listeners\StartProcessingSubscription::class);
        Event::listen(Events\SubscriptionInvoicePaid::class, Listeners\ActivateIntendedSubscription::class);
        Event::listen(Events\SubscriptionInvoicePaid::class, Listeners\RenewIncompleteSubscription::class);
    }

}
