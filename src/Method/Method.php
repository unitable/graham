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
     * Get an invoice payment info.
     *
     * @param SubscriptionInvoice $invoice
     * @return array|null
     */
    public function getInvoicePaymentInfo(SubscriptionInvoice $invoice): ?array {
        return null;
    }

    /**
     * Get the engine.
     *
     * @return Engine
     */
    public abstract function getEngineAttribute(): Engine;

}
