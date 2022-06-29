<?php

namespace App\Http\Livewire;

use App\Models\Report;
use App\Models\ReportVote;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Policies\VoteFlagPolicy;
class ReportVotes extends Component
{
    use AuthorizesRequests;
    public $report;

    public function mount($reportID){
        $this->report = Report::find($reportID);
    }


    public function render()
    {
        return view('livewire.report-votes');

    }

    public function vote($vote){
        
        //Ensures that only PUB class users can vote as per the ReportVotePolicy in the Policies folder
        $this->authorize('vote',$this->report);
        //If the user has not voted, then create the vote log on the report_votes table.
         if(!ReportVote::where('report_id',$this->report->id)->where('user_id',Auth::user()->id)->count()){

            ReportVote::create([
               'report_id' => $this->report->id,
               'user_id' => Auth::user()->id,
               'vote' => $vote
           ]);
            //Increment the vote count on the reports table.
           $this->report->increment('votes',$vote);
          }  
       }
        
    
   
}
