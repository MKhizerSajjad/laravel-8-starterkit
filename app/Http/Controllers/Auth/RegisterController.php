<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
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
        // return Validator::make($data, [
        //     'first_name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'password' => ['required', 'string', 'min:8', 'confirmed'],
        // ]);

        return Validator::make($data, [
            'picture' => 'file|mimes:jpeg,jpg,gif,png|max:2048',
            'title' => 'required',
            'first_name' => 'required|regex:/^[\pL\s]+$/u',
            'last_name' => 'required|regex:/^[\pL\s]+$/u',
            'email' => 'required|email|max:255|unique:users',
            'mobile_number' => 'min:12|max:18|unique:users',
            'emergency_number' => 'required|min:10|max:18|unique:users',
            'date_of_birth' => 'required',
            'registration_date' => 'required',
            'occupation' => 'required',
            'party_position' => 'required',
            'branch' => 'required',
            'chapter' => 'required',
            'membership_type' => 'required',
            'status' => 'required',
            'password' => 'required|string|min:8|confirmed',
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

        // Picture
        if (isset($data['picture'])) {
            $imageStorage = public_path('images/users');
            $imageExt = array('jpeg', 'gif', 'png', 'jpg', 'webp');
            $picture = $data['picture'];
            $extension = 'png';
            // $extension = $picture->getClientOriginalExtension();

            if(in_array($extension, $imageExt)) {
                // $name = preg_replace('/\s+/', '', $request->first_name);
                // $frontNewName = $name.'-'.$user->id.$extension;
                
                $uniqueIdentifier = uniqid();

                $data['picture'] = $image = $uniqueIdentifier.'.'.$extension;
                $picture->move($imageStorage, $image); // Move File
            }
        }
        
        $data['password'] = Hash::make($data['password']);
        unset($data['password_confirmation']);
        return User::create($data);
    }
}
