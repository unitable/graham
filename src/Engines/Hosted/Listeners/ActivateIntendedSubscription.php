<?php

namespace Unitable\Graham\Engines\Hosted\Listeners;

use Unitable\Graham\Engines\Hosted\HostedEngine;
use Unitable\Graham\Engines\Hosted\Jobs\ActivateInvoicePeriod;
use Unitable\Graham\Events\SubscriptionInvoicePaid;

class ActivateIntendedSubscription {

    /**
     * Handle the event.
     *
     * @param SubscriptionInvoicePaid $event
     * @return void
     */
    public function handle(SubscriptionInvoicePaid $event) {
        $invoice = $event->invoice;

        if ($invoice->engine instanceof HostedEngine) {
            $subscription = $invoice->subscription;

            if (!$subscription->intent()) return;

            if ($invoice->markedAsRenewalInvoice()) {
                ActivateInvoicePeriod::dispatch($subscription, $invoice);
            }
        }
    }

}
