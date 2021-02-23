<?php

namespace Unitable\Graham\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Unitable\Graham\Subscription\SubscriptionInvoice;

class SubscriptionInvoiceCreated {

    use Dispatchable;

    /**
     * The event invoice.
     *
     * @var SubscriptionInvoice
     */
    public SubscriptionInvoice $invoice;

    /**
     * Create a new event instance.
     *
     * @param SubscriptionInvoice $invoice
     */
    public function __construct(SubscriptionInvoice $invoice) {
        $this->invoice = $invoice;
    }

}
