<?php

namespace Unitable\Graham\Engine\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Unitable\Graham\Engine\Contracts\SubscriptionInvoiceBuilder;
use Unitable\Graham\Subscription\Subscription;
use Unitable\Graham\Subscription\SubscriptionInvoice;

trait ManagesInvoices {

    /**
     * Get a new invoices query.
     *
     * @return Builder
     */
    public function invoices(): Builder {
        return SubscriptionInvoice::query()->where('engine', static::class);
    }

    /**
     * Create a new invoice.
     *
     * @param Subscription $subscription
     * @return SubscriptionInvoiceBuilder
     */
    public abstract function newInvoice(Subscription $subscription): SubscriptionInvoiceBuilder;

}
