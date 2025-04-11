<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class OwnerController extends Controller
{
    public function index()
    {
        $owners = User::where('type', 'owner')->where('parent', Auth::user()->creatorId())->get();
        return view('company.realestate.owners.index', compact('owners'));
    }

    public function create()
    {

        return view('company.realestate.owners.form');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|max:100',
            'email'    => 'required|email|unique:users',
            'mobile'   => 'required',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $user = new User();
        $user->name            = $request->name;
        $user->email           = $request->email;
        $user->mobile          = $request->mobile;
        $user->type            = 'owner';
        $user->is_enable_login = $request->has('password_switch');
        $user->created_by      = auth()->user()->id;
        $user->parent          = auth()->user()->creatorId();
        $user->password        = Hash::make($request->password);
        $user->save();

        $owner = new Owner();
        $owner->user_id = $user->id;
        $owner->is_tenants_approval = $request->has('is_tenants_approval');
        $owner->save();

        $role_r = Role::findByName('owner-' . Auth::user()->creatorId());
        $user->assignRole($role_r);

        return redirect()->route('company.realestate.owners.index')->with('success', 'Owner created successfully.');
    }

    public function edit($id)
    {
        $user = User::with('owner')->where('parent', '=', Auth::user()->creatorId())->where('id', $id)->first() ?? abort(404);

        return view('company.realestate.owners.form', compact('user'));
    }

    public function update(Request $request, User $owner)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|max:100',
            'email'  => 'required|email|unique:users,email,' . $owner->id,
            'mobile' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $owner->name            = $request->name;
        $owner->email           = $request->email;
        $owner->mobile          = $request->mobile;
        $owner->is_enable_login = $request->has('password_switch');
        $owner->save();


        $personal = Owner::where('user_id', $owner->id)->first();
        $personal->is_tenants_approval = $request->has('is_tenants_approval');
        $personal->save();


        return redirect()->route('company.realestate.owners.index')->with('success', 'Owner updated successfully.');
    }

    public function destroy(User $owner)
    {
        Owner::where('user_id', $owner->id)->delete();
        $owner->delete();
        return redirect()->back()->with('success', 'Owner deleted successfully.');
    }

    public function show(User $owner)
    {
        return view('company.realestate.owners.show', compact('owner'));
    }

    public function resetPasswordForm(User $user)
    {
        return view('company.realestate.owners.reset-password', compact('user'));
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password reset successfully.');
    }
}
