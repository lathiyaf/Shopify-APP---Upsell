<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
//        Registered::class => [
//            SendEmailVerificationNotification::class,
//        ],

        'App\Events\CheckCheckoutCreate' => [
            'App\Listeners\CheckoutCreate',
        ],
        'App\Events\CheckOrderUpdate' => [
            'App\Listeners\OrderUpdate',
        ],
        'jdavidbakr\MailTracker\Events\EmailSentEvent' => [
            'App\Listeners\EmailSent',
        ],
        'jdavidbakr\MailTracker\Events\ViewEmailEvent' => [
            'App\Listeners\EmailViewed',
        ],
        'jdavidbakr\MailTracker\Events\LinkClickedEvent' => [
            'App\Listeners\EmailLinkClicked',
        ],
        'AshAllenDesign\ShortURL\Events\ShortURLVisited' => [
            'App\Listeners\SmsLinkClicked',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
