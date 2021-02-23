<?php

namespace Unitable\Graham\Console\Commands;

use Illuminate\Console\Command;
use Unitable\Graham\Events\CronjobFired;
use Unitable\Graham\Jobs\DeleteExpiredEntities;

class CronjobCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'graham:cronjob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the Graham cronjob';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        DeleteExpiredEntities::dispatch();

        CronjobFired::dispatch();

        $this->info('The cronjob was successfully executed.');
    }

}
