<?php

namespace App\Listeners;

use App\Events\ReportCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReportCreatedEmail;

class SendReportCreatedEmail
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
     * @param  \App\Events\ReportCreated  $event
     * @return void
     */
    public function handle(ReportCreated $event)
    {
        //
        Mail::to($event->user->email)->send(
            new ReportCreatedEmail($event->user, $event->report)
        );
    }
}
