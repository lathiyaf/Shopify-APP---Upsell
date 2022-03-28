<?php

namespace App\Traits;

use App\Models\RcAutomation;
use App\Models\RcAutomationBodyText;
use App\Models\RcMaxDiscount;
use App\Models\RcSetting;
use App\Models\RcShop;
use App\Models\RcWelcomePush;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

trait PublishAppTrait{
    use BaseTrait;
    public function handle(Request $request){
        try{
            $user = Auth::user();
            $this->createShop($user);

            $response = $this->createDefaultAutomations($user, $request->data['discount']);
            $response = $this->createWelcomePush($user);
            $response = $this->createScriptTag($user);

            if( !$response ){
                return response()->json(['data' => 'Shop not found'], 422);
            }

            $user->is_published = 1;
            $user->save();
            return response()->json(['data' => 'App published successfully.'], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    /**
     * @param $user
     */
    public function createDefaultAutomations($user, $discount){
        try{
            logger('========== START :: createDefaultAutomations ==========');

            $shop = RcShop::where('user_id', $user->id)->first();

            if(!$shop){
                return false;
            }
            //            create max discount
            $maxDiscount = new RcMaxDiscount;
            $maxDiscount->user_id = $user->id;
            $maxDiscount->shop_id = $shop->id;
            $maxDiscount->discount = $discount;
            $maxDiscount->save();

            $setting = new RcSetting();
            $setting->user_id = $user->id;
            $setting->shop_id = $shop->id;
            $setting->sms_countries = json_encode(array('all'));
            $setting->save();

            $half = (int)ceil($discount/2);

            $automations = Config::get('defaultautomation.default');
            foreach ($automations as $akey=>$aval){
                if($akey == 'sms'){
                    $discArr = [0, $half, (int)$discount];
                }else{
                    $discArr = [0, $half, $half, (int)$discount, (int)$discount];
                }

                foreach ($aval as $adkey=>$adval){
//                    create new automation
                    $automation = new RcAutomation;

                    $autodata = [
                        'user_id' => $user->id,
                        'shop_id' => $shop->id,
                        'reminder_type' => $akey,
                        'automation_type' => 'automation',
                         'campaign_sending_type' => 0,
                        'campaign_sending_time' => date('Y-m-d H:i:s'),
                        'discount_type' => $adval['discount_type'],
                        'discount_value' => $discArr[$adkey],
                        'discount_code' => '',
                        'sending_time' => $adval['sending_time'],
                        'sending_type' => $adval['sending_type'],
                        'is_default' => $adval['is_default'],
                        'sender_provider' => '',
                        'is_active' => 1,
                    ];
                    $automation_id = $this->createAutomation($autodata, $automation);

//                    create body text for automation

                    $autobodyArr = $adval['body'];
                    $autobodyArr['user_id'] = $user->id;
                    $autobodyArr['shop_id'] = $shop->id;
                    $autobodyArr['automation_id'] = $automation_id;

                    $autobody = RcAutomationBodyText::create($autobodyArr);
                }
            }

            return true;
        }catch(\Exception $e){
            logger('========== ERROR :: createDefaultAutomations ==========');
            logger(json_encode($e));
        }
    }

    public function createWelcomePush($user){
        try{
            logger('========== START :: createWelcomePush ==========');
            $shop = RcShop::where('user_id', $user->id)->first();

            $welcomePush = new RcWelcomePush;
            $welcomePush->user_id = $user->id;
            $welcomePush->shop_id = $shop->id;
            $welcomePush->discount_type = 0;
            $welcomePush->discount_value = 10;
            $welcomePush->logo = '10.png';
            $welcomePush->headline = 'Your {discountValue}% code: {discountCode}';
            $welcomePush->body_text = 'Your {discountValue}% code: {discountCode}';
            $welcomePush->url = 'https://' . $shop->domain;
            $welcomePush->active = 0;
            $welcomePush->save();

            return true;
        }catch(\Exception $e){
            logger('========== ERROR :: createWelcomePush ==========');
            logger(json_encode($e));
        }
    }

    /**
     * @param $user
     */
    public function createShop($user){
        try{
            logger('========== START :: createShop ==========');

            $endPoint = '/admin/api/' . env('SHOPIFY_API_VERSION') . '/shop.json';
            $result = $user->api()->rest('GET', $endPoint);

            if( !$result['errors'] ){
                $sh_shop = $result['body']->container['shop'];
                $is_exist_shop = RcShop::where('user_id', $user->id)->first();
                $db_shop = ( $is_exist_shop ) ? $is_exist_shop : new RcShop;
                $db_shop->user_id = $user->id;
                $db_shop->shopify_store_id = $sh_shop['id'];
                $db_shop->test_store = ($sh_shop['plan_name'] == 'partner_test');
                $db_shop->name = $sh_shop['name'];
                $db_shop->email = $sh_shop['email'];
                $db_shop->myshopify_domain = $sh_shop['myshopify_domain'];
                $db_shop->domain = $sh_shop['domain'];
                $db_shop->owner = $sh_shop['shop_owner'];
                $db_shop->shopify_plan = $sh_shop['plan_name'];
                $db_shop->timezone = $sh_shop['timezone'];
                $db_shop->address1 = $sh_shop['address1'];
                $db_shop->address2 = $sh_shop['address2'];
                $db_shop->checkout_api_supported = $sh_shop['checkout_api_supported'];
                $db_shop->city = $sh_shop['city'];
                $db_shop->country = $sh_shop['country'];
                $db_shop->country_code = $sh_shop['country_code'];
                $db_shop->country_name = $sh_shop['country_name'];
                $db_shop->country_taxes = $sh_shop['county_taxes'];
                $db_shop->customer_email = $sh_shop['customer_email'];
                $db_shop->currency = $sh_shop['currency'];
                $db_shop->currency_symbol = currencyH($sh_shop['currency']);
                $db_shop->zip = $sh_shop['zip'];
                $db_shop->primary_locale = $sh_shop['primary_locale'];
                $db_shop->province = $sh_shop['province'];
                $db_shop->province_code = $sh_shop['province_code'];
                $db_shop->save();
            }

        }catch(\Exception $e){
            logger('========== ERROR :: createShop ==========');
            logger(json_encode($e));
        }
    }

    public function createScriptTag($user){
        try{
            logger('========== START :: createScriptTag ==========');

            $endPoint = 'admin/api/' . env('SHOPIFY_API_VERSION') . '/script_tags.json';

            $result = $user->api()->rest('GET', $endPoint);

            $url = env('APP_URL') . '/js/royal-cart.js';
            $is_exist = false;
            if(!$result['errors']){
                $existScriptTags = $result['body']->container['script_tags'];
                if(!empty($existScriptTags)){
                    foreach ($existScriptTags as $key=>$val){
                        if($url == $val['src']){
                            $is_exist = true;
                            break;
                        }
                    }
                }
            }

            if(!$is_exist){
                $parameter = [
                    'script_tag' => [
                        'event' => 'onload',
                        'src' => env('APP_URL') . '/js/royal-cart.js',
                        'display_scope' => 'online_store'
                    ]
                ];

                $result = $user->api()->rest('POST', $endPoint, $parameter);
                if(!$result['errors']){
                    return true;
                }else{
                    return false;
                }
            }
           return true;

           logger(json_encode($result));
        }catch(\Exception $e){
            logger('========== ERROR :: createScriptTag ==========');
            logger(json_encode($e));
        }
    }
}
