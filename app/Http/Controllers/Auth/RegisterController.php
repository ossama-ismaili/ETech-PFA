<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Cart;
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'avatar' => ['image','mimes:jpg,png,jpeg'],
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
        $default_settings= collect(["locale"=>"en"]);
        $cart=Cart::create([
            'total'=>0,
            'items_count'=>0
        ]);
        $user=User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'settings' => $default_settings,
        ]);
        $cart->update(['user_id'=>$user->id]);
        $user->update(['cart_id'=>$cart->id]);

        if (isset($data['avatar'])) {
            $avatar=request()->file('avatar')->getClientOriginalName();
            $file_name=md5(time()).$avatar;
            $file_path='users/'.date('FY').'/';
            request()->file('avatar')->storeAs('public/'.$file_path,$file_name,'');
            $user->update(['avatar'=>$file_path.$file_name]);
        }
        else{
            $user->update(['avatar' => 'users/default.png']);
        }

        return $user;
    }
}
