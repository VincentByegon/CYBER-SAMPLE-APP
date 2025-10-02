<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsApproved
{
    public function handle(Request $request, Closure $next)
    {
       // If user is logged in but not approved
        if (Auth::check() && !Auth::user()->approved) {
            // Avoid redirect loop if already on approval page or logging out
            if (! $request->routeIs('approval.wait') && ! $request->routeIs('logout')) {
                return redirect()->route('approval.wait');
            }
        }

        return $next($request);
    }
}
