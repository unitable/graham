<?php

namespace Unitable\Graham\Method;

use Illuminate\Database\Eloquent\Model;
use Unitable\Graham\Engine\Engine;

/**
 * @property ?int $id
 * @property Engine $engine
 */
abstract class Method extends Model {

    /**
     * Get the engine.
     *
     * @return Engine
     */
    public abstract function getEngineAttribute(): Engine;

}
