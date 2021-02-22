<?php

namespace Unitable\Graham\Engines\Hosted\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Str;
use Unitable\Graham\Console\Commands\WorkCommand;
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
     * The job command.
     *
     * @var WorkCommand|null
     */
    protected ?WorkCommand $command;

    /**
     * Create a new job instance.
     *
     * @param Subscription $subscription
     * @param WorkCommand|null $command
     */
    public function __construct(Subscription $subscription, ?WorkCommand $command = null) {
        $this->subscription = $subscription;
        $this->command = $command;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $subscription = $this->subscription;

        $method = Str::studly('handle_' . $subscription->status . 'Subscription');

        if (method_exists($this, $method))
            $this->{$method}($subscription);
    }

    /**
     * Handle a subscription with "processing" status.
     *
     * @param Subscription $subscription
     */
    protected function handleProcessingSubscription(Subscription $subscription) {
        if ($subscription->trial_ends_at !== null) {
            $subscription->update([
                'status' => Subscription::TRIAL
            ]);
        }
    }

    /**
     * Handle a subscription with "trial" status.
     *
     * @param Subscription $subscription
     */
    protected function handleTrialSubscription(Subscription $subscription) {
        //
    }

}
