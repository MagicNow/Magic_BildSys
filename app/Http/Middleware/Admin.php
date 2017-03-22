<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $admin = 0;
            if(Auth::guard($guard)->user()->admin==1)
            {
                $admin=1;
            }
            if($admin==0){
                return redirect('/');
            }
            return $next($request);

        }

        return redirect('/login');
    }
}
