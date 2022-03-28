<?php

namespace App\Listeners;

use App\Models\RcAutomation;
use App\Models\RcAutomationTrack;
use App\Models\User;
use App\Traits\BaseTrait;
use AshAllenDesign\ShortURL\Events\ShortURLVisited;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SmsLinkClicked
{
    use BaseTrait;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ShortURLVisited  $event
     * @return void
     */
    public function handle(ShortURLVisited $event)
    {
        logger('========== LISTENER :: LinkClickedEvent =========');
        $id = $event->shortURL->id;
        $track = RcAutomationTrack::where('short_url_id', $id)->first();

        if($track){

            if(!$track->is_clicked){
                $track->is_clicked = 1;
                $track->save();
            }

            $automation = RcAutomation::find($track->automation_id);
            $automation->total_clicked = $automation->total_clicked + 1;
            $automation->save();
            $user = User::find($automation->user_id);
            $udata = [
                'clicks' => 1,
                'opened' => 1
            ];
            $t = ($automation->automation_type == 'automation') ? $automation->reminder_type : $automation->reminder_type.$automation->automation_type;
            $this->runAnalytics($user, $t, $udata);
        }

    }
}
