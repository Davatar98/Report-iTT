<?php

namespace App\Jobs;

use App\Events\NotifyAdmin;
use App\Events\ReportDeleted;
use App\Listeners\SendNotifyAdminMail;
use App\Models\Report;
use App\Models\User;
use App\Models\ReportStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Events\StatusUpdated;

use App\Mail\UserReportStatusEmail;
use App\Models\UserStrike;

class HandleReportStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $report;
    
    public function __construct( $report)
    {
        //
        $this->report = $report;
       

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Find the user by the report id.
      $user = User::find($this->report->user_id);


      //Condition for report to be escalated to service provider. These values are set for the demonstration purposes.
    if($this->report->votes > 0 && $this->report->flags <= 0){//change conditions 
        //Update report status
       $this->report->update(['status' => 'Submitted']);
       
       //Create entry in report_statuses table to log the time it was changed.
       ReportStatus::create([
           'report_id' => $this->report->id,
           'user_id' => $user->id,
           'status' => 'Submitted'
       ]);

       //Get all SP-class users from the organisation the report refers to.
        $users = User::where('organisation_id',$this->report->organisation_id)->get();
          
        //Dispatch event and listener to notify them via email.
              foreach($users as $user){
                   event(new NotifyAdmin($user,$this->report)); 
             
             }
      
    }

    //Condition for discarding the report without penalty to the report creator.
    elseif($this->report->votes <= 0 && $this->report->flags <= 0){ 
        //Update the status on the reports table.
        $this->report->update(['status' => 'Discarded']);
        //Delete the report. This is a soft deletion as defined in the model. This is set so for monitoring purposes to see
        //how the system behaves in the public beta testing.
        $this->report->delete();
    }
    
    //Condition for discarding with penalty (inappropriate report, malicious user case etc.)
    elseif($this->report->flags > 0){

        //Delete report
       $this->report->delete();
      // $user = User::find($this->report->user_id);

       //Check if user has received previous strikes.
       $strike = UserStrike::where('user_id', '=',$this->report->user_id)->first();

       //If no strikes, create entry in the user_strikes table.
       if($strike === NULL){
            UserStrike::create([
           'user_id' => $user->id,
        ]);
       
       }
       //If the user has strikes, increment the number.
       //This is used to ban the user, and the event is dispatched in the UserStrikeObserver.php file in the 
       //Observers folder.
       else{
        $strike->increment('strikes');
        $strike->save();

       }

    }
        
    }
}
