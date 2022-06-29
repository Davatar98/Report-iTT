<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fault extends Model
{
    use HasFactory;

    //Defining relationships
    public function organisations(){
        return $this->belongsTo(Organisation::class);
    }

    public function reports(){
        return $this->hasMany(Report::class);
    }
}
