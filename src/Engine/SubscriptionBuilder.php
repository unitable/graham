<?php

namespace Unitable\Graham\Engine;

abstract class SubscriptionBuilder implements Contracts\SubscriptionBuilder {

    /**
     * The subscription attributes.
     *
     * @var array
     */
    protected array $attributes;

    /**
     * SubscriptionBuilder constructor.
     */
    public function __construct() {
        $this->attributes = [];
    }

}
