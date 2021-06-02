<?php

namespace Unitable\Graham\Engines\Hosted\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Unitable\Graham\Engines\Hosted\HostedEngine;
use Unitable\Graham\Engines\Hosted\Events\AfterActivateInvoicePeriod;
use Unitable\Graham\Engines\Hosted\Events\BeforeActivateInvoicePeriod;
use Unitable\Graham\Subscription\Subscription;
use Unitable\Graham\Subscription\SubscriptionInvoice;

class ActivateInvoicePeriod {

    use Dispatchable;

    /**
     * The engine instance.
     *
     * @var HostedEngine
     */
    protected HostedEngine $engine;

    /**
     * The job subscription.
     *
     * @var Subscription
     */
    protected Subscription $subscription;

    /**
     * The job invoice.
     *
     * @var SubscriptionInvoice
     */
    protected SubscriptionInvoice $invoice;

    /**
     * Create a new job instance.
     *
     * @param Subscription $subscription
     * @param SubscriptionInvoice $invoice
     */
    public function __construct(Subscription $subscription, SubscriptionInvoice $invoice) {
        $this->engine = app()->make(HostedEngine::class);
        $this->subscription = $subscription;
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        BeforeActivateInvoicePeriod::dispatch($this->subscription, $this->invoice);

        $new_days = $this->invoice->plan_price->duration; // Use invoice instead subscription.
        $renews_at = now()->addDays($new_days);

        $this->subscription->update([
            'status' => Subscription::ACTIVE,
            'period_ends_at' => $renews_at
        ]);

        if ($this->invoice->markedAsRenewalInvoice()) {
            $this->subscription->detachRenewalInvoice();
        }

        AfterActivateInvoicePeriod::dispatch($this->subscription, $this->invoice);
    }

}
