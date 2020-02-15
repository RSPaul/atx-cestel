<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if (Auth::user()->user_type == "admin") {
                return redirect('/admin');
            } else if (Auth::user()->user_type == "user") {
                return redirect('/user/dashboard');
            } else {
                return redirect('/laundress/dashboard');
            }
        }

        return $next($request);
    }
}
