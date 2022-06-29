<?php

namespace App\Http\Livewire;

use App\Models\Report;
use App\Models\ReportFlag;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ReportFlags extends Component
{
    use AuthorizesRequests;

    public $report;

    public function mount($reportID){
        $this->report = Report::find($reportID);
    }

    public function render()
        {
            return view('livewire.report-flags');
        }

    

    public function flag($flag){
        //Ensures that only PUB class users can flag as per the ReportFlagPolicy in the Policies folder
        $this->authorize('flag',$this->report);
        //If the user has not flagged, then create the flag log on the report_flags table.
        if(!ReportFlag::where('report_id',$this->report->id)->where('user_id',Auth::user()->id)->count()){

            ReportFlag::create([
               'report_id' => $this->report->id,
               'user_id' => Auth::user()->id,
               'flag' => $flag
           ]);
           //Increment the flag count on the reports table.
           $this->report->increment('flags',$flag);
           }
    }
}
