<?php

namespace Unitable\Graham\Engine\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Unitable\Graham\Engine\Contracts\SubscriptionBuilder;
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
     * @return SubscriptionBuilder
     */
    public abstract function newSubscription(): SubscriptionBuilder;

    /**
     * Cancel a subscription.
     *
     * @param Subscription $subscription
     * @return void
     */
    public abstract function cancelSubscription(Subscription $subscription);

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
