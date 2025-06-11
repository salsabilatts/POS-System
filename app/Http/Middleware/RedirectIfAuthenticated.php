<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                if ($user->role === 'admin') {
                    return redirect('/admin/dashboard');
                } elseif ($user->role === 'kasir') {
                    return redirect('/kasir/dashboard');
                } elseif ($user->role === 'pemilik') {
                    return redirect('/pemilik/dashboard');
                } else {
                    return redirect('/home');
                }
            }
        }

        return $next($request);
    }
}
