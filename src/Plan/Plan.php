<?php

namespace Unitable\Graham\Plan;

use Unitable\Graham\Support\Model;
use Unitable\Graham\GrahamFacade as Graham;

/**
 * @property int $id
 * @property string $name
 * @property float $price
 */
class Plan extends Model {

    protected $guarded = [];

    /**
     * Get the localized price model.
     *
     * @return PlanPrice
     */
    public function price(): PlanPrice {
        return $this->hasOne(PlanPrice::class)
            ->where('currency_code', Graham::currency()->code)
            ->first();
    }

    /**
     * Get the localized price.
     *
     * @return float
     */
    public function getPriceAttribute(): float {
        return $this->price()->currency_price;
    }

}
