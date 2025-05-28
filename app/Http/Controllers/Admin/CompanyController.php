<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessType;
use App\Models\Company;
use App\Models\CompanySubscription;
use App\Models\Coupon;
use App\Models\CustomField;
use App\Models\Mail\UserCreate;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Role;
use App\Models\Section;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\Utility;
use File;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Session;
use Lab404\Impersonate\Impersonate;
use App\Traits\Media\HandlesMediaFolders;
use App\Traits\ActivityLogger;


class  CompanyController extends Controller
{

    use HandlesMediaFolders;
    use ActivityLogger;


    public function __construct()
    {
        $this->middleware('auth'); // Ensure user is authenticated
    }

    public function index()
    {
        if (Auth::user()->can('company listing')) {
            $users = User::where('type', '=', 'company')->get();
            return view('admin.company.index')->with('users', $users);
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function create()
    {
        if (Auth::user()->can('create company')) {
            $customFields    = CustomField::where('created_by', '=', Auth::user()->creatorId())->where('module', '=', 'user')->get();
            $user            = Auth::user();
            $business_types  = BusinessType::get();
            $plans           = Plan::get();

            return view('admin.company.form', compact('customFields', 'business_types', 'plans'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create company')) {
            DB::beginTransaction();
            $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->first();
            $company_default_language = DB::table('settings')->select('value')->where('name', 'company_default_language')->first();
            // $date = DB::table('settings')->select('value')->where('name', 'email_verification')->first();

            $userpassword               = $request->input('password');

            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'email' => 'required|email|unique:users',
                    'bussiness_type' => 'required|max:120',
                    'address' => 'required|max:250',
                    'landmark' => 'required|max:250',
                    'postalcode' => 'required|max:250',
                    'city' => 'required|max:250',
                    'identify_code' => 'required|max:10',
                    'plan'   => 'required',
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

            $plan               = Plan::where('id', $request->plan)->first();

            $user               = new User();
            $user['name']       = $request->name;
            $user['email']      = $request->email;
            $user['mobile']     = $request->mobile;
            $user['email_verified_at'] = date('Y-m-d H:i:s');
            $psw                = $request->password;
            $user['password'] = !empty($userpassword) ? Hash::make($userpassword) : null;
            $user['type']       = 'company';
            $user['lang']       = !empty($default_language) ? $default_language->value : '';
            $user['created_by'] = Auth::user()->creatorId();
            $user['is_enable_login'] = $enableLogin;
            $user['is_active']  = 1;
            $user->save();

            if ($request->hasFile('profile')) {
                $file_id = $this->uploadAndSaveFile($request->profile, $user->id, 'avatar');
                $user->avatar = $file_id;
            }

            $user->save();

            Company::planOrderStore($plan, $user->id);

            $order = Order::where('company_id', $user->id)->orderBy('created_at', 'desc')->first();

            $company                 = new Company();
            $company->user_id        = $user->id;
            $company->bussiness_name = $request->name ?? '';
            $company->bussiness_type = $request->bussiness_type ?? '';
            $company->address        = $request->address;
            $company->landmark       = $request->landmark;
            $company->postalcode     = $request->postalcode;
            $company->city           = $request->city;
            $company->country        = $request->country ?? 'UAE';
            $company->referral_code  = Utility::generateReferralCode();
            $company->identify_code  = $request->identify_code;
            $company->invoice_prefix = $request->identify_code;
            $company->quotation_prefix = $request->identify_code;
            $company->credit_note_prefix = $request->identify_code;
            $company->plan_order_id  = $order->id;
            $company->max_owners     = $plan->max_owners;
            $company->max_tenants     = $plan->max_tenants;
            $company->max_staff      = $plan->max_users;
            $company->storage_capacity = $plan->storage_limit;
            $company->save();

            CustomField::saveData($user, $request->customField);

            Company::createCompanyRoles($user->id);

            $role_r = Role::findByName('company-' . $user->id);
            $user->assignRole($role_r);

            // $user->userDefaultDataRegister($user->id);
            // Utility::chartOfAccountTypeData($user->id);
            // Utility::chartOfAccountData1($user->id);

            $uArr = [
                'email' => $user->email,
                'password' => $psw,
            ];

            try {
                $resp = Utility::sendEmailTemplate('user_created', [$user->id => $user->email], $uArr);
            } catch (\Exception $e) {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            }


            $uArr = [
                'email' => $user->email,
                'password' => $psw,
            ];

            try {
                $resp = Utility::sendEmailTemplate('user_created', [$user->id => $user->email], $uArr);
            } catch (\Exception $e) {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            }

            DB::commit();

            $this->logActivity(
                'Create a Company',
                'Company Id ' . $user->id,
                route('admin.company.index'),
                'New Company Created successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );


            return redirect()->route('admin.company.index')->with('success', __('User successfully added.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        } else {
            DB::rollBack();
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function edit($id)
    {

        if (Auth::user()->can('edit company')) {
            $user  = Auth::user();
            $business_types    = BusinessType::get();
            $plans             = Plan::get();
            $user              = User::findOrFail($id);
            $user->customField = CustomField::getData($user, 'user');
            $customFields      = CustomField::where('created_by', '=', Auth::user()->creatorId())->where('module', '=', 'user')->get();

            return view('admin.company.form', compact('user', 'customFields', 'business_types', 'plans'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit company')) {
            $user = User::findOrFail($id);

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'email' => 'required|email|unique:users,email,' . $id,
                    'bussiness_type' => 'required|max:120',
                    'address' => 'required|max:250',
                    'landmark' => 'required|max:250',
                    'postalcode' => 'required|max:250',
                    'city' => 'required|max:250',
                    'identify_code' => 'required|max:10',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $input = $request->all();
            $user->fill($input)->save();

            if ($request->hasFile('profile')) {
                $file_id = $this->uploadAndSaveFile($request->profile, $user->id, 'avatar');
                $user->avatar = $file_id;
            }

            $user->save();

            $company                = Company::where('user_id', $user->id)->first();
            $company->bussiness_name = $request->name ?? '';
            $company->bussiness_type = $request->bussiness_type ?? '';
            $company->address       = $request->address;
            $company->landmark      = $request->landmark;
            $company->postalcode    = $request->postalcode;
            $company->city          = $request->city;
            $company->identify_code =  $request->identify_code;
            $company->save();

            CustomField::saveData($user, $request->customField);

            $this->logActivity(
                'Update a Company',
                'Company Id ' . $user->id,
                route('admin.company.index'),
                'Company Updated successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );

            return redirect()->route('admin.company.index')->with(
                'success',
                'User successfully updated.'
            );
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function destroy($id)
    {

        if (Auth::user()->can('delete company')) {
            $user = User::find($id);

            if ($user) {


                User::where('type', '=', 'company')->where('parent', $id)->delete();
                $user->delete();
                return redirect()->back()->with('success', __('Company Successfully deleted'));

                $this->logActivity(
                    'Delete a Company',
                    'Company Id ' . $user->id,
                    route('admin.company.index'),
                    'Company Delete successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function profile()
    {
        if (Auth::user()->can('profile manage')) {
            $userDetail              = Auth::user();
            $userDetail->customField = CustomField::getData($userDetail, 'user');
            $customFields            = CustomField::where('created_by', '=', Auth::user()->creatorId())->where('module', '=', 'user')->get();

            return view('admin.company.profile', compact('userDetail', 'customFields'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function editprofile(Request $request)
    {
        if (Auth::user()->can('profile manage')) {
            $userDetail = Auth::user();
            $user       = User::findOrFail($userDetail['id']);
            $this->validate(
                $request,
                [
                    'name' => 'required|max:120',
                    'email' => 'required|email|unique:users,email,' . $userDetail['id'],
                ]
            );

            if ($request->hasFile('profile')) {
                if (Auth::user()->type = 'super admin') {
                    $file_path = $user['avatar'];
                    $filenameWithExt = $request->file('profile')->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('profile')->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $settings = Utility::getStorageSetting();

                    if ($settings['storage_setting'] == 'local') {
                        $dir        = 'uploads/avatar/';
                    } else {
                        $dir        = 'uploads/avatar';
                    }
                    $image_path = $dir . $userDetail['avatar'];

                    $url = '';
                    // $path = $request->file('profile')->storeAs('uploads/avatar/', $fileNameToStore);
                    // dd($path);
                    $path = Utility::upload_file($request, 'profile', $fileNameToStore, $dir, []);
                    // dd($path);
                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->route('profile', Auth::user()->id)->with('error', __($path['msg']));
                    }
                } else {
                    $file_path = $user['avatar'];
                    $image_size = $request->file('profile')->getSize();
                    $result = Utility::updateStorageLimit(Auth::user()->creatorId(), $image_size);

                    if ($result == 1) {

                        Utility::changeStorageLimit(Auth::user()->creatorId(), $file_path);
                        $filenameWithExt = $request->file('profile')->getClientOriginalName();
                        $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension       = $request->file('profile')->getClientOriginalExtension();
                        $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                        $settings = Utility::getStorageSetting();

                        if ($settings['storage_setting'] == 'local') {
                            $dir        = 'uploads/avatar/';
                        } else {
                            $dir        = 'uploads/avatar';
                        }
                        $image_path = $dir . $userDetail['avatar'];

                        $url = '';
                        // $path = $request->file('profile')->storeAs('uploads/avatar/', $fileNameToStore);
                        // dd($path);
                        $path = Utility::upload_file($request, 'profile', $fileNameToStore, $dir, []);
                        // dd($path);
                        if ($path['flag'] == 1) {
                            $url = $path['url'];
                        } else {
                            return redirect()->route('profile', Auth::user()->id)->with('error', __($path['msg']));
                        }
                    } else {
                        return redirect()->back()->with('error', $result);
                    }
                }
            }

            if (!empty($request->profile)) {
                $user['avatar'] =  $url;
            }
            $user['name']  = $request['name'];
            $user['email'] = $request['email'];
            $user->save();
            CustomField::saveData($user, $request->customField);

            return redirect()->back()->with(
                'success',
                __('Profile successfully updated.') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : '')
            );
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function updatePassword(Request $request)
    {
        if (Auth::user()->can('profile manage')) {
            if (Auth::Check()) {
                $request->validate(
                    [
                        'current_password' => 'required',
                        'new_password' => 'required|min:6',
                        'confirm_password' => 'required|same:new_password',
                    ]
                );
                $objUser          = Auth::user();
                $request_data     = $request->All();
                $current_password = $objUser->password;
                if (Hash::check($request_data['current_password'], $current_password)) {
                    $user_id            = Auth::User()->id;
                    $obj_user           = User::find($user_id);
                    $obj_user->password = Hash::make($request_data['new_password']);;
                    $obj_user->save();

                    $this->logActivity(
                        'Company Password Reseted',
                        'Company Id ' . $user->id,
                        route('admin.company.index'),
                        'Company Password Reset successfully',
                        Auth::user()->creatorId(),
                        Auth::user()->id
                    );


                    return redirect()->route('profile', $objUser->id)->with('success', __('Password successfully updated.'));
                } else {
                    return redirect()->route('profile', $objUser->id)->with('error', __('Please enter correct current password.'));
                }
            } else {
                return redirect()->route('profile', Auth::user()->id)->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function upgradePlan($user_id)
    {
        if (Auth::user()->can('company plan upgrade')) {
            $user = User::find($user_id);
            $company = $user->company;
            $plans = Plan::where('business_type', $company->bussiness_type)->get();
            return view('admin.company.upgrade-plan', compact('user', 'plans'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function upgradePlanStore(Request $request, $company_id, $plan_id)
    {
        if (Auth::user()->can('company plan upgrade')) {
            $plan = Plan::where('id', $plan_id)->first();
            Company::planOrderStore($plan, $company_id);
            $this->logActivity(
                'Company Plan Upgrade',
                'Company Id ' . $user->id,
                route('admin.company.index'),
                'Company Plan Upgraded successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );

            return redirect()->back()->with('success', __('successfully upgraded.'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function activePlan($user_id, $plan_id)
    {
        if (Auth::user()->can('company plan upgrade')) {
            $user       = User::find($user_id);
            $assignPlan = $user->assignPlan($plan_id);
            $plan       = Plan::find($plan_id);
            if ($assignPlan['is_success'] == true && !empty($plan)) {
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                Order::create(
                    [
                        'order_id' => $orderID,
                        'name' => null,
                        'card_number' => null,
                        'card_exp_month' => null,
                        'card_exp_year' => null,
                        'plan_name' => $plan->name,
                        'plan_id' => $plan->id,
                        'price' => $plan->price,
                        'price_currency' => isset(Auth::user()->planPrice()['currency']) ? Auth::user()->planPrice()['currency'] : 'AED',
                        'txn_id' => '',
                        'payment_status' => 'succeeded',
                        'receipt' => null,
                        'user_id' => $user->company->id,
                    ]
                );

                return redirect()->back()->with('success', 'Plan successfully upgraded.');
            } else {
                return redirect()->back()->with('error', 'Plan fail to upgrade.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    // change mode 'dark or light'
    public function changeMode()
    {

        $usr = Auth::user();
        if ($usr->mode == 'light') {
            $usr->mode      = 'dark';
        } else {
            $usr->mode      = 'light';
        }
        $usr->save();
        return redirect()->back();
    }

    public function userPassword($id)
    {
        $eId        = \Crypt::decrypt($id);
        $user = User::find($eId);

        return view('admin.company.reset', compact('user'));
    }

    public function userPasswordReset(Request $request, $id)
    {
        if (Auth::user()->can('profile manage')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'password' => 'required|confirmed|same:password_confirmation',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            $user                 = User::where('id', $id)->first();
            $user->forceFill([
                'password' => Hash::make($request->password),
                'is_enable_login' => 1,
            ])->save();

            return redirect()->route('admin.company.index')->with(
                'success',
                'User Password successfully updated.'
            );
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function LoginWithCompany(Request $request,   $id)
    {
        if (Auth::user()->can('login as company')) {
            // dd($request,  $request->user(), $id);
            $user = User::find($id);
            if ($user && auth()->check()) {
                Impersonate::take($request->user(), $user);

                $this->logActivity(
                    'Company Account Accessed',
                    'Company Id ' . $user->id,
                    route('admin.company.index'),
                    'Company Account Accessed successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );

                return redirect('/');
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function ExitCompany(Request $request)
    {
        Auth::user()->leaveImpersonation($request->user());
        $this->logActivity(
            'Company Account Existed',
            'Company Id ' . $user->id,
            route('admin.company.index'),
            'Company Account Existed successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );

        return redirect('/');
    }

    public function CompnayInfo($id)
    {
        if (Auth::user()->can('company details')) {
            if (!empty($id)) {
                $data = $this->Counter($id);
                if ($data['is_success']) {
                    $users_data = $data['response']['users_data'];
                    return view('admin.company.companyinfo', compact('id', 'users_data'));
                }
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function UserUnable(Request $request)
    {
        if (Auth::user()->can('login company disable')) {
            if (!empty($request->id) && !empty($request->company_id)) {
                if ($request->name == 'user') {
                    User::where('id', $request->id)->update(['is_disable' => $request->is_disable]);
                    $data = $this->Counter($request->company_id);
                }

                if ($data['is_success']) {
                    $users_data = $data['response']['users_data'];
                }
                if ($request->is_disable == 1) {

                    return response()->json(['success' => __('Successfully Enable.'), 'users_data' => $users_data]);
                } else {
                    return response()->json(['success' => __('Successfull Disable.'), 'users_data' => $users_data]);
                }
            }
            return response()->json('error');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function Counter($id)
    {
        $response = [];
        if (!empty($id)) {

            $users = User::where('created_by', $id)->selectRaw('COUNT(*) as total_users, SUM(CASE WHEN is_disable = 0 THEN 1 ELSE 0 END) as disable_users, SUM(CASE WHEN is_disable = 1 THEN 1 ELSE 0 END) as active_users')->first();

            $users_data[$users->name] = [
                'total_users' => !empty($users->total_users) ? $users->total_users : 0,
                'disable_users' => !empty($users->disable_users) ? $users->disable_users : 0,
                'active_users' => !empty($users->active_users) ? $users->active_users : 0,
            ];

            $response['users_data'] = $users_data;

            return [
                'is_success' => true,
                'response' => $response,
            ];
        }
        return [
            'is_success' => false,
            'error' => 'Plan is deleted.',
        ];
    }

    public function LoginManage($id)
    {
        if (Auth::user()->can('login company disable')) {
            $eId        = Crypt::decrypt($id);
            $user = User::find($eId);
            if ($user->is_enable_login == 1) {
                $user->is_enable_login = 0;
                $user->save();
                $this->logActivity(
                    'Company Login Disabled',
                    'Company Id ' . $user->id,
                    route('admin.company.index'),
                    'Company login disable successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );

                return redirect()->back()->with('success', __('Company login disable successfully.'));
            } else {
                $user->is_enable_login = 1;
                $user->save();
                $this->logActivity(
                    'Company Login Enabled',
                    'Company Id ' . $user->id,
                    route('admin.company.index'),
                    'Company login enable successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );
                return redirect()->back()->with('success', __('Company login enable successfully.'));
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function addonFeatures($company_id)
    {
        if (Auth::user()->can('company addon features')) {
            $existingSectionIds = CompanySubscription::where('company_id', $company_id)->get();
            $addonSections    = Section::get();
            $user             = User::where('id', $company_id)->first();

            $existingSections = Section::with(['addedSections' => function ($query) use ($company_id) {
                $query->where('company_id', $company_id);
            }])
                ->whereHas('addedSections', function ($q) use ($company_id) {
                    $q->where('company_id', $company_id);
                })
                ->get();
            return view('admin.company.addon-features', compact('existingSections', 'existingSectionIds', 'addonSections', 'user'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }



    public function existingFeaturesRemove($company_id, $id)
    {
        if (Auth::user()->can('company addon features')) {
            if (!$id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Section ID is required.'
                ], 400);
            }

            $addedSection = CompanySubscription::where('section_id', $id)
                ->where('company_id', $company_id)
                ->first();

            if (!$addedSection) {
                return response()->json([
                    'status' => false,
                    'message' => 'Feature not found.'
                ], 404);
            }

            $addedSection->delete();

            return response()->json([
                'status' => true,
                'message' => 'Feature removed successfully.'
            ]);
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function addonFeaturesStore(Request $request, $company_id)
    {
        if (Auth::user()->can('company addon features')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'features' => 'required|array',
                    'subtotal' => 'required|numeric',
                    'tax' => 'required|numeric',
                    'discount' => 'nullable|numeric',
                    'grandtotal' => 'required|numeric',
                    'coupon_code' => 'nullable|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $features = $request->features ?? [];

            Company::sectionOrderStore($features, $company_id, $request->tax, $request->subtotal, $request->discount, $request->coupon_code, $request->grandtotal);

            $this->logActivity(
                'Company Feature Purchasing Completed',
                'Company Id ' . $company_id,
                route('admin.company.index'),
                'Company Feature Purchasing Completed',
                Auth::user()->creatorId(),
                Auth::user()->id
            );

            return back()->with('success', 'Purchase  completed successfully!');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function validateCoupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->where('is_active', true)->first();

        if ($coupon) {
            return response()->json([
                'success' => true,
                'amount' => $coupon->discount_amount,
            ]);
        }

        return response()->json([
            'success' => false,
        ]);
    }
}
