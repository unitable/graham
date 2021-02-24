<?php

namespace Unitable\Graham\Engines\Hosted\Concerns;

use Unitable\Graham\Engines\Hosted\SubscriptionBuilder;
use Unitable\Graham\Method\Method;
use Unitable\Graham\Plan\Plan;
use Unitable\Graham\Plan\PlanPrice;
use Unitable\Graham\Subscription\Subscription;
use Unitable\Graham\Subscription\SubscriptionInvoice;

trait ManagesSubscriptions {

    /**
     * Create a new subscription.
     *
     * @param $owner
     * @param Plan $plan
     * @param Method $method
     * @param PlanPrice|null $plan_price
     * @return SubscriptionBuilder
     */
    public function newSubscription($owner, Plan $plan, Method $method, ?PlanPrice $plan_price = null): SubscriptionBuilder {
        return new SubscriptionBuilder($owner, $plan, $method, $plan_price);
    }

    /**
     * Cancel a subscription.
     *
     * @param Subscription $subscription
     */
    public function cancelSubscription(Subscription $subscription) {
        if ($subscription->canceled())
            throw new \Exception('Subscription was already canceled.');

        if (!$subscription->ends_at)
            $subscription->ends_at = $subscription->period_ends_at ?? now();

        if ($subscription->ends_at->lessThanOrEqualTo(now())) {
            $subscription->status = Subscription::CANCELED;

            /** @var SubscriptionInvoice $invoice */
            foreach ($subscription->invoices()->ongoing()->get() as $invoice) {
                $invoice->cancel();
            }
        }

        $subscription->save();
    }

    /**
     * Resume a subscription.
     *
     * @param Subscription $subscription
     */
    public function resumeSubscription(Subscription $subscription) {
        if (!$subscription->onGracePeriod())
            throw new \Exception('Subscription is not on grace period.');

        $subscription->ends_at = null;

        $subscription->save();
    }

    /**
     * Modify a subscription.
     *
     * @param Subscription $subscription
     * @param array $data
     */
    public function modifySubscription(Subscription $subscription, array $data) {
        /*
         * Não trocar de método/engine se houver alguma fatura aberta. Se precisar, migrar a engine da fatura também.
         * Se criar uma nova vai ficar aparecendo duplicado pro cliente.
         * Colisão com as jobs automáticas, como o handle de incomplete verifica só se a fatura é HostedEngine mas não a assinatura...
         */

        throw new \LogicException('Not implemented.');
    }

}
