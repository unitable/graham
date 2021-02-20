<?php

namespace Unitable\Graham\Coupon;

use Illuminate\Database\Eloquent\Model;
use Unitable\Graham\Contracts\Discountable;
use Unitable\Graham\Subscription\Subscription;

/**
 * @property int $id
 * @property string $code
 */
class Coupon extends Model implements Discountable {

    protected $guarded = [];

    /**
     * Get the discount for a subscription.
     *
     * @param Subscription $subscription
     * @return float
     */
    public function getDiscount(Subscription $subscription): float {
        // TODO

        return 10.00;
    }
}
