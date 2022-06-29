<?php

namespace App\Models;

use App\Events\StatusUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = [
       
        'title',
        'description',
        'organisation_id',
        'latitude',
        'longitude',
        'user_id',
        'votes',
        'flags',
        'status',
        'acknowledgement_time_hrs',
        'resolve_time_hrs'
        
        
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function organisations(){
        return $this->belongsTo(Organisation::class);
    }

    public function users(){
        return $this->belongsTo(User::class,'id');
    }

    public function reportStatus(){
        return  $this->hasMany(ReportStatus::class);
    }

    public function votes(){
        return $this->hasMany(ReportVote::class);
    }

    public function flags(){
        return $this->hasMany(Report::class);
    }

    public function photos(){
        return $this->hasMany(Photo::class);
    }

    public function faults(){
        return $this->belongsTo(Fault::class);
    }
   public static function boot(){
   
        parent::boot();
        Report::observe(new \App\Observers\ReportObserver);
     
   }
   
}
