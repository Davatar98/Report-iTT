<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class MonthlyReportExport implements  WithHeadings, WithStrictNullComparison, WithMultipleSheets{
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
        

       
           
            $sheets[] = new AllReportsThisMonth($this->organisation_id);
            $sheets[] = new AllReportsCompletedThisMonth($this->organisation_id);
            $sheets[] = new AllOutstandingReports($this->organisation_id);
            $sheets[] = new OutstandingReportsCompletedThisMonth($this->organisation_id);

        return $sheets;
    }

    


    public function headings(): array
    {
        return [
        // 'Issue Number','Organisation ID','User ID',
        // 'Title','Description','Latitude','Longitude',
        // 'Created At', 'Updated At','Status','Votes',
        // 'False Flags'
        'Issue Number','Title','Description','Latitude','Longitude','Status','Votes','Flags','Created At'
        
        ];
    }
    

}
