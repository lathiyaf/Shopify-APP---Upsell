<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\RcSetting;
use App\Models\RcSmsCost;
use App\Models\SmsCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request){
        try{
            $user = Auth::user();
            $setting = RcSetting::select('sms_countries', 'is_enable_sms_max_price', 'sms_max_price')->where('user_id', $user->id)->orderBy('created_at', 'desc')->first();

            $countries = RcSmsCost::select('country', 'iso')->distinct()->get();
            $data['setting']['is_enable_sms_max_price'] = $setting->is_enable_sms_max_price;
            $data['setting']['sms_max_price'] = $setting->sms_max_price;
            $data['setting']['sms_countries'] = ($setting->sms_countries != null) ? (array)json_decode($setting->sms_countries) : [];
            $data['countries'] = $countries;
            return response()->json(['data' => $data], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 400);
        }
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        try{
            $user = Auth::user();
            $data = $request->data;

            $setting = RcSetting::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();

            $setting->sms_countries = json_encode($data['sms_countries']);
            $setting->is_enable_sms_max_price = $data['is_enable_sms_max_price'];
            $setting->sms_max_price = $data['sms_max_price'];
            $setting->save();

            return response()->json(['data' => 'Saved!'], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 400);
        }
    }
}
