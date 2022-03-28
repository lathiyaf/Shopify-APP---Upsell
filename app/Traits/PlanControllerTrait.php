<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Osiset\ShopifyApp\Storage\Models\Plan;

trait PlanControllerTrait
{
    /**
     * Get current login user's plan id.
     */
    public function getPlanT(){
        try{
            $shop = Auth::user();
            return $data['plans'] = $shop->plan_id;
        }catch(\Exception $e){
          logger("============= ERROR :: getPlanT =============" . $e->getMessage());
        }
    }

    /**
     * Get plans data.
     */
    public function getPlansT($shop){
        try{
            $plans = Plan::select('id', 'name', 'price', 'upto')->get();
            $data['plans'] = $plans;
            $data['shop'] = $shop->name;
            $data['curr_plan'] = $shop->plan_id;
            $data['is_published'] = $shop->is_published;

            return $data;
        }catch(\Exception $e){
            logger("============= ERROR :: getPlansT =============" . $e->getMessage());
        }
    }
}
