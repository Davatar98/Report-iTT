<?php

declare(strict_types = 1);

namespace App\Charts;

use App\Models\Fault;
use App\Models\Report;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class FaultIncidencesByType extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public ?string $name = 'fault_incidences';

    public ?string $routeName = 'fault_incidences';
    public function handler(Request $request): Chartisan
    {
        //get fault types from faults table for the current user's organisation.
        $faults =  Fault::where('organisation_id', auth()->user()->organisation_id)->get();
        $faultName = [];
        $faultCount = [];

         //get count for each reported fault on the reports table
        foreach ($faults as $fault){
            //populate the array which represents the x-axis of the chart
            array_push($faultName, $fault->fault_type);
            //populate the y-axis for each type with the count
            array_push($faultCount, Report::where('title',$fault->fault_type)->count());
        }
        return Chartisan::build()
            ->labels($faultName)
            ->dataset('Fault',$faultCount);
         
    }
}