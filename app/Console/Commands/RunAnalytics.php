<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Traits\BaseTrait;
use Illuminate\Console\Command;

class RunAnalytics extends Command
{
    use BaseTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:analytics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will create every day analytic entry in Rc_analytics table.';

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
        $users = User::where('plan_id', '!=', null)->where('deleted_at', null)->where('password', '!=', '')->get();
        $types = ['email', 'sms', 'email_campaign', 'push'];
        if(count($users) > 0){
            foreach ($users as $ukey=>$user){
                if($user){
                    foreach($types as $tkey=>$t){
                        $this->runAnalytics($user, $t, []);
                    }
                }

            }
        }
        return 0;
    }
}
