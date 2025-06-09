<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\PermissionRegistrar;
use App\Traits\ActivityLogger;
use Exception;

class SystemController extends Controller
{

    use ActivityLogger;
    public function index()
    {
        // if (\Auth::user()->can('manage system settings')) {
        $settings              = Utility::settings();

        return view('owner.settings.index', compact('settings'));
        // } else {
        //     return redirect()->back()->with('error', 'Permission denied.');
        // }
    }


    public function resetPermissions()
    {
        // Get the authenticated user
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 403);
        }

        // Remove all existing permissions
        $user->permissions()->detach();

        $company_id = Auth::user()->creatorId();

        $roles = $user->roles;

        foreach ($roles as $role) {
            $permissionNames = $role->permissions->pluck('name');
            $user->givePermissionTo($permissionNames);
        }

        $this->logActivity(
            'Permission Reseted',
            'Permission Reseted',
            route('owner.settings.index'),
            'Permission Reseted',
            Auth::user()->creatorId(),
            Auth::user()->id
        );

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        return redirect()->back()->with('success', __('Permissions reset successfully'));
    }
}
