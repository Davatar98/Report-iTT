<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organisation extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'organisation',
        'domain',
    ];
 //Defining relationships
    public function users(){
        return $this->hasMany(User::class);
    }

    public function reports(){
        return $this->hasMany(Report::class);
    }

    public function faults(){
        return $this->hasMany(Fault::class);
    }
}
