<?php

namespace App\Http\Middleware;

use Closure;

class DisableRegister
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
        if (config('twina.disable_register')) {
            $request->session()->flash('warning', __('The register feature is disabled.'));
            return redirect()->back();
        }

        return $next($request);
    }
}
