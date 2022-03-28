<?php

namespace App\Console\Commands;

use App\Jobs\SendAutomationJob;
use App\Traits\SendAutomationTrait;
use Illuminate\Console\Command;

class SendAutomation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:automation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to identify automation and send to user to remind them there abandoned checkouts.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        SendAutomationJob::dispatch();
        return 0;
    }
}
