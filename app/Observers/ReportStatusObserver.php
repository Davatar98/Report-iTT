<?php

namespace App\Observers;

use App\Models\Report;
use App\Models\User;
use App\Models\ReportStatus;
use App\Events\StatusUpdated;
use App\Events\NotifyAdmin;
use App\Events\ReportAcknowledgedByAdmin;
use App\Events\ReportResolvedByAdmin;
use Illuminate\Support\Facades\DB;
class ReportStatusObserver
{
    /**
     * Handle the ReportStatus "created" event.
     *
     * @param  \App\Models\ReportStatus  $reportStatus
     * @return void
     */
    public function saved(ReportStatus $reportStatus){
        //Create Mail to User and Admin on Report Status
        $report = Report::find($reportStatus->report_id);
        if($reportStatus->status == "Submitted"){
            
             $users = User::where('organisation_id',$report->organisation_id);
             foreach($users as $user){
                 event(new NotifyAdmin($user,$report)); 
             }
             $user = User::find($report->user_id);
         
             event(new StatusUpdated($user,$report));

            }

            elseif($reportStatus->status == "In-Progress"){
                $user = User::find($report->user_id);
         //Add logic here to find acknowledgement time. could call a job or event.
                event(new ReportAcknowledgedByAdmin($report->id));
                event(new StatusUpdated($user,$report));

            }

            elseif($reportStatus->status == "Completed"){
                $user = User::find($report->user_id);
         //add computational logic here for the resolve time.
                event(new ReportResolvedByAdmin($report->id));
                event(new StatusUpdated($user,$report));
            }

    }
    public function created(ReportStatus $reportStatus)
    {
        // //
        
         
    }

    /**
     * Handle the ReportStatus "updated" event.
     *
     * @param  \App\Models\ReportStatus  $reportStatus
     * @return void
     */
    public function updated(ReportStatus $reportStatus)
    {
        //
      
         
    }

    /**
     * Handle the ReportStatus "deleted" event.
     *
     * @param  \App\Models\ReportStatus  $reportStatus
     * @return void
     */
    public function deleted(ReportStatus $reportStatus)
    {
        //
    }

    /**
     * Handle the ReportStatus "restored" event.
     *
     * @param  \App\Models\ReportStatus  $reportStatus
     * @return void
     */
    public function restored(ReportStatus $reportStatus)
    {
        //
    }

    /**
     * Handle the ReportStatus "force deleted" event.
     *
     * @param  \App\Models\ReportStatus  $reportStatus
     * @return void
     */
    public function forceDeleted(ReportStatus $reportStatus)
    {
        //
    }
}
