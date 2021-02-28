<?php

namespace Unitable\Graham\Method;

use Unitable\Graham\Support\Model;
use Unitable\Graham\Engine\Engine;

/**
 * @property int|null $id
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
