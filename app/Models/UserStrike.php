<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStrike extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','strikes'];

    public function users(){
        return $this->belongsTo(User::class);
    }


    public static function boot(){
        parent::boot();
        UserStrike::observe(new \App\Observers\UserStrikeObserver);
    }
    //public static function boot(){
   
//         parent::boot();
//         Report::observe(new \App\Observers\ReportObserver);
     
//    }
}
