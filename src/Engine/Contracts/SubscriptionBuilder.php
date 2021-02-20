<?php

namespace Unitable\Graham\Engine\Contracts;

use Unitable\Graham\Subscription\Subscription;

interface SubscriptionBuilder {

    /**
     * Create the subscription.
     *
     * @return Subscription
     */
    public function create(): Subscription;

}
