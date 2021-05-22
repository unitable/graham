<?php

namespace Unitable\Graham\Engines\Hosted\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Unitable\Graham\Engines\Hosted\HostedEngine;
use Unitable\Graham\Engines\Hosted\Events\AfterProcessEndedSubscriptionPeriod;
use Unitable\Graham\Engines\Hosted\Events\BeforeProcessEndedSubscriptionPeriod;
use Unitable\Graham\Subscription\Subscription;

class ProcessEndedSubscriptionsPeriods {

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
            ->whereDate('period_ends_at', '<=', now())
            ->get();

        /** @var Subscription $subscription */
        foreach ($subscriptions as $subscription) {
            BeforeProcessEndedSubscriptionPeriod::dispatch($subscription);

            if ($invoice = $subscription->renewal_invoice) {
                if ($invoice->paid()) {
                    ActivateInvoicePeriod::dispatch($subscription, $invoice);
                } else if (!$subscription->markedForCancellation()) {
                    $subscription->markAsIncomplete();
                }
            } else {
                $subscription->cancel();
            }

            AfterProcessEndedSubscriptionPeriod::dispatch($subscription);
        }
    }

}
