<?php

namespace App\Listeners;

use App\Events\ImageInput;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UploadImageToCloud
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
     * @param  \App\Events\ImageInput  $event
     * @return void
     */
    public function handle(ImageInput $event)
    {
        //
    }
}
