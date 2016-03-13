<?php

namespace openvidsys\Http\Middleware;

use Closure;

class ResProtect
{
    /**
     * Handle an incoming request to check if researcher role logged in
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     public function handle($request, Closure $next)
     {
       if ($request->user()->role_id != 3) {
         return response('Unauthorized.', 401);
       }
       return $next($request);
     }
}
