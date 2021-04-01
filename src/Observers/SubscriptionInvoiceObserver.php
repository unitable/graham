<?php

namespace Unitable\Graham\Observers;

use Unitable\Graham\Subscription\SubscriptionInvoice;
use Unitable\Graham\Events\SubscriptionInvoiceCreated;
use Unitable\Graham\Events\SubscriptionInvoiceOpen;
use Unitable\Graham\Events\SubscriptionInvoicePaid;
use Unitable\Graham\Events\SubscriptionInvoiceUpdated;

class SubscriptionInvoiceObserver {

    /**
     * Handle the invoice "created" event.
     *
     * @param SubscriptionInvoice $invoice
     * @return void
     */
    public function created(SubscriptionInvoice $invoice) {
        SubscriptionInvoiceCreated::dispatch($invoice);

        $this->dispatchStatuses($invoice);
    }

    /**
     * Handle the invoice "updated" event.
     *
     * @param SubscriptionInvoice $invoice
     * @return void
     */
    public function updated(SubscriptionInvoice $invoice) {
        SubscriptionInvoiceUpdated::dispatch($invoice);

        if ($invoice->isDirty('status')) {
            $this->dispatchStatuses($invoice);
        }
    }

    /**
     * Dispatch the statuses events.
     *
     * @param SubscriptionInvoice $invoice
     * @return void
     */
    protected function dispatchStatuses(SubscriptionInvoice $invoice) {
        switch ($invoice->status) {
            case SubscriptionInvoice::OPEN:
                SubscriptionInvoiceOpen::dispatch($invoice);
            break;
            case SubscriptionInvoice::PAID:
                $invoice->paid_at = now();
                $invoice->saveQuietly();

                SubscriptionInvoicePaid::dispatch($invoice);
            break;
        }
    }

}
