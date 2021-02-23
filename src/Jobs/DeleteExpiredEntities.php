<?php

namespace Unitable\Graham\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Unitable\Graham\Subscription\SubscriptionDiscount;
use Unitable\Graham\Subscription\SubscriptionFlag;

class DeleteExpiredEntities {

    use Dispatchable;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        SubscriptionDiscount::query()
            ->whereDate('expires_at', '<=', now())
            ->delete();

        SubscriptionFlag::query()
            ->whereDate('expires_at', '<=', now())
            ->delete();
    }

}
