<?php

namespace Unitable\Graham\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Unitable\Graham\Console\Commands\WorkCommand;

class WorkCommandFired {

    use Dispatchable;

    /**
     * The event command.
     *
     * @var WorkCommand
     */
    public WorkCommand $command;

    /**
     * WorkCommandFired constructor.
     *
     * @param WorkCommand $command
     */
    public function __construct(WorkCommand $command) {
        $this->command = $command;
    }

}
