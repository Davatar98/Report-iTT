<?php

namespace App\Models;

use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelPhone\Casts\RawPhoneNumberCast;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use Twilio\Rest\Client;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
   // use HasApiTokens, HasFactory, Notifiable;
   use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'organisation_id',
        'isAdmin',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    public $casts = [
        //'phone' => RawPhoneNumberCast::class.':BE',
        'phone' => E164PhoneNumberCast::class.':TT',
    ];

    // public function generateCode()
    // {
    //     $code = rand(1000, 9999);
  
    //     UserCode::updateOrCreate(
    //         [ 'user_id' => auth()->user()->id ],
    //         [ 'code' => $code ]
    //     );
  
    //     $receiverNumber = auth()->user()->phone;
    //     $message = "Your ReportiTT verification code is: ". $code;
    
    //     try {
   
    //         $account_sid = getenv("TWILIO_SID");
    //         $auth_token = getenv("TWILIO_TOKEN");
    //         $twilio_number = getenv("TWILIO_FROM");
    
    //         $client = new Client($account_sid, $auth_token);
    //         $client->messages->create($receiverNumber, [
    //             'from' => $twilio_number, 
    //             'body' => $message]);
    
    //     } catch (Exception $e) {
    //         info("Error: ". $e->getMessage());
    //     }
    // }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    public function organisations(){
        return $this->belongsTo(Organisation::class);
        
    }
    public function reports(){
        return $this->hasMany(Report::class)->withTrashed();
    }
    public function reportStatus(){
        return $this->hasMany(ReportStatus::class);
    }

    public function votes(){
        return $this->hasMany(ReportVote::class);
    }

    public function flags(){
        return $this->hasMany(ReportFlag::class);
    }

    public function strikes(){
        return $this->hasMany(UserStrike::class);
    }
}
