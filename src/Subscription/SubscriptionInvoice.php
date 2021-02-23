<?php

namespace Unitable\Graham\Subscription;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Unitable\Graham\Engine\Engine;
use Unitable\Graham\Method\Method;

/**
 * @property int $id
 * @property string $status
 * @property int $subscription_id
 * @property Subscription $subscription
 * @property Collection|SubscriptionInvoiceDiscount[] $discounts
 * @property float $discount
 * @property float $subtotal
 * @property string $currency_code
 * @property float $currency_rate
 * @property float $total
 * @property Method $method
 * @property Engine $engine
 */
class SubscriptionInvoice extends Model {

    const PROCESSING = 'processing';
    const OPEN = 'open';
    const PAID = 'paid';
    const OVERDUE = 'overdue';
    const CANCELED = 'canceled';

    protected $guarded = [];

    /**
     * Get the subscription model.
     *
     * @return BelongsTo
     */
    public function subscription(): BelongsTo {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the invoice discounts models.
     *
     * @return HasMany
     */
    public function discounts(): HasMany {
        return $this->hasMany(SubscriptionInvoiceDiscount::class);
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

    /**
     * Get the method.
     *
     * @param string $abstract
     * @return Method
     */
    public function getMethodAttribute(string $abstract): Method {
        $method = app()->make($abstract);

        return !$this->method_id ? $method :
            $method->find($this->method_id) ?? $method;
    }

    /**
     * Get the engine.
     *
     * @param string $abstract
     * @return Engine
     */
    public function getEngineAttribute(string $abstract): Engine {
        return app()->make($abstract);
    }

}
