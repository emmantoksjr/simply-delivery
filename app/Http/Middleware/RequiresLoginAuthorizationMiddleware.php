<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequiresLoginAuthorizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Closure $next
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $access_token = $request->header('X-ACCESS-TOKEN');

        if (! $access_token) {
            abort(HTTP_FORBIDDEN, 'An access token is required!');
        }

        abort_if($access_token !== config('app.key'), HTTP_FORBIDDEN, 'Access token authentication failed!');

        return $next($request);
    }
}
