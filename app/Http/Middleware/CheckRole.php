<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // return $next($request);
         $user = Auth::user();

        if (!$user) {
            return redirect('/login'); 
        }

        if ($user->role === 'superadmin') {
            return $next($request);
        }
        
        // Règles d'accès
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

         abort(403, 'Accès refusé.');
    }
}
