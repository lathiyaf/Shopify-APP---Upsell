<?php

namespace App\Listeners;

use App\Models\RcAutomation;
use App\Models\User;
use App\Traits\BaseTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use jdavidbakr\MailTracker\Events\ViewEmailEvent;

class EmailViewed
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
     * @param  ViewEmailEvent  $event
     * @return void
     */
    public function handle(ViewEmailEvent $event)
    {
        logger('========== LISTENER :: ViewEmailEvent =========');
        $tracker = $event->sent_email;
        $automation_id = $event->sent_email->getHeader('X-Automation-ID');
        $automation = RcAutomation::find($automation_id);
        if($automation){
            $user = User::find($automation->user_id);
            $automation->total_opened = $automation->total_opened + 1;
            $automation->save();

            $udata = [
                'opened' => 1
            ];
            $t = ($automation->automation_type == 'automation') ? $automation->reminder_type : $automation->reminder_type.$automation->automation_type;
            $this->runAnalytics($user, $t, $udata);
        }
    }
}
