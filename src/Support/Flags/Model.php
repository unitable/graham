<?php

namespace Unitable\Graham\Support\Flags;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * @property string $type
 * @property string|null $model_type
 * @property int|null $model_id
 * @property mixed $model
 * @property Carbon|null $expires_at
 */
class Model extends BaseModel {

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
