<?php

namespace Unitable\Graham\Engines\Hosted\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Unitable\Graham\Engines\Hosted\HostedEngine;
use Unitable\Graham\Events\AfterCancelEndedSubscription;
use Unitable\Graham\Events\BeforeCancelEndedSubscription;
use Unitable\Graham\Subscription\Subscription;

class CancelEndedSubscriptions {

    use Dispatchable;

    /**
     * The engine instance.
     *
     * @var HostedEngine
     */
    protected HostedEngine $engine;

    /**
     * Create a new job instance.
     */
    public function __construct() {
        $this->engine = app()->make(HostedEngine::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $subscriptions = $this->engine->subscriptions()->active()
            ->whereDate('ends_at', '<=', now())
            ->get();

        /** @var Subscription $subscription */
        foreach ($subscriptions as $subscription) {
            BeforeCancelEndedSubscription::dispatch($subscription);

            $subscription->cancel();

            AfterCancelEndedSubscription::dispatch($subscription);
        }
    }

}
