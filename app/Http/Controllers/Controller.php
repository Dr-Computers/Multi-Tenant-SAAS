<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function dashboard()
    {
        if (Auth::check()) {
            if (Auth::user()->type == 'super admin' || Auth::user()->type == 'admin staff') {
                return redirect()->route('admin.dashboard');
            } else if (Auth::user()->type == 'company' || Auth::user()->type == 'company staff') {
                return redirect()->route('company.dashboard');
            } else if (Auth::user()->type == 'owner') {
                return redirect()->route('owner.dashboard');
            } else if (Auth::user()->type == 'tenant') {
                return redirect()->route('tenant.dashboard');
            } else if (Auth::user()->type == 'maintainer') {
                return redirect()->route('maintainer.dashboard');
            }
        } else {
            return redirect()->route('login');
        }
    }
}
