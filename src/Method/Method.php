<?php

namespace Unitable\Graham\Method;

use Unitable\Graham\Subscription\SubscriptionInvoice;
use Unitable\Graham\Support\Model;
use Unitable\Graham\Engine\Engine;

/**
 * @property int|null $id
 * @property Engine $engine
 */
abstract class Method extends Model {

    /**
     * Get an invoice payment url.
     *
     * @param SubscriptionInvoice $invoice
     * @return string|null
     */
    public function getInvoicePaymentUrl(SubscriptionInvoice $invoice): ?string {
        return null;
    }

    /**
     * Get the engine.
     *
     * @return Engine
     */
    public abstract function getEngineAttribute(): Engine;

}
