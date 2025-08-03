<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class OwnerAccess
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->owner_id === 0) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
