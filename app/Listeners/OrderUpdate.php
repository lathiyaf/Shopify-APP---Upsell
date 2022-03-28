<?php

namespace App\Listeners;

use App\Events\CheckOrderUpdate;
use App\Models\RcAbandonedCheckout;
use App\Models\RcAutomation;
use App\Models\RcAutomationTrack;
use App\Models\RcLineItems;
use App\Traits\BaseTrait;
use App\Models\RcShop;
use App\Models\RcWebhook;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class OrderUpdate
{
    use BaseTrait;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CheckOrderUpdate  $event
     * @return void
     */
    public function handle(CheckOrderUpdate $event)
    {
        try{
            logger("========== START:: LISTENER OrderUpdate =========");

            DB::beginTransaction();
                $ids = $event->ids;
                $user = User::find($ids['user_id']);
                $shop = RcShop::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
                $webhookResonse = RcWebhook::find($ids['webhook_id']);
                if ($webhookResonse) {
                    $data = json_decode($webhookResonse->body);

                    $is_exist_db_chkout = RcAbandonedCheckout::where('sh_checkout_id', $data->checkout_id)->where('shop_id',
                        $shop->id)->orderBy('created_at', 'desc')->first();
                    if ($is_exist_db_chkout) {
                        $is_exist_db_chkout->is_ordered = 1;
                        $is_exist_db_chkout->total_line_items_price = $data->total_line_items_price;
                        $is_exist_db_chkout->total_price = $data->total_price;
                        $is_exist_db_chkout->save();
                    }

                    $noteAttr = $data->note_attributes;

                    if(!empty($noteAttr)){
                        $newAttr = [];
                        foreach ($noteAttr as $nkey => $nval) {
                            if($nval->name == 'automation'){

                                 $trackAutomation = RcAutomationTrack::where('user_id', $user->id)->where('id', $nval->value)->first();

                                 if($trackAutomation){
                                    $trackAutomation->total_revenue = $trackAutomation->total_revenue + $data->total_price;
                                     $trackAutomation->is_opened = 1;
                                     $trackAutomation->is_ordered = 1;
                                     $trackAutomation->ordered_at = date('Y-m-d H:i:s');
                                     $trackAutomation->save();
                                 }

                                 $automation = RcAutomation::where('user_id', $user->id)->where('id', $trackAutomation->automation_id)->first();

                                 if($automation){
                                    $automation->total_revenue = $automation->total_revenue + $data->total_price;
                                     // $automation->open_rate = ($automation->total_sent > 0) ? calculateRates($automation->total_opened, $automation->total_sent) : ;
                                     // $automation->ctr = calculateRates($automation->total_clicked, $automation->total_sent);
                                     // $automation->cvr = calculateRates($automation->total_order, $automation->total_sent);
                                     // $automation->roi = calculateRates($automation->total_revenue, $automation->cost);
                                     $automation->save();

                                     $t = ($automation->automation_type == 'automation') ? $automation->reminder_type : $automation->reminder_type.$automation->automation_type;

                                     $udata = [
                                        'total_revenue' => $data->total_price,
                                        'orders' => 1
                                     ];
                                     $this->runAnalytics($user, $t, $udata);
                                 }

                               
                            }else{
                                $dt['name'] = $nval->name;
                                $dt['value'] = $nval->value;
                                $newAttr[] = $dt;
                            }
                        }

//                        update note attribute (delete automation id)
                        $parameter = [
                            'order' => [
                                'id' => $data->id,
                                'note_attributes' => $newAttr
                            ],
                        ];

                        $sh_order_result = $user->api()->rest('PUT', 'admin/orders/' . $data->id . '.json', $parameter);
                    }
                }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            logger("========== ERROR:: LISTENER OrderUpdate =========");
            logger(json_encode($e));
        }
    }
}
