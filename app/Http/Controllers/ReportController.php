<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Excel as BaseExcel;

use App\Events\ImageUploaded;
use App\Events\StatusUpdated;
use App\Events\NotifyAdmin;
use App\Exports\FortnightlyReportExport;
use App\Exports\MonthlyReportExport;
use App\Exports\WeeklyReportExport;
use App\Jobs\CompressImage;
use App\Jobs\CompressUploadedImage;
use App\Models\User;
use App\Jobs\HandleReportStatus;
use App\Jobs\SendFortnightlyReports;
use App\Jobs\SendMonthlyReports;
use App\Jobs\SendQuarterlyReports;
use App\Jobs\SendWeeklyReport;
use App\Jobs\SendYearlyReports;
use App\Mail\TTECWeeklyMail;
use App\Models\ReportVote;
use App\Models\Fault;
use App\Models\Organisation;
use App\Models\Report;
use App\Models\ReportStatus;
use App\Models\Photo;
use App\Models\UserStrike;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\ImageOptimizer\ImageOptimizerFactory;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

//Note: All grayed out inclusions are not required for the controller to function.
//They have been included as the /export route was used to test some functionality.
//They can be removed in production.
class ReportController extends Controller
{
  

    public function __construct()
    {
        //Ensures that only fully verified(by phone and email) and logged in users can access the website/content.
        $this->middleware(['auth','checkPhoneVerification','verified','CheckBanned']);
       
       //middleware used for permissions. Only PUB users can create and save reports.
       $this->middleware('checkUser')->only([
                'create',
                'store' 
         ]);
     //middleware used for permissions. Only service providers can update the status of the report.
     //This includes deletions.
       $this->middleware('checkIfProvider')->only([
             'updateStatus'
        ]);
       
        $this->middleware('XSS')->only([
           
            'store' 
     ]);
    }

      
    public function index(){
     //Returns the main report page.
      
        return view('report.index');
    }

    public function getFaultNames($id){
    //Gets the fault names via AJAX call on the reports.create view.
        $faults = DB::table('faults')->where('organisation_id', $id)->pluck("fault_type","id");
      
       return json_encode($faults);
        
      
    }

    public function create(){
        //Gets the organisation name and id for the create form. 
        //This checks for all registered organisations.
        //Recall: users have a NULL or '' domain and thus is avoided.
        $organisations = DB::table('organisations')
        ->whereNotIn('domain',['','mailinator.com'])//mailinator is also a domain, created to show how organisations can be added.
        ->pluck("organisation","id");

        return view('report.create', compact("organisations"));
    }


    public function getCoordinatesTTEC(){
        //Gets the coordinates and marker details for TTEC reports.

          return Report::all()->where('organisation_id',1)
          ->makeHidden(['updated_at','user_id','resolve_time_hrs', 'acknowledgement_time_hrs', 'organisation_id' ]);


    }

     public function getCoordinatesWASA(){
        //Gets the coordinates and marker details for WASA reports.
        
        return Report::all()->where('organisation_id',2)
              ->makeHidden(['updated_at','user_id','resolve_time_hrs', 'acknowledgement_time_hrs', 'organisation_id' ]);
   
    //  return $report;
    }


    public function getCoordinatesTSTT(){
        //Gets the coordinates and marker details for TSTT reports.    
            
        return Report::all()->where('organisation_id',3) ->makeHidden(['updated_at','user_id','resolve_time_hrs', 'acknowledgement_time_hrs', 'organisation_id' ]);

    //  return $report;
    }



    public function store(Request $request){
     //this is the store method, where the report entry is created in the database.

     //This is the second layer of permission validation, using the permissions defined in the ReportPolicy in the Policies folder.

     //This and the middleware is used. The middleware uses Gates, which uses permissions defined in AuthServiceProvider.php
        $this->authorize('create',Report::class);
        $this->validate($request,[
            //validates the submitted data.
            
            //'provider' => 'exists:organisations,organisation',
            //'fault' => 'exists:faults,fault_type',
            'provider' => 'required|numeric|between:1,3',
            'fault' => 'required|numeric|between:1,10',
            'description' => 'required|string|max:600',
            //'description' => ['required','string','min:5','max:500'],
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'images' => 'array|max:3',
            'images.*' => 'image|mimes:jpg,png,jpeg|max:18240|',
            
           // 'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048|',
          ]);

      $fault_id = $request->get('fault');
      $title = Fault::find($fault_id,['fault_type']);

    
    //Creates report after the request has been validated.
       $report =  Report::create([
      'title' => $title->fault_type,
      'description' => $request->description,
      'organisation_id' => $request->provider,
      'latitude' => $request->latitude,
      'longitude' => $request->longitude,
      'user_id' => Auth::user()->id,
     
     ]);
    $report->save();
    
//Creates an entry on the Report_statuses table, which is used to log the times of the status changes for reports.
    ReportStatus::create([
        'report_id'=>$report->id,
        'user_id' => Auth::user()->id,
        
    ]);

     //check for images in the form 
     if($request->hasFile('images')){
    
        $images= $request->images;//Gets the uploaded images from the request.
        
       foreach ($images as $image) {
         
       // Name and place the image in the public folder.
           $name = time().'.'.$report->id.'.'.$image->getClientOriginalName();//.'.'.$image->getClientOriginalExtension();
           $path = $image->move(public_path('reportimages'), $name);
          
     //Creates an entry on the database under the Photos table, which relates all images to a report as well as has
     //information on the storage path. This will change when the application is deployed/using cloud storage.     
          Photo::create([
                'name' => $name,
                'path' => '/public/reportimages/'.$name,
                'report_id' => $report->id,
              ]);
    
    }

    }
   
     return redirect()->route('report.index')
                      ->with('success','Report created successfully.');
     
      
    }

    /*
    call public function show when a report is clicked. This will take the user to a page with the
    report details on the side as well as all of the edit(admin) and vote(user) controls
    */

   
    public function show(Report $report){
        
        //Gets data for the report.show view.
        $tz = $report->created_at; 
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $tz, 'UTC');

        $date->setTimezone('America/Port_of_Spain')->format('d-m-Y H:i:s');
        $newDate = Carbon::parse($date)->format('d/m/Y');
        $user = DB::table('users')->where('id',$report->user_id)->first();

        $images = DB::table('photos')->where('report_id',$report->id)->get();
        
        return view('report.show',compact('report','newDate','user','images'));
       
    }

    public function vote($report_id, $vote){
        //This function has the logic for updating the votes, however, as Livewire was chosen, the logic is instead implemented
        //in the ReportVotes.php file in the Livewire folder. This function can be removed in production.
        $report =  Report::find($report_id);

        if(!ReportVote::where('report_id',$report_id)->where('user_id',Auth::user()->id)->count()){

         ReportVote::create([
            'report_id' => $report_id,
            'user_id' => Auth::user()->id,
            'vote' => $vote
        ]);
        $report->increment('votes',$vote);
        }
      
       return redirect()->route('report.show',$report);
    }
    
    // public function getDetail($id){

    //     $report = Report::find($id);
        
    //     $tz = $report->created_at; 
    //     $date = Carbon::createFromFormat('Y-m-d H:i:s', $tz, 'UTC');

    //     $date->setTimezone('America/Port_of_Spain')->format('d-m-Y H:i:s');
    //     $newDate = Carbon::parse($date)->format('d/m/Y');

    //     $user = DB::table('users')->where('id',$report->user_id)->first();
    //    // dd($user->name);
    
    //     return view('report.show',compact('report','newDate','user'));
      
    // }

    public function updateStatus(Request $request){
        
       //Uses the ReportPolicy as a second layer of protection to ensure that only SP-class users can perform this action.
       //The update status included deletion.
       //This was as all deleted reports are soft deleted and can be retrieved afterwards.
       //This was implemented to allow for the checking and tuning of the automatic discarding of reports.
       //This implementation can change based on how the companies want their data handled.
       $this->authorize('updateStatus', Report::find($request->id));
    

      //Validates the request passed by the buttons.
        $this->validate($request,[
                
                'id'=> 'required|numeric',
                'status'=>'required',
            ]);
          
        $report = Report::find($request->id);
        //Deletes the report if the delete button is selected.
        if($request->status == 'Discarded'){
          
            $report->forceDelete();
            //Add strike to user
            $strike = UserStrike::where('user_id', '=',$report->user_id)->first();

            //If no strikes, create entry in the user_strikes table.
            if($strike === NULL){
                 UserStrike::create([
                'user_id' => $report->user_id,
             ]);
            
            }
            //If the user has strikes, increment the number.
            //This is used to ban the user, and the event is dispatched in the UserStrikeObserver.php file in the 
            //Observers folder.
            else{
             $strike->increment('strikes');
             $strike->save();
     
            }
            return redirect('/adminreportlist');

        }
      //Updates the report status accordingly if the other update status option is selected.
           $report->update(['status' => $request->status]);
        ReportStatus::create([
            'report_id' => $report->id,
            'user_id'=>Auth::user()->id,
             'status' => $request->status
        ]);//k
       

        
        
        return redirect()->route('report.show', $request->id)
                         ->with('success','Report updated successfully');
        
    }

    public function export(){

       //Route and method used in testing.
       
             
    //     $now = Carbon::now();
    //     $start = $now->startOfWeek()->format('Y-m-d H:i:s');
    //     $end =$now->endOfWeek()->format('Y-m-d H:i:s');
       
    //   $reports = Report::select('id','title','description','latitude','longitude','status','votes','flags','Created_at')
    //                  ->where('organisation_id',1)
    //                  ->where('status','!=','Created')
    //                  ->whereBetween('created_at', [$start,$end])
    //                  ->orderBy('created_at')
    //                  ->get();

    // dd($now->month);
   
        // $reports = Report::with('faults.fault_type')->where('organisation_id', auth()->user()->organisation_id)->get();
        // $faultName1 = [];
        // $faultCount1 = [];
        // dd($reports->fault_type);
     
    //     //This uncommented code will show the averages
    //   //Check with hand calcs to verify the average(dummy data)
    //     $faults =  Fault::where('organisation_id', 1)->get();
        
        
    //       $faultName1 = [];
    //       $faultCount1 = [];
    //     foreach ($faults as $fault){
    //          array_push($faultName1, $fault->fault_type);
    //          array_push($faultCount1, Report::where('title',$fault->type)->where('status','Completed')->avg('resolve_time_hrs'));
    //     }

# code...
//   $reports = Report::where('status', 'Completed')->get();
//   //$reports= Report::all();
    
// $faultName = [];
// $faultCount = [];
//Have to fix this part of this. This will not create the fault names
//that I want. The array needs to use the "Fault" table

//A;ternative: create a column that calculates the average when a report is completed. This may be the best option
//especially for saving queries.
//Explore this further and mention in the demonstration on Monday.
//   foreach ($reports as $report) {
     
//         array_push($faultName, $report->title);

//         $average = Report::where('title',$report->title)->where('status','Completed')->avg('resolve_time_hrs');
//       //  $average = Report::avg('resolve_time_hrs');
//         array_push($faultCount, $average);

//   }

    //   return view('export');

        // $faults =  Fault::where('organisation_id', auth()->user()->organisation_id)->get();

        

        // $faultName = [];
        // $faultCount = [];

        // // foreach ($reports as $report){
        // //     array_push($faults, $report->title);
        // //     array_push($faultCount, $report->title->count());
        // // }

        // //get count for each fault in the report 
        // foreach ($faults as $fault){
        //     array_push($faultName, $fault->fault_type);
        //     array_push($faultCount, Report::where('title',$fault->fault_type)->count());
        // }
        // dd($faultName);
    //   $organisations = Organisation::where('id','!=',5)->get();
    // $now = Carbon::now();
    // $start = $now->startOfWeek()->format('Y-m-d H:i:s');
    // $end = $now->endOfWeek()->format('Y-m-d H:i:s');
    // $data= DB::table('reports')
    // ->where('status','Created')
    // ->whereBetween('Created_at',[$start,$end])
    // ->count();
    // dd($data);
    //   foreach ($organisations as $organisation) {
        // $now = Carbon::now();
        // $start = $now->startOfWeek()->format('Y-m-d H:i:s');
        // $end = $now->endOfWeek()->format('Y-m-d H:i:s');

        // // $data["received"] =DB::table('reports')
        // //                  ->where('status','Created')
        // //                  ->whereBetween('Created_at',[$start,$end])
        // //                  ->count();
        // // $data["resolved"] = DB::table('reports')
        // //                   ->where('status','Completed')
        // //                   ->whereBetween('Created_at',[$start,$end])
        // //                   ->count();
        // $resolved =DB::table('reports')
        //                  ->where('status','Completed')
        //                  ->whereBetween('Created_at',[$start,$end])
        //                  ->count();
        // $received = DB::table('reports')
        //                   ->where('status','Created')
        //                   ->whereBetween('Created_at',[$start,$end])
        //                   ->count();
        
       
//         // $data["received"] =$received;
//         // $data["resolved"] = $resolved;
//         // $data["rate"]  = 100 * ($resolved/$received);
//         // dd($data["rate"]);
//         $resolved =DB::table('reports')
//         ->where('status','Completed')
//         ->where('organisation_id',1)
//         ->whereBetween('Created_at',[$start,$end])
//         ->count();
        
// //Number of received reports this week 
// $received = DB::table('reports')
//          ->where('status','Created')
//          ->where('organisation_id',1)
//          ->whereBetween('Created_at',[$start,$end])
//          ->count();
// //Outstanding Reports 
// //Total Outstanding Reports:

// $outstanding = DB::table('reports')
//     ->where('status','!=','Completed')
//     ->where('status','!=','Created')
//     ->where('status','!=','Discarded')
//     ->where('organisation_id',1)
//     ->count();
// //Outstanding Reports Resolved
// $outstandingResolved = DB::table('reports')
//             ->where('status','Completed')
            
//             ->where('organisation_id',1)
//             ->whereBetween('Updated_at',[$start,$end])
//             ->count();
//         //  SendWeeklyReport::dispatch(1);
//     //       # code...
//       }
// $now = Carbon::now();
//         $start = $now->startOfWeek()->format('Y-m-d H:i:s');
//         $end =$now->endOfWeek()->format('Y-m-d H:i:s');
       
//     $report = Report::select('id','title','description','latitude','longitude','status','votes','flags','Created_at')
//                      ->where('organisation_id',1)
//                      ->where('status','!=','Created')
//                      ->whereBetween('created_at', [$start,$end])
//                      ->orderBy('created_at')
//                      ->get();
//Find the report by the id entered.



//Get the time the status was changed to Submitted
// $reportSubmitted = ReportStatus::where('report_id',98)
//                   ->where('status','Submitted')
//                   ->first();

// //Get the time the status was changed to In-Progress
// $reportAcknowledged = ReportStatus::where('report_id',98)
//                     ->where('status','In-Progress')
//                     ->first();

// //Calculate the difference between the times and ensure that the holidays are subtracted(working hours/days)

// $acknowledgeTimeHrs = round($reportAcknowledged->created_at->diffinSeconds($reportSubmitted->created_at)/3600,2) ;//calculates acknowledgement time in hours

// //update the table column
// $report = Report::find(98);
// $report->update(['acknowledgement_time_hrs'=> $acknowledgeTimeHrs]);

//$diff3 = round($reportAcknowledged->created_at->diffinSeconds($reportSubmitted->created_at)/(60*60),2);
//Update the fields in the 
 //Get the time the status was changed to Submitted
//  $reportSubmitted = ReportStatus::where('report_id',103)
//  ->where('status','Submitted')
//  ->first();

//  //Get the time the status was changed to In-Progress
//  $reportResolved = ReportStatus::where('report_id',103)
//  ->where('status','Completed')
//  ->first();

//  //Calculate the difference between the times

//  $resolveTimeHrs = round($reportSubmitted->created_at->diffinSeconds($reportResolved->created_at)/3600,2) ;//calculates acknowledgement time in hours

//  //update the table column
//  $report = Report::find(103);
//  $report->update(['resolve_time_hrs'=> $resolveTimeHrs]);
// dd($resolveTimeHrs);
    
    }
}
