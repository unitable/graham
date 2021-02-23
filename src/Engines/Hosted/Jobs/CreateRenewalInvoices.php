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
        $subscriptions = $this->engine->subscriptions()->active()
            ->whereDate('ends_at', '<=', now()->addDays(7))
            ->withoutFlag('renewal_invoice')
            ->get();

        /** @var Subscription $subscription */
        foreach ($subscriptions as $subscription) {
            $invoice = $subscription->newInvoice()->create();

            $subscription->flags()->create([
                'type' => 'renewal_invoice',
                'model_type' => get_class($invoice),
                'model_id' => $invoice->id
            ]);

            if ($subscription->onTrial()) {
                $subscription->flags()->create([
                    'type' => 'trial_invoice',
                    'model_type' => get_class($invoice),
                    'model_id' => $invoice->id
                ]);
            }
        }
    }

}
