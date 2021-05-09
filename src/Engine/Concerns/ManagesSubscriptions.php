<?php

namespace Unitable\Graham\Engine\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Unitable\Graham\Engine\Contracts\SubscriptionBuilder;
use Unitable\Graham\Method\Method;
use Unitable\Graham\Plan\Plan;
use Unitable\Graham\Plan\PlanPrice;
use Unitable\Graham\Subscription\Subscription;

trait ManagesSubscriptions {

    /**
     * Get a new subscriptions query.
     *
     * @return Builder
     */
    public function subscriptions(): Builder {
        return Subscription::query()->where('engine', static::class);
    }

    /**
     * Create a new subscription.
     *
     * @param $owner
     * @param Plan $plan
     * @param Method $method
     * @param PlanPrice|null $plan_price
     * @return SubscriptionBuilder
     */
    public abstract function newSubscription($owner, Plan $plan, Method $method, ?PlanPrice $plan_price = null): SubscriptionBuilder;

    /**
     * Cancel a subscription.
     *
     * @param Subscription $subscription
     * @return void
     */
    public abstract function cancelSubscription(Subscription $subscription);

    /**
     * Cancel a subscription immediately.
     *
     * @param Subscription $subscription
     * @return void
     */
    public abstract function cancelSubscriptionImmediately(Subscription $subscription);

    /**
     * Resume a subscription.
     *
     * @param Subscription $subscription
     */
    public abstract function resumeSubscription(Subscription $subscription);

    /**
     * Modify a subscription.
     *
     * @param Subscription $subscription
     * @param array $data
     */
    public abstract function modifySubscription(Subscription $subscription, array $data);

}
