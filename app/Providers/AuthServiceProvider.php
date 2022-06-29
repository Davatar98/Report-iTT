<?php

namespace App\Providers;

use App\Models\ReportFlag;
use App\Policies\ReportFlagPolicy;
use App\Policies\ReportPolicy;
use App\Policies\ReportVotePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Report::class => ReportPolicy::class,
        ReportVote::class =>ReportVotePolicy::class,
        ReportFlag::class => ReportFlagPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

       
        Gate::define('isAdmin',function($user){
            return $user->isAdmin == 1;
        });

        Gate::define('isUser',function($user){
            return $user->organisation_id == 5 && $user->isAdmin == 0;
        });

        Gate::define('isProvider',function($user){
            return $user->organisation_id != 5 && $user->isAdmin ==1;
        });

        Gate::define('TTEC-Provider',function($user){
            return $user->organisation_id == 1;
        });

        Gate::define('WASA-Provider',function($user){
            return $user->organisation_id == 2;
        });

        Gate::define('TSTT-Provider',function($user){
            return $user->organisation_id == 3;
        });
        
        Gate::define('update-status',function($user,$report){
            return $user->organisation_id == $report->organisation_id && $user->isAdmin == 1;
        });

        Gate::define('create-report',function($user){
            return $user->organisation_id == 5 && $user->isAdmin == 0;
        });
        
        Gate::define('view-statistics',function($user){
            return $user->organisation_id != 5 && $user->isAdmin == 1;
        });

        Gate::define('view-own-reports',function($user, $report){
            return $user->id == $report->user_id;
        });
        
        Gate::define('view-details',function($user,$report){
            return $user->isAdmin == 1 && $user->organisation_id == $report->organisation_id;
        });

        Gate::define('can-vote',function($user){
            return $user->isAdmin == 0 && $user->organisation_id == 5;
        });
     
       
    }
}
