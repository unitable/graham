<?php

namespace Unitable\Graham\Subscription;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $discount_type
 * @property int $discount_id
 * @property float $value
 */
class SubscriptionDiscount extends Model {

    protected $guarded = [];

    public $timestamps = false;

}
