<?php

namespace Unitable\Graham\Coupon;

use Illuminate\Database\Eloquent\Model;
use Unitable\Graham\Contracts\Discountable;
use Unitable\Graham\Subscription\Subscription;

/**
 * @property int $id
 * @property string $code
 * @property float $value
 */
class Coupon extends Model implements Discountable {

    protected $guarded = [];

    /**
     * Find a coupon by its code.
     *
     * @param string $code
     * @return static|null
     */
    public static function findByCode(string $code) {
        return static::query()->where('code', $code)->first();
    }

    /**
     * Get the discount for a subscription.
     *
     * @param Subscription $subscription
     * @return float
     */
    public function getDiscount(Subscription $subscription): float {
        return $subscription->price * ($this->value / 100);
    }
}
