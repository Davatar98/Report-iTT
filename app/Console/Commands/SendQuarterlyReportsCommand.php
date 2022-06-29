<?php

namespace App\Console\Commands;

use App\Jobs\SendQuarterlyReports;
use App\Models\Organisation;
use Illuminate\Console\Command;

class SendQuarterlyReportsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quarterlyreports:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends reports every quarter';

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
          SendQuarterlyReports::dispatch($organisation->id);
    
      }
    }
}
