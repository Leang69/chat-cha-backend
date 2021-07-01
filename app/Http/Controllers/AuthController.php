<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    function register(Request $request)
    {
        $validation = array(
            'username' => 'required|string|max:55',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        );
        $messages = array(
            'username.required' => 'username is required',
            'username.string' => 'username must be string',
            'username.max' => 'username over 55 character',
            'email.required' => 'email is required',
            'email.email' => 'email is not a email format',
            'email.unique' => 'email is already use',
            'password.required' => 'password is required',
            'password.string' => 'password must be string',
            'password.min' => 'password under 8 character',
        );

        $validator = Validator::make($request->all(), $validation, $messages);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors(),
            ], 406);
        } else {
            $user = new User;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->userFrom = "chat cha";
            $user->email_verified_at = null;
            $user->save();
            $user['token'] = $user->createToken('token')->plainTextToken;
            return response()->json([
                "token" => $user->createToken('token')->plainTextToken,
                "message" => 'success',
                'email_verified_at' => $user->email_verified_at
            ], 200);        }
    }

    function login(Request $request)
    {
        $validation = array(
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        );
        $messages = array(
            'email.required' => 'email is required',
            'email.email' => 'email is not a email format',
            'password.required' => 'password is required',
            'password.string' => 'password must be string',
            'password.min' => 'password under 8 character',
        );

        $validator = Validator::make($request->all(), $validation, $messages);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors(),
            ], 406);
        } else {
            $user = User::firstWhere('email', $request->email);
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'email or password is incorrected.'
                ], 406);
            } else {
                return response()->json([
                    "token" => $user->createToken('token')->plainTextToken,
                    "message" => 'success',
                    'email_verified_at' => $user->email_verified_at
                ], 200);
            }
        }
    }

    function googleOauth(){
        return Socialite::driver('google')->stateless()->redirect();
    }

    function googleOauthInfo(){
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::firstOrCreate(
            ["email" => $googleUser->email],
            [
                "username" => $googleUser->name,
                "password" => bcrypt(Str::random(10)),
                "userFrom" => "google",
                "profile" => $googleUser->avatar,
            ]
        );
        if (!$user->email_verified_at){
            $user->markEmailAsVerified();
        }
        return response()->json([
                "token" => $user->createToken('token')->plainTextToken,
                "message" => 'success',
                'email_verified_at' => $user->email_verified_at
            ], 200);
    }

    function changePassword(Request $request){
        if (Hash::check($request->password, $request->user()->password)){
            if (Hash::check($request->newPassword, $request->user()->password)){
                return response()->json(["massage"=>"new password and old password are the same"],406);
            }
            $request->user()->password = bcrypt($request->newPassword);
            $request->user()->save();
            return response()->json(["massage"=>"success"],200);
        }else{
            return response()->json(["massage"=>"wrong password"],406);
        }
    }

    function user(Request $request) {
        return $request->user();
    }

}
