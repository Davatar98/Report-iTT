<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use Twilio\Rest\Client;
 use App\Models\User;
use App\Models\UserCode;
use Exception;
use Illuminate\Support\Facades\Auth;

class PhoneVerificationController extends Controller
{
    //One Improvement: Move the logic to a queueable job/event listener to improve user experience.

    public function index(){
// Find your Account SID and Auth Token at twilio.com/console
// and set the environment variables. See http://twil.io/secure
$code = random_int(1000, 9999);
  
UserCode::updateOrCreate(
    [ 'user_id' => auth()->user()->id ],
    [ 'code' => $code ]
);

$receiverNumber = auth()->user()->phone;
$message = "Your ReportiTT verification code is: ". $code;

try {

    $account_sid = getenv("TWILIO_SID");
    $auth_token = getenv("TWILIO_TOKEN");
    $twilio_number = getenv("TWILIO_FROM");

    $client = new Client($account_sid, $auth_token);
    $client->messages->create($receiverNumber, [
        'from' => $twilio_number, 
        'body' => $message]);

} catch (Exception $e) {
    info("Error: ". $e->getMessage());
}


        return view('phoneverification');
    }


    public function store(Request $request)
    {
        $request->validate([
            'code'=>'required',
        ]);
  
        $find = UserCode::where('user_id', auth()->user()->id)
                        ->where('code', $request->code)
                        ->where('updated_at', '>=', now()->subMinutes(2))
                        ->first();
  
        if (!is_null($find)) {
            $user =  User::where('id',auth()->user()->id)->first();
            $user->isPhoneVerified = 1;
            $user->save();
            return redirect()->route('report.create');
        }

      
  
        return back()->with('error', 'Wrong code entered. Please try again.');
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function resend()
    {
        $code = random_int(1000, 9999);
  
        UserCode::updateOrCreate(
            [ 'user_id' => auth()->user()->id ],
            [ 'code' => $code ]
        );
        
        $receiverNumber = auth()->user()->phone;
        $message = "Your ReportiTT verification code is: ". $code;
        
        try {
        
            $account_sid = getenv("TWILIO_SID");
            $auth_token = getenv("TWILIO_TOKEN");
            $twilio_number = getenv("TWILIO_FROM");
        
            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number, 
                'body' => $message]);
        
        } catch (Exception $e) {
            info("Error: ". $e->getMessage());
        }
  
        return back()->with('success', 'The code has been sent to your mobile device.');
    }
}
