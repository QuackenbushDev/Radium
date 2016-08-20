<?php namespace App\Http\Middleware;

use Auth;
use Closure;

class APIAuthentication {
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
        if (!Auth::check() && !session()->get('portal_username')) {
            return abort(503, 'authentication required.');
        }

        return $next($request);
    }

}