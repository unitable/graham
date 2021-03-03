<?php

namespace Unitable\Graham\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Unitable\Graham\Subscription\Subscription;

class SubscriptionUpdated {

    use Dispatchable;

    /**
     * The event subscription.
     *
     * @var Subscription
     */
    public Subscription $subscription;

    /**
     * Create a new event instance.
     *
     * @param Subscription $subscription
     */
    public function __construct(Subscription $subscription) {
        $this->subscription = $subscription;
    }

}
