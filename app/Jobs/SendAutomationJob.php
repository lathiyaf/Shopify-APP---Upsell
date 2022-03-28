<?php

namespace App\Jobs;

use App\Models\RcAbandonedCheckout;
use App\Models\RcAutomationTrack;
use App\Models\RcAutomation;
use App\Models\RcCustomer;
use App\Models\RcLineItems;
use App\Models\RcSetting;
use App\Models\RcShop;
use App\Models\User;
use App\Traits\BaseTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAutomationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use BaseTrait;
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
             logger("============= START :: SendAutomationJob =============");
            $users = User::where('plan_id', '!=', null)->where('deleted_at', null)->where('password', '!=', '')->get();

            if(count($users) > 0){
                foreach ($users as $ukey=>$uval){
                    $this->fetchAbandonedCheckout($uval);
                }
            }
        }catch(\Exception $e){
            logger("============= ERROR :: SendAutomationJob (handle) =============");
            logger($e);
        }
    }

    /**
     * @param $user
     */
    public function fetchAbandonedCheckout($user){
        try{
            logger("============= START :: fetchAbandonedCheckout =============");
            if($user){
                $abandonedOrders = RcAbandonedCheckout::where('user_id', $user->id)->where('is_ordered', 0)->get();
                foreach ($abandonedOrders as $aokey=>$aoval){
                    $automationTypes = ['email', 'sms', 'push'];

                    foreach ($automationTypes as $atkey=>$atval){
                        $t = 'is_emailsubscriber';
                        $t = ($atval == 'sms') ? 'is_smssubscriber' : $t;
                        $t = ($atval == 'push') ? 'is_pushsubscriber' : $t;

                        $dbCustomer = RcCustomer::where('user_id', $user->id)->where('sh_customer_id', $aoval->sh_customer_id)->where($t, 1)->first();
                        if($dbCustomer){
//                         check automation type is email or web or push, if push then need phone number
                        if( ($atval == 'email' && ($aoval->email != null || $aoval->email != ''))  || $atval == 'push' || (($atval == 'sms') && ($aoval->phone != null || $aoval->phone != '')) ){

                            $fetchLastAutomation = RcAutomationTrack::where('db_checkout_id', $aoval->id)->where('reminder_type', $atval)->where('is_success', 1)->orderBy('created_at', 'desc')->first();

                            if($fetchLastAutomation){
                                logger("============== $atval fetchLastAutomation");
                                // send next reminder after previous

                                $automation = RcAutomation::with('BodyText')->where('user_id', $user->id)->where('reminder_type', $atval)->where('id', '>', $fetchLastAutomation->automation_id)->where('is_active', 1)->orderBy('created_at', 'asc')->first();

                                $lastSendingDate = $fetchLastAutomation->created_at;
                            }else{
                                logger("============== $atval first automation");
                                // send first reminder

                                $automation = RcAutomation::with('BodyText')->where('user_id', $user->id)->where('reminder_type', $atval)->where('is_default', 1)->where('is_active', 1)->orderBy('created_at', 'asc')->first();

                                $lastSendingDate = $aoval->checkout_created_at;
                            }
                            // calculate date diff
                            if($automation){
                                $isCreateDiscount = $this->calculateDateDiff($automation->sending_type, $automation->sending_time, $lastSendingDate);


                                logger('isCreateDiscount :: ' . $isCreateDiscount);
                                if($isCreateDiscount){
                                    $this->sendAutomation($automation, $user, $aoval);
                                }
                            }
                        }
                        }
                    }
                }
            }
        }catch(\Exception $e){
            logger("============= ERROR :: fetchAbandonedCheckout =============");
            logger($e);
        }
    }

    /**
     * @param $sendingType
     * @param $sendingTime
     * @param $fromDate
     * @return bool|void
     */
    public function calculateDateDiff($sendingType, $sendingTime, $fromDate){
        try{
            $from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $fromDate);
            $to = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

            logger('FROM :: ' . $fromDate);
            logger('To :: ' . $to);
            logger('Sending Type :: ' . $sendingType);
            logger('Sending Time :: ' . $sendingTime);

            $diff = ($sendingType == 0) ? $to->diffInMinutes($from) : $to->diffInHours($from);

            logger('DIFF :: ' . $diff);
            return ($diff >= $sendingTime);

        }catch(\Exception $e){
            logger("============= ERROR :: calculateDateDiff =============");
            logger($e);
        }
    }

    public function sendAutomation($automation, $user, $checkout){
        try{
            $preMadeDisc = ($automation->discount_type == 1) ? $automation->discount_code : '';
            $discountCode = ($automation->discount_type == 0 && $automation->discount_value > 0) ? $this->createDiscountCode($automation->discount_value, $automation->reminder_type, $user) : $preMadeDisc;

            if($discountCode != '' || $automation->discount_value == 0){
//                send reminder
                $reminderType = $automation->reminder_type;

                $isSend = true;
                if($reminderType == 'sms'){
                    $isSend = isEligibleForSmsH($checkout['phone'], $user);
                }

                if($isSend){
                    $trackAutomation = $this->createSentAutomation($automation, $user, $checkout, $discountCode);

                    $response = $this->$reminderType($automation, $user, $checkout, $discountCode, $trackAutomation);

                    if($response['message'] == 'success'){
                        $trackAutomation->is_success = 1;
                        $trackAutomation->is_sent = 1;
                        $trackAutomation->to = $response['to'];
                        $trackAutomation->from = $response['from'];
                        $trackAutomation->cost = $response['cost'];
                        $trackAutomation->save();

                        $automation->total_sent = $automation->total_sent + 1;
                        $automation->save();

                        $udata = [
                            'sent' => 1
                        ];
                        $t = ($automation->automation_type == 'automation') ? $automation->reminder_type : $automation->reminder_type.$automation->automation_type;
                        $this->runAnalytics($user, $t, $udata);

                    }
                }
            }
        }catch(\Exception $e){
            logger("============= ERROR :: createDiscount =============");
            logger($e);
        }
    }

    public function email($automation, $user, $checkout, $discountCode, $trackAutomation){
        try{
            logger("====== automation =====");
            $shop = RcShop::where('user_id', $user->id)->first();
            $lineItems = RcLineItems::where('user_id', $user->id)->where('db_checkout_id', $checkout->id)->get()->toArray();
            $dbCustomer = RcCustomer::where('user_id', $user->id)->where('sh_customer_id', $checkout->sh_customer_id)->first();

            $checkout = $checkout->toArray();
            $automation = $automation->toArray();
            $automation['line_items'] = $lineItems;
            $automation['checkout'] = $checkout;
            $automation['shop'] = $shop;
            $automation['customer'] = $dbCustomer->toArray();
            $automation['cart_link'] = generatePreCartURL($checkout['abandoned_checkout_url'], $lineItems);

            $fromMail = ($automation['sender_provider'] != '' ) ? $automation['sender_provider'] : $shop->email;
            $msg = sendMailH($automation, $fromMail, $checkout['email'], $shop->name, $user->id, $discountCode, $trackAutomation);

            $res['message'] = $msg;
            $res['cost'] = 0;
            $res['to'] = $checkout['email'];
            $res['from'] = $shop->email;
            return $res;
        }catch(\Exception $e){
            logger("============= ERROR :: email =============");
            logger($e);
        }
    }

    public function sms($automation, $user, $checkout, $discountCode, $trackAutomation){
        try{
            logger("====== sms automation =====");
            $shop = RcShop::where('user_id', $user->id)->first();
            $lineItems = RcLineItems::where('user_id', $user->id)->where('db_checkout_id', $checkout->id)->get()->toArray();
            $dbCustomer = RcCustomer::where('user_id', $user->id)->where('sh_customer_id', $checkout->sh_customer_id)->first();

            $checkout = $checkout->toArray();
            $automation = $automation->toArray();
            $automation['line_items'] = $lineItems;
            $automation['checkout'] = $checkout;
            $automation['shop'] = $shop;
            $automation['customer'] = $dbCustomer->toArray();
            $automation['cart_link'] = generatePreCartURL($checkout['abandoned_checkout_url'], $lineItems);

            // $automation['body_text']['body_text'] . " <br> " .  $automation['body_text']['unsubscribe_text']);
            $result = createSMSH($automation, $checkout['phone'], $user->id, $discountCode, $trackAutomation);

            $udata = [
                'spent' => $result['cost']
            ];
            $t = ($automation['automation_type'] == 'automation') ? $automation['reminder_type'] : $automation['reminder_type'].$automation['automation_type'];
            $this->runAnalytics($user, $t, $udata);

            $this->createUsageCharge($user, $result['cost'], $trackAutomation->id);

            $res['message'] = $result['msg'];
            $res['cost'] = $result['cost'];
            $res['to'] = $checkout['phone'];
            $res['from'] = env("TWILIO_FROM");
            return $res;
        }catch(\Exception $e){
            logger("============= ERROR :: email =============");
            logger($e);
        }
    }

    public function push($automation, $user, $checkout, $discountCode, $trackAutomation){
        try{
            logger("====== push automation =====");
            $shop = RcShop::where('user_id', $user->id)->first();
            $lineItems = RcLineItems::where('user_id', $user->id)->where('db_checkout_id', $checkout->id)->get()->toArray();
            $dbCustomer = RcCustomer::where('user_id', $user->id)->where('sh_customer_id', $checkout->sh_customer_id)->first();

            $checkout = $checkout->toArray();
            $automation = $automation->toArray();
            $automation['line_items'] = $lineItems;
            $automation['checkout'] = $checkout;
            $automation['shop'] = $shop;
            $automation['customer'] = $dbCustomer->toArray();
            $automation['cart_link'] = generatePreCartURL($checkout['abandoned_checkout_url'], $lineItems);

            $automation = updateTagsH($automation, $discountCode, 'push', $trackAutomation);
            $message = $automation['body_text']['body_text'];

            // $automation['body_text']['body_text'] . " <br> " .  $automation['body_text']['unsubscribe_text']);
            $result = sendNotificationH(array($user->id), $message, $automation['body_text']['headline'], $automation['cart_link']);

            logger($result);
            $resArr = json_decode($result);
            $msg = 'error';
            if($resArr->recipients > 0){
                $msg = 'success';

            }
            $ta = RcAutomationTrack::find($trackAutomation['id']);

            $ta->response = $result;
            $ta->save();

            $res['message'] = $msg;
            $res['cost'] = 0;
            $res['to'] = '';
            $res['from'] = '';
            return $res;
        }catch(\Exception $e){
            logger("============= ERROR :: email =============");
            logger($e);
        }
    }
}
