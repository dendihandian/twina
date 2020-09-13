<?php

namespace App\Http\Middleware;

use Closure;

class ProductionRestriction
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
        if (config('app.env') === 'production') {
            $request->session()->flash('warning', __('This feature is disabled in production'));
            return redirect()->route('landing_page');
        }

        return $next($request);
    }
}
