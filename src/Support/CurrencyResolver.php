<?php

namespace Unitable\Graham\Support;

use Unitable\Graham\Contracts\CurrencyResolver as Resolver;

class CurrencyResolver implements Resolver {

    /**
     * Resolve the currency.
     *
     * @param string|null $code
     * @return mixed
     */
    public function resolve(?string $code = null) {
        $currency_code = config('graham.currency');

        if (($code || $currency_code) && (!$code || $currency_code === $code)) {
            $currency = new \stdClass;
            $currency->code = strtoupper($code ?? $currency_code);
            $currency->rate = 1.0000000000;

            return $currency;
        }

        throw new \RuntimeException('Currency resolver not configured.');
    }

}
