<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Traits\ActivityLogger;

class RoleController extends Controller
{
    use ActivityLogger;

    public function index()
    {
        if (Auth::user()->can('role listing')) {
            $roles = Role::where('created_by', '=', Auth::user()->creatorId())->get();
            return view('admin.role.index')->with('roles', $roles);
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function create()
    {
        if (Auth::user()->can('create role')) {
            $permissions = Permission::where('is_admin', 1)->get();

            return view('admin.role.form', ['permissions' => $permissions]);
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function store(Request $request)
    {
        if (Auth::user()->can('create role')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:100|unique:roles,name,NULL,id,created_by,' . \Auth::user()->creatorId(),
                    'permissions' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $name             = $request['name'];
            $role             = new Role();
            $role->name       = $name;
            $role->created_by = Auth::user()->creatorId();
            $permissions      = $request['permissions'];
            $role->save();

            foreach ($permissions as $permission) {
                $p = Permission::where('id', '=', $permission)->firstOrFail();
                $role->givePermissionTo($p);
            }


            $this->logActivity(
                'Role as Created',
                'Role Name ' . $role->name,
                route('admin.roles.index'),
                'Role Name '.$role->name.' is Created successfully',
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

            $user = Auth::user();
            $permissions = Permission::where('is_admin', 1)->get();
            return view('admin.role.form', compact('role', 'permissions'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function update(Request $request, Role $role)
    {
        if (Auth::user()->can('edit role')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:100|unique:roles,name,' . $role['id'] . ',id,created_by,' . \Auth::user()->creatorId(),
                    'permissions' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $input       = $request->except(['permissions']);
            $permissions = $request['permissions'];
            $role->fill($input)->save();

            $p_all = Permission::where('is_admin', 1)->get();

            foreach ($p_all ?? [] as $p) {
                $role->revokePermissionTo($p);
            }

            foreach ($permissions ?? [] as $permission) {
                $p = Permission::where('id', '=', $permission)->firstOrFail();
                $role->givePermissionTo($p);
            }

            $this->logActivity(
                'Role as Updated',
                'Role Name ' . $role->name,
                route('admin.roles.index'),
                'Role Name '.$role->name.' is Updated successfully',
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
        if (Auth::user()->can('delete role ')) {
            
          
            $this->logActivity(
                'Role as Deleted',
                'Role Name ' . $role->name,
                route('admin.roles.index'),
                'Role Name '.$role->name.' is Deleted successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );
            
            $role->delete();

            return redirect()->route('roles.index')->with(
                'success',
                'Role successfully deleted.'
            );
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
