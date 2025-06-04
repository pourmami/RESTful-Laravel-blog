<?php

namespace Modules\Auth\app\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTokenHasAbility
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $ability)
    {
        $user = $request->user();

        if (!$user || !$user->tokenCan($ability)) {
            return response()->json(['message' => "there is no access to this endpoint.".$ability], 403);
        }

        return $next($request);
    }
}
