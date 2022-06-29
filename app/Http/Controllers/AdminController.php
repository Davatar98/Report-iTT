<?php

namespace App\Http\Controllers;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB; 

class AdminController extends Controller
{
    //
    public function __construct()
    {
      //Ensures that only fully verified(by phone and email) and logged in users can access the website/content.
        $this->middleware(['auth','checkPhoneVerification','verified','CheckBanned']);
        $this->middleware('checkIfProvider');// Ensures only service provider admins can view this page. Correlates to the SP class users.
                 
    }
    
    public function index(){
     //Get reports related to the organisation id of the user.
        $reports = Report::where('organisation_id',Auth::user()->organisation_id)
                ->where('deleted_at', NULL)
                ->get();
     
       return view('admin.reports')->with('reports',$reports);
     }
    
     //The edit status has been placed in the report controller. The logic is placed there and the ReportPolicy is used to 
     //Control the access/permissions. The logic was placed there as it is an "update" method for reports,
   
     public function statistics(){
         return view('admin.statistics');
     }

     
}
