<?php

namespace App\Exports;

use App\Models\Report;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class OutstandingReportsCompletedThisQuarter implements FromCollection, WithHeadings, WithTitle
{ 
    protected $organisation_id;
    public function __construct($organisation_id)
    {
        return $this->organisation_id = $organisation_id;
    }
    public function collection()
    {
        //
        $now = Carbon::now();
        $start = $now->startOfQuarter()->format('Y-m-d H:i:s');
        $end =$now->endOfQuarter()->format('Y-m-d H:i:s');

        return Report::select('id','title','description','latitude','longitude','status','votes','flags','created_at','updated_at')
            ->where('status','Completed')
            ->where('organisation_id',$this->organisation_id)
            ->whereNotBetween('created_at',[$start,$end])
            ->whereBetween('updated_at',[$start,$end])
            ->orderBy('created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
        // 'Issue Number','Organisation ID','User ID',
        // 'Title','Description','Latitude','Longitude',
        // 'Created At', 'Updated At','Status','Votes',
        // 'False Flags'
        'Issue Number','Title','Description','Latitude','Longitude','Status','Votes','Flags','Created At', 'Date Resolved'
        
        ];
    }

    public function title(): string
    {
        return 'All Outstanding Reports Completed This Quarter';
    }
}
