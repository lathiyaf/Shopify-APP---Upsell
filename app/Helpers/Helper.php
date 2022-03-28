<?php

use App\Models\RcAutomation;
use App\Models\RcAutomationTrack;
use App\Models\RcSetting;
use App\Models\RcSmsCost;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Intl\Currencies;
use App\Traits\GraphQLTrait;
use Twilio\Rest\Client;
use App\Traits\BaseTrait;
if (!function_exists('currencyH')) {

    /**
     * @return mixed
     * @return mixed
     */
    function currencyH($c)
    {
        return Currencies::getSymbol($c);
    }
}

if (!function_exists('calculateRates')) {

    /**
     * @return mixed
     * @return mixed
     */
    function calculateRates($impression, $conversation)
    {
        return (($impression / $conversation) * 100);
    }
}

if (!function_exists('generateCode')) {
    /**
     * @return mixed
     * @return mixed
     */
    function generateCode()
    {
        $letters = range('A', 'Z');
        $digits = range(0,9);

        shuffle($letters);
        shuffle($digits);

        $random = rand(0,20);
        $code = array_slice($letters, $random, 4);
        $code[count($code)] = implode('', array_slice($digits, 5,1));
        shuffle($code);
        return implode('', $code);
    }
}

if (!function_exists('generateShortURL')) {
    /**
     * @return mixed
     * @return mixed
     */
    function generateShortURL($url)
    {
        $builder = new \AshAllenDesign\ShortURL\Classes\Builder();

        $shortURLObject = $builder->destinationUrl($url)->make();

        return $shortURLObject;
    }
}

if (!function_exists('generatePreCartURL')) {
    /**
     * @return mixed
     * @return mixed
     */
    function generatePreCartURL($url, $lineItem)
    {
       $parse = parse_url($url);
       $host = $parse['scheme'] . '://' . $parse['host'] . '/cart/';

       $variantString = '';
       foreach ($lineItem as $key => $value) {
           $variantString .= $value['sh_variant_id'] . ':' . $value['quantity'];
           $variantString .= ($key != count($lineItem) - 1) ? ',' : '';
       }

       return $host . $variantString;
    }
}

if (!function_exists('generateDateRangeH')) {
    /**
     * @return mixed
     * @return mixed
     */
    function generateDateRangeH($dateRange)
    {
        $rangeArr = [];
        $period = new DatePeriod(
            new \DateTime($dateRange['startDate']),
            new \DateInterval('P1D'),
            new \DateTime($dateRange['endDate'])
        );
        foreach ($period as $key => $value) {
            $rangeArr[] = $value->format('Y-m-d');
        }
        return $rangeArr;
    }
}

if (!function_exists('getSmsPriceH')) {
    /**
     * @return mixed
     * @return mixed
     */
    function getSmsPriceH($mobileNumber)
    {
        try{
            logger('============ START :: getSmsPriceH ==========');
            $mobileNumber = preg_replace("/[^0-9]/","", $mobileNumber);
            $costs = RcSmsCost::orderBy('dial_code', 'asc')->get();

            $iso = 'US';
            foreach( $costs as $key=>$value )
            {
                if ( substr( $mobileNumber, 0, strlen( $value['dial_code'] ) ) == $value['dial_code'] )
                {
                    // match
                    $iso = $value['iso'];
                    break;
                }
            }

            $cost = RcSmsCost::where('iso', $iso)->max('price');
            $c = ($cost) ?: 0.175;

            return round(($c * 3), 2);
        }catch(\Exception $e){
            logger('============ ERROR :: getSmsPriceH ==========');
            logger(json_encode($e));
        }
    }
}

if (!function_exists('isEligibleForSmsH')) {
    /**
     * @return mixed
     * @return mixed
     */
    function isEligibleForSmsH($mobileNumber, $user)
    {
        try{
            logger('============ START :: isEligibleForSmsH ==========');
            $setting = RcSetting::select('sms_countries', 'is_enable_sms_max_price', 'sms_max_price')->where('user_id', $user->id)->orderBy('created_at', 'desc')->first();

            $mobileNumber = preg_replace("/[^0-9]/","", $mobileNumber);
            $costs = RcSmsCost::orderBy('dial_code', 'asc')->get();

            $iso = 'US';
            foreach( $costs as $key=>$value )
            {
                if ( substr( $mobileNumber, 0, strlen( $value['dial_code'] ) ) == $value['dial_code'] )
                {
                    // match
                    $iso = $value['iso'];
                    break;
                }
            }
            $isSend = false;
            $eligibleISo = json_decode($setting->sms_countries);
            if($eligibleISo != null){
                $isSend = in_array("all", $eligibleISo);
                if(!$isSend){
                    $isSend = in_array($iso, $eligibleISo);
                }
            }
            if($isSend){
                $cost = RcSmsCost::where('iso', $iso)->max('price');

                logger("================== PRICE :: $cost ==================");
                $c = ($cost) ?: 0.175;

                if($setting->is_enable_sms_max_price){
                    $isSend = ((float)$c < (float)$setting->sms_max_price);
                }else{
                    $isSend = true;
                }
            }

            logger("================== IS SEND :: $isSend ==================");
            return $isSend;
        }catch(\Exception $e){
            logger('============ ERROR :: isEligibleForSmsH ==========');
            logger(json_encode($e));
        }
    }
}

if (!function_exists('sendMailH')) {

    /**
     * @return mixed
     * @return mixed
     */
    function sendMailH($automation, $from, $to, $fromname, $userId, $discountCode, $trackAutomation)
    {
        try {
            logger('send mailllll');
            $automation = updateTagsH($automation, $discountCode, 'email', $trackAutomation);
            $subject = $automation['body_text']['subject'];
            $to = str_replace(' ', '', $to);

            $data = array('data' => $automation);


            $res = Mail::send('mail.automation', $data, function ($message) use ($subject, $from, $to, $fromname, $automation) {
                $message->from($from, $fromname);
                $message->to($to);
                $message->subject($subject);
                $message->getHeaders()->addTextHeader('X-Automation-ID',$automation['id']);
            });
            logger($res);
            return 'success';
        } catch (\Exception $e) {
            logger($e);
            return $e->getMessage();
        }
    }
}

if (!function_exists('createSMSH')) {

    /**
     * @return mixed
     * @return mixed
     */
    function createSMSH($automation, $to, $userId, $discountCode, $trackAutomation)
    {
        try {
            logger('send sendSMSH');

            $automation = updateTagsH($automation, $discountCode, 'sms', $trackAutomation);

            $message = $automation['body_text']['body_text'] . ' ' . $automation['body_text']['unsubscribe_text'];
//                $message = 'testing response';
                $conversationId = SmsConversationIdH($to, $userId, $automation['checkout']['id']);

                logger("===== Conversation ID :: ".$conversationId." ====");
//                $res = $client->messages->create($receiverNumber, [
//                    'from' => $twilio_number,
//                    'body' => $message
//                ]);
                $twillio = getTwillioH();
                $res = $twillio->conversations->v1->conversations($conversationId)
                    ->messages
                    ->create([
                            "body" => $message,
                        ]
                    );
                logger('=========== SMSSSSS :: RESULT ============');
                $r = $res->toArray();
                logger(json_encode($r));
                if(!empty($trackAutomation)){

                    $price = getSmsPriceH($to);
                    logger('PRICE :: ' . $price);

                    $ta = RcAutomationTrack::find($trackAutomation['id']);

                    $ta->cost = $price;
                    $ta->response = json_encode($res->toArray());
                    $ta->save();

                    $a = RcAutomation::find($automation['id']);
                    $a->cost = $a->cost + $price;
                    $a->save();
//
                    $rr['msg'] = 'success';
                    $rr['cost'] = $price;
                    return $rr;
                }else{

                    return 'success';
                }

        } catch (\Exception $e) {
            logger($e);
            return $e->getMessage();
        }
    }
}

if (!function_exists('getTwillioH')) {

    /**
     * @return mixed
     * @return mixed
     */
    function getTwillioH()
    {
        try {
            $twilio['account_sid'] = env("TWILIO_SID");
            $twilio['auth_token'] = env("TWILIO_TOKEN");
            $twilio['twilio_number'] = env("TWILIO_FROM");

            return new Client($twilio['account_sid'], $twilio['auth_token']);
        } catch (\Exception $e) {
            logger($e);
        }
    }
}

if (!function_exists('SmsConversationIdH')) {

    /**
     * @return mixed
     * @return mixed
     */
    function SmsConversationIdH($to, $user_id, $db_checkout_id)
    {
        try {
            logger('========== SmsConversationIdH =========');
            $conversation = \App\Models\RcConversation::where('user_id', $user_id)->where('phone', $to)->first();
            if($conversation){
                if($conversation->participant_sid == ''){
                    // add participant

                    $conversation->participant_sid =  AddParticipantH($conversation->conversation_sid, $to);
                    $conversation->save();
                }
            }else{
                // create conversation
                $conversation_sid = createConversationH($to);
                $participant_sid = AddParticipantH($conversation_sid, $to);

                $conversation = new \App\Models\RcConversation();
                $conversation->user_id = $user_id;

                if($db_checkout_id != ''){
                    $conversation->db_checkout_id = $db_checkout_id;
                }
                $conversation->phone = $to;
                $conversation->conversation_sid = $conversation_sid;
                $conversation->participant_sid = $participant_sid;
                $conversation->friendly_name = 'Conversation of ' . $to;
                $conversation->save();
            }
            return $conversation->conversation_sid;

        } catch (\Exception $e) {
            logger('========== ERROR :: SmsConversationIdH =========');
            logger($e);
        }
    }
}

if (!function_exists('AddParticipantH')) {

    /**
     * @return mixed
     * @return mixed
     */
    function AddParticipantH($conversationSid, $to)
    {
        try {
            $twillio = getTwillioH();
            $participant = $twillio->conversations->v1->conversations($conversationSid)
                ->participants
                ->create([
                        "messagingBindingAddress" => $to,
                        "messagingBindingProxyAddress" => env('TWILIO_FROM')
                    ]
                );
            logger('========== AddParticipantH =========');
            logger($participant);
            $participant = $participant->toArray();
            return $participant['sid'];
        } catch (\Exception $e) {
            logger($e);
        }
    }
}

if (!function_exists('createConversationH')) {

    /**
     * @return mixed
     * @return mixed
     */
    function createConversationH($to)
    {
        try {
            $twillio = getTwillioH();
            $conversation = $twillio->conversations->v1->conversations
                ->create([
                        "friendlyName" => 'Conversation of ' . $to
                    ]
                );

            logger('========== createConversationH =========');
            logger($conversation);
            $conversation = $conversation->toArray();
            return $conversation['sid'];
        } catch (\Exception $e) {
            logger('========== ERROR :: createConversationH =========');
            logger($e);
        }
    }
}
if (!function_exists('getSMSH')) {

    /**
     * @return mixed
     * @return mixed
     */
    function getSMSH($to)
    {
        try {
            logger('send sendSMSH');

            $account_sid = env("TWILIO_SID");
            $auth_token = env("TWILIO_TOKEN");
            $twilio_number = env("TWILIO_FROM");

            $twilio = new Client($account_sid, $auth_token);
//
//            $res = $client->messages
//                ->read([
//                    "from" => $twilio_number,
//                    "to" => "+919737499391"
//                ]);
            $res = $twilio->conversations->v1->conversations
                ->read(20);
            logger('=========== SMSSSSS :: RESULT ============');
//            $r = $res->toArray();
            return $res;
        } catch (\Exception $e) {
            logger($e);
            return $e->getMessage();
        }
    }
}

if (!function_exists('sendNotificationH')) {
    /**
     * @param $str
     * @return bool|false|string
     */
    function sendNotificationH($users, $message, $heading, $url)
    {
        $rest_api_key = env('ONESIGNAL_REST_API_KEY');
        $app_id = env('ONESIGNAL_APPID');

        $content = array(
            "en" => $message,
        );

        foreach ( $users as $key=>$val ){
            $filters[] = (array("field" => "tag", "key" => "user_id", "relation" => "=", "value" => $val));

            if( count($users) != ($key + 1) ){
                $filters[] = ["operator"=> "OR"];
            }
        }
        $fields = array(
            'filters' => $filters,
            'app_id' => $app_id,
            'contents' => $content,
            'headings' => array("en"=>$heading),
            'url' => $url
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic '. $rest_api_key ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);


        return $response;
    }
}

if (!function_exists('updateTagsH')) {

    /**
     * @return mixed
     * @return mixed
     */
    function updateTagsH($automation, $discountCode, $type, $trackAutomation)
    {
        try {

            $html = ['subject', 'email_preview', 'header', 'before_cart_body', 'after_cart_body', 'discount_banner_text', 'footer', 'cart_title', 'product_description', 'product_price', 'product_qty', 'product_total', 'cart_total', 'button_text', 'headline', 'body_text', 'unsubscribe_text'];

            if(!empty($trackAutomation)) {
                $automation['cart_link'] = $automation['cart_link'].'?attributes[automation]='.$trackAutomation->id;
                if($discountCode != ''){
                    $automation['cart_link'] .= '&discount=' . $discountCode;
                }

                if($type == 'email' && !empty($trackAutomation)){
                    $cartLink = '<a class="" target="_blank" href="' . $automation['cart_link'] . '">';
                }else if(($type == 'sms' || $type == 'push') && !empty($trackAutomation)){
                    $cartLinkObject = generateShortURL($automation['cart_link']);
                    $cartLink = $cartLinkObject->default_short_url;

                    if(!empty($trackAutomation)){
                        $trackAutomation->short_url_id = $cartLinkObject->id;
                        $trackAutomation->save();
                    }

                    $cartLink = formatLinkH($cartLink, 'http://', '');
                    $cartLink = formatLinkH($cartLink, 'https://', '');
                }


                $unsubscribeLink = env('APP_URL') . '/unsubscribe/'.$type.'/' . $automation['customer']['id'];

                $unsubscribeLinkObject = generateShortURL($unsubscribeLink);
                $unsubscribeShortLink = $unsubscribeLinkObject->default_short_url;

                $unsubscribeShortLink = formatLinkH($unsubscribeLink, 'http://', '');
                $unsubscribeShortLink = formatLinkH($unsubscribeLink, 'https://', '');

                $emailsubscribeLink = '<a class="" target="_blank" href="' . $unsubscribeLink . '">';
            }else{
                $cartLink = $automation['cart_link'];
                $unsubscribeShortLink = '';
                $emailsubscribeLink = '';
            }

           foreach ($html as $hkey=>$hval){

               $automation['body_text'][$hval] = str_replace('{domain}', $automation['shop']['myshopify_domain'], $automation['body_text'][$hval]);

               $automation['body_text'][$hval] = str_replace('{firstName}', $automation['customer']['first_name'], $automation['body_text'][$hval]);

               $automation['body_text'][$hval] = str_replace('{lastName}', $automation['customer']['last_name'], $automation['body_text'][$hval]);

               $automation['body_text'][$hval] = str_replace('{siteName}', $automation['shop']['domain'], $automation['body_text'][$hval]);

               $automation['body_text'][$hval] = str_replace('{cartLink}', $cartLink, $automation['body_text'][$hval]);

               $automation['body_text'][$hval] = str_replace('{/cartLink}', '</a>', $automation['body_text'][$hval]);

               $automation['body_text'][$hval] = str_replace('{campaignLink}', $automation['shop']['domain'], $automation['body_text'][$hval]);

               $automation['body_text'][$hval] = str_replace('{/campaignLink}', '</a>', $automation['body_text'][$hval]);

               $automation['body_text'][$hval] = str_replace('{unsubscribeLink}', $emailsubscribeLink, $automation['body_text'][$hval]);

               $automation['body_text'][$hval] = str_replace('{/unsubscribeLink}', '</a>', $automation['body_text'][$hval]);

               $automation['body_text'][$hval] = str_replace('{discountValue}', $automation['discount_value'], $automation['body_text'][$hval]);

               $automation['body_text'][$hval] = str_replace('{discountCode}', $discountCode, $automation['body_text'][$hval]);

               $automation['body_text'][$hval] = str_replace('{storeEmail}', $automation['shop']['email'], $automation['body_text'][$hval]);

               if(($type == 'sms' || $type == 'push') && $hval == 'unsubscribe_text'){
                   $automation['body_text'][$hval] = ' ' . $automation['body_text'][$hval] . ' ' . $unsubscribeShortLink;
               }
           }

           return $automation;

        } catch (\Exception $e) {
            logger($e);
            return $e->getMessage();
        }
    }

    if (!function_exists('formatLinkH')) {

    /**
     * @return mixed
     * @return mixed
     */
    function formatLinkH($link, $search, $replace)
    {
        try {
             return str_replace($search, $replace, $link);
        } catch (\Exception $e) {
            logger($e);
            return $e->getMessage();
        }
    }
}
}
