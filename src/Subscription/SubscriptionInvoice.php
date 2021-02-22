<?php

namespace Unitable\Graham\Subscription;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $status
 * @property Collection|SubscriptionDiscount[] $discounts
 * @property float $discount
 * @property float $subtotal
 * @property float $total
 */
class SubscriptionInvoice extends Model {

    const PROCESSING = 'processing';
    const OPEN = 'open';
    const PAID = 'paid';
    const OVERDUE = 'overdue';

    protected $guarded = [];

    /**
     * Get the invoice discounts models.
     *
     * @return HasMany
     */
    public function discounts(): HasMany {
        return $this->hasMany(SubscriptionDiscount::class);
    }

    /**
     * Get the invoice discount sum.
     *
     * @return float
     */
    public function getDiscountAttribute() {
        $total = 0.00;

        foreach ($this->discounts as $discount) {
            $total += $discount->value;
        }

        return $total;
    }

}
