<?php

namespace Unitable\Graham;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \stdClass currency(?string $code = null) Get the active currency or a given one.
 *
 * @see \Unitable\Graham\Graham
 */
class GrahamFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'graham';
    }
}
