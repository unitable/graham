<?php

namespace Unitable\Graham\Engines\Hosted\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Unitable\Graham\Engines\Hosted\Events\AfterProcessSubscription;
use Unitable\Graham\Engines\Hosted\Events\BeforeProcessSubscription;
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
        $subscription = $this->subscription;

        BeforeProcessSubscription::dispatch($subscription);

        if ($subscription->trial_ends_at) {
            $subscription->update([
                'status' => Subscription::TRIAL,
                'period_ends_at' => $subscription->trial_ends_at
            ]);

            if (!$subscription->hasFlag('managed_invoices')) {
                $subscription->newTrialInvoice()->create();
            }
        } else {
            $limit = config('graham.intent_days');

            $subscription->update([
                'status' => Subscription::INTENT,
                'period_ends_at' => now()->addDays($limit)
            ]);

            if (!$subscription->hasFlag('managed_invoices')) {
                $subscription->newIntentInvoice()->create();
            }
        }

        AfterProcessSubscription::dispatch($subscription);
    }

}
