<?php

namespace Unitable\Graham\Concerns;

use Unitable\Graham\Engine\Contracts\SubscriptionBuilder;
use Unitable\Graham\Method\Method;
use Unitable\Graham\Plan\Plan;
use Unitable\Graham\Plan\PlanPrice;

trait ManagesSubscriptions {

    /**
     * Create a new subscription.
     *
     * @param Plan $plan
     * @param Method $method
     * @param PlanPrice|null $plan_price
     * @return SubscriptionBuilder
     */
    public function newSubscription(Plan $plan, Method $method, ?PlanPrice $plan_price = null): SubscriptionBuilder {
        return $method->engine->newSubscription($this, $plan, $method, $plan_price);
    }

}
