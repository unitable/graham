<?php

namespace Unitable\Graham\Listeners;

use Unitable\Graham\Events\SubscriptionInvoiceCreated;
use Unitable\Graham\Subscription\SubscriptionInvoice;

class StartProcessingInvoice {

    /**
     * Handle the event.
     *
     * @param SubscriptionInvoiceCreated $event
     * @return void
     */
    public function handle(SubscriptionInvoiceCreated $event) {
        $invoice = $event->invoice;

        if ($invoice->total <= 0.00) {
            $invoice->update([
                'status' => SubscriptionInvoice::PAID,
                'paid_at' => now()
            ]);
        }
    }

}
