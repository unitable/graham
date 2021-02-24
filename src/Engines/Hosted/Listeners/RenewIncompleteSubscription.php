<?php

namespace Unitable\Graham\Engines\Hosted\Listeners;

use Unitable\Graham\Engines\Hosted\HostedEngine;
use Unitable\Graham\Engines\Hosted\Jobs\RenewSubscriptionWithPaidInvoice;
use Unitable\Graham\Events\SubscriptionInvoicePaid;

class RenewIncompleteSubscription {

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

            if ($subscription->incomplete()) {
                RenewSubscriptionWithPaidInvoice::dispatch($subscription, $invoice);
            }
        }
    }

}
