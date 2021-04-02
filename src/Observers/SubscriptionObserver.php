<?php

namespace Unitable\Graham\Observers;

use Unitable\Graham\Events\SubscriptionActivated;
use Unitable\Graham\Events\SubscriptionCanceled;
use Unitable\Graham\Events\SubscriptionCancelRequested;
use Unitable\Graham\Events\SubscriptionCreated;
use Unitable\Graham\Events\SubscriptionTrialEnded;
use Unitable\Graham\Events\SubscriptionTrialStarted;
use Unitable\Graham\Events\SubscriptionUpdated;
use Unitable\Graham\Subscription\Subscription;

class SubscriptionObserver {

    /**
     * Handle the subscription "created" event.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function created(Subscription $subscription) {
        SubscriptionCreated::dispatch($subscription);

        $this->dispatchStatuses($subscription);
    }

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
        if ($subscription->getOriginal('status') === Subscription::TRIAL) {
            SubscriptionTrialEnded::dispatch($subscription);
        }

        switch ($subscription->status) {
            case Subscription::TRIAL:
                SubscriptionTrialStarted::dispatch($subscription);
            break;
            case Subscription::ACTIVE:
                SubscriptionActivated::dispatch($subscription);
            break;
            case Subscription::CANCELED:
                SubscriptionCanceled::dispatch($subscription);
            break;
        }
    }

}
