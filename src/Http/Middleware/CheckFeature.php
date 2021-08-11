<?php

namespace CharlGottschalk\FeatureToggle\Http\Middleware;

use Closure;
use CharlGottschalk\FeatureToggle\Facades\Feature;

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
