<?php

namespace Unitable\Graham\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Unitable\Graham\Subscription\Subscription;

class SubscriptionCreated {

    use Dispatchable, InteractsWithSockets, SerializesModels;

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
