<?php

namespace Unitable\Graham\Plan;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $currency_code
 * @property float $currency_price
 */
class PlanPrice extends Model {

    protected $guarded = [];

    public $timestamps = false;

}
