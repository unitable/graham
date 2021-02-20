<?php

namespace Unitable\Graham;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Unitable\Graham\Skeleton\SkeletonClass
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
