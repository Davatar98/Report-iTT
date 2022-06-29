<?php

namespace App\Console\Commands;

use App\Jobs\SendWeeklyReport;
use App\Models\Organisation;
use Illuminate\Console\Command;

class SendWeeklyReportsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weeklyreports:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends Weekly Reports to Organisations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $organisations = Organisation::where('id','!=',5)->get();

      foreach ($organisations as $organisation) {
          
          SendWeeklyReport::dispatch($organisation->id);
     
      }
      
    
    }
}
