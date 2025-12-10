<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if (Auth::check()) {

            // Jika admin arahkan ke dashboard admin
            if (Auth::user()->role === 'admin') {
                return redirect('/dashboard');
            }

            // Jika user biasa
            return redirect('/user/dashboard');
        }

        return $next($request);
    }
}
