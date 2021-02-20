<?php

namespace Unitable\Graham\Engines\Hosted\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Unitable\Graham\Subscription\Subscription;

class ProcessSubscriptionTrial {

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
        $subscription = $this->subscription;

        if ($subscription->trial_ends_at !== null) {
            $subscription->update([
                'status' => Subscription::TRIAL
            ]);
        }
    }

}
