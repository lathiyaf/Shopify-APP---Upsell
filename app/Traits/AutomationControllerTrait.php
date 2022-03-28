<?php

namespace App\Traits;

use App\Jobs\SendCampaignJob;
use App\Models\RcAutomation;
use App\Models\RcAutomationBodyText;
use App\Models\RcCustomer;
use App\Models\RcMaxDiscount;
use App\Models\RcShop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Osiset\ShopifyApp\Storage\Models\Charge;
use App\Http\Requests\AutomationRequest;

trait AutomationControllerTrait
{
    use QueryTrait, BaseTrait;

    /**
     * @param  Request  $request
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, $type, $automationtype = 'automation'){
        try{
            $user = Auth::user();
            $shop = RcShop::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();

            $parameter['reminder_type'] = $type;
            $parameter['automation_type'] = $automationtype;
            $data['automations'] = $this->fetchAutomations($user, $parameter);
            $data['charge'] = Charge::select('balance_remaining')->where('user_id', $user->id)->where('status', 'ACTIVE')->orderBy('created_at', 'desc')->first();
            if($automationtype == 'campaign'){
                $data['sent'] = $this->fetchSentCampaigns($user, $parameter);
            }

            $data['analytics'] = $this->calculateAnalytics($user);
            $data['shop']['currency_symbol'] = $shop->currency_symbol;
            return response()->json(['data' => $data], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    /**
     * @param  Request  $request
     * @param $type
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request, $type, $automationtype = 'automation', $id = ''){
        try{
            $user = Auth::user();

            $shop = RcShop::where('user_id', $user->id)->first();

            $automations = Config::get('defaultautomation.default');
            $default = end($automations[$type]);

            if( $id == '' ) {
                $maxDisc = RcMaxDiscount::select('discount')->where('user_id', $user->id)->first();
                $disc = ($maxDisc) ? $maxDisc->discount : 30;

                //                    create new automation
                $automation = new RcAutomation;

                $autodata = [
                    'user_id' => $user->id,
                    'shop_id' => $shop->id,
                    'reminder_type' => $type,
                    'automation_type' => $automationtype,
                    'campaign_sending_type' => 0,
                    'campaign_sending_time' => date('Y-m-d H:i:s'),
                    'discount_type' => $default['discount_type'],
                    'discount_code' => '',
                    'discount_value' => $disc,
                    'sending_time' => $default['sending_time'],
                    'sending_type' => $default['sending_type'],
                    'is_default' => 0,
                    'sender_provider' => '',
                    'is_active' => 1,
                ];
                $id = $this->createAutomation($autodata, $automation);

                //                    create body text for automation

                $autobodyArr = $default['body'];
                $autobodyArr['user_id'] = $user->id;
                $autobodyArr['shop_id'] = $shop->id;
                $autobodyArr['automation_id'] = $automation->id;

                $autobody = RcAutomationBodyText::create($autobodyArr);

            }

            $data['automation'] = RcAutomation::with('BodyText')->where('user_id', $user->id)->where('id', $id)->first();

            $data['allAutomations'] = RcAutomation::select('id')->where('user_id', $user->id)->where('reminder_type', $type)->get();
            return response()->json(['data' => $data], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    /**
     * @param  Request  $request
     * @param $type
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AutomationRequest $request, $type, $automationtype = 'automation', $id = ''){
        try{
            $data = $request->data;

            $user = Auth::user();
            $shop = RcShop::where('user_id', $user->id)->first();

            $automation = ($data['id']) ? RcAutomation::find($data['id']) : new RcAutomation;
            $autodata = [
                'user_id' => $user->id,
                'shop_id' => $shop->id,
                'reminder_type' => $type,
                'automation_type' => $automationtype,
                'discount_type' => $data['discount_type'],
                'discount_code' => $data['discount_code'],
                'discount_value' => $data['discount_value'],
                'sending_time' => $data['sending_time'],
                'sending_type' => $data['sending_type'],
                'is_default' => $data['is_default'],
                'is_active' => $data['is_active'],
                'campaign_sending_type' => $data['campaign_sending_type'],
                'sender_provider' => $data['sender_provider'],
                'campaign_sending_time' => ($data['campaign_sending_type'] == 0) ? date('Y-m-d H:i:s') : $data['campaign_sending_time'],
            ];

            $id = $this->createAutomation($autodata, $automation);

            $bodyText = ($data['body_text']['id']) ? RcAutomationBodyText::find($data['body_text']['id']) : new RcAutomationBodyText;

            $bodyData = $data['body_text'];
            $bodyId = $bodyData['id'];

            $bodyData['automation_id'] = $id;
            $bodyData['updated_at'] = date('Y-m-d H:i:s');

            unset($bodyData['created_at']);

            RcAutomationBodyText::where('id', $bodyId)->update($bodyData);

            if($automationtype == 'campaign' && $data['campaign_sending_type'] == 0){
                $campaign = RcAutomation::with('BodyText')->where('id', $id)->first();
                ($campaign) ? SendCampaignJob::dispatch($campaign, $user) : '';
            }
            return response()->json(['data' => 'Saved'], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendTestAutomationMail(Request $request){
        try{
            $user = Auth::user();
            $shop = RcShop::where('user_id', $user->id)->first();

            $automation = $request->data;
            $automation['customer'] = [
                'first_name' => 'John',
                'last_name' => 'Doe',
            ];

            $automation['shop'] = $shop;
            $automation['cart_link'] = $shop->domain;
            $automation['line_items'] = [
                [
                    'title' => 'Test Product',
                    'quantity' => 1,
                    'price' => 50.00,
                    'total_price' => 50.00,
                    'image' => '',
                ]
            ];
            $automation['checkout'] = [
                'id' => '',
                'currency_symbol' => '$',
                'total_line_items_price' => 50.00,
            ];
            $automation['body_text']['subject'] = 'Send test mail from royal cart';

            if($automation['reminder_type'] == 'email'){
                $fromMail = ($automation['sender_provider'] != '' ) ? $automation['sender_provider'] : $shop->email;
                $msg = sendMailH($automation, $fromMail, $request->receiver, $shop->name, $user->id, 'YMBg12', []);
            }else if($automation['reminder_type'] == 'sms'){
                $msg = createSMSH($automation, $request->receiver, $user->id, 'YMBg12', []);
            }

            $type = ($automation['reminder_type'] == 'email') ? 'Mail' : 'SMS';
            if($msg == 'success'){
                return response()->json(['data' => 'Test '.$automation['reminder_type'].' sent successfully.'], 200);
            }
            return response()->json(['data' => $type.' not sent.'], 422);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus($id){
        try{
            $automation = RcAutomation::find($id);
            if($automation){
                $automation->is_active = !$automation->is_active;
                $automation->save();
            }
            return response()->json(['data' => 'Saved'], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id){
        try{
            $automation = RcAutomation::find($id);
            $automation->delete();

            return response()->json(['data' => 'Deleted'], 200);
        }catch ( \Exception $e ){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    /**
     * @param $customer_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function unsubscribeCustomer($type, $customer_id){
         try{
            $customer = RcCustomer::find($customer_id);
            $user = User::find($customer->user_id);
             $t = 'is_emailsubscriber';
             $t = ($type == 'sms') ? 'is_smssubscriber' : $t;
             $t = ($type == 'push') ? 'is_pushsubscriber' : $t;

            if($customer){
                $customer->$t = 0;
                $customer->save();
            }

             $udata = [
                'unsubscriber' => 1
             ];
             $this->runAnalytics($user, $type, $udata);
            return view('sms.unsubscribe');
        }catch ( \Exception $e ){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }
}
