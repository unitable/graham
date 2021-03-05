<?php

namespace Unitable\Graham\Contracts;

interface CurrencyResolver {

    /**
     * Resolve the currency.
     *
     * @param string|null $code
     * @return mixed
     */
    public function resolve(?string $code = null);

}
