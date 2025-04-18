<?php

namespace App\Http\Controllers\Company\HRMS;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;


class RoleController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('manage role')) {
            $roles = Role::where('created_by', '=', Auth::user()->creatorId())->get();
            return view('company.hrms.role.index')->with('roles', $roles);
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function create()
    {
        if (Auth::user()->can('create role')) {
            $user_id = Auth::user()->creatorId();
            $permissions = Permission::whereHas('company_permissions', function ($query) use ($user_id) {
                $query->where('company_id', 'LIKE', '%' . $user_id . '%');
            })->get();

            return view('company.hrms.role.form', ['permissions' => $permissions]);
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function show($id)
    {
        $role = Role::with('permissions')->where('created_by', '=', Auth::user()->creatorId())->findOrFail($id);
        $allPermissions = Permission::all();

        return view('company.hrms.role.show', compact('role', 'allPermissions'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create role')) {
            // Step 1: Prepare _name field
            $request->merge([
                '_name' => $request->input('name') . '-' . Auth::user()->creatorId()
            ]);

            // Step 2: Validate everything at once
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => [
                        'required',
                        'max:100',
                        'regex:/^[A-Za-z\s]+$/', // Only letters and spaces
                    ],
                    '_name' => [
                        'required',
                        'max:100',
                        'unique:roles,name,NULL,id,created_by,' . \Auth::user()->creatorId(),
                    ],
                    'permissions' => 'required|array|min:1',
                ],
                [
                    'name.regex' => 'The name must only contain letters and spaces.',
                    'permissions.required' => 'At least one permission must be selected.',
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            // Step 3: Store the role
            $role = new Role();
            $role->name = $request['_name']; // Save the unique merged name
            $role->is_editable = 1;
            $role->is_deletable = 1;
            $role->created_by = Auth::user()->creatorId();
            $role->save();

            // Step 4: Attach permissions
            foreach ($request['permissions'] as $permission) {
                $p = Permission::findOrFail($permission);
                $role->givePermissionTo($p);
            }

            return redirect()->back()->with('success', __('Role successfully created.'));
        }

        return redirect()->back()->with('error', 'Permission denied.');
    }


    public function edit(Role $role)
    {
        if (Auth::user()->can('edit role')) {

            $user_id = Auth::user()->creatorId();
            $permissions = Permission::whereHas('company_permissions', function ($query) use ($user_id) {
                $query->where('company_id', 'LIKE', '%' . $user_id . '%');
            })->get();
            return view('company.hrms.role.form', compact('role', 'permissions'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function update(Request $request, Role $role)
    {
        if (Auth::user()->can('edit role')) {

            // Merge custom _name to apply uniqueness check with company ID
            $request->merge([
                '_name' => $request->input('name') . '-' . Auth::user()->creatorId(),
            ]);

            // Validation
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => [
                        'required',
                        'max:100',
                        'regex:/^[A-Za-z\s]+$/', // Only letters and spaces
                    ],
                    '_name' => [
                        'required',
                        'max:100',
                        Rule::unique('roles', 'name')
                            ->where('created_by', Auth::user()->creatorId())
                            ->ignore($role->id),
                    ],
                    'permissions' => 'required|array|min:1',
                ],
                [
                    'name.regex' => 'The name must only contain letters and spaces.',
                    'permissions.required' => 'At least one permission must be selected.',
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            // Update role
            $role->name = $request['_name'];
            $role->save();

            // Sync permissions
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);

            return redirect()->back()->with('success', __('Role successfully updated.'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function destroy(Role $role)
    {
        if (Auth::user()->can('delete role')) {
            $role->delete();

            return redirect()->route('company.hrms.roles.index')->with(
                'success',
                'Role successfully deleted.'
            );
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
