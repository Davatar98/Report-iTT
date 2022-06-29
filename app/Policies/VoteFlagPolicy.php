<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VoteFlagPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function vote(User $user, Report $report){
        return $user->isAdmin == 0 && $report->status = "Created";
    }

    public function flag(User $user, Report $report){
        return $user->isAdmin == 0 && $report->status = "Created";
    }
}
