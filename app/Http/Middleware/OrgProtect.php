<?php

namespace openvidsys\Http\Middleware;

use Closure;

class OrgProtect
{
    /**
     * Handle an incoming request to check if organization role logged in
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     public function handle($request, Closure $next)
     {
       if ($request->user()->role_id != 2) {
         return response('Unauthorized.', 401);
       }
       return $next($request);
     }
}
