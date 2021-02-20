<?php

namespace Unitable\Graham;

class Graham {

    /**
     * Get the active currency or a given one.
     *
     * @param string|null $code
     * @return \stdClass
     */
    public function currency(?string $code = null): \stdClass {
        // TODO

        $obj = new \stdClass;
        $obj->code = 'BRL';
        $obj->rate = 1.0000000000;

        return $obj;
    }

}
