<?php

namespace Unitable\Graham\Console\Commands;

use Illuminate\Console\Command;
use Unitable\Graham\Events\WorkCommandFired;

class WorkCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'graham:work';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start processing Graham jobs';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        WorkCommandFired::dispatch($this);

        $this->info('Jobs processed successfully.');
    }

}
