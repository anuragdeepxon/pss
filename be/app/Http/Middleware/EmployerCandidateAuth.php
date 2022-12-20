<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class EmployerCandidateAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('employers-api')->user();
        $candidate_user = Auth::guard('candidates-api')->user();

        if(($user ) || ($candidate_user))
        {
            return $next($request);
        }
        return response(['error'=> 'Unauthorized Access'],403);
    }
}
