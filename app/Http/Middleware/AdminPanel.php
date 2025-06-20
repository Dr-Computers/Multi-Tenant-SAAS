<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminPanel
{
    public function handle($request, Closure $next)
    {
    
        if (Auth::check() && (Auth::user()->type === 'super admin' || Auth::user()->type === 'admin-staff')) {
            return $next($request);
        }
        return redirect()->route('dashboard')->with('error', 'Access denied.');
    }
}
