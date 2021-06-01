<?php

namespace Unitable\Graham\Subscription;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Unitable\Graham\Contracts\InvoicePaymentUrlResolver;
use Unitable\Graham\Support\Flags\HasFlags;
use Unitable\Graham\Support\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Unitable\Graham\Engine\Engine;
use Unitable\Graham\Method\Method;
use Unitable\Graham\Plan\Plan;
use Unitable\Graham\Plan\PlanPrice;

/**
 * @property int $id
 * @property string $status
 * @property int $subscription_id
 * @property Subscription $subscription
 * @property int $user_id
 * @property Plan $plan
 * @property PlanPrice $plan_price
 * @property Collection|SubscriptionInvoiceDiscount[] $discounts
 * @property float $discount
 * @property float $subtotal
 * @property string $currency_code
 * @property float $currency_rate
 * @property float $total
 * @property Carbon|null $due_at
 * @property Carbon|null $paid_at
 * @property string|null $payment_url
 * @property Method $method
 * @property Engine $engine
 */
class SubscriptionInvoice extends Model {

    const PROCESSING = 'processing';
    const OPEN = 'open';
    const PAID = 'paid';
    const PAST_DUE = 'past_due';
    const EXPIRED = 'expired';
    const CANCELED = 'canceled';

    use HasFlags;

    protected $guarded = [];

    protected $dates = [
        'due_at',
        'paid_at'
    ];

    /**
     * Determine whether invoice is ongoing or not.
     *
     * @return bool
     */
    public function ongoing(): bool {
        return in_array($this->status, [
            static::PROCESSING, static::OPEN, static::PAST_DUE
        ]);
    }

    /**
     * Query only ongoing invoices.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOngoing(Builder $query): Builder {
        return $query->whereIn('status', [
            static::PROCESSING, static::OPEN, static::PAST_DUE
        ]);
    }

    /**
     * Determine whether invoice is open or not.
     *
     * @return bool
     */
    public function open(): bool {
        return $this->status === static::OPEN;
    }

    /**
     * Query only open invoices.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOpen(Builder $query): Builder {
        return $query->where('status', static::OPEN);
    }

    /**
     * Determine whether invoice is paid or not.
     *
     * @return bool
     */
    public function paid(): bool {
        return $this->status === static::PAID;
    }

    /**
     * Query only paid invoices.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePaid(Builder $query): Builder {
        return $query->where('status', static::PAID);
    }

    /**
     * Cancel the invoice.
     *
     * @return void
     */
    public function cancel() {
        $this->status = SubscriptionInvoice::CANCELED;

        $this->save();
    }

    /**
     * Get the subscription model.
     *
     * @return BelongsTo
     */
    public function subscription(): BelongsTo {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the owner model.
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo {
        $model = config('graham.model');

        return $this->belongsTo($model, 'user_id');
    }

    /**
     * Filter the invoices by its related subscription status.
     *
     * @param Builder $query
     * @param string|array $status
     * @return Builder
     */
    public function scopeWhereSubscriptionStatus(Builder $query, $status): Builder {
        return $query->whereHas('subscription', function(Builder $query) use($status) {
            if (is_array($status)) {
                $query->whereIn('status', $status);
            } else {
                $query->where('status', $status);
            }
        });
    }

    /**
     * Determine if is the subscription renewal invoice.
     *
     * @return bool
     */
    public function markedAsRenewalInvoice(): bool {
        return $this->subscription->renewal_invoice->id === $this->id;
    }

    /**
     * Get the invoice plan model.
     *
     * @return BelongsTo
     */
    public function plan(): BelongsTo {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the plan price model.
     *
     * @return BelongsTo
     */
    public function plan_price(): BelongsTo {
        return $this->belongsTo(PlanPrice::class);
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
     * Get the payment info.
     *
     * @return array|null
     */
    public function getPaymentInfo(): ?array {
        return $this->method->getInvoicePaymentInfo($this);
    }

    /**
     * Get the payment url.
     *
     * @return string|null
     */
    public function getPaymentUrl(): ?string {
        if (app()->has(InvoicePaymentUrlResolver::class)) {
            $resolver = app()->make(InvoicePaymentUrlResolver::class);

            return $resolver->resolve($this);
        }

        return null;
    }

    /**
     * Resolve the payment url attribute.
     *
     * @return string|null
     */
    public function getPaymentUrlAttribute(): ?string {
        return $this->getPaymentUrl();
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

    /**
     * Get the flags models.
     *
     * @return HasMany
     */
    public function flags(): HasMany {
        return $this->hasMany(SubscriptionInvoiceFlag::class);
    }

    /**
     * Create a flag attributes array for addFlag() method.
     *
     * @return array
     */
    protected function createFlagAttributesArray(): array {
        return [
            'subscription_id' => $this->subscription_id
        ];
    }

}
