<?php

namespace Unitable\Graham\Engines\Hosted\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Unitable\Graham\Engines\Hosted\HostedEngine;
use Unitable\Graham\Engines\Hosted\Events\AfterProcessExpiredSubscriptionIntent;
use Unitable\Graham\Engines\Hosted\Events\BeforeProcessExpiredSubscriptionIntent;
use Unitable\Graham\Subscription\Subscription;

class ProcessExpiredSubscriptionsIntents {

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
        $subscriptions = $this->engine->subscriptions()->intent()
            ->where('period_ends_at', '<=', now())
            ->get();

        /** @var Subscription $subscription */
        foreach ($subscriptions as $subscription) {
            BeforeProcessExpiredSubscriptionIntent::dispatch($subscription);

            $subscription->markAsIncomplete();

            AfterProcessExpiredSubscriptionIntent::dispatch($subscription);
        }
    }

}
