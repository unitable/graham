<?php

namespace Unitable\Graham\Contracts;

use Unitable\Graham\Subscription\SubscriptionInvoice;

interface InvoicePaymentUrlResolver {

    /**
     * Resolve an invoice payment url.
     *
     * @param SubscriptionInvoice $invoice
     * @return string|null
     */
    public function resolve(SubscriptionInvoice $invoice): ?string;

}
