<?php namespace App\Http\Middleware;

use Closure;

class PortalAuthentication {
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
        $guestPaths = [
            '/portal/login',
            '/portal/logout',
            '/portal/forgotPassword',
            '/portal/changePassword'
        ];

        if (!session()->has('portal_username') && !in_array($request->getPathInfo(), $guestPaths)) {
            return redirect(route('portal::login'));
        }

        return $next($request);
    }

}