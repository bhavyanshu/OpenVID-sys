<?php

namespace openvidsys\Http\Middleware;

use Closure;

class AdminProtect
{
    /**
     * Handle an incoming request to check if admin role logged in
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     public function handle($request, Closure $next)
     {
       if ($request->user()->role_id != 1) {
         return response('Unauthorized.', 401);
       }
       return $next($request);
     }
}
