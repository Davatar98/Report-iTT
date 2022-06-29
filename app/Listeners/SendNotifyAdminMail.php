<?php

namespace App\Listeners;

use App\Events\NotifyAdmin;
use App\Mail\NotifyAdminMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
class SendNotifyAdminMail
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
     * @param  \App\Events\NotifyAdmin  $event
     * @return void
     */
    public function handle(NotifyAdmin $event)
    {
        //
        Mail::to($event->user->email)->send(
            new NotifyAdminMail($event->user, $event->report)
        );
    }
}
