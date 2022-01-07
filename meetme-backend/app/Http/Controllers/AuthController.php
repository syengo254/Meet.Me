<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
//use Illuminate\Auth\Events\Registered;
use Session;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(Request $request){
        $user_data = $request->validate([
            "first_name" => "required",
            "last_name" => "required",
            "email" => "required|unique:users,email|email",
            "password" => "required|confirmed",
            "dob" => "required",
        ]);

        $user = User::create([
            'first_name' => $user_data['first_name'],
            'last_name' => $user_data['last_name'],
            'email' => $user_data['email'],
            'password' => Hash::make($user_data['password']),
        ]);

        if($user->id){
            //event(new Registered($user));
        }
        else{
            $response = [
                "success" => 0,
                "message" => "failed to create user",
            ];

            return response($response, 200, ["Content-Type" => "application/json"]);
        }

        Auth::login($user);

        $token = $user->createToken('meetmelogintoken')->plainTextToken;

        $user_profile = UserProfile::create([
            'dob' => $user_data['dob'],
            'user_id' => $user->id,
            'avatar' => '/img/avatars/default.png',
        ]);

        if($user_profile->id){
            $response = [
                "success" => 1,
                "token" => $token,
                "message" => "user created successfully",
                "user" => $user,
            ];

            return response($response, 200, ["Content-Type" => "application/json"]);
        }
        else {
            $response = [
                "success" => 1,
                "token" => $token,
                "message" => "user created successfully",
                "user" => $user,
            ];

            return response($response, 200, ["Content-Type" => "application/json"]);
        }

    }

    public function login (Request $request) {
        $logins = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        if(!Auth::attempt($logins)){
            return response([ "message" => "wrong credentials", "success" => 0 ], 401);
        }

        //$request->session()->regenerate();

        $token = $request->user()->createToken('meetmelogintoken')->plainTextToken;

        // $user = User::where('email','like', "%{$logins['email']}%")->first();

        // if(!$user || !Hash::check($logins['password'], $user->password)){
        //     return response([ "message" => "wrong credentials", "success" => 0 ], 401);
        // }

        //$token = $user->createToken('meetmelogintoken')->plainTextToken;

        $response = [
            "success" => 1,
            "token" => $token,
            "message" => "Login successful",
            "user" => $request->user(),
        ];

        return response($response, 200, ["Content-Type" => "application/json"]);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        $request->user()->tokens()->delete();

        return response(["message"=>"logged out"], 201);
    }

    public function check(Request $request){
        $check = $request->validate([
            "token" => 'required'
        ]);

        $id = $request->user()->id;
        $userdata = User::with('user_profile')->where('id', $id)->first();

        return response(["success" => 1, "message" => "The session token is valid", "user" => $userdata], 200);
    }

    public function sendResetEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? ['success' => 1, 'status' => __($status)]
                    : ['success' => 0, 'message' => __($status)];
    }

    public function resetPassword(Request $request){
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
    
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
    
                $user->save();
            }
        );
    
        return $status === Password::PASSWORD_RESET
                    ? ['success' => 1, 'status'=> __($status)]
                    : ['success' => 0, 'email' => [__($status)]];
    }
}
