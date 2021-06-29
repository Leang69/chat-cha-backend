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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//open route
Route::post('/register',[\App\Http\Controllers\AuthController::class,'register']);
Route::post('/login',[\App\Http\Controllers\AuthController::class,'login']);
Route::get('/redirect/google', [\App\Http\Controllers\AuthController::class,'googleOauth']);
Route::get('/google/auth', [\App\Http\Controllers\AuthController::class,'googleOauthInfo']);
Route::get('/google/user', [\App\Http\Controllers\AuthController::class,'googleUser']);

//protect route
Route::middleware('auth:sanctum')->group(function (){
    Route::post('/change-password',[\App\Http\Controllers\AuthController::class,'changePassword'])
        ->middleware(['userNotFromGoogle']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});



