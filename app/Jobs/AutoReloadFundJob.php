<?php

namespace App\Jobs;

use App\Models\RcCounter;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Osiset\ShopifyApp\Storage\Models\Charge;

class AutoReloadFundJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $rcCounters = RcCounter::where('status', 'active')->where('fund_type', 'recur')->get();
            foreach ($rcCounters as $key=>$val){
                $db_charge = Charge::where('user_id', $val->user_id)->where('status', 'ACTIVE')->orderBy('created_at', 'desc')->first();

                if($db_charge){
                    if($db_charge->balance_remaining < $val->recur_min){
                        $this->increaseCappedAmt($db_charge, $val);
                    }
                }
            }
        }catch(\Exception $e){
            logger("============= ERROR :: AutoReloadFundJob Handle =============");
            logger($e);
        }
    }

    public function increaseCappedAmt($db_charge, $counter){
        try{
            $user = User::find($db_charge->user_id);
            $parameter = [
                'recurring_application_charge' => [
                    'id' => $db_charge->charge_id,
                    "return_url"=> env('APP_URL') ."/update-charge/".$db_charge->user_id,
                    'capped_amount' => $db_charge->capped_amount + $counter->fund
                ]
            ];

            $endPoint = '/admin/api/2021-07/recurring_application_charges/'.$db_charge->charge_id .'/customize.json';
            $resultAddFunds = $user->api()->rest('PUT', $endPoint, $parameter);

            dd($resultAddFunds);
        }catch(\Exception $e){
            logger("============= ERROR :: increaseCappedAmt =============");
            logger($e);
        }
    }
}
