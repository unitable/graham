<?php

namespace Unitable\Graham\Subscription;

use Illuminate\Database\Eloquent\Builder;
use Unitable\Graham\Support\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Unitable\Graham\Engine\Contracts\SubscriptionInvoiceBuilder;
use Unitable\Graham\Method\Method;
use Unitable\Graham\Engine\Engine;
use Unitable\Graham\Plan\Plan;
use Unitable\Graham\Plan\PlanPrice;

/**
 * @property int $id
 * @property string $status
 * @property int $user_id
 * @property Plan $plan
 * @property int $plan_id
 * @property string $currency_code
 * @property int $plan_price_id
 * @property float $price
 * @property float $renewal_price
 * @property SubscriptionInvoice|null $renewal_invoice
 * @property Collection|SubscriptionDiscount[] $discounts
 * @property float $discount
 * @property Method $method
 * @property Engine $engine
 * @property Collection|SubscriptionInvoice[] $invoices
 * @property Carbon|null $trial_ends_at
 * @property Carbon|null $period_ends_at
 * @property Carbon|null $ends_at
 * @property Collection|SubscriptionFlag[] $flags
 */
class Subscription extends Model {

    const PROCESSING = 'processing';
    const TRIAL = 'trial';
    const ACTIVE = 'active';
    const INCOMPLETE = 'incomplete';
    const CANCELED = 'canceled';

    protected $guarded = [];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'period_ends_at' => 'datetime',
        'ends_at' => 'datetime'
    ];

    /**
     * Determine whether subscription is ongoing or not.
     *
     * @return bool
     */
    public function ongoing(): bool {
        return in_array($this->status, [
            static::PROCESSING, static::TRIAL, static::ACTIVE, static::INCOMPLETE
        ]);
    }

    /**
     * Query only ongoing subscriptions.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOngoing(Builder $query): Builder {
        return $query->whereIn('status', [
            static::PROCESSING, static::TRIAL, static::ACTIVE, static::INCOMPLETE
        ]);
    }

    /**
     * Determine if the subscription is on trial period.
     *
     * @return bool
     */
    public function onTrial(): bool {
        return $this->status === static::TRIAL;
    }

    /**
     * Query only trialing subscriptions.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOnTrial(Builder $query): Builder {
        return $query->where('status', static::TRIAL);
    }

    /**
     * Determine whether subscription is active or not.
     *
     * @return bool
     */
    public function active(): bool {
        return in_array($this->status, [
            static::TRIAL, static::ACTIVE
        ]);
    }

    /**
     * Query only active subscriptions.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder {
        return $query->whereIn('status', [
            static::TRIAL, static::ACTIVE
        ]);
    }

    /**
     * Determine whether subscription is inactive or not.
     *
     * @return bool
     */
    public function inactive(): bool {
        return !$this->active();
    }

    /**
     * Query only inactive subscriptions.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeInactive(Builder $query): Builder {
        return $query->whereNotIn('status', [
            static::TRIAL, static::ACTIVE
        ]);
    }

    /**
     * Mark the subscription as incomplete.
     *
     * @return void
     */
    public function markAsIncomplete() {
        $this->update([
            'status' => static::INCOMPLETE
        ]);
    }

    /**
     * Determine whether subscription is incomplete or not.
     *
     * @return bool
     */
    public function incomplete(): bool {
        return $this->status === static::INCOMPLETE;
    }

    /**
     * Query only incomplete subscriptions.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeIncomplete(Builder $query): Builder {
        return $query->where('status', static::INCOMPLETE);
    }

    /**
     * Determine if the subscription is on grace period.
     *
     * @return bool
     */
    public function onGracePeriod(): bool {
        return $this->active() && $this->markedForCancellation();
    }

    /**
     * Query only subscriptions within grace periods.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOnGracePeriod(Builder $query): Builder {
        return $query->active()->markedForCancellation();
    }

    /**
     * Determine whether the subscription was marked for cancellation or not.
     *
     * @return bool
     */
    public function markedForCancellation(): bool {
        return $this->ends_at !== null;
    }

    /**
     * Query only subscriptions marked for cancellation.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeMarkedForCancellation(Builder $query): Builder {
        return $query->whereNotNull('ends_at');
    }

    /**
     * Determine if the subscription was canceled.
     *
     * @return bool
     */
    public function canceled(): bool {
        return $this->status === static::CANCELED;
    }

    /**
     * Query only canceled subscriptions.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeCanceled(Builder $query): Builder {
        return $query->where('status', static::CANCELED);
    }

    /**
     * Cancel the subscription.
     *
     * @return void
     */
    public function cancel() {
        $this->engine->cancelSubscription($this);
    }

    /**
     * Cancel the subscription immediately.
     *
     * @return void
     */
    public function cancelImmediately() {
        $this->engine->cancelSubscriptionImmediately($this);
    }

    /**
     * Resume the subscription.
     *
     * @return void
     */
    public function resume() {
        $this->engine->resumeSubscription($this);
    }

    /**
     * Modify  the subscription.
     *
     * @param array $data
     */
    public function modify(array $data) {
        $this->engine->modifySubscription($this, $data);
    }

    /**
     * Get the subscription plan model.
     *
     * @return BelongsTo
     */
    public function plan(): BelongsTo {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the subscription price model.
     *
     * @return PlanPrice
     */
    public function price(): PlanPrice {
        return $this->belongsTo(PlanPrice::class, 'plan_price_id')->first();
    }

    /**
     * Get the subscription currency code.
     *
     * @return string
     */
    public function getCurrencyCodeAttribute() {
        return $this->price()->currency_code;
    }

    /**
     * Get the subscription price.
     *
     * @return float
     */
    public function getPriceAttribute(): float {
        return $this->price()->currency_price;
    }

    /**
     * Get the subscription discounts models.
     *
     * @return HasMany
     */
    public function discounts(): HasMany {
        return $this->hasMany(SubscriptionDiscount::class);
    }

    /**
     * Get the subscription discount sum.
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
     * Get the subscription renewal price.
     *
     * @return float
     */
    public function getRenewalPriceAttribute(): float {
        return $this->price - $this->discount;
    }

    /**
     * Get the subscription renewal invoice.
     *
     * @return SubscriptionInvoice|null
     */
    public function getRenewalInvoiceAttribute(): ?SubscriptionInvoice {
        /** @var SubscriptionFlag|null $flag */
        $flag = $this->flags()
            ->where('type', 'renewal_invoice')
            ->first();

        return $flag ? $flag->model : null;
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
     * Get the subscription invoices models.
     *
     * @return HasMany
     */
    public function invoices(): HasMany {
        return $this->hasMany(SubscriptionInvoice::class);
    }

    /**
     * Get the subscription latest invoices models.
     *
     * @return HasMany
     */
    public function latest_invoices(): HasMany {
        return $this->invoices()
            ->orderBy('due_at', 'DESC')
            ->orderBy('id', 'DESC');
    }

    /**
     * Create a new invoice.
     *
     * @return SubscriptionInvoiceBuilder
     */
    public function newInvoice(): SubscriptionInvoiceBuilder {
        return $this->engine->newInvoice($this);
    }

    /**
     * Get the flags models.
     *
     * @return HasMany
     */
    public function flags(): HasMany {
        return $this->hasMany(SubscriptionFlag::class);
    }

    /**
     * Determine whether the subscription has a given flag or not.
     *
     * @param string $type
     * @return bool
     */
    public function hasFlag(string $type): bool {
        return $this->flags()->where('type', $type)->exists();
    }

    /**
     * Query only subscriptions with a given flag.
     *
     * @param Builder $query
     * @param string $type
     * @return Builder
     */
    public function scopeWithFlag(Builder $query, string $type): Builder {
        return $query->whereHas('flags', function(Builder $query) use($type) {
            $query->where('type', $type);
        });
    }

    /**
     * Query only subscriptions without a given flag.
     *
     * @param Builder $query
     * @param string $type
     * @return Builder
     */
    public function scopeWithoutFlag(Builder $query, string $type): Builder {
        return $query->whereDoesntHave('flags', function(Builder $query) use($type) {
            $query->where('type', $type);
        });
    }

}
