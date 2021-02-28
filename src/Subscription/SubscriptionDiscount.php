<?php

namespace Unitable\Graham\Subscription;

use Unitable\Graham\Support\Model;

/**
 * @property string $discount_type
 * @property int $discount_id
 * @property float $value
 * @property string $notes
 */
class SubscriptionDiscount extends Model {

    protected $guarded = [];

    public $timestamps = false;

}
