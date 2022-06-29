<?php

namespace App\Listeners;

use App\Events\StatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserReportStatusEmail;


class SendUserReportStatusEmail implements ShouldQueue
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
     * @param  \App\Events\StatusUpdated  $event
     * @return void
     */
    public function handle(StatusUpdated $event)
    {
        //
        Mail::to($event->user->email)->send(
            new UserReportStatusEmail($event->user, $event->report)
        );
    }
}
