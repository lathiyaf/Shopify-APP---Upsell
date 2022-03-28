<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RcShop;
use App\Models\RcWelcomePush;
use App\Models\User;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use Osiset\ShopifyApp\Storage\Models\Plan;
use Response;

class PlanController extends Controller
{
    use BaseTrait;
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlans(){
        try{
            $plans = Plan::select('id', 'name', 'price', 'upto')->get();
            $data['plans'] = $plans;

            return response()->json(['data' => $data], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    public function getWelcomePush(Request $request){
        try{
            $domain = $request->shop;
            $user = User::where('name', $domain)->first();
            $shop = RcShop::where('user_id', $user->id)->first();

            $welcomePush = RcWelcomePush::where('user_id', $user->id)->where('active', 1)->orderBy('created_at', 'desc')->first();

            if($welcomePush){
                $preMadeDisc = ($welcomePush->discount_type == 1) ? $welcomePush->discount_code : '';
                $discountCode = ($welcomePush->discount_type == 0 && $welcomePush->discount_value > 0) ? $this->createDiscountCode($welcomePush->discount_value, 'welcome', $user, 'push') : $preMadeDisc;

                $welcomePush = $welcomePush->toArray();
                $welcomePush['logo'] =  $welcomePush['logo'] = ($welcomePush['logo'] == '10.png' || $welcomePush['logo'] == 'welcome-push-img.jpg') ? url('images/'.$welcomePush['logo']) : \Storage::disk('public')->url('images/uploads/'.$welcomePush['logo']);

                $updateData = $welcomePush;
                $updateData = $this->updateTagApi($updateData, $shop, $discountCode);

                return Response::json([
                    'isSuccess' => true,
                    'welcome_push' => \View::make('push.welcome', ["data" => $updateData])->render(), "data" => []
                ], 200);
            }else{
                return Response::json([
                    'isSuccess' => true,
                    'welcome_push' => '',
                    "data" => []
                ], 200);
            }

        }catch(\Exception $e){
            logger($e);
            return response()->json(['data' => $e, 'isSuccess' => false], 422);
        }
    }

    public function updateTagApi($welcomePush, $shop, $discountCode){
        try{
            $tagType = ['headline', 'body_text'];

            foreach ($tagType as $key=>$val){
                $welcomePush[$val] = str_replace('{discountValue}', $welcomePush['discount_value'], $welcomePush[$val] );
                $welcomePush[$val] = str_replace('{discountCode}', $discountCode, $welcomePush[$val] );
                $welcomePush[$val] = str_replace('{storeEmail}', $shop->email, $welcomePush[$val] );
                $welcomePush[$val] = str_replace('{siteName}', $shop->domain, $welcomePush[$val] );
            }
            return $welcomePush;
        }catch(\Exception $e){
            logger("=========== ERROR :: getWelcomePush ==========");
            logger($e);
        }
    }
}
