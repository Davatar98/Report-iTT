<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected $commands = [
        Commands\SendMonthlyReportsCommand::class,
        Commands\SendWeeklyReportsCommand::class,
        Commands\SendQuarterlyReportsCommand::class,
        Commands\SendYearlyReportsCommand::class,
        Commands\SendFortnightlyReportsCommand::class,
    ];
    protected function schedule(Schedule $schedule)
    {
        //The scheduled command execution time would be set appropriately in production.
        //It has been set to run everyminute for testing purposes.
        // $schedule->command('inspire')->hourly();
        //   $schedule->command('monthlyreport:send')
        //           ->everyMinute();
        //  $schedule->command('weeklyreport:send')
        //           ->everyMinute();
         $schedule->command('quarterlyreport:send')
                  ->everyMinute();
    //   $schedule->command('fortnightlyreport:send')
    //                ->everyMinute();
        //  $schedule->command('yearlyreport:send')
        //         ->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
