<?php

namespace Unitable\Graham\Engine\Contracts;

use Unitable\Graham\Coupon\Coupon;
use Unitable\Graham\Plan\PlanPrice;
use Unitable\Graham\Subscription\Subscription;

interface SubscriptionBuilder {

    /**
     * Set the subscription trial days.
     *
     * @param int $days
     * @return mixed
     */
    public function trial(int $days);

    /**
     * Set the subscription price.
     *
     * @param PlanPrice $price
     * @return $this
     */
    public function price(PlanPrice $price);

    /**
     * Set the subscription coupon.
     *
     * @param Coupon $coupon
     * @return $this
     */
    public function coupon(Coupon $coupon);

    /**
     * Create the subscription.
     *
     * @return Subscription
     */
    public function create(): Subscription;

}
