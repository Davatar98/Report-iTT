<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class YearlyReportExport implements  WithStrictNullComparison, WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $organisation_id;

    public function __construct($organisation_id)
    {
        return $this->organisation_id = $organisation_id;
    }

   
    public function sheets(): array
    {
        $sheets = [];
        

            $sheets[] = new AllReportsThisYear($this->organisation_id);
            $sheets[] = new AllReportsCompletedThisYear($this->organisation_id);
            $sheets[] = new AllOutstandingReports($this->organisation_id);
            $sheets[] = new OutstandingReportsCompletedThisYear($this->organisation_id);


        return $sheets;
    }
}
