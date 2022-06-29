<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class WeeklyReportExport implements   WithStrictNullComparison, WithMultipleSheets
{
    use Exportable;
    protected $organisation_id;

    public function __construct($organisation_id)
    {
        return $this->organisation_id = $organisation_id;
    }

   
    public function sheets(): array
    {
        $sheets = [];
        

            $sheets[] = new AllReportsThisWeek($this->organisation_id);
            $sheets[] = new AllReportsCompletedThisWeek($this->organisation_id);
            $sheets[] = new AllOutstandingReports($this->organisation_id);
            $sheets[] = new OutstandingReportsCompletedThisWeek($this->organisation_id);

        return $sheets;
    }

   
    
}
