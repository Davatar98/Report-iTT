<?php

namespace App\Jobs;

use App\Models\Photo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;


class CompressUploadedImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $reportID;
    public function __construct($reportID)
    {
        //
        $this->reportID = $reportID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
      //  ImageOptimizer::optimize($this->path);
    $images =  Photo::where('report_id',$this->reportID)->get();
    foreach ($images as $image) {
        $realPath = public_path('reportimages/'.$image->name);
        ImageOptimizer::optimize($realPath);
        # code...
    }
    }
}
