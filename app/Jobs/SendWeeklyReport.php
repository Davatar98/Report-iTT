<?php

namespace App\Jobs;

use App\Exports\WeeklyReportExport;
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

class SendWeeklyReport implements ShouldQueue
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
   
        $data["title"] = "Weekly Report";
        
        //Queries for PDF
        $now = Carbon::now();
        $start = $now->startOfWeek()->format('Y-m-d H:i:s');
        $end = $now->endOfWeek()->format('Y-m-d H:i:s');

        // $data["received"] =DB::table('reports')
        //                  ->where('status','Created')
        //                  ->whereBetween('Created_at',[$start,$end])
        //                  ->count();
        // $data["resolved"] = DB::table('reports')
        //                   ->where('status','Completed')
        //                   ->whereBetween('Created_at',[$start,$end])
        //                   ->count();
        //Number of resolved reports this week
        $resolved =DB::table('reports')
                         ->where('status','Completed')
                         ->where('organisation_id',$this->organisation_id)
                         ->whereBetween('Created_at',[$start,$end])
                         ->whereBetween('Updated_at',[$start,$end])
                         ->count();
        //Number of received reports this week 
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
            $data["rate"]  = 100 * ($resolved/$received);
        }
        else{
            $data["rate"] = '--' ;
        }
        
        $data["outstanding"] = $outstanding;
        $data["outstandingResolved"] = $outstandingResolved;

       $pdf = PDF::loadView('emails.myTestMail', $data);
       $excel =  Excel::raw(new WeeklyReportExport($this->organisation_id),  \Maatwebsite\Excel\Excel::XLSX);//pass organisation id here.
       foreach ($emails as $email) {

           Mail::send('mail', $data, function($message)use($data, $pdf, $excel,$email) {
           $message->to($email)
                   ->subject($data["title"])
                   ->attachData($pdf->output(), "text.pdf")
                   ->attachData($excel,'weeklyreport.xlsx');
       });
           
       }
    }
}
