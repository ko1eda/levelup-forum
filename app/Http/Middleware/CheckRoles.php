<?php

namespace App\Http\Middleware;

use Closure;

class CheckRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$args)
    {
        // if the user has a role that corresponds to one of
        // the acceptable roles we've passed to the middleware
        // let them pass (perform the action)
        if ($request->user()->hasRoles($args)) {
            return $next($request);
        }

        if ($request->wantsJson()) {
            abort(403, 'You do not have the required permissions');
        }

        return back()->with('flash', 'You do not have the required permissions~danger');
    }
}
