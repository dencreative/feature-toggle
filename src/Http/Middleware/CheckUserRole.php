<?php

namespace DenCreative\FeatureToggle\Http\Middleware;

use Closure;
use DenCreative\FeatureToggle\Facades\Feature;

class CheckUserRole
{
    public function handle($request, Closure $next)
    {
        $roleValue = Feature::getUserRole($request->user());
        $roleAllowed = false;

        if(is_array($roleValue)) {
            foreach ($roleValue as $role) {
                if($role == config('features.auth.role')) {
                    $roleAllowed = true;
                }
            }
        } else {
            $roleAllowed = ($roleValue == config('features.auth.role'));
        }

        if(!$roleAllowed) {
            abort(404);
        }

        return $next($request);
    }
}
