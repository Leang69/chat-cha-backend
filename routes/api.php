<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//open route
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::get('/redirect/google', [\App\Http\Controllers\AuthController::class, 'googleOauth']);
Route::get('/google/auth', [\App\Http\Controllers\AuthController::class, 'googleOauthInfo']);

Route::get('/email/verification-handler/{id}/{hash}', [\App\Http\Controllers\EmailVerificationController::class, 'userVerificationHandler'])
    ->middleware('signed')->name('verification.verify');


//protect route
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [\App\Http\Controllers\AuthController::class, 'user']);
    Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('/check-token', function(){
        return response()->json(["valid" => true]);
    });

    //route for user not use google credential
    Route::post('/change-password', [\App\Http\Controllers\AuthController::class, 'changePassword'])
        ->middleware('userNotFromGoogle');

    Route::get('/email/resend-verify', [\App\Http\Controllers\EmailVerificationController::class, 'resendVerify'])
        ->middleware('userNotFromGoogle')->name('verification.send');

    Route::post('/send-massage', [\App\Http\Controllers\MessageController::class, 'sendMassage']);
    Route::post('/get-massage', [\App\Http\Controllers\MessageController::class, 'getMassage']);
    Route::post('/get-massage-between-us', [\App\Http\Controllers\MessageController::class, 'getLastMessage']);
    Route::post('/get-massage-history', [\App\Http\Controllers\ChatListController::class, 'lassMassage']);
});



