<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                //return redirect(RouteServiceProvider::HOME);

                $role = Auth::user()->role;

                if(in_array($role, ['ADMINISTRATOR', 'STAFF', 'BUDGET OFFICER', 'PROCUREMENT OFFICER', 'ACCOUNTING STAFF', 'PROCESSOR'])){
                    return redirect('/dashboard');
                }
            }
        }

        return $next($request);
    }
}
