<?php

namespace App\Observers;

use App\Events\ImageUploaded;
use App\Jobs\CompressImage;
use App\Jobs\CompressUploadedImage;
use App\Models\Photo;
use App\Models\Report;

class PhotoObserver
{
    /**
     * Handle the Photo "created" event.
     *
     * @param  \App\Models\Photo  $photo
     * @return void
     */
    public function created(Photo $photo)
    {
        //
       
       // Compress images after uploading. This observer looks out for when entries are created on the Photos table and 
       //dispatches the job accordingly.
      
        //    event(new ImageUploaded($report->id));
    //    foreach ($photo as $image) {
    //       CompressUploadedImage::dispatch($image->report_id);
    //    }
        CompressUploadedImage::dispatch($photo);
        

    }

    /**
     * Handle the Photo "updated" event.
     *
     * @param  \App\Models\Photo  $photo
     * @return void
     */
    public function updated(Photo $photo)
    {
        //
    }

    /**
     * Handle the Photo "deleted" event.
     *
     * @param  \App\Models\Photo  $photo
     * @return void
     */
    public function deleted(Photo $photo)
    {
        //
    }

    /**
     * Handle the Photo "restored" event.
     *
     * @param  \App\Models\Photo  $photo
     * @return void
     */
    public function restored(Photo $photo)
    {
        //
    }

    /**
     * Handle the Photo "force deleted" event.
     *
     * @param  \App\Models\Photo  $photo
     * @return void
     */
    public function forceDeleted(Photo $photo)
    {
        //
    }
}
