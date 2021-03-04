<?php

namespace Unitable\Graham\Engine;

use Unitable\Graham\GrahamFacade as Graham;
use Unitable\Graham\Subscription\Subscription;
use Unitable\Graham\Subscription\SubscriptionInvoice;
use Unitable\Graham\Subscription\SubscriptionInvoiceDiscount;

abstract class SubscriptionInvoiceBuilder implements Contracts\SubscriptionInvoiceBuilder {

    /**
     * The invoice subscription.
     *
     * @var Subscription
     */
    protected Subscription $subscription;

    /**
     * The invoice status.
     *
     * @var string
     */
    protected string $status;

    /**
     * SubscriptionInvoiceBuilder constructor.
     *
     * @param Subscription $subscription
     */
    public function __construct(Subscription $subscription) {
        $this->subscription = $subscription;
    }

    /**
     * Set the invoice status.
     *
     * @param string $status
     * @return $this
     */
    public function status(string $status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Create the invoice.
     *
     * @return SubscriptionInvoice
     */
    public function create(): SubscriptionInvoice {
        $subscription = $this->subscription;
        $price = $subscription->price();
        $currency = Graham::currency($price->currency_code);

        $subtotal = $subscription->price;
        $total = $subtotal - $subscription->discount;

        $status = $this->status ?? (
            $total <= 0.00 ? SubscriptionInvoice::PAID : SubscriptionInvoice::PROCESSING
        );

        /** @var SubscriptionInvoice $invoice */
        $invoice = $subscription->invoices()->create([
            'status' => $status,
            'user_id' => $subscription->user_id,
            'plan_id' => $subscription->plan_id,
            'plan_price_id' => $subscription->plan_price_id,
            'method' => get_class($subscription->method),
            'method_id' => $subscription->method->id,
            'engine' => get_class($subscription->engine),
            'currency_code' => $currency->code,
            'currency_rate' => $currency->rate,
            'subtotal' => $subtotal,
            'total' => $total,
            'due_at' =>  $subscription->period_ends_at,
            'paid_at' => $status === SubscriptionInvoice::PAID ? now() : null
        ]);

        foreach ($subscription->discounts as $discount) {
            $invoice->discounts()->create([
                'subscription_id' => $subscription->id,
                'discount_type' => $discount->discount_type,
                'discount_id' => $discount->discount_id,
                'value' => $discount->value,
                'notes' => $discount->notes
            ]);
        }

        return $invoice;
    }

}
