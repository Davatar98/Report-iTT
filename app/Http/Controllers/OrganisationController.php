<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Organisation;
use Illuminate\Support\Facades\DB;

class OrganisationController extends Controller
{
    //


    /*
    |--------------------------------------------------------------------------
    | Organisation Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the assignment of users to an organisation when they are created.
    This controller was in an earlier implementation and is now obsolete. Should be removed.
    |
    *
    */

    public function createOrg(){
        $user = DB::table('users')->get();
        $id = $user->id;
        $email = $user->email;
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

        // create event here, pass user object into it and do the switch case for the organisations.



         $createorg = Organisation::create([
             'user_id' => $user->id,
            'organisation' => $org_name,
            'domain' => $email_domain,
            
        ]);
        return $createorg;
      


    }
  
    protected function create()
    {

        //Get user from database
        $user = DB::table('users')->get();
        $email = $user->email;
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

     /*   $email = $user->email;


        $email_domain = substr($email, strpos($email, "@") +1);//get domain
        // Array for matching organisation and id
        $org_array = array(0 => 'ttec.co.tt',1 => 'tstt.co.tt', 2 => 'wasa.gov.tt', 3 => 'mailinator.com');
        
        $org_id = array_search($email_domain, $org_array);
       
        $org_name_array = array(0 => 'TTEC',1 => 'TSTT', 2 => 'WASA', 3 => 'TEST.com');

        $org_name = array_search($org_id, $org_name_array);
*/

        // create event here, pass user object into it and do the switch case for the organisations.



         $createorg = Organisation::create([
             'user_id' => $user->id,
            'organisation' => $org_name,
            'domain' => $email_domain,
            
        ]);
        return $createorg;
    }
}