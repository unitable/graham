<?php

namespace Unitable\Graham\Concerns;

use Illuminate\Support\Collection;
use Unitable\Graham\Subscription\SubscriptionInvoice;

trait ManagesInvoices {

    /**
     * Get the subscriptions models.
     *
     * @return Collection|SubscriptionInvoice[]
     */
    public function invoices() {
        return $this->hasMany(SubscriptionInvoice::class, 'user_id')
            ->orderBy('id', 'desc');
    }

}
