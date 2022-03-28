<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\RcAutomation;
use App\Models\RcSetting;
use App\Models\RcShop;
use App\Models\User;
use App\Traits\PublishAppTrait;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\CheckOrderUpdate;
use Illuminate\Support\Facades\Http;
use Osiset\ShopifyApp\Storage\Models\Charge;

class TestController extends Controller
{
    use  PublishAppTrait;
    public function index(Request $request){
        try{
            $user = User::find(18);
//            dd(createSMSH('', '+919737499391', 18, '', ''));
            dd($this->smsConversation('+33757130734'));
//            $to  = '91 97374 99391';
//            $to = '+1 (581) 980-9990';
//            getSmsPriceH($to);
            $endPoint = 'https://api.twilio.com/2010-04-01/Accounts/ACef36039f97d50d3135013b3902d0f315/Messages.json?PageSize=50&Page=1&PageToken=PASM62eaf6d7011d406b915cb93abcd5ffd2';

            $result = Http::withBasicAuth(
                    env("TWILIO_SID"),
                    env("TWILIO_TOKEN")
                )->get($endPoint);
//            $res = $client->get($endPoint, [
//                'auth' => [
//                    env("TWILIO_SID"),
//                    env("TWILIO_TOKEN")
//                ]
//            ]);

            dump($result);
            dd($result->json());

//            $to = '+1 (581) 980-9990';
//            $to  = '+1 (581) 980-9990';
            sendSMSH('', $to, '', '', '');
            dd('1111');
            // $name = 'crawlapps-info.myshopify.com';
           $name = 'developmenttapp.myshopify.com';
            $user = User::where('name', $name)->first();
            $autmation = RcAutomation::with('BodyText')->where('reminder_type', 'email')->orderBy('created_at', 'desc')->first();
            dd($autmation->BodyText);
//            $
//            $result = $this->createDiscountCode(5, 'email' ,$user);
//            dd($result);
            $webhooks = $user->api()->rest('GET', '/admin/webhooks.json');

            dd($webhooks);
        }catch(\Exception $e){
            dd($e);
        }
    }

    function smsConversation($to){
        try{
            $account_sid = env("TWILIO_SID");
            $auth_token = env("TWILIO_TOKEN");
            $twilio_number = env("TWILIO_FROM");
            $twilio = new Client($account_sid, $auth_token);

            $conversationID = SmsConversationIdH($to, 18, '');

            dump($conversationID);
//            1. create service
//            2. create conversation
//            3. create participate
//            4. create message

//            $service = $twilio->messaging->v1->services
//                ->create("Royal cart");
//
//            dd($service);
        //    $conversation = $twilio->conversations->v1->conversations
        //        ->create([
        //                "friendlyName" => "Test Response Conversation"
        //            ]
        //        );
//            dd($conversation);

//            $participant = $twilio->conversations->v1->conversations("CH67cfda6b073f4bbdb1f6f96680d4503b")
//                ->participants
//                ->create([
//                        "messagingBindingAddress" => $to,
//                        "messagingBindingProxyAddress" => $twilio_number
//                    ]
//                );
//
//            dd($participant);

           $message = $twilio->conversations->v1->conversations($conversationID)
               ->messages
               ->create([
                       "body" => "Test Service",
                   ]
               );
           dd($message);
//            "accountSid" => "ACef36039f97d50d3135013b3902d0f315"
//    "conversationSid" => "CH67cfda6b073f4bbdb1f6f96680d4503b"
//    "sid" => "IM53fec5d18e984ee8bfefe77413ccf8f7"
//    "index" => 0
//    "author" => "system"
//    "body" => "Test chat"
//    "media" => null
//    "attributes" => "{}"
//    "participantSid" => null
//    "dateCreated" => DateTime @1634709000 {#1229 ▶}
//                "dateUpdated" => DateTime @1634709000 {#1318 ▶}
//                    "url" => "https://conversations.twilio.com/v1/Conversations/CH67cfda6b073f4bbdb1f6f96680d4503b/Messages/IM53fec5d18e984ee8bfefe77413ccf8f7"
//    "delivery" => null
//    "links" => array:1 [▶]

            $messages = $twilio->conversations->v1->conversations("CH67cfda6b073f4bbdb1f6f96680d4503b")
                ->messages
                ->read([], 20);
dump($messages);
//get_class_methods(Twilio\Rest\Conversations\V1\Conversation\MessageInstance);
//            dd(get_class_methods('Twilio\Rest\Conversations\V1\Conversation\MessageInstance'));
            dd($messages[0]->toArray());
        }catch(\Exception $e){
            dd($e);
        }
    }
    function sendSMSTest($to)
    {
        try {
            logger('send sendSMSH');
            $receiverNumber = $to;

            $message = 'Testing response';
            $account_sid = env("TWILIO_SID");
            $auth_token = env("TWILIO_TOKEN");
            $twilio_number = env("TWILIO_FROM");

            $client = new Client($account_sid, $auth_token);

            $res = $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'body' => $message
            ]);
            logger('=========== SMSSSSS :: RESULT ============');
            $r = $res->toArray();
           return $r;
        } catch (\Exception $e) {
            logger($e);
            return $e->getMessage();
        }
    }
}
