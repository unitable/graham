<?php

namespace Unitable\Graham\Subscription;

use Unitable\Graham\Support\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property string $type
 * @property string|null $model_type
 * @property int|null $model_id
 * @property mixed $model
 * @property Carbon|null $expires_at
 */
class SubscriptionFlag extends Model {

    protected $guarded = [];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    /**
     * Get the related model.
     *
     * @return MorphTo
     */
    public function model(): MorphTo {
        return $this->morphTo('model');
    }

}
