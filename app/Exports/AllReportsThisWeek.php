<?php

namespace App\Exports;

use App\Models\Report;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;

class AllReportsThisWeek implements FromCollection, WithHeadings, WithTitle, WithStrictNullComparison
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
        $start = $now->startOfWeek()->format('Y-m-d H:i:s');
        $end =$now->endOfWeek()->format('Y-m-d H:i:s');
       
      return Report::select('id','title','description','latitude','longitude','status','votes','flags','Created_at')
                     ->where('organisation_id',$this->organisation_id)
                     ->where('status','!=','Created')
                     ->whereBetween('created_at', [$start,$end])
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
        'Issue Number','Title','Description','Latitude','Longitude','Status','Votes','Flags','Created At'
        
        ];
    }

    public function title(): string
    {
        return 'Reports This Week';
    }
}
