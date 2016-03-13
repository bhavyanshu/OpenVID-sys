<?php

namespace openvidsys\Http\Middleware;

use Closure;
use Session;

class Blockcheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      //$response = $next($request);
      if ($request->user()->blocked == 1 || $request->user()->confirmed == 0) {
        return response()->View('users.blocked');
      }
      else {
        return $next($request);
      }
    }
}
