<?php

namespace Unitable\Graham\Support\Flags;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

trait HasFlags {

    /**
     * Get the flags models.
     *
     * @return HasMany
     */
    public abstract function flags(): HasMany;

    /**
     * Determine whether the model has a given flag or not.
     *
     * @param string $type
     * @return bool
     */
    public function hasFlag(string $type): bool {
        return $this->flags()->where('type', $type)->exists();
    }

    /**
     * Get a flag.
     *
     * @param string $type
     * @param Model|null $model
     * @return Model|null
     */
    public function getFlag(string $type, ?Model $model = null): ?Model {
        $builder = $this->flags()->where('type', $type);

        if ($model) {
            $builder->where('model_type', get_class($model));
            $builder->where('model_id', $model->{$model->getKey()});
        }

        return $builder->first();
    }

    /**
     * Add flag to the model.
     *
     * @param string $type
     * @param Model|null $model
     * @param mixed $data
     * @param Carbon|null $expires_at
     * @return Model
     */
    public function addFlag(string $type, ?Model $model = null, $data = null, ?Carbon $expires_at = null): Model {
        $attributes = array_merge($this->createFlagAttributesArray(), [
            'type' => $type,
            'expires_at' => $expires_at,
            'data' => is_array($data) ? json_encode($data) : $data
        ]);

        if ($model) {
            $attributes['model_type'] = get_class($model);
            $attributes['model_id'] = $model->{$model->getKey()};
        }

        return $this->flags()->create($attributes);
    }

    /**
     * Create a flag attributes array for addFlag() method.
     *
     * @return array
     */
    protected function createFlagAttributesArray(): array {
        return [];
    }

    /**
     * Remove a flag from the model.
     *
     * @param string $type
     * @param Model|null $model
     */
    public function removeFlag(string $type, ?Model $model = null) {
        $query = $this->flags()
            ->where('type', $type);

        if ($model) {
            $query->where('model_type', get_class($model));

            if ($model_id = $model->{$model->getKey()}) {
                $query->where('model_id', $model_id);
            }
        }

        $query->delete();
    }

    /**
     * Query only models with a given flag.
     *
     * @param Builder $query
     * @param string $type
     * @param Model|null $model
     * @return Builder
     */
    public function scopeWithFlag(Builder $query, string $type, ?Model $model = null): Builder {
        return $query->whereHas('flags', function(Builder $query) use($type, $model) {
            $query->where('type', $type);

            if ($model) {
                $query->where('model_type', get_class($model));
                $query->where('model_id', $model->{$model->getKey()});
            }
        });
    }

    /**
     * Query only models with given flags.
     *
     * @param Builder $query
     * @param array|string[] $types
     * @param Model|null $model
     * @return Builder
     */
    public function scopeWithFlags(Builder $query, array $types, ?Model $model = null): Builder {
        return $query->whereHas('flags', function(Builder $query) use($types, $model) {
            $query->whereIn('type', $types);

            if ($model) {
                $query->where('model_type', get_class($model));
                $query->where('model_id', $model->{$model->getKey()});
            }
        });
    }

    /**
     * Query only models without a given flag.
     *
     * @param Builder $query
     * @param string $type
     * @param Model|null $model
     * @return Builder
     */
    public function scopeWithoutFlag(Builder $query, string $type, ?Model $model = null): Builder {
        return $query->whereDoesntHave('flags', function(Builder $query) use($type, $model) {
            $query->where('type', $type);

            if ($model) {
                $query->where('model_type', get_class($model));
                $query->where('model_id', $model->{$model->getKey()});
            }
        });
    }

    /**
     * Query only models without given flags.
     *
     * @param Builder $query
     * @param array|string[] $types
     * @param Model|null $model
     * @return Builder
     */
    public function scopeWithoutFlags(Builder $query, array $types, ?Model $model = null): Builder {
        return $query->whereDoesntHave('flags', function(Builder $query) use($types, $model) {
            $query->whereIn('type', $types);

            if ($model) {
                $query->where('model_type', get_class($model));
                $query->where('model_id', $model->{$model->getKey()});
            }
        });
    }

}
