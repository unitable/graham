<?php

namespace Unitable\Graham\Engines\Hosted\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Unitable\Graham\Console\Commands\WorkCommand;
use Unitable\Graham\Engines\Hosted\HostedEngine;
use Unitable\Graham\Subscription\Subscription;

class ProcessTrialSubscriptions {

    use Dispatchable;

    /**
     * The job command.
     *
     * @var WorkCommand|null
     */
    protected ?WorkCommand $command;

    /**
     * The engine instance.
     *
     * @var HostedEngine
     */
    protected HostedEngine $engine;

    /**
     * Create a new job instance.
     *
     * @param WorkCommand|null $command
     */
    public function __construct(?WorkCommand $command = null) {
        $this->command = $command;
        $this->engine = app()->make(HostedEngine::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $this->createInvoices();
    }

    /**
     * Create invoices for trial subscriptions.
     *
     * @return void
     */
    protected function createInvoices() {
        $subscriptions = $this->engine->subscriptions()->onTrial()
            ->whereDate('trial_ends_at', '<=', now()->addDays(7))
            ->withoutFlag('trial_invoice_created')
            ->get();

        print_r($subscriptions->toArray());

        /** @var Subscription $subscription */
        foreach ($subscriptions as $subscription) {


//            $subscription->flags()->create([
//                'type' => 'trial_invoice_created',
//            ]);
        }
    }

}
