<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SessionExpired
{
    public function handle($request, Closure $next)
{
    if (!auth()->check()) {

        // Cek apakah route ini butuh auth
        $needsAuth = collect($request->route()->middleware())
                        ->contains(function ($m) {
                            return str_contains($m, 'auth');
                        });

        if ($needsAuth) {
            return redirect()->route('login')
                ->with('expired', 'Session kamu telah berakhir, silakan login kembali.');
        }
    }

    return $next($request);
}

}
