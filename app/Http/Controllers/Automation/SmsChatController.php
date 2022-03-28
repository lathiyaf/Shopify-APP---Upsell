<?php

namespace App\Http\Controllers\Automation;

use App\Http\Controllers\Controller;
use App\Models\RcAbandonedCheckout;
use App\Models\RcConversation;
use App\Models\RcCustomer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;
use App\Traits\BaseTrait;

class SmsChatController extends Controller
{
    use BaseTrait;
    public function index(Request $request){
        try{
            $user = Auth::user();
            $chat = $this->fetchAllConversation($user->id);
            $data['chat'] = $chat;
            $data['from'] = env('TWILIO_FROM');
            return response()->json(['data' => $data], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    public function fetchAllConversation($user_id){
        try {
            $finalChat = [];
            $db_conversations = RcConversation::select('phone', 'conversation_sid', 'participant_sid')->where('user_id', $user_id)->where('participant_sid', '!=', '')->orderBy('created_at', 'desc')->get();

            $twillio = getTwillioH();
            foreach ($db_conversations as $conv_key=>$conv_val){

                $user_conversations = $twillio->conversations->v1->conversations($conv_val->conversation_sid)
                    ->messages
                    ->read([]);

                if(count($user_conversations) > 0) {
                    foreach ($user_conversations as $user_conv_key => $user_conv_val) {
                        $chat[$conv_key]['message'][$user_conv_key] = $user_conv_val->toArray();
                    }

                    $msg = $chat[$conv_key]['message'];
                    $is_id = array_search($conv_val->phone, array_column($msg, 'author'));
                //    if (false !== $is_id){
                        $db_checkout = RcAbandonedCheckout::find($conv_val->db_checkout_id);
                        if($db_checkout) {
                            $db_customer = RcCustomer::select('first_name', 'last_name')->where('sh_customer_id',
                                $db_checkout->sh_customer_id)->first();
                            if ($db_customer) {
                                $customer['name'] = $db_customer->first_name.' '.$db_customer->last_name;
                            }
                        }else{
                            $customer['name'] = '';
                        }
                        $customer['phone'] = $conv_val->phone;

                        $msgs = collect($chat[$conv_key]['message'])->map(function ($name) {
                            return [
                                'conversationSid' => $name['conversationSid'],
                                'author' => $name['author'],
                                'body' => $name['body'],
                                'dateCreated' => \Carbon\Carbon::parse($name['dateCreated'])->format('Y-m-d H:i:s'),
                            ];
                        })->toArray();

                        $c['messages'] = $msgs;
                        $c['customer'] = $customer;
                        $finalChat[] = $c;
                //    }
                }
            }
            return $finalChat;
        }catch(\Exception $e){
            logger("========== ERROR :: fetchAllConversation =========");
            logger($e);
        }
    }

    public function sendSms(Request $request){
        try{
            $user = Auth::user();
            $data = $request->data;

            $twillio = getTwillioH();
            $res = $twillio->conversations->v1->conversations($data['conversationId'])
                ->messages
                ->create([
                        "body" => $data['message'],
                    ]
                );

                $to = $data['phone'];
                $price = getSmsPriceH($to);
                $usageCharge = $this->createUsageCharge($user, $price, null);

            return response()->json(['data' => 'Sms Sent', 'isSuccess' => true, 'date' => date('Y-m-d H:i:s')], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }
}
