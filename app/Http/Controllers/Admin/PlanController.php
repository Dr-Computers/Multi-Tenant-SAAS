<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessType;
use App\Models\Plan;
use App\Models\PlanModuleSection;
use App\Models\Section;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;

class PlanController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); // Ensure user is authenticated
    }
    public function index()
    {
        if (\Auth::user()->can('plan listing')) {
            // Extract unique business types based on relationship
            $businessTypes = BusinessType::get();;
            $plans                 = Plan::orderBy('price', 'asc')->get();
            $admin_payment_setting = Utility::getAdminPaymentSetting();
            return view('admin.plan.index', compact('plans', 'admin_payment_setting', 'businessTypes'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function create()
    {

        if (\Auth::user()->can('create plan')) {
            $arrDuration = [
                'lifetime' => __('Lifetime'),
                'month' => __('Per Month'),
                'year' => __('Per Year')
            ];
            $business_types  = BusinessType::get();
            $sections        = Section::get();
            return view('admin.plan.form', compact('arrDuration', 'business_types', 'sections'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function store(Request $request)
    {

        if (\Auth::user()->can('create plan')) {
            $validation = [
                'name' => 'required|unique:plans',
                'price' => 'required|numeric|min:0',
                'duration' => 'required',
                'max_users' => 'required|numeric',
                'max_customers' => 'required|numeric',
                'max_venders' => 'required|numeric',
                'storage_limit' => 'required|numeric',
                'business_type' => 'required',
            ];

            $request->validate($validation);

            $post = $request->all();
            if ($request->hasFile('image')) {
                $filenameWithExt = $request->file('image')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('image')->getClientOriginalExtension();
                $fileNameToStore = 'plan_' . time() . '.' . $extension;
                $dir = storage_path('uploads/plan/');
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                //$path          = $request->file('image')->storeAs('uploads/plan/', $fileNameToStore);
                $post['image'] = $fileNameToStore;
            }
            try {

                $plan = Plan::create($request->all());

                foreach ($request->sections ?? [] as $sec) {
                    $sections                    = new PlanModuleSection();
                    $sections->business_type_id  = $request->business_type;
                    $sections->section_id        = $sec;
                    $sections->plan_id           = $plan->id;
                    $sections->save();
                }

                return redirect()->back()->with('success', __('Plan Successfully created.'));
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __('Failed to create plan: ') . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function edit($plan_id)
    {
        if (\Auth::user()->can('edit plan')) {
            $arrDuration =  [
                'lifetime' => __('Lifetime'),
                'month' => __('Per Month'),
                'year' => __('Per Year'),
            ];

            $plan            = Plan::find($plan_id);
            $business_types  = BusinessType::get();
            $sections        = Section::get();
            return view('admin.plan.form', compact('plan', 'arrDuration', 'business_types', 'sections'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function update(Request $request, $plan_id)
    {
        if (\Auth::user()->can('edit plan')) {
            $admin_payment_setting = Utility::getAdminPaymentSetting();
            $plan = Plan::find($plan_id);
            if (!empty($plan)) {
                $validation                  = [];
                $validation['name']          = 'required|unique:plans,name,' . $plan_id;
                $validation['duration']      = 'required';
                $validation['max_users']     = 'required|numeric';
                $validation['max_owners'] = 'required|numeric';
                $validation['max_tenants']   = 'required|numeric';
                $validation['storage_limit'] = 'required|numeric';
                $validation['business_type'] = 'required';

                $request->validate($validation);

                $post = $request->all();
                if (array_key_exists('enable_chatgpt', $post)) {
                    $post['enable_chatgpt'] = 'on';
                } else {
                    $post['enable_chatgpt'] = 'off';
                }
                if (isset($request->trial)) {
                    $post['trial'] = 1;
                    $post['trial_days'] = $request->trial_days;
                } else {
                    $post['trial'] = 0;
                    $post['trial_days'] = null;
                }

                if ($request->hasFile('image')) {
                    $filenameWithExt = $request->file('image')->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('image')->getClientOriginalExtension();
                    $fileNameToStore = 'plan_' . time() . '.' . $extension;

                    $dir = storage_path('uploads/plan/');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $image_path = $dir . '/' . $plan->image;  // Value is not URL but directory file path
                    if (File::exists($image_path)) {

                        chmod($image_path, 0755);
                        File::delete($image_path);
                    }
                    $path = $request->file('image')->storeAs('uploads/plan/', $fileNameToStore);

                    $post['image'] = $fileNameToStore;
                }

                if ($plan->update($post)) {
                    PlanModuleSection::where('plan_id', $plan->id)->delete();
                    foreach ($request->sections ?? [] as $sec) {
                        $sections                    = new PlanModuleSection();
                        $sections->business_type_id  = $request->business_type;
                        $sections->section_id        = $sec;
                        $sections->plan_id           = $plan->id;
                        $sections->save();
                    }

                    return redirect()->back()->with('success', __('Plan successfully updated.'));
                } else {
                    return redirect()->back()->with('error', __('Something is wrong.'));
                }
            } else {
                return redirect()->back()->with('error', __('Plan not found.'));
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function userPlan(Request $request)
    {
        if (\Auth::user()->can('edit plan')) {
            $objUser = Auth::user();
            $planID  = \Illuminate\Support\Facades\Crypt::decrypt($request->code);
            $plan    = Plan::find($planID);
            if ($plan) {
                if ($plan->price <= 0) {
                    $objUser->assignPlan($plan->id);
                    return redirect()->route('admin.plans.index')->with('success', __('Plan successfully activated.'));
                } else {
                    return redirect()->back()->with('error', __('Something is wrong.'));
                }
            } else {
                return redirect()->back()->with('error', __('Plan not found.'));
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function planTrial($plan)
    {
        if (\Auth::user()->can('edit plan')) {
            $objUser = \Auth::user();
            $planID  = \Illuminate\Support\Facades\Crypt::decrypt($plan);
            $plan    = Plan::find($planID);

            if ($plan) {
                if ($plan->price > 0) {
                    $user = User::find($objUser->id);
                    $user->trial_plan = $planID;
                    $currentDate = date('Y-m-d');
                    $numberOfDaysToAdd = $plan->trial_days;

                    $newDate = date('Y-m-d', strtotime($currentDate . ' + ' . $numberOfDaysToAdd . ' days'));
                    $user->trial_expire_date = $newDate;
                    $user->save();

                    $objUser->assignPlan($planID);

                    return redirect()->route('admin.plans.index')->with('success', __('Plan successfully activated.'));
                } else {
                    return redirect()->back()->with('error', __('Something is wrong.'));
                }
            } else {
                return redirect()->back()->with('error', __('Plan not found.'));
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function destroy(Request $request, $id)
    {
        if (\Auth::user()->can('delete plan')) {
            $plan = Plan::find($id);
            if ($plan->id == $id) {
                PlanModuleSection::where('plan_id', $plan->id)->delete();
                $plan->delete();
                return redirect()->back()->with('success', __('Plan deleted successfully'));
            } else {
                return redirect()->back()->with('error', __('Something went wrong'));
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function planDisable(Request $request)
    {
        if (\Auth::user()->can('edit plan')) {
            $userPlan = User::where('plan', $request->id)->first();
            if ($userPlan != null) {
                return response()->json(['error' => __('The company has subscribed to this plan, so it cannot be disabled.')]);
            }

            Plan::where('id', $request->id)->update(['is_disable' => $request->is_disable]);

            if ($request->is_disable == 1) {
                return response()->json(['success' => __('Plan successfully enable.')]);
            } else {
                return response()->json(['success' => __('Plan successfully disable.')]);
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function Sections(Request $request)
    {
        if (\Auth::user()->can('section listing')) {
            $sections = Section::orderBy('category')->get();
            return view('admin.plan.sections', compact('sections'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function SectionEdit($id)
    {
        if (\Auth::user()->can('edit section')) {
            $section = Section::where('id', $id)->first() ?? abort(404);
            return view('admin.plan.sections-edit', compact('section'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function sectionUpdate(Request $request, $id)
    {
        if (\Auth::user()->can('edit section')) {
            $section           = Section::where('id', $id)->first() ?? abort(404);
            if ($section) {
                $section->category = $request->category;
                $section->price    = $request->price;
                $section->name     =  $request->name;
                $section->save();
                return redirect()->back()->with('success', __('section successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('section not found.'));
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
