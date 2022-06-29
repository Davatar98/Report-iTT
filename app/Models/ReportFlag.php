<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportFlag extends Model
{
    use HasFactory;
    protected $fillable = [ 'report_id','user_id','flag'];

    public function users(){
        return $this->belongsTo(User::class);
    }

    public function reports(){
        return $this->belongsTo(Report::class);
    }
}
