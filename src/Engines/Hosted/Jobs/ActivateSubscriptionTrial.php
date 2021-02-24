<?php

namespace Unitable\Graham\Engines\Hosted\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Unitable\Graham\Subscription\Subscription;

class ActivateSubscriptionTrial {

    use Dispatchable;

    /**
     * The job subscription.
     *
     * @var Subscription
     */
    protected Subscription $subscription;

    /**
     * Create a new job instance.
     *
     * @param Subscription $subscription
     */
    public function __construct(Subscription $subscription) {
        $this->subscription = $subscription;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        if ($this->subscription->trial_ends_at !== null) {
            $this->subscription->update([
                'status' => Subscription::TRIAL,
                'period_ends_at' => $this->subscription->trial_ends_at
            ]);
        }
    }

}
