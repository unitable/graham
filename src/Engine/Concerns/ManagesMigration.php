<?php

namespace Unitable\Graham\Engine\Concerns;

use Unitable\Graham\Method\Method;
use Unitable\Graham\Subscription\Subscription;

trait ManagesMigration {

    /**
     * Migrate the subscription to this method.
     *
     * @param Method $method
     * @param Subscription $subscription
     * @param array $data
     * @return void
     */
    public function migrateUp(Method $method, Subscription $subscription, array $data) {
        //
    }

    /**
     * Migrate the subscription from this method.
     *
     * @param Method $method
     * @param Subscription $subscription
     * @param array $data
     * @return void
     */
    public function migrateDown(Method $method, Subscription $subscription, array $data) {
        //
    }

}
