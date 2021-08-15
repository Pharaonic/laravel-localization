<?php

namespace Pharaonic\Laravel\Localization\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocleRemover
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
        if ($request->route()->hasParameter('locale'))
            $request->route()->forgetParameter('locale');

        return $next($request);
    }
}
