<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsSubscriber
{
     public function handle(Request $request, Closure $next)
    {
        if(Auth::User()->isSubscriber ==1){
            return $next($request);
        } 
        else{
            return abort(404);
        }

    }
}
