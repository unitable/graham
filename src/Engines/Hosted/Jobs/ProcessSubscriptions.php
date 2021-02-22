<?php

namespace Unitable\Graham\Engines\Hosted\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Unitable\Graham\Console\Commands\WorkCommand;

class ProcessSubscriptions {

    use Dispatchable;

    /**
     * The job command.
     *
     * @var WorkCommand|null
     */
    protected ?WorkCommand $command;

    /**
     * Create a new job instance.
     *
     * @param WorkCommand|null $command
     */
    public function __construct(?WorkCommand $command = null) {
        $this->command = $command;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        ProcessTrialSubscriptions::dispatch($this->command);
    }

}
