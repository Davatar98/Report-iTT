<?php

namespace App\Listeners;

use App\Events\ReportResolvedByAdmin;
use App\Models\Report;
use App\Models\ReportStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CalculateResolveTime
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
     * @param  \App\Events\ReportResolvedByAdmin  $event
     * @return void
     */
    public function handle(ReportResolvedByAdmin $event)
    {
        //
          
       
        //Get the time the status was changed to Submitted
        $reportSubmitted = ReportStatus::where('report_id',$event->reportID)
        ->where('status','Submitted')
        ->first();

        //Get the time the status was changed to In-Progress
        $reportResolved = ReportStatus::where('report_id',$event->reportID)
        ->where('status','Completed')
        ->first();

        //Calculate the difference between the times

        $resolveTimeHrs = round($reportSubmitted->created_at->diffinSeconds($reportResolved->created_at)/3600,2) ;//calculates acknowledgement time in hours

        //update the table column
        $report = Report::find($event->reportID);
        $report->update(['resolve_time_hrs'=> $resolveTimeHrs]);
    
    }
    
}
