<?php

namespace Unitable\Graham\Engine;

use Unitable\Graham\Subscription\Subscription;
use Unitable\Graham\Subscription\SubscriptionInvoice;

abstract class SubscriptionInvoiceBuilder implements Contracts\SubscriptionInvoiceBuilder {

    /**
     * The invoice subscription.
     *
     * @var Subscription
     */
    protected Subscription $subscription;

    /**
     * SubscriptionInvoiceBuilder constructor.
     *
     * @param Subscription $subscription
     */
    public function __construct(Subscription $subscription) {
        $this->subscription = $subscription;
    }

    /**
     * Create the invoice.
     *
     * @return SubscriptionInvoice
     */
    public function create(): SubscriptionInvoice {
        //
    }

}
