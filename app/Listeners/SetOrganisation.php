<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class SetOrganisation
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
     * @param  \App\Events\UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        //
        $id = $event->user->id;
        $email = $event->user->email;
        $email_domain = substr($email, strpos($email, "@") +1);//get domain

        switch($email_domain){
            case('ttec.co.tt'):
                $org_name = "TTEC";//"Trinidad and Tobago Electricity Commission";
            break;

            case('wasa.gov.tt'):
                $org_name = "WASA";//"Water and Sewerage Authority";
                break;

            case('tstt.co.tt'):
                $org_name = "TSTT";//"Telecommunications Services of Trinidad and Tobago";
                break;
                case('mailinator.com'):
                    $org_name = "TEST";//"Telecommunications Services of Trinidad and Tobago";
                    break;
            default: NULL;
        }

        DB::table('organisations')->insert(
            ['organisation' =>$org_name,
            'domain' =>$email_domain,
            'user_id' => $id]
        );

    }
}
