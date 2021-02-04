<?php

namespace App\Http\Middleware;

use Closure;

class appKeyMiddleware
{

    public function handle($request, Closure $next)
    {
        if($request->bearerToken()){
            if(env('KEY_PROGRAM')==$request->bearerToken()){
                return $next($request);
            }
        }

        if($request->has('appkey')){
            if(env('KEY_PROGRAM')==$request->appkey){
                return $next($request);
            }
        }

        return response('Unauthorized.', 401);

    }
}
