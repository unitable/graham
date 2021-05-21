<?php

namespace Unitable\Graham\Observers;

use Unitable\Graham\Events\SubscriptionActivated;
use Unitable\Graham\Events\SubscriptionCanceled;
use Unitable\Graham\Events\SubscriptionCancelRequested;
use Unitable\Graham\Events\SubscriptionCreated;
use Unitable\Graham\Events\SubscriptionIntended;
use Unitable\Graham\Events\SubscriptionStarted;
use Unitable\Graham\Events\SubscriptionTrialEnded;
use Unitable\Graham\Events\SubscriptionTrialStarted;
use Unitable\Graham\Events\SubscriptionUpdated;
use Unitable\Graham\Subscription\Subscription;

class SubscriptionObserver {

    /**
     * Handle the subscription "updated" event.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function updated(Subscription $subscription) {
        SubscriptionUpdated::dispatch($subscription);

        if ($subscription->isDirty('status')) {
            $this->dispatchStatuses($subscription);
        }

        if ($subscription->isDirty('ends_at')
            && $subscription->getOriginal('ends_at') === null
        ) {
            if ($subscription->active()) {
                SubscriptionCancelRequested::dispatch($subscription);
            }
        }
    }

    /**
     * Dispatch the statuses events.
     *
     * @param Subscription $subscription
     * @return void
     */
    protected function dispatchStatuses(Subscription $subscription) {
        $original = $subscription->getOriginal('status');

        if ($original === Subscription::TRIAL) {
            // Dispatch even if is not active to track all trial ends.
            SubscriptionTrialEnded::dispatch($subscription);
        }

        switch ($subscription->status) {
            case Subscription::PROCESSING:
                SubscriptionCreated::dispatch($subscription);
            break;
            case Subscription::INTENT:
                SubscriptionIntended::dispatch($subscription);
            break;
            case Subscription::TRIAL:
                SubscriptionTrialStarted::dispatch($subscription);
            break;
            case Subscription::ACTIVE:
                if ($original === Subscription::INTENT) {
                    SubscriptionStarted::dispatch($subscription);
                }

                SubscriptionActivated::dispatch($subscription);
            break;
            case Subscription::CANCELED:
                SubscriptionCanceled::dispatch($subscription);
            break;
        }
    }

}
