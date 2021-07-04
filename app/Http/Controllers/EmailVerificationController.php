<?php

namespace App\Http\Controllers;

use App\Events\VerifyEmailSuccess;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    //
    public function resendVerify(Request $request){
        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'success']);
    }

    public function userVerificationHandler (Request $request){

        $user = User::find($request->id);
        if (!$user){
            return response()->json(['message' => 'have not this user'],406);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'account has verified rmail'],406);
        }

        $user->markEmailAsVerified();
        VerifyEmailSuccess::dispatch($user);
        return response()->json(['message' => 'success']);
    }

}
