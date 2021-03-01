<?php

namespace Unitable\Graham;

class Graham {

    /**
     * Find the billable user.
     *
     * @param int $owner_id
     * @return Billable|null
     */
    public function findBillable(int $owner_id) {
        $model = config('graham.model');

        return $model::find($owner_id);
    }

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
