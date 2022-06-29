<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportVote extends Model
{
    use HasFactory;
    protected $fillable = [ 'report_id','user_id','vote'];

    public function reports(){
        return $this->belongsTo(Report::class);
    }

    public function users(){
        return $this->belongsTo(User::class);
    }
}
