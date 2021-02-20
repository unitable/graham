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
     * @param Method $method
     * @param Plan $plan
     * @param PlanPrice|null $plan_price
     * @return SubscriptionBuilder
     */
    public function newSubscription(Method $method, Plan $plan, ?PlanPrice $plan_price = null): SubscriptionBuilder {
        return $method->engine->newSubscription($method, $plan, $plan_price);
    }

}
