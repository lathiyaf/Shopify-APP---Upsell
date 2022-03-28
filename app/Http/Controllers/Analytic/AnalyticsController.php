<?php

namespace App\Http\Controllers\Analytic;

use App\Http\Controllers\Controller;
use App\Models\RcAutomationTrack;
use App\Models\RcShop;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    use BaseTrait;
    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request){
        try{
            $dateRange = $request->data['dateRange'];

            $filter['date'] = [];

            if(!empty($dateRange) && $dateRange['startDate'] != ''){
                $dateRange['startDate'] = str_replace('/', '-', $dateRange['startDate']);
                $dateRange['endDate'] = str_replace('/', '-', $dateRange['endDate']);
                $filter['date']['startDate'] =  date('Y-m-d', strtotime($dateRange['startDate']));
                $filter['date']['endDate'] =  date('Y-m-d', strtotime($dateRange['endDate']));
            }

            $filter['chartType'] = $request->data['chartType'];

            $user = Auth::user();
            $shop = RcShop::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();

            $data['analytics'] = $this->calculateAnalytics($user, $filter);
            $data['shop']['currency_symbol'] = $shop->currency_symbol;

            $data['latestMessages'] = RcAutomationTrack::select('reminder_type', 'automation_type', 'is_ordered', 'is_clicked', 'total_revenue', 'cost')->where('user_id', $user->id)->where('reminder_type', $filter['chartType'])->where('is_success', 1)->orderBy('created_at', 'desc')->paginate(10);
            return response()->json(['data' => $data], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 400);
        }
    }
}
