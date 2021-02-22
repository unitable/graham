<?php

namespace Unitable\Graham\Contracts;

use Unitable\Graham\Subscription\Subscription;

interface DiscountMethod {

    /**
     * Get the discount for a subscription.
     *
     * @param Subscription $subscription
     * @return float
     */
    public function getDiscount(Subscription $subscription): float;

}
