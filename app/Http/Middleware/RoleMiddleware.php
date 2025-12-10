<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $role)
    {
        // Jika belum login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Jika role tidak sesuai
        if (Auth::user()->role !== $role) {
            return redirect('/login')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}
