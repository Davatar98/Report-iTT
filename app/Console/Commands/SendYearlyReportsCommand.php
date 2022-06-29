<?php

namespace App\Console\Commands;

use App\Jobs\SendYearlyReports;
use App\Models\Organisation;
use Illuminate\Console\Command;

class SendYearlyReportsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yearlyreport:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a report with data from the year';

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
            SendYearlyReports::dispatch($organisation->id);
         
        }
    }
}
