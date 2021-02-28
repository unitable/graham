<?php

namespace Unitable\Graham\Coupon;

use Unitable\Graham\Support\Model;
use Unitable\Graham\Contracts\DiscountMethod;
use Unitable\Graham\Subscription\Subscription;

/**
 * @property int $id
 * @property string $code
 * @property float $value
 */
final class Coupon extends Model implements DiscountMethod {

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
     * @param Subscription|float $object
     * @return float
     */
    public function getDiscount($object): float {
        $price = $object instanceof Subscription ?
            $object->price : $object;

        return $price * ($this->value / 100);
    }

}
