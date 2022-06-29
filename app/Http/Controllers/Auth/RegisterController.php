<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Organisation;
use App\Events\UserRegistered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('XSS'); //sanitizes the input for tags.
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        //Validates the user form input.
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required','string','phone:TT','min:7','max:7',]
            //'unique:users' should be here as well but left out to allow for testing using my own personal number.
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        //Gets email entered by the user.
        $email = $data['email'];

        //Finds the domain by substring after the @ in the email address.
        $email_domain = substr($email, strpos($email, "@") +1);

        //Check if the domain exists in the database.
        if (Organisation::where('domain', $email_domain) ->exists()) {
            //if it exists, find the relevant organisation.
            $org_id = Organisation::where('domain', $email_domain)->firstOrFail();
            $admin = true;
        }
       else{
           //if not, set as a normal user. The default org id for non-registered organisation is 5.
            $org_id = Organisation::where('domain', NULL)->firstOrFail();
        
            $admin =false;
      }
 
      //Create the user and save to the database.
         $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
          //  'organisation_id' => 'mailinator.com', laravel does not let me do this assignment here.
            'organisation_id' => $org_id->id,   // this works 
            'isAdmin' => $admin,
            'phone' =>$data['phone'],
        ]);

  //event(new UserRegistered($user));
   return $user;
    }
}
