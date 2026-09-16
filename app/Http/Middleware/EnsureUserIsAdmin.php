<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || $request->user()->role !== UserRole::Admin) {
            abort(403);
        }

        return $next($request);
    }
}
