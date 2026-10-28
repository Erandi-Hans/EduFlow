<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Allow the request only if the logged-in user has one of the given roles.
     * Usage: ->middleware('role:Admin') or 'role:Admin,Teacher'
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role->role_name, $roles, true)) {
            abort(403, 'You do not have permission to view this page.');
        }

        return $next($request);
    }
}
