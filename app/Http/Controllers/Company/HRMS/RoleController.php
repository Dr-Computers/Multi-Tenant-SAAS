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

use App\Traits\ActivityLogger;

class RoleController extends Controller
{
    use ActivityLogger;
    public function index()
    {
        if (Auth::user()->can('role listing')) {
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
                $query->where('company_id', $user_id);
            })->get();

            return view('company.hrms.role.form', ['permissions' => $permissions]);
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function show($id)
    {
        if (Auth::user()->can('role details')) {
            $role = Role::with('permissions')->where('created_by', '=', Auth::user()->creatorId())->findOrFail($id);
            $allPermissions = Permission::all();

            return view('company.hrms.role.show', compact('role', 'allPermissions'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
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

            $this->logActivity(
                'Role as Created',
                'Role Name ' . $role->name,
                route('company.hrms.roles.index'),
                'Role Name ' . $role->name . ' is Created successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );

            return redirect()->back()->with('success', __('Role successfully created.'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function edit(Role $role)
    {
        if (Auth::user()->can('edit role')) {
            $user_id = Auth::user()->creatorId();
            $permissions = Permission::whereHas('company_permissions', function ($query) use ($user_id) {
                $query->where('company_id', 'LIKE', '%' . $user_id . '%');
            });

            if ('owner-' . $user_id) {
                $permissions = $permissions->where('is_owner', 1);
            } else if ('tenant-' . $user_id) {
                $permissions = $permissions->where('is_tenant', 1);
            } else if ('maintainer-' . $user_id) {
                $permissions = $permissions->where('is_maintainer', 1);
            }

            $permissions = $permissions->get();



            return view('company.hrms.role.form', compact('role', 'permissions'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function update(Request $request, Role $role)
    {
        if (Auth::user()->can('edit role')) {
            $user_id = Auth::user()->creatorId();
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

            if ($role->name == 'owner-' . $user_id || $role->name == 'tenant-' . $user_id || $role->name == 'maintainer-' . $user_id) {
            } else {
                $role->name = $request['_name'];
            }


            // Update role

            $role->save();

            // Sync permissions
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);

            $this->logActivity(
                'Role as Updated',
                'Role Name ' . $role->name,
                route('company.hrms.roles.index'),
                'Role Name ' . $role->name . ' is Updated successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );

            return redirect()->back()->with('success', __('Role successfully updated.'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function destroy(Role $role)
    {
        if (Auth::user()->can('delete role')) {
            $role->delete();

            $this->logActivity(
                'Role as Deleted',
                'Role Name ' . $role->name,
                route('company.hrms.roles.index'),
                'Role Name ' . $role->name . ' is Deleted successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );
            return redirect()->route('company.hrms.roles.index')->with(
                'success',
                'Role successfully deleted.'
            );
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
