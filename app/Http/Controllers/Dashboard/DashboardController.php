<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\RcAutomationTrack;
use Illuminate\Http\Request;
use App\Traits\BaseTrait;
use App\Models\RcShop;
use App\Models\RcAutomation;
use Illuminate\Support\Facades\Auth;
use Osiset\ShopifyApp\Storage\Models\Charge;

class DashboardController extends Controller
{
	use BaseTrait;
    public function index(Request $request){
    	try{

    		$user = Auth::user();
    		 $shop = RcShop::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();

             $requestData = $request->data;
            $dateRange = $requestData['dateRange'];
            $filter['date'] = [];
            if(!empty($dateRange) && $dateRange['startDate'] != ''){
                $filter['date']['startDate'] = date('Y-m-d', strtotime($dateRange['startDate']));
                $filter['date']['endDate'] = date('Y-m-d', strtotime($dateRange['endDate']));
            }

            $filter['chartType'] = $requestData['chartType'];

    		$data['analytics'] = $this->calculateAnalytics($user, $filter);
    		$data['shop']['currency_symbol'] = $shop->currency_symbol;
            $data['charge'] = Charge::select('balance_remaining')->where('user_id', $user->id)->where('status', 'ACTIVE')->orderBy('created_at', 'desc')->first();

    		$types = ['email', 'sms', 'push'];

    		foreach ($types as $key => $value) {
                $auto_total_revenue_query = RcAutomation::where('user_id', $user->id)->where('reminder_type', $value)->where('automation_type', 'automation');

                if($requestData['filterType'] != 'all_time'){
                    $auto_total_revenue_query = $auto_total_revenue_query->whereBetween('created_at', [$filter['date']['startDate'], $filter['date']['endDate']]);
                }

    			$data[$value]['automation']['sales'] = $auto_total_revenue_query->sum('total_revenue');

                $camp_total_revenue_query = RcAutomation::where('user_id', $user->id)->where('reminder_type', $value)->where('automation_type', 'campaign');
                if($requestData['filterType'] != 'all_time'){
                    $camp_total_revenue_query = $camp_total_revenue_query->whereBetween('created_at', [$filter['date']['startDate'], $filter['date']['endDate']]);
                }

    			$data[$value]['campaign']['sales'] = $camp_total_revenue_query->sum('total_revenue');

                $auto_active_query = RcAutomation::where('user_id', $user->id)->where('reminder_type', $value)->where('automation_type', 'automation')->where('is_active', 1);
                if($requestData['filterType'] != 'all_time'){
                    $auto_active_query = $auto_active_query->whereBetween('created_at', [$filter['date']['startDate'], $filter['date']['endDate']]);
                }
    			$data[$value]['automation']['active'] = $auto_active_query->count();

                $camp_active_query = RcAutomation::where('user_id', $user->id)->where('reminder_type', $value)->where('automation_type', 'campaign')->where('is_active', 1);
                if($requestData['filterType'] != 'all_time'){
                    $camp_active_query = $camp_active_query->whereBetween('created_at', [$filter['date']['startDate'], $filter['date']['endDate']]);
                }
    			$data[$value]['campaign']['active'] = $camp_active_query->count();

                $data[$value]['latestMessages'] = RcAutomationTrack::select('reminder_type', 'automation_type', 'is_ordered', 'is_clicked', 'total_revenue', 'cost', 'to', 'created_at')->where('user_id', $user->id)->where('reminder_type', $value)->where('is_success', 1)->orderBy('created_at', 'desc')->paginate(10);
    		}
    	 	return response()->json(['data' => $data], 200);
        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }
}
