<?php

declare(strict_types = 1);

namespace App\Charts;

use App\Models\Fault;
use App\Models\Report;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class AverageResolveTimePerFaultChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public ?string $name = 'resolve_time_average';

    public ?string $routeName = 'resolve_time_average';
    public function handler(Request $request): Chartisan
    {
        //Get the fault names for the current user based on their organisation id
        $faults =  Fault::where('organisation_id', auth()->user()->organisation_id)->pluck('fault_type');
      
        
        $faultName1 = [];
        $faultCount1 = [];

      foreach ($faults as $fault){
          // x-axis of chart
           array_push($faultName1, $fault);
          //y-axis. Calculates the average resolve time based on each fault type discovered.
          //This is technically an n + 1 query, however, it is limited by the number of reportable faults
          //in the faults table. It is possible to improve performance by caching and/or introducing an
          //average column in the faults table, where the average would be calculated and updated 
          // each time the report status is updated(using a job or event). This would eliminate the query below.
        
           array_push($faultCount1, round(Report::where('title',$fault)->where('status','Completed')->avg('resolve_time_hrs'),2) );
      }
        return Chartisan::build()
            ->labels($faultName1)
            ->dataset('Average Resolve Time', $faultCount1);
            
    }
}