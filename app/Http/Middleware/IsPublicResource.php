<?php

namespace App\Http\Middleware;

use Closure;

class IsPublicResource
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->merge(['isPub' => true]);
        return $next($request);
    }
}
