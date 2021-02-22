<?php

namespace Unitable\Graham\Engine\Contracts;

use Unitable\Graham\Subscription\SubscriptionInvoice;

interface SubscriptionInvoiceBuilder {

    /**
     * Create the invoice.
     *
     * @return SubscriptionInvoice
     */
    public function create(): SubscriptionInvoice;

}
