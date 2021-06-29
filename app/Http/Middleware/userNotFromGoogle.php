<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class userNotFromGoogle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Str::contains($request->user()->userFrom,'google')){
            return response()->json(['message'=>'this account create with google so we can not change password']);
        }
        return $next($request);
    }
}
