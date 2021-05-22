<?php

namespace Unitable\Graham\Engines\Hosted\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Unitable\Graham\Subscription\Subscription;
use Unitable\Graham\Subscription\SubscriptionInvoice;

class BeforeActivateInvoicePeriod {

    use Dispatchable;

    /**
     * The event subscription.
     *
     * @var Subscription
     */
    public Subscription $subscription;

    /**
     * The event invoice.
     *
     * @var SubscriptionInvoice
     */
    public SubscriptionInvoice $invoice;

    /**
     * Create a new event instance.
     *
     * @param Subscription $subscription
     */
    public function __construct(Subscription $subscription, SubscriptionInvoice $invoice) {
        $this->subscription = $subscription;
        $this->invoice = $invoice;
    }

}
