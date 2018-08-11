<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/threads';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('confirm');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * Note that we use an md5 hash of the email concatonated with
     * a string random to help ensure that each token is unique
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $u = new User;
        $u->name = $data['name'];
        $u->username = $data['username'];
        $u->email = $data['email'];
        $u->password = Hash::make($data['password']);
        $u->confirmation_token = str_limit(md5($data['email'] . str_random()), 35, '');

        $u->save();

        return $u;
    }


    /**
     * Get the ?tokenID from the query string
     * and see if that token exists in the database
     * if so confirm that user is fully registered
     *
     * @param Request $req
     * @return void
     */
    public function confirm(Request $req)
    {
        $user = User::where('confirmation_token', $req->query('tokenID'))->first();

        // if we don't find a user aka ($user = null), redirect
        if (!$user) {
            return redirect()->route('threads.index')->with('flash', 'The confirmation token was invalid~danger');
        }
   
        // switch the users confirmed status to true
        $user->confirmed = 1;

        // reset the users confirmation token
        $user->confirmation_token = null;

        // update the user
        $user->save();

        return redirect()->route('threads.index')->with('flash', 'Ok you are good to go');
    }
}
