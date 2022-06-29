<?php

namespace App\Listeners;

use App\Events\ReportAcknowledgedByAdmin;
use App\Models\Report;
use App\Models\ReportStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CalculateAcknowledgementTime
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
     * @param  \App\Events\ReportAcknowledgedByAdmin  $event
     * @return void
     */
    public function handle(ReportAcknowledgedByAdmin $event)
    {
        //Find the report by the id entered.
        
       
        //Get the time the status was changed to Submitted
        $reportSubmitted = ReportStatus::where('report_id',$event->reportID)
        ->where('status','Submitted')
        ->first();

        //Get the time the status was changed to In-Progress
        $reportAcknowledged = ReportStatus::where('report_id',$event->reportID)
        ->where('status','In-Progress')
        ->first();

        //Calculate the difference between the times

        $acknowledgeTimeHrs = round($reportAcknowledged->created_at->diffinSeconds($reportSubmitted->created_at)/3600,2) ;//calculates acknowledgement time in hours

        //update the table column
        $report = Report::find($event->reportID);
        $report->update(['acknowledgement_time_hrs'=> $acknowledgeTimeHrs]);
    
    }
}
