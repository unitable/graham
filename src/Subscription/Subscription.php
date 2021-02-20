<?php

namespace Unitable\Graham\Subscription;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Unitable\Graham\Method\Method;
use Unitable\Graham\Engine\Engine;

/**
 * @property string $status
 * @property Method $method
 * @property Engine $engine
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
        'ends_at' => 'datetime'
    ];

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
        // TODO: implement method.
    }

    /**
     * Query only subscriptions within grace periods.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOnGracePeriod(Builder $query): Builder {
        // TODO: implement method.
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
