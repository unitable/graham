<?php

namespace Unitable\Graham\Plan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Unitable\Graham\GrahamFacade as Graham;

/**
 * @property int $id
 * @property PlanPrice $price
 */
class Plan extends Model {

    protected $guarded = [];

    /**
     * Get the localized price model.
     *
     * @return HasOne
     */
    public function price(): HasOne {
        return $this->hasOne(PlanPrice::class)
            ->where('currency_code', Graham::currency()->code);
    }

}
