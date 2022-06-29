<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function edit(){
        //Add permission such that the user can update their own profile.

        //Returns the data of the logged in user based on their id.
        $profile = User::find(Auth::user()->id);
        $number = substr($profile->phone,5) ;
        //substr($str, 4);
       //  dd($number);
     
         
        return view('profile.index',compact(['profile','number','CheckBanned']));
    }

    public function update(Request $request){
        //validate data
        //Use the UserPolicy to ensure that only the users can update their own profiles.
        $this->authorize('updateProfile',User::class);
        $this->validate($request,[
            
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required','string','phone:TT','min:7','max:7',]
          ]);
        //Find the user.
        $user = User::find(auth()->user()->id);
        //If the phone number has changed, reset the phone verification status
        if($request->phone != $user->phone){
            $user->isPhoneVerified = 0;
            $user->save();
           
        }
        //If the email has changed, reset the email verification status
        if($request->email != $user->email){
            $user->email_verified_at = NULL;
            $user->save();
        }
        //Save changes.
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone
        ]);
    }
}
