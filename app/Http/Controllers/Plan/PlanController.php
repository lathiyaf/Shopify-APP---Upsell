<?php

namespace App\Http\Controllers\Plan;

use App\Http\Controllers\Controller;
use App\Models\RcCounter;
use App\Models\RcOneTimeFund;
use App\Models\User;
use App\Traits\BaseTrait;
use App\Traits\PlanControllerTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Osiset\ShopifyApp\Storage\Models\Charge;
use Osiset\ShopifyApp\Storage\Models\Plan;

class PlanController extends Controller
{
    use PlanControllerTrait, BaseTrait;

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlan(){
        try{
            $data['plan'] = $this->getPlanT();
            return response()->json(['data' => $data], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSidebar(){
        try{
            $user = Auth::user();

            $charge = Charge::select('user_id', 'balance_remaining', 'trial_ends_on')->where('user_id', $user->id)->where('status', 'ACTIVE')->orderBy('created_at', 'desc')->first();
            $data['charge'] = $charge;
            $data['credit_msg'] = ($charge) ? $this->getTrailMessage($charge) : '';
            $data['user_id'] = $user->id;
            return response()->json(['data' => $data], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlans(){
        try{
            $shop = Auth::user();
            $data = $this->getPlansT($shop);
            return response()->json(['data' => $data], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function pricingView(){
        return view('pricing');
    }

    /**
     * @param $plan
     * @return \Illuminate\Http\JsonResponse
     */
    public function appPlanChange($plan)
    {
        try {
            $user = \Auth::user();

            $new_plan = $plan;
            $db_plan = Plan::where('id', $plan)->first();

            $db_charge = Charge::where('user_id', $user->id)->where('status', 'ACTIVE')->orderBy('created_at', 'desc')->first();

            if( $db_charge ){
                $curr_date = date('Y-m-d H:i:s');
                $to = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $db_charge->trial_ends_on);
                $from = $curr_date;
                $trial_days = ( $to > $from ) ? $to->diffInDays($from) + 1 : 0;
            }else{
                $trial_days = $db_plan->trial_days;
            }

            $oneTimeFund = RcOneTimeFund::find(1);
            $parameter = [
                'recurring_application_charge' => [
                    "name"=> $db_plan->name,
                    "trial_days"=> $trial_days,
                    "price"=> $db_plan->price,
                    "return_url"=> env('APP_URL') ."/update-charge/". $user->id,
                    "capped_amount"=> $oneTimeFund->price,
                    "terms"=> $db_plan->terms,
                    "test" => $db_plan->test,
                ]
            ];

            $result = $user->api()->rest('POST', 'admin/api/recurring_application_charges.json', $parameter);

            $data = $result['body']->container['recurring_application_charge'];
            if( !$result['errors'] ){
                return response()->json(['data' => $data], 200);
            }else{
                return response()->json(['data' => $data], 422);
            }
        } catch (\Exception $e) {
            return response()->json(['data' => $e], 422);
        }
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addFundOneTime(Request $request){
        try{
            $user = Auth::user();
            $data = $request->data;

            $recurringCharge = Charge::where('user_id', $user->id)->where('status', 'ACTIVE')->orderBy('created_at', 'desc')->first();
            $amount = $recurringCharge->capped_amount + $data['price'];
            $parameter = [
                'recurring_application_charge' => [
                    'id' => $recurringCharge->charge_id,
                    'capped_amount' => $amount
                ]
            ];

            $endPoint = '/admin/api/2021-07/recurring_application_charges/'.$recurringCharge->charge_id .'/customize.json';
            $resultAddFunds = $user->api()->rest('PUT', $endPoint, $parameter);

            if(!$resultAddFunds['errors']){
                $data = $resultAddFunds['body']->container['recurring_application_charge'];

                $returnURL = $data['update_capped_amount_url'];
                return response()->json(['data' => $returnURL], 200);
            }else{
                logger('============= ERORR :: Capped amount not updated ============');
                logger(json_encode($resultAddFunds));
                return response()->json(['data' => 'ERROR!'], 422);
            }

        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    /**
     * @param  Request  $request
     * @param $amount
     * @param $user_id
     * @param $oneTimeId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateCharge(Request $request,$user_id){
        try{
            $user = User::find($user_id);
            $old_charge = Charge::where('status', 'ACTIVE')->where('user_id', $user_id)->orderBy('created_at', 'desc')->first();


            if( $old_charge ){
                $oldCappedamount = $old_charge->capped_amount;
                $old_charge->status = 'CANCELLED';
                $old_charge->cancelled_on = date('Y-m-d H:i:s');
                $old_charge->save();
            }
            $response = $user->api()->rest("GET",'/admin/api/'.env('SHOPIFY_API_VERSION').'/recurring_application_charges/'.$request->charge_id);

            if( !$response['errors'] ){
                $charge_data = $response['body']->container['recurring_application_charge'];

                $oneTimeFund = RcOneTimeFund::find(1);
                if($old_charge){
                    $cappedDiff = $charge_data['capped_amount'] - $old_charge->capped_amount;
                    if($cappedDiff > 0){
                        $oneTimeFund = RcOneTimeFund::where('price', $cappedDiff)->first();
                    }
                }

                $plan = Plan::where('name', $charge_data['name'])->first();
                $charge = new Charge;
                $charge->charge_id = $charge_data['id'];
                $charge->test = $charge_data['test'];
                $charge->status = strtoupper($charge_data['status']);
                $charge->name = $charge_data['name'];
                $charge->terms = $plan->terms;
                $charge->interval = $plan->interval;
                $charge->capped_amount = $charge_data['capped_amount'];
                $charge->type = 'RECURRING';
                $charge->price = $charge_data['price'];
                $charge->trial_days = $charge_data['trial_days'];
                $charge->billing_on = date("Y-m-d H:i:s", strtotime($charge_data['billing_on']));
                $charge->activated_on = date("Y-m-d H:i:s", strtotime($charge_data['activated_on']));
                $charge->trial_ends_on = date("Y-m-d H:i:s", strtotime($charge_data['trial_ends_on']));
                $charge->balance_used = $charge_data['balance_used'];
                $charge->balance_remaining = $charge_data['balance_remaining'];
                $charge->bonus = (float)$oneTimeFund->bonus;
                $charge->bonus_used = 0;
                $charge->created_at = date("Y-m-d H:i:s", strtotime($charge_data['created_at']));
                $charge->updated_at = date("Y-m-d H:i:s", strtotime($charge_data['updated_at']));
                $charge->plan_id = $plan->id;
                $charge->user_id = $user->id;
                $charge->save();

                $oldCounter = RcCounter::where('user_id', $user->id)->where('status', 'active')->first();
                if($oldCounter){
                    $oldCounter->status = 'canceled';
                    $oldCounter->save();
                }

                $counter = new RcCounter;
                $counter->user_id = $user->id;
                $counter->db_charge_id = $charge->id;
                $counter->shopify_charge_id = $charge_data['id'];
                $counter->plan_id = $plan->id;
                $counter->fund_type = 'onetime';
                $counter->onetime_amount = $oneTimeFund->price;
                $counter->onetime_bonus = $oneTimeFund->bonus;
                $counter->status = 'active';
                $counter->save();

                $user->plan_id = $plan->id;
                $user->save();
            }

            Auth::login($user);

            return redirect()->route('home');
        }catch(\Exception $e){
            logger('============ ERROR :: updateCharge ============');
            logger($e);
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOnetimeFunds(){
        try{
            $data['oneTimeFunds'] = RcOneTimeFund::select('id', 'price', 'bonus')->get();
            return response()->json(['data' => $data], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecurFund(){
        try{
            $user = \Auth::user();
            $data['recur'] = RcCounter::where('user_id', $user->id)->where('fund_type', 'recur')->where('status', 'active')->orderBy('created_at', 'desc')->first();
            return response()->json(['data' => $data], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    public function enableRecur(Request $request){
        try{
            $user = \Auth::user();
            $data = $request->data;

            $db_charge = Charge::where('user_id', $user->id)->where('status', 'ACTIVE')->orderBy('created_at', 'desc')->first();
            $counter = new RcCounter;
            $counter->user_id = $user->id;
            $counter->db_charge_id = $db_charge->id;
            $counter->shopify_charge_id = $db_charge->charge_id;
            $counter->plan_id = $db_charge->plan_id;
            $counter->fund = $data['fund'];
            $counter->fund_type = $data['fund_type'];
            $counter->recur_min = $data['recur_min'];
            $counter->recur_max = $data['recur_max'];
            $counter->status = 'active';
            $counter->save();
            return response()->json(['data' => 'Saved!'], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }
}
