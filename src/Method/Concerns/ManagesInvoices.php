<?php

namespace Unitable\Graham\Method\Concerns;

use Unitable\Graham\Subscription\SubscriptionInvoice;

trait ManagesInvoices {

    /**
     * Get an invoice payment info.
     *
     * @param SubscriptionInvoice $invoice
     * @return array|null
     */
    public function getInvoicePaymentInfo(SubscriptionInvoice $invoice): ?array {
        return null;
    }

}
