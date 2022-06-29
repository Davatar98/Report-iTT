<?php

namespace App\Jobs;

use App\Exports\FortnightlyReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class SendFortnightlyReports implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $organisation_id;
    public function __construct($organisation_id)
    {
        //
        $this->organisation_id = $organisation_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         $emails = DB::table('users')->where('organisation_id', $this->organisation_id)->pluck('email');
         $data["email"] = $emails;
    
         $data["title"] = "Fortnightly Report";
        
          //Queries for PDF
 
        $start = Carbon::now()->subWeeks(2)->format('Y-m-d H:i:s');
        $end = Carbon::now()->format('Y-m-d H:i:s');

        //Number of resolved reports this Fortnight
        $resolved =DB::table('reports')
                         ->where('status','Completed')
                         ->where('organisation_id',$this->organisation_id)
                         ->whereBetween('Created_at',[$start,$end])
                         ->whereBetween('updated_at',[$start,$end])
                         ->count();

        //Number of received reports this Fortnight 
        $received = DB::table('reports')
                          ->where('status','!=','Created')
                          ->where('organisation_id',$this->organisation_id)
                          ->whereBetween('Created_at',[$start,$end])
                          ->count();
                          
        //Outstanding Reports 
        //Total Outstanding Reports:
        $outstanding = DB::table('reports')
                     ->where('status','!=','Completed')
                     ->where('status','!=','Created')
                     ->where('organisation_id',$this->organisation_id)
                     ->count();
        //Outstanding Reports Resolved
        $outstandingResolved = DB::table('reports')
                             ->where('status','Completed')
                             ->where('organisation_id',$this->organisation_id)
                             ->whereNotBetween('Created_at',[$start,$end])
                             ->whereBetween('Updated_at',[$start,$end])
                             ->count();
       
        $data["received"] =$received;
        $data["resolved"] = $resolved;
        if($received != 0){
            $data["rate"]  = round(100 * ($resolved/$received),2) ;
        }
        else{
            $data["rate"] = '--' ;
        }
        
        $data["outstanding"] = $outstanding;
        $data["outstandingResolved"] = $outstandingResolved;
       
        $pdf = PDF::loadView('emails.myTestMail', $data);
        $excel =  Excel::raw(new FortnightlyReportExport($this->organisation_id),  \Maatwebsite\Excel\Excel::XLSX);//pass organisation id here.
        foreach ($emails as $email) {

            Mail::send('mail', $data, function($message)use($data, $pdf, $excel,$email) {
            $message->to($email)
                    ->subject($data["title"])
                    ->attachData($pdf->output(), "Fortnight.pdf")
                    ->attachData($excel,'Fortnightreport.xlsx');
        });
            
        }
        
    }
}
