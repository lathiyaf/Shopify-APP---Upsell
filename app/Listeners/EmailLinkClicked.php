<?php

namespace App\Listeners;

use App\Models\RcAutomation;
use App\Models\User;
use App\Traits\BaseTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use jdavidbakr\MailTracker\Events\LinkClickedEvent;

class EmailLinkClicked
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
     * @param  LinkClickedEvent  $event
     * @return void
     */
    public function handle(LinkClickedEvent $event)
    {
        logger('========== LISTENER :: LinkClickedEvent =========');
        $tracker = $event->sent_email;
        $automation_id = $event->sent_email->getHeader('X-Automation-ID');
        $automation = RcAutomation::find($automation_id);
        if($automation){
            $user = User::find($automation->user_id);
            $automation->total_clicked = $automation->total_clicked + 1;
            $automation->save();

            $udata = [
                'clicks' => 1
            ];
            $t = ($automation->automation_type == 'automation') ? $automation->reminder_type : $automation->reminder_type.$automation->automation_type;
            $this->runAnalytics($user, $t, $udata);
        }
    }
}
