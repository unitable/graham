<?php

namespace Unitable\Graham\Engines\Hosted\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Unitable\Graham\Engines\Hosted\HostedEngine;
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
            if ($invoice = $subscription->renewal_invoice) {
                if ($invoice->paid()) {
                    RenewSubscriptionWithPaidInvoice::dispatch($subscription, $invoice);
                } else {
                    $subscription->markAsIncomplete();
                }
            } else {
                $subscription->cancel();
            }
        }
    }

}
