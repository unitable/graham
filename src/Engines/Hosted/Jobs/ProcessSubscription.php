<?php

namespace Unitable\Graham\Engines\Hosted\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Unitable\Graham\Subscription\Subscription;

class ProcessSubscription {

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
        if ($this->subscription->trial_ends_at) {
            $this->subscription->update([
                'status' => Subscription::TRIAL,
                'period_ends_at' => $this->subscription->trial_ends_at
            ]);

            $this->subscription->newTrialInvoice()->create();
        } else {
            $limit = config('graham.intent_days');

            $this->subscription->update([
                'status' => Subscription::INTENT,
                'period_ends_at' => now()->addDays($limit)
            ]);

            $this->subscription->newIntentInvoice()->create();
        }
    }

}
