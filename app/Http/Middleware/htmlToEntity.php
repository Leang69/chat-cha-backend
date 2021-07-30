<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class htmlToEntity
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
        $input = $request->all();
        foreach($input as $faile => $value){
            $input[$faile] = htmlentities($value);
        }
        $request->replace($input);
        return $next($request);
    }
}
