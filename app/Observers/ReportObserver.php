<?php

namespace App\Observers;

use App\Events\ReportCreated;
use App\Events\ReportDeleted;
use App\Jobs\CompressUploadedImage;
use App\Models\Report;
use App\Models\User;

use App\Jobs\HandleReportStatus;
use App\Listeners\CompressImage;

class ReportObserver
{
    /**
     * Handle the Report "created" event.
     *
     * @param  \App\Models\Report  $report
     * @return void
     */
    public function created(Report $report)
    {
        //Event to notify the report creator that the report has been created
        $user = User::find($report->user_id);
        event(new ReportCreated($user,$report));
        //Job to handle report flow.
        HandleReportStatus::dispatch($report)->delay(now()->addSeconds(70));
        //CompressUploadedImage::dispatch($report->id); (Moved from here to photo observer)


    }

    /**
     * Handle the Report "updated" event.
     *
     * @param  \App\Models\Report  $report
     * @return void
     */

    
    public function updated(Report $report)
    {
        //
    }

    /**
     * Handle the Report "deleted" event.
     *
     * @param  \App\Models\Report  $report
     * @return void
     */
    public function deleted(Report $report)
    {
        //
        $user = User::find($report->user_id);
        event(new ReportDeleted($user,$report));
    }

    /**
     * Handle the Report "restored" event.
     *
     * @param  \App\Models\Report  $report
     * @return void
     */
    public function restored(Report $report)
    {
        //
    }

    /**
     * Handle the Report "force deleted" event.
     *
     * @param  \App\Models\Report  $report
     * @return void
     */
    public function forceDeleted(Report $report)
    {
        //
    }
}
