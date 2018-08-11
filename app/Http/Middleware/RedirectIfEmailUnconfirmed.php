<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfEmailUnconfirmed
{
    /**
     * If the user has not confirmed their email address
     * redirect them to the home page
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->user()->confirmed) {
            return redirect()
                ->route('threads.index')
                ->with('flash', 'First confirm your email address~danger');
        }

        return $next($request);
    }
}
