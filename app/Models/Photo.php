<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'path',
        'report_id',
        'thumb'
    ];
 //Defining relationships
    public function reports(){
        return $this->belongsTo(Report::class);
    }
    public static function boot(){
   //Attaching model observer.
        parent::boot();
        Photo::observe(new \App\Observers\PhotoObserver);
     
   }
}
