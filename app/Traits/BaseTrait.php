<?php

namespace App\Traits;

use App\Models\RcAutomation;
use App\Models\RcAutomationTrack;
use App\Models\RcCounter;
use App\Models\RcCustomer;
use App\Models\RcLineItems;
use App\Models\RcShop;
use App\Models\RcUsageCharge;
use App\Models\RcWebhook;
use App\Models\RcAnalytic;
use DatePeriod;
use Osiset\ShopifyApp\Storage\Models\Charge;

trait BaseTrait{

    use GraphQLTrait;
    /**
     * @param $topic
     * @param $user_id
     * @param $data
     * @return false
     */
    public function webhook($topic, $user_id, $data)
    {
        try {
            $shop = RcShop::where('user_id', $user_id)->first();

            $rc_webhook = new RcWebhook();
            $rc_webhook->topic = $topic;
            $rc_webhook->user_id = $user_id;
            $rc_webhook->shop_id = $shop->id;
            $rc_webhook->body = $data;
            $rc_webhook->status = 'new';
            $rc_webhook->save();
            return $rc_webhook->id;
        } catch (\Exception $e) {
            logger("========= ERROR:: webhook ========");
            logger($e);
            return false;
        }
    }

    /**
     * @param $data
     * @param $automation
     * @return mixed|void
     */
    public function createAutomation($data, $automation){
        try{
            $automation->user_id = $data['user_id'];
            $automation->shop_id = $data['shop_id'];
            $automation->reminder_type = $data['reminder_type'];
            $automation->automation_type = $data['automation_type'];
            $automation->campaign_sending_type = $data['campaign_sending_type'];
            $automation->campaign_sending_time = $data['campaign_sending_time'];
            $automation->discount_type = $data['discount_type'];
            $automation->discount_value = $data['discount_value'];
            $automation->discount_code = $data['discount_code'];
            $automation->sending_time = $data['sending_time'];
            $automation->sending_type = $data['sending_type'];
            $automation->sending_type = $data['sending_type'];
            $automation->is_default = $data['is_default'];
            $automation->is_active = $data['is_active'];
            $automation->sender_provider = $data['sender_provider'];
            $automation->save();

            return $automation->id;
        }catch(\Exception $e){
            logger("========= ERROR:: createAutomation ========");
            logger(json_encode($e));
        }
    }

    /**
     * @param $discount
     * @param $reminder_type
     * @param $user
     * @return mixed|string|void
     */
    public function createDiscountCode($discount, $reminder_type, $user, $automation_type = 'reminder')
    {
        try{
            logger('============= START :: createDiscountCode ===========');
            $code = generateCode();
            $title = $reminder_type . '_' . $automation_type;
            $currDate = date('Y-m-d') .'T'. date('H:i:s');

            $query = 'mutation MyMutation {
                  priceRuleCreate(
                    priceRule: {oncePerCustomer: true, title: "'.$title.'", value: {percentageValue: -'.$discount.'}, target: LINE_ITEM, allocationMethod: ACROSS, customerSelection: {forAllCustomers: true},
                    validityPeriod: {start: "'.$currDate.'"},
                    itemEntitlements: {targetAllLineItems: true}}
                    priceRuleDiscountCode: {code: "'.$code.'"}
                  ) {
                    userErrors {
                      field
                      message
                    }
                  }
                }
                ';
            $result = $this->graph($user, $query);
            logger(json_encode($result));
            return (!$result['errors']) ? $code : '';
        }catch(\Exception $e){
            logger("========= ERROR:: createDiscountCode ========");
            logger($e);
        }
    }

    /**
     * @param $automation
     * @param $user
     * @param $checkout
     * @param $discountCode
     */
    public function createSentAutomation($automation, $user, $checkout, $discountCode){
        try{
            $shop = RcShop::where('user_id', $user->id)->first();
            $trackAutomation = new RcAutomationTrack;
            $trackAutomation->user_id = $user->id;
            $trackAutomation->shop_id = $shop->id;

            if(@$checkout->id){
                $trackAutomation->db_checkout_id = $checkout->id;
            }

            $trackAutomation->cost = 0;
            $trackAutomation->automation_id = $automation->id;
            $trackAutomation->reminder_type = $automation->reminder_type;
            $trackAutomation->automation_type = $automation->automation_type;
            $trackAutomation->is_success = 0;
            $trackAutomation->sh_discount_code = $discountCode;
            $trackAutomation->is_sent = 0;
            $trackAutomation->save();

            return $trackAutomation;
        }catch(\Exception $e){
            logger("========= ERROR:: createSentAutomation ========");
            logger($e);
        }
    }

    /**
     * @param $user
     * @param $shopifyId
     * @param $resource
     * @param  string  $fields
     * @return array|mixed
     */
    public function getShopifyData($user, $shopifyId, $resource, $fields = 'id')
    {
        $endPoint = 'admin/api/' . env('SHOPIFY_API_VERSION') . '/'.$resource.'s/' . $shopifyId . '.json';
        $parameter['fields'] = $fields;
        $result = $user->api()->rest('GET', $endPoint, $parameter);
        if (!$result['errors']) {
            return $result['body']->container[$resource];
        } else {
            return [];
        }
    }



    public function sendAutomation($automation, $user, $customer){
        try{
            logger("====== sendAutomation =====");
            $discountCode = ($automation->discount_type == 0 && $automation->discount_value > 0) ? $this->createDiscountCode($automation->discount_value, $automation->reminder_type, $user, 'campaign') : '';

            if($discountCode != '' || $automation->discount_value == 0){
//                send reminder
                $reminderType = $automation->reminder_type;

                $trackAutomation = $this->createSentAutomation($automation, $user, [], $discountCode);

                $msg = $this->$reminderType($automation, $user, [], $discountCode, $trackAutomation, $customer);

                if($msg == 'success'){
                    $trackAutomation->is_success = 1;
                    $trackAutomation->is_sent = 1;
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
        }catch(\Exception $e){
            logger("============= ERROR :: createDiscount =============");
            logger($e);
        }
    }

    public function email($automation, $user, $checkout, $discountCode, $trackAutomation, $customer){
        try{
            logger("====== automation =====");
            $shop = RcShop::where('user_id', $user->id)->first();
            $lineItems = (@$checkout->id) ? RcLineItems::where('user_id', $user->id)->where('db_checkout_id', $checkout->id)->get()->toArray() : [];

            $checkout = (!is_array($checkout)) ? $checkout->toArray() : $checkout;
            $automation = $automation->toArray();
            $automation['line_items'] = $lineItems;
            $automation['checkout'] = $checkout;
            $automation['shop'] = $shop;
            $automation['customer'] = (!is_array($customer)) ? $customer->toArray() : $customer;
            $automation['cart_link'] = (@$checkout['abandoned_checkout_url']) ? $checkout['abandoned_checkout_url'] . '&discount=' . $discountCode : $shop['domain'];

            return sendMailH($automation, $shop->email, $customer->email, $shop->name, $user->id, $discountCode, $trackAutomation);
        }catch(\Exception $e){
            logger("============= ERROR :: email =============");
            logger($e);
        }
    }

    public function calculateAnalytics($user, $filter = []){
        try{
            $atype = ['email', 'sms', 'email_campaign', 'push', 'all'];
            $shop = RcShop::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();

            $analytics = [];
            $subscriberChartEntity = [];
            foreach ($atype as $akey => $avalue) {
                $query = RcAnalytic::query();

                if(!empty($filter) && @(!empty($filter['date']))){
                    foreach ($filter as $key=>$val){
                        if($key == 'date'){
                            $query = $query->whereBetween('date', [$val['startDate'], $val['endDate']]);
                        }
                    }
                }

                $query = $query->where('shop_id', $shop->id)->where('type', $avalue);

                $entity = $query->get();
               $analytics[$avalue]['orders'] = $entity->sum('orders');
               $analytics[$avalue]['abandoned_orders'] = $entity->sum('abandoned_orders');
               $analytics[$avalue]['spent'] = $entity->sum('spent');
               $analytics[$avalue]['revenue'] = $entity->sum('revenue');
               $analytics[$avalue]['subscriber'] = $entity->sum('subscriber');
               $analytics[$avalue]['unsubscriber'] = $entity->sum('unsubscriber');
               $analytics[$avalue]['sent'] = $entity->sum('sent');
               $analytics[$avalue]['received'] = $entity->sum('received');
                $analytics[$avalue]['clicks'] = $entity->sum('clicks');
                $analytics[$avalue]['opened'] = $entity->sum('opened');

                if(@$filter['chartType']){
                    if($avalue == $filter['chartType']){
                        $subscriberChartEntity = (count($entity) > 0) ? $query->get(['date', 'subscriber', 'revenue'])->toArray() : [];
                    }
                }
            }

            $dateRange = [];
            if(!empty($filter) && @(!empty($filter['date']))) {
                $dateRange = generateDateRangeH($filter['date']);
            }
            $analytics['charts']['subscriber'] = $this->formatChartData($dateRange, $subscriberChartEntity, 'int', 'subscriber');
            $analytics['charts']['sales'] = $this->formatChartData($dateRange, $subscriberChartEntity, 'float', 'revenue');

         return $analytics;
        }catch(\Exception $e){
            logger("============= ERROR :: calculateAnalytics =============");
            logger($e);
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    /**
     * @param $range
     * @param $data
     * @param $parseType
     * @param $keydata
     * @return array|void
     */
    public function formatChartData($range, $data, $parseType, $keydata){
        try{
            $formatted = [];

            if(!empty($range)){
                foreach ($range as $key=>$val){
                    $i = array_search($val, array_column($data, 'date'));
                    if($i !== false){
                        $formatted[$val] = $data[$i][$keydata];
                    }else{
                        $formatted[$val] = ($parseType == 'int') ? 0 : 0.00;
                    }
                }
            }else{
                foreach ($data as $key=>$val){
                    $formatted[$val['date']] = $val[$keydata];
                }
            }

            $arr['keys'] = array_keys($formatted);
            $arr['value'] = array_values($formatted);
            return $arr;
        }catch(\Exception $e){
            logger("============= ERROR :: formatChartData =============");
            logger($e);
        }
    }

    /**
     * @param $user
     * @param $type
     * @param $data
     */
    public function runAnalytics($user, $type, $data){
        try{
            $currDate = date('Y-m-d');
            $shop = RcShop::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();

            if($shop){
                $isAnalytic = RcAnalytic::where('shop_id', $shop->id)->where('type', $type)->where('date', $currDate)->first();
                $analytic = ($isAnalytic) ? $isAnalytic : new RcAnalytic();

                $analytic->shop_id = $shop->id;
                $analytic->type = $type;
                $analytic->date = $currDate;

                $isAllAnalytic = RcAnalytic::where('shop_id', $shop->id)->where('type', 'all')->where('date', $currDate)->first();
                $allAnalytic = ($isAllAnalytic) ? $isAllAnalytic : new RcAnalytic();
                $allAnalytic->shop_id = $shop->id;
                $allAnalytic->type = 'all';
                $allAnalytic->date = $currDate;

                foreach ($data as $key => $value) {
                    $analytic->$key = $analytic->$key + $value;
                    $allAnalytic->$key = $allAnalytic->$key + $value;
                }

                $analytic->save();
                $allAnalytic->save();
            }
        }catch(\Exception $e){
            logger("============= ERROR :: runAnalytics =============");
            logger($e);
        }
    }

    public function createUsageCharge($user, $cost, $automation_track_id){
        try{
            logger("============= START :: createUsageCharge =============");
            $db_charge = Charge::where('user_id', $user->id)->where('status', 'ACTIVE')->orderBy('created_at', 'desc')->first();
            if($db_charge){
                $remainingBonus = $db_charge->bonus - $db_charge->bonus_used;
                if($remainingBonus < $cost){
                    $endPoint = '/admin/recurring_application_charges/' . $db_charge->charge_id . '/usage_charges.json';
                    $description = "Add Fund";

                    $parameter = [
                        "usage_charge" => [
                            "description"=> 'SMS charge for automation',
                            "price" => $cost
                        ]
                    ];
                    $usageCharge = $user->api()->rest('POST', $endPoint, $parameter);

                    logger(json_encode($usageCharge));
                    if(!$usageCharge['errors']){
                        $shResponse = $usageCharge['body']->container['usage_charge'];
                        $rcShop = RcShop::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
                        $db_usageCharge = new RcUsageCharge;
                        $db_usageCharge->user_id = $user->id;
                        $db_usageCharge->shop_id = $rcShop->id;
                        $db_usageCharge->db_charge_id = $db_charge->id;
                        $db_usageCharge->db_automation_track_id = $automation_track_id;
                        $db_usageCharge->shopify_charge_id = $db_charge->charge_id;
                        $db_usageCharge->shopify_usagecharge_id = $shResponse['id'];
                        $db_usageCharge->plan_id = $db_charge->plan_id;
                        $db_usageCharge->description = $parameter['usage_charge']['description'];
                        $db_usageCharge->price = $parameter['usage_charge']['price'];
                        $db_usageCharge->balance_used = $shResponse['balance_used'];
                        $db_usageCharge->balance_remaining = $shResponse['balance_remaining'];
                        $db_usageCharge->save();

                        $db_charge->balance_used = $shResponse['balance_used'];
                        $db_charge->balance_remaining = $shResponse['balance_remaining'];
                        $db_charge->save();
                    }
                }else{
                    $db_charge->bonus_used = $db_charge->bonus_used + $cost;
                    $db_charge->save();
                }
            }
            return true;
        }catch(\Exception $e){
            logger("============= ERROR :: createUsageCharge =============");
            logger($e);
        }
    }

    public function getTrailMessage($charge){
        try{
            $shop = RcShop::where('user_id', $charge->user_id)->orderBy('created_at', 'desc')->first();

            $trial_end_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $charge->trial_ends_on);
            $curr_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

            $message = $shop->name;
            if($trial_end_date > $curr_date){
                $daysDiff = $curr_date->diffInDays($trial_end_date);

                $message .= " Your Your free trial ends in $daysDiff days.";
            }
            $message .= ' You have $' . number_format($charge->balance_remaining, 2) . ' of free SMS credits left.';
            return $message;
        }catch(\Exception $e){
            logger("============= ERROR :: getTrailMessage =============");
            logger($e);
        }
    }
}
