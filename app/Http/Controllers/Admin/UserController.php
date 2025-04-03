<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessType;
use App\Models\Company;
use App\Models\CustomField;
use App\Models\Plan;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Session;
use Spatie\Permission\Models\Role;
use Lab404\Impersonate\Impersonate;


class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); // Ensure user is authenticated
        // Apply permission-based middleware for specific methods
        $this->middleware('can:manage user')->only(['index']);
        $this->middleware('can:create user')->only(['create', 'store']);
        $this->middleware('can:edit user')->only(['edit', 'update']);
        $this->middleware('can:delete user')->only(['destroy']);
    }

    public function index()
    {
        $users = User::where('type', '=', 'admin-staff')->get();
        return view('admin.user.index')->with('users', $users);
    }


    public function create()
    {
        $customFields = CustomField::where('created_by', '=', Auth::user()->creatorId())->where('module', '=', 'user')->get();
        $user         = Auth::user();
        $roles  = Role::where('created_by',Auth::user()->creatorId())->get();
    
        return view('admin.user.form', compact('customFields', 'roles'));
    }

    public function store(Request $request)
    {
        $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->first();
        $userpassword               = $request->input('password');
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:120',
                'email' => 'required|email|unique:users',
                'mobile' => 'required|max:250',
                'role'   => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $enableLogin       = 0;
        if (!empty($request->password_switch) && $request->password_switch == 'on') {
            $enableLogin   = 1;
            $validator = Validator::make(
                $request->all(),
                ['password' => 'required|min:6']
            );

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }
        }

        $user               = new User();
        $user['name']       = $request->name;
        $user['email']      = $request->email;
        $user['mobile']     = $request->mobile;
        $user['email_verified_at'] = date('Y-m-d H:i:s');
        $psw                = $request->password;
        $user['password'] = !empty($userpassword) ? Hash::make($userpassword) : null;
        $user['type']       = 'admin-staff';
        $user['lang']       = !empty($default_language) ? $default_language->value : '';
        $user['created_by'] = Auth::user()->creatorId();
        $user['is_enable_login'] = $enableLogin;
        $user->save();

        $role_r = Role::findById($request->role);
        $user->assignRole($role_r);

        $uArr = [
            'email' => $user->email,
            'password' => $psw,
        ];

        try {
            $resp = Utility::sendEmailTemplate('user_created', [$user->id => $user->email], $uArr);
        } catch (\Exception $e) {
            $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
        }

        return redirect()->route('admin.users.index')->with('success', __('User successfully added.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
    }

    public function edit($id)
    {

        $user  = Auth::user();
        $user              = User::findOrFail($id);
        $user->customField = CustomField::getData($user, 'user');
        $roles  = Role::where('created_by',Auth::user()->creatorId())->get();
        $customFields      = CustomField::where('created_by', '=', Auth::user()->creatorId())->where('module', '=', 'user')->get();

        return view('admin.user.form', compact('user', 'customFields', 'roles'));
    }


    public function update(Request $request, $id)
    {
            $user = User::findOrFail($id);

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'email' => 'required|email|unique:users,email,' . $id,
                    'mobile' => 'required|max:120',   
                    'role'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $input = $request->all();
            $user->fill($input)->save();


            // $role_r = Role::findById($request->role);
            // $user->assignRole($role_r);
            $user->roles()->sync([$request->input('role')]);

            CustomField::saveData($user, $request->customField);

            return redirect()->route('admin.users.index')->with(
                'success',
                'User successfully updated.'
            );
        
     
    }


    public function destroy($id)
    {

        $user = User::find($id);

        if ($user) {
           

                User::where('type', '=', 'company')->delete();
                $user->delete();
                return redirect()->back()->with('success', __('Company Successfully deleted'));

                // if ($user->delete_status == 0) {
                //     $user->delete_status = 1;
                // } else {
                //     $user->delete_status = 0;
                // }
                // $user->save();
         

            return redirect()->route('users.index')->with('success', __('User successfully deleted .'));
        } else {
            return redirect()->back();
        }
    }
}