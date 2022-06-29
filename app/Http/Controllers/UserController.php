<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Gate;
class UserController extends Controller
{
    //
    public function __construct()
    {
        //Ensures that only fully verified(by phone and email) and logged in users can access the website/content.
        $this->middleware(['auth','checkPhoneVerification','verified','CheckBanned']);
        $this->middleware('checkUser');//Ensures that the auth user is a general user (PUB-class)
    }

    
    public function index(){

      //Get reports created by user.
        $reports = Report::where('user_id', Auth::user()->id)
                ->where('deleted_at',NULL)
                ->get();
       
        return view('user.reports',compact('reports'));
      
      
  
    }

    public function userReportDatatables(){
        
        return view('user.reports',compact('reports'));
    }

    
}
