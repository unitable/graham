<?php

namespace Unitable\Graham\Engines\Hosted\Listeners;

use Unitable\Graham\Engines\Hosted\Jobs\ProcessSubscriptions;
use Unitable\Graham\Events\WorkCommandFired;

class DispatchWorkJobs {

    /**
     * Handle the event.
     *
     * @param WorkCommandFired $event
     * @return void
     */
    public function handle(WorkCommandFired $event) {
        ProcessSubscriptions::dispatch($event->command);
    }

}
