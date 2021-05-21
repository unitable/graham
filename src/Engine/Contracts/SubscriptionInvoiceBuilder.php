<?php

namespace Unitable\Graham\Engine\Contracts;

use Unitable\Graham\Subscription\SubscriptionInvoice;
use Unitable\Graham\Support\BuilderInterface;

interface SubscriptionInvoiceBuilder extends BuilderInterface {

    /**
     * Set the status.
     *
     * @param string $status
     * @return $this
     */
    public function status(string $status);

    /**
     * Create the invoice.
     *
     * @return SubscriptionInvoice
     */
    public function create(): SubscriptionInvoice;

}
