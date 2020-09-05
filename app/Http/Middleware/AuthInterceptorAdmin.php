<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class AuthInterceptorAdmin
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
        error_log("I am AuthInterceptorAdmin");

        $userId = $request->header('user_id');
        $userToken = $request->header('user_token');

        $roleArr = DB::table('users')
                ->where('id', $userId)
                ->where('token', $userToken)
                ->pluck('role');

        if( count($roleArr) == 1 && 'ADMIN' == $roleArr[0]) {
            error_log('Request Allowed For Admin');
        } else {
            error_log('Token Invalid');
            return Response("Incorrect");
        }

        return $next($request);
    }
}
