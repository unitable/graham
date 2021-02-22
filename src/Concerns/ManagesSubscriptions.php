<?php

namespace Unitable\Graham\Concerns;

use Illuminate\Support\Collection;
use Unitable\Graham\Engine\Contracts\SubscriptionBuilder;
use Unitable\Graham\Method\Method;
use Unitable\Graham\Plan\Plan;
use Unitable\Graham\Plan\PlanPrice;
use Unitable\Graham\Subscription\Subscription;

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

    /**
     * Get the subscriptions models.
     *
     * @return Collection|Subscription[]
     */
    public function subscriptions() {
        return $this->hasMany(Subscription::class, 'owner_id');
    }

    /**
     * Determine whether the billable is subscribed or not.
     *
     * @param Plan|null $plan
     * @return bool
     */
    public function subscribed(?Plan $plan = null) {
        $subscribed = $this->subscriptions()->active();

        if ($plan instanceof Plan)
            $subscribed->where('plan_id', $plan->id);

        return $subscribed->exists();
    }

}
