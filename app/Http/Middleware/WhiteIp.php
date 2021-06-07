<?php

namespace App\Http\Middleware;

use Closure;

class WhiteIp
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
        if(env('WHITE_SWITCH')){
            $ip = request()->getClientIp();
            $white_list = explode(',',env('WHITE_IP_LIST'));
            if(!in_array($ip,$white_list)){
                return response()->json(['status'=>404]);
            }
        }
        return $next($request);
    }
}
