<?php

namespace App\Observers;

use App\Events\UserStriked;
use App\Models\User;
use App\Models\UserStrike;

class UserStrikeObserver
{
    /**
     * Handle the UserStrike "created" event.
     *
     * @param  \App\Models\UserStrike  $userStrike
     * @return void
     */
    public function created(UserStrike $userStrike)
    {
        //
        
    }

    /**
     * Handle the UserStrike "updated" event.
     *
     * @param  \App\Models\UserStrike  $userStrike
     * @return void
     */
    public function updated(UserStrike $userStrike)
    {
        //Checks if the user has 3 strikes, then sets the value of the isBanned column on the users table to true (1)
        //The middleware CheckBanned is used on all website/navigation routes to ensure that the banned users cannot access
        //the website content.
        if($userStrike->strikes >= 3){
            $user = User::where('id', $userStrike->user_id);
            $user->update(['isBanned'=> true]);
        }
       
        //The commented line below was made as a background event which is queued. 
        //The event contains the same code as above.
       // event(new UserStriked($userStrike));
        
    }

    /**
     * Handle the UserStrike "deleted" event.
     *
     * @param  \App\Models\UserStrike  $userStrike
     * @return void
     */
    public function deleted(UserStrike $userStrike)
    {
        //
    }

    /**
     * Handle the UserStrike "restored" event.
     *
     * @param  \App\Models\UserStrike  $userStrike
     * @return void
     */
    public function restored(UserStrike $userStrike)
    {
        //
    }

    /**
     * Handle the UserStrike "force deleted" event.
     *
     * @param  \App\Models\UserStrike  $userStrike
     * @return void
     */
    public function forceDeleted(UserStrike $userStrike)
    {
        //
    }
}
