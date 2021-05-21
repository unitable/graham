<?php

namespace Unitable\Graham\Method\Concerns;

use Unitable\Graham\Subscription\Subscription;

trait ManagesMigration {

    /**
     * Migrate the subscription to this method.
     *
     * @param Subscription $subscription
     * @param array $data
     * @return void
     */
    public function migrateUp(Subscription $subscription, array $data) {
        $this->engine->migrateUp($this, $subscription, $data);
    }

    /**
     * Migrate the subscription from this method.
     *
     * @param Subscription $subscription
     * @param array $data
     * @return void
     */
    public function migrateDown(Subscription $subscription, array $data) {
        $this->engine->migrateDown($this, $subscription, $data);
    }

}
