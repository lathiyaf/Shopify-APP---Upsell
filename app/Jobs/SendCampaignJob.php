<?php

namespace App\Jobs;

use App\Models\RcAbandonedCheckout;
use App\Models\RcAutomation;
use App\Models\RcAutomationTrack;
use App\Models\RcCustomer;
use App\Models\RcLineItems;
use App\Models\RcShop;
use App\Models\User;
use App\Traits\BaseTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use BaseTrait;

    private $user = [];
    private $campaign = [];
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($campaign, $user)
    {
        $this->user = $user;
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            logger("============= START :: SendAutomationJob =============");
            $this->sendCampaign($this->campaign, $this->user);
        }catch(\Exception $e){
            logger("============= ERROR :: SendAutomationJob (handle) =============");
            logger($e);
        }
    }
    public function sendCampaign($campaign, $user){
        try{
            $reminderType = $campaign->reminder_type;
            $t = 'is_emailsubscriber';
            $t = ($reminderType == 'sms') ? 'is_smssubscriber' : $t;
            $t = ($reminderType == 'push') ? 'is_pushsubscriber' : $t;

            $dbCustomers = RcCustomer::where('user_id', $user->id)->where($t, 1)->get();
//                         check automation type is email or web or push, if push then need phone number

            logger(json_encode($dbCustomers));
            foreach ($dbCustomers as $ckey=>$cval){
                if( ($reminderType == 'email' && ($cval->email != null || $cval->email != ''))  || (($reminderType == 'push' || $reminderType == 'sms') && ($cval->phone != null || $cval->phone != '')) ){

                    logger($reminderType);
                    $givenDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $campaign->campaign_sending_time);
                    $currDate = date('Y-m-d H:i:s');

                    $trackAuto = RcAutomationTrack::where('automation_id', $campaign->id)->orderBy('created_at', 'desc')->first();

                    logger(json_encode($trackAuto));
                    logger($givenDate . '<=' . $currDate);
                    if ((strtotime($givenDate) <= strtotime($currDate)) && !$trackAuto) {
                        // send campaign

                        logger('send campaign');
                        $this->sendAutomation($campaign, $user, $cval);
                    }
                }
            }
        }catch(\Exception $e){
            logger("============= ERROR :: sendCampaign =============");
            logger($e);
        }
    }
//    public function fetchAbandonedCheckout($user){
//        try{
//            logger("============= START :: fetchAbandonedCheckout =============");
//            if($user){
//
//                $automationTypes = ['email', 'sms', 'push'];
//
//                foreach ($automationTypes as $atkey=>$atval){
//                    $t = 'is_emailsubscriber';
//                    $t = ($atval == 'sms') ? 'is_smssubscriber' : $t;
//                    $t = ($atval == 'push') ? 'is_pushsubscriber' : $t;
//
//                    $dbCustomers = RcCustomer::where('user_id', $user->id)->where($t, 1)->get();
////                         check automation type is email or web or push, if push then need phone number
//
//                    foreach ($dbCustomers as $ckey=>$cval){
//                        if( ($atval == 'email' && ($cval->email != null || $cval->email != ''))  || (($atval == 'push' || $atval == 'sms') && ($cval->phone != null || $cval->phone != '')) ){
//
//                            $fetchCampaigns = RcAutomation::with('BodyText')->where('automation_type', 'campaign')->where('reminder_type', $atval)->get();
//
//                            foreach ($fetchCampaigns as $campaignKey=>$campaign){
//                                $givenDate = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $campaign->campaign_sending_time);
//                                $currDate = date('Y-m-d H:i:s');
//
//                                $trackAuto = RcAutomationTrack::where('automation_id', $campaign->id)->orderBy('created_at', 'desc')->first();
//                                if ((strtotime($givenDate) <= $currDate) && !$trackAuto) {
//                                    // send campaign
//                                    $this->sendAutomation($campaign, $user);
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//        }catch(\Exception $e){
//            logger("============= ERROR :: fetchAbandonedCheckout =============");
//            logger($e);
//        }
//    }

}
