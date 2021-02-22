<?php

namespace Unitable\Graham\Subscription;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $type
 * @property string|null $model_type
 * @property int|null $model_id
 * @property Carbon|null $expires_at
 */
class SubscriptionFlag extends Model {

    protected $guarded = [];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

}
