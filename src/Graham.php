<?php

namespace Unitable\Graham;

use Illuminate\Container\Container;
use Unitable\Graham\Contracts\CurrencyResolver;

class Graham {

    /**
     * The IoC container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * Graham constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container) {
        $this->container = $container;
    }

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
     * @return mixed
     */
    public function currency(?string $code = null) {
        /** @var CurrencyResolver $resolver */
        $resolver = $this->container->make(CurrencyResolver::class);

        return $resolver->resolve($code);
    }

}
