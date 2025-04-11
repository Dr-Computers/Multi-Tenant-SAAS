<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\PersonalDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = User::where('type', 'tenant')->where('parent', Auth::user()->creatorId())->get();
        return view('company.realestate.tenants.index', compact('tenants'));
    }

    public function create()
    {

        return view('company.realestate.tenants.form');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|max:100',
            'email'    => 'required|email|unique:users',
            'mobile'   => 'required',
            'password' => 'required|min:6',
            'address'=> 'required',
            'city'=> 'required',
            'state'=> 'required',
            'postal_code'=> 'required',
            'country'=> 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $user = new User();
        $user->name            = $request->name;
        $user->email           = $request->email;
        $user->mobile          = $request->mobile;
        $user->type            = 'tenant';
        $user->is_enable_login = $request->has('password_switch');
        $user->created_by      = auth()->user()->id;
        $user->parent          = auth()->user()->creatorId();
        $user->password        = Hash::make($request->password);
        $user->save();

        $personal               = new PersonalDetail();
        $personal->user_id        = $user->id;
        $personal->address        = $request->address;
        $personal->trn_no        =  $request->trn_no;
        $personal->city            =  $request->city;
        $personal->state        =  $request->state;
        $personal->postal_code    =  $request->postal_code;
        $personal->country        =  $request->country;
        $personal->save();

        $role_r = Role::findByName('tenant-' . Auth::user()->creatorId());
        $user->assignRole($role_r);

        return redirect()->route('company.realestate.tenants.index')->with('success', 'Tenant created successfully.');
    }

    public function edit($id)
    {
        $user = User::with('personal')->where('parent', '=', Auth::user()->creatorId())->where('id', $id)->first() ?? abort(404);

        return view('company.realestate.tenants.form', compact('user'));
    }

    public function update(Request $request, User $tenant)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|max:100',
            'email'  => 'required|email|unique:users,email,' . $tenant->id,
            'mobile' => 'required',
            'address'=> 'required',
            'city'=> 'required',
            'state'=> 'required',
            'postal_code'=> 'required',
            'country'=> 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $tenant->name            = $request->name;
        $tenant->email           = $request->email;
        $tenant->mobile          = $request->mobile;
        $tenant->is_enable_login = $request->has('password_switch');
        $tenant->save();


        $personal = PersonalDetail::where('user_id', $tenant->id)->first();
        $personal->address        = $request->address;
        $personal->trn_no        =  $request->trn_no;
        $personal->city            =  $request->city;
        $personal->state        =  $request->state;
        $personal->postal_code    =  $request->postal_code;
        $personal->country        =  $request->country;
        $personal->save();


        return redirect()->route('company.realestate.tenants.index')->with('success', 'Tenant updated successfully.');
    }

    public function destroy(User $tenant)
    {
   
        PersonalDetail::where('user_id', $tenant->id)->delete();
        $tenant->delete();
        return redirect()->back()->with('success', 'Tenant deleted successfully.');
    }

    public function show(User $tenant)
    {
        return view('company.realestate.tenants.show', compact('tenant'));
    }

    public function resetPasswordForm(User $user)
    {
        return view('company.realestate.tenants.reset-password', compact('user'));
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
