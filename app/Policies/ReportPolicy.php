<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
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
    public function create(User $user){
        return $user->isAdmin == 0 && $user->organisation_id == 5;;
        //Must not be an admin and must not belong to an organisation.
    }

    public function updateStatus(User $user, Report $report){
        return $user->isAdmin == 1 && $user->organisation_id == $report->organisation_id;
    }

    public function vote(User $user, Report $report){
        return $user->isAdmin == 0 && $report->status = "Created";
    }

    public function flag(User $user, Report $report){
        return $user->isAdmin == 0 && $report->status = "Created";
    }
}
