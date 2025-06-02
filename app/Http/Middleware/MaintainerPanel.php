<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MaintainerPanel
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->type === 'maintainer') {
            return $next($request);
        }
        return redirect()->route('dashboard')->with('error', 'Access denied.');
    }
}
