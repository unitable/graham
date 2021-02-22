<?php

namespace Unitable\Graham\Engine;

use Illuminate\Support\Carbon;
use Unitable\Graham\Billable;
use Unitable\Graham\Coupon\Coupon;
use Unitable\Graham\Method\Method;
use Unitable\Graham\Plan\Plan;
use Unitable\Graham\Plan\PlanPrice;
use Unitable\Graham\Subscription\Subscription;
use Unitable\Graham\Subscription\SubscriptionDiscount;

abstract class SubscriptionBuilder implements Contracts\SubscriptionBuilder {

    /**
     * The subscription owner.
     *
     * @var Billable
     */
    protected $owner;

    /**
     * The subscription plan.
     *
     * @var Plan
     */
    protected Plan $plan;

    /**
     * The subscription method.
     *
     * @var Method
     */
    protected Method $method;

    /**
     * The subscription trial days.
     *
     * @var int
     */
    protected int $trial_days;

    /**
     * The subscription plan price.
     *
     * @var PlanPrice
     */
    protected PlanPrice $plan_price;

    /**
     * The subscription coupon.
     *
     * @var Coupon
     */
    protected Coupon $coupon;

    /**
     * SubscriptionBuilder constructor.
     *
     * @param $owner
     * @param Plan $plan
     * @param Method $method
     * @param PlanPrice|null $plan_price
     */
    public function __construct($owner, Plan $plan, Method $method, ?PlanPrice $plan_price = null) {
        $this->owner = $owner;
        $this->plan = $plan;
        $this->method = $method;
        $this->plan_price = $plan_price ?? $plan->price();
    }

    /**
     * Set the subscription trial days.
     *
     * @param int $days
     * @return $this
     */
    public function trialDays(int $days) {
        $this->trial_days = $days;

        return $this;
    }

    /**
     * Set the subscription price.
     *
     * @param PlanPrice $price
     * @return $this
     */
    public function price(PlanPrice $price) {
        $this->plan_price = $price;

        return $this;
    }

    /**
     * Set the subscription coupon.
     *
     * @param Coupon $coupon
     * @return $this
     */
    public function coupon(Coupon $coupon) {
        $this->coupon = $coupon;

        return $this;
    }

    /**
     * Create the subscription.
     *
     * @return Subscription
     */
    public function create(): Subscription {
        $subscription = Subscription::create([
            'status' => Subscription::PROCESSING,
            'owner_id' => $this->owner->id,
            'plan_id' => $this->plan->id,
            'plan_price_id' => $this->plan_price->id,
            'method' => get_class($this->method),
            'method_id' => $this->method->id,
            'engine' => get_class($this->method->engine),
            'trial_ends_at' => $this->resolveTrialEndsAt()
        ]);

        if (isset($this->coupon)) {
            SubscriptionDiscount::create([
                'subscription_id' => $subscription->id,
                'discount_type' => get_class($this->coupon),
                'discount_id' => $this->coupon->id,
                'value' => $this->coupon->getDiscount($subscription)
            ]);
        }

        return $subscription;
    }

    /**
     * Resolve the trial_ends_at attribute.
     *
     * @return Carbon|null
     */
    protected function resolveTrialEndsAt(): ?Carbon {
        return isset($this->trial_days) ?
            now()->addDays($this->trial_days) : null;
    }

}
