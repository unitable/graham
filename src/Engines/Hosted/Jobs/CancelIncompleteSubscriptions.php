<?php

namespace Unitable\Graham\Engines\Hosted\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Unitable\Graham\Engines\Hosted\HostedEngine;
use Unitable\Graham\Engines\Hosted\Events\AfterCancelIncompleteSubscription;
use Unitable\Graham\Engines\Hosted\Events\BeforeCancelIncompleteSubscription;
use Unitable\Graham\Subscription\Subscription;

class CancelIncompleteSubscriptions {

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
        $range = config('graham.incomplete_days');

        $subscriptions = $this->engine->subscriptions()->incomplete()
            ->where('period_ends_at', '<=', now()->subDays($range))
            ->get();

        /** @var Subscription $subscription */
        foreach ($subscriptions as $subscription) {
            BeforeCancelIncompleteSubscription::dispatch($subscription);

            $subscription->cancel();

            AfterCancelIncompleteSubscription::dispatch($subscription);
        }
    }

}
