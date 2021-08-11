<?php

namespace DenCreative\FeatureToggle\Http\Middleware;

use Closure;
use DenCreative\FeatureToggle\Facades\Feature;

class CheckFeature
{
    public function handle($request, Closure $next, $feature)
    {
        if (!Feature::enabled($feature)) {
            abort(404);
        }

        return $next($request);
    }
}
