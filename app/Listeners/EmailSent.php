<?php

namespace App\Listeners;

use App\Models\RcAutomation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use jdavidbakr\MailTracker\Events\EmailSentEvent;

class EmailSent
{
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
     * @param  EmailSentEvent  $event
     * @return void
     */
    public function handle(EmailSentEvent $event)
    {
        logger('========== LISTENER :: EmailSentEvent =========');
        $tracker = $event->sent_email;
        $automation_id = $event->sent_email->getHeader('X-Automation-ID');
        $automation = RcAutomation::find($automation_id);
       if($automation){
           $automation->total_sent = $automation->total_sent + 1;
           $automation->save();
       }
    }
}
