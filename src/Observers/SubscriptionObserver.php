<?php

namespace Unitable\Graham\Observers;

use Unitable\Graham\Events\SubscriptionCreated;
use Unitable\Graham\Subscription\Subscription;

class SubscriptionObserver {

    /**
     * Handle the subscription "created" event.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function created(Subscription $subscription) {
        SubscriptionCreated::dispatch($subscription);
    }

}
