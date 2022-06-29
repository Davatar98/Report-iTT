<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportStatus extends Model
{
    use HasFactory;
    protected $fillable = ['report_id','status','user_id'];


    public function reports(){
      return  $this->belongsTo(Report::class);
    }

    public function users(){
        return $this->belongsTo(User::class);
    }

    public static function boot(){
      parent::boot();
      //User::observe(new \App\Observers\UserActionsObserver);
      ReportStatus::observe(new \App\Observers\ReportStatusObserver);
    }
}
