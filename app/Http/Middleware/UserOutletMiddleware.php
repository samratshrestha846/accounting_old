<?php

namespace App\Http\Middleware;

use App\Models\OutletBiller;
use Closure;
use Illuminate\Http\Request;

class UserOutletMiddleware
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
        $user = auth()->user();

        if(!$user)
            return redirect()->route('login');

        if(!$user->isSuperAdmin()) {
            //if the user is not biller then abort
            if(!$user->isBiller())
                abort(404);

            //if the user has not outlet assigned then abort
            if(!$user->getSessionOutlet())
                abort(404);
        }

        return $next($request);
    }
}
