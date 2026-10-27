<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = $request->user();
        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'Huna ruhusa ya kuingia hapa.');
        }
        return $next($request);
    }
}
