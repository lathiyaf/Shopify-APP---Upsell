<?php

namespace App\Console\Commands;

use App\Jobs\SendCampaignJob;
use App\Models\RcAutomation;
use App\Models\User;
use Illuminate\Console\Command;

class SendCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:campaign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to identify campaigns and send to user.';

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

        if(count($users) > 0){
            foreach ($users as $ukey=>$user){
                if($user){
                    $fetchCampaigns = RcAutomation::with('BodyText')->where('automation_type', 'campaign')->where('user_id', $user->id)->get();
                    foreach ($fetchCampaigns as $campaignKey=>$campaign) {
                        SendCampaignJob::dispatch($campaign, $user);
                    }
                }

            }
        }
        return 0;
    }
}
