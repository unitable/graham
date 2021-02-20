<?php

namespace Unitable\Graham\Engines\Hosted\Listeners;

use Unitable\Graham\Engines\Hosted\HostedEngine;
use Unitable\Graham\Engines\Hosted\Jobs\ProcessSubscriptionTrial;
use Unitable\Graham\Events\SubscriptionCreated;

class ProcessSubscription {

    /**
     * Handle the event.
     *
     * @param SubscriptionCreated $event
     * @return void
     */
    public function handle(SubscriptionCreated $event) {
        $subscription = $event->subscription;

        if ($subscription->engine instanceof HostedEngine) {
            ProcessSubscriptionTrial::dispatch($subscription);
        }
    }



}
