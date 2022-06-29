<?php

namespace App\Listeners;
use App\Models\User;
use App\Events\UserStriked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ChangeUserBannedStatus
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
     * @param  \App\Events\UserStriked  $event
     * @return void
     */
    public function handle(UserStriked $event)
    {
        //event fired to change user banned status.
       

        if($event->userStrike->strikes >= 3){
            $user = User::where('id', $event->userStrike->user_id);
            $user->update(['isBanned'=> true]);
        }
    
    }
}
