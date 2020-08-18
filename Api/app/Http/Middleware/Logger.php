<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\UserLogs;
use App\User;
class Logger
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
        //Very basic logger on inital stage of query a full transaction log would contain both this and more,
        //you can get from the URI and Method what the are trying to do.
        if($request->api_token){
            $user = user::Where('api_token',$request->api_token)->first();
        }else{
            $user = false;
        }


        if($user){

            //build log array
            $logArray = [
                'user_id'            => $user->id,
                'transaction'        => collect(['query' => $request->query(), 'full_uri' => $request->fullUrl(), 'method' => $request->method()]),
                'ip'                 => $request->ip()
            ];

            //Create log
            UserLogs::create($logArray);

        }else{

            $logArray = [
                'user_id'            => 0,
                'transaction'        => collect(['query' => $request->query(), 'full_uri' => $request->fullUrl(), 'method' => $request->method()]),
                'ip'                 => $request->ip()
            ];

            //Create Log
            UserLogs::create($logArray);
        }

        return $next($request);
    }
}
