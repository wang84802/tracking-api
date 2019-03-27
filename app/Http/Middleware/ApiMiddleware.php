<?php

namespace App\Http\Middleware;

use DB;
use Log;
use Request;
use Closure;

class ApiMiddleware
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
        $api = $request->header('Api-Token');
        $result = $this->Token_Exist($api);
        if(!$result)
        {
            Log::info('test');
            return response()->json(
                [
                    'status' => 400,
                    'error' => [[
                        'key' => 'Api-Token',
                        'code' => '400049100',
                        'message' => 'Api-Token is unauthorized.'
                    ]],
                ]
                ,401);
        }
        else
            return $next($request);
    }
    public function Token_Exist($api)
    {
        return DB::table('api_token')->where('token',$api)->exists();
    }
}
