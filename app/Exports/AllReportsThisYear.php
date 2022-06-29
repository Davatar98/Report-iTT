<?php

namespace App\Exports;

use App\Models\Report;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;

class AllReportsThisYear implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $organisation_id;
    public function __construct($organisation_id)
    {
        return $this->organisation_id = $organisation_id;
    }
    public function collection()
    {
        //
        $now = Carbon::now();
        $start = $now->startOfYear()->format('Y-m-d H:i:s');
        $end =$now->endOfYear()->format('Y-m-d H:i:s');
       
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
        return 'Reports This Year';
    }
}
