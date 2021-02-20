<?php

namespace Unitable\Graham\Engines\Hosted\Concerns;

use Unitable\Graham\Engines\Hosted\SubscriptionBuilder;
use Unitable\Graham\Method\Method;
use Unitable\Graham\Plan\Plan;
use Unitable\Graham\Plan\PlanPrice;
use Unitable\Graham\Subscription\Subscription;

trait ManagesSubscriptions {

    /**
     * Create a new subscription.
     *
     * @param $owner
     * @param Plan $plan
     * @param Method $method
     * @param PlanPrice|null $plan_price
     * @return SubscriptionBuilder
     */
    public function newSubscription($owner, Plan $plan, Method $method, ?PlanPrice $plan_price = null): SubscriptionBuilder {
        return new SubscriptionBuilder($owner, $plan, $method, $plan_price);
    }

    /**
     * Cancel a subscription.
     *
     * @param Subscription $subscription
     */
    public function cancelSubscription(Subscription $subscription) {
        // TODO: Implement cancelSubscription() method.
    }

    /**
     * Resume a subscription.
     *
     * @param Subscription $subscription
     */
    public function resumeSubscription(Subscription $subscription) {
        // TODO: Implement resumeSubscription() method.
    }

    /**
     * Modify a subscription.
     *
     * @param Subscription $subscription
     * @param array $data
     */
    public function modifySubscription(Subscription $subscription, array $data) {
        // TODO: Implement modifySubscription() method.
    }

}
