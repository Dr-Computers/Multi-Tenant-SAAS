<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TenantPanel
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->type === 'tenant') {
            return $next($request);
        }
        return redirect()->route('dashboard')->with('error', 'Access denied.');
    }
}
