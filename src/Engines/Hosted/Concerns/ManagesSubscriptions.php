<?php

namespace Unitable\Graham\Engines\Hosted\Concerns;

use Unitable\Graham\Engines\Hosted\SubscriptionBuilder;
use Unitable\Graham\Subscription\Subscription;

trait ManagesSubscriptions {

    /**
     * Create a new subscription.
     *
     * @return SubscriptionBuilder
     */
    public function newSubscription(): SubscriptionBuilder {
        return new SubscriptionBuilder();
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
