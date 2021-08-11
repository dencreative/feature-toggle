<?php

namespace DenCreative\FeatureToggle\Http\Middleware;

use Closure;
use DenCreative\FeatureToggle\Facades\Feature;

class CheckFeatureRole
{
    public function handle($request, Closure $next, $feature)
    {
        if (!Feature::enabled($feature)) {
            abort(404);
        }

        $roleValue = Feature::getUserRole($request->user());

        if(!Feature::enabledFor($feature, $roleValue)) {
            abort(404);
        }

        return $next($request);
    }
}
