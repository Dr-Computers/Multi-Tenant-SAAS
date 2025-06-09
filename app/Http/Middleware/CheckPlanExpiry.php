<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPlanExpiry
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->company && $user->company->activeSubscription && $user->company->activeSubscription->end_of_date) {
            $today = now()->startOfDay();
            $expiryDate = \Carbon\Carbon::parse($user->company->activeSubscription->end_of_date)->startOfDay();

            if ($expiryDate->lt($today)) {
                return redirect()->route('company.plan-expired');
            }
        } else {
            return redirect()->route('company.plan-expired');
        }

        return $next($request);
    }
}
