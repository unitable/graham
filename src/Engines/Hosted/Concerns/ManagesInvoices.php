<?php

namespace Unitable\Graham\Engines\Hosted\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Unitable\Graham\Engines\Hosted\SubscriptionInvoiceBuilder;
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
    public function newInvoice(Subscription $subscription): SubscriptionInvoiceBuilder {
        return new SubscriptionInvoiceBuilder($subscription);
    }

}
