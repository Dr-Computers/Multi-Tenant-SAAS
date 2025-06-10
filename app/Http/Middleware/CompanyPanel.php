<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CompanyPanel
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && (Auth::user()->type === 'company' || Auth::user()->type === 'company-staff')) {
            return $next($request);
        }
        return redirect()->route('dashboard')->with('error', 'Access denied.');
    }
}
