<?php

namespace App\Traits;

use App\Models\RcAutomation;
use App\Models\RcAutomationTrack;

trait QueryTrait{
    /**
     * @param $user
     * @param $parameter
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function fetchAutomations($user, $parameter){
        $query = RcAutomation::query();

        $query = $query->with('BodyText');
        foreach ($parameter as $key=>$val){
            $query = $query->where($key, $val);
        }
        $query = $query->where('user_id', $user->id);

        $entities = $query->paginate(10);
        return $entities;
    }

    /**
     * @param $user
     * @param $parameter
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function fetchSentCampaigns($user,  $parameter){
        $query = RcAutomationTrack::query();

        foreach ($parameter as $key=>$val){
            $query = $query->where($key, $val);
        }
        $query = $query->where('user_id', $user->id)->where('is_success', 1)->orderBy('created_at', 'desc');

        $entities = $query->paginate(10);
        return $entities;
    }
}
