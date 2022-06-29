<?php

namespace App\Listeners;

use App\Events\ReportDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Mail;
use App\Mail\ReportDeletedEmail;
class SendReportDeletedEmail
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
     * @param  \App\Events\ReportDeleted  $event
     * @return void
     */
    public function handle(ReportDeleted $event)
    {
        //
        Mail::to($event->user->email)->send(
            new ReportDeletedEmail($event->user, $event->report)
        );
    }
}
