<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class AuthInterceptorFileAccess {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        error_log("AuthInterceptorFileAccess Triggered");

        $userId = $request->input('user_id');
        $userToken = $request->input('user_token');

        error_log($userId);
        error_log($userToken);

        $roleArr = DB::table('users')
                            ->where('id', $userId)
                            ->where('token', $userToken)
                            ->pluck('role');

        if( count($roleArr) == 1) {
            error_log('Request Allowed For Admin');
        } else {
            error_log('Token Invalid');
            return Response("Authentication Invalid");
        }

        return $next($request);
    }
        
}
