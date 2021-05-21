<?php

namespace Unitable\Graham\Engines\Hosted\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Unitable\Graham\Engines\Hosted\HostedEngine;
use Unitable\Graham\Subscription\Subscription;

class CreateRenewalInvoices {

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
        $range = config('graham.renewal_days');

        $subscriptions = $this->engine->subscriptions()->active()
            ->whereDate('period_ends_at', '<=', now()->addDays($range))
            ->withoutFlag('renewal_invoice')
            ->get();

        /** @var Subscription $subscription */
        foreach ($subscriptions as $subscription) {
            $subscription->newRenewalInvoice()->create();
        }
    }

}
