<?php

namespace Unitable\Graham\Plan;

use Unitable\Graham\Support\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $duration
 * @property string $currency_code
 * @property float $currency_price
 */
class PlanPrice extends Model {

    protected $guarded = [];

    public $timestamps = false;

}
