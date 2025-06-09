<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\BalanceSheet;
use App\Models\BankAccount;
use App\Models\Bill;
use App\Models\Company;
use App\Models\CompanySubscription;
use App\Models\Coupon;
use App\Models\Expense;
use App\Models\Goal;
use App\Models\Invoice;
use App\Models\MediaFile;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\PlanRequest;
use App\Models\ProductServiceCategory;
use App\Models\ProductServiceUnit;
use App\Models\Property;
use App\Models\RealestateInvoice;
use App\Models\RealestateLease;
use App\Models\Revenue;
use App\Models\Section;
use App\Models\SectionPlanRequest;
use App\Models\SupportTicket;
use App\Models\Tax;
use App\Models\Utility;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\Media\HandlesMediaFolders;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    use HandlesMediaFolders;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if (Auth::check()) {
            $company_id                     =   Auth::user()->creatorId();
            $data['total_staff_users']      =   User::where('type', 'company-staff')->where('parent', $company_id)->count();
            $data['total_owners']           =   User::where('type', 'owner')->where('parent', $company_id)->count();
            $data['total_tenants']          =   User::where('type', 'tenant')->where('parent', $company_id)->count();
            $data['total_propeties']        =   Property::where('company_id', $company_id)->count();
            $data['total_ticket']           =   SupportTicket::where('company_id', $company_id)->count();
            $data['bankAccounts']           =   BankAccount::where('company_id', $company_id)->count();
            $data['bankAccountDetail']      =   BankAccount::where('company_id', '=', $company_id)->get();
            $data['totalExpenses']          =   Expense::where('company_id', $company_id)->sum('amount');
            $data['totalDeposits']          =   RealestateLease::where('company_id', $company_id)->sum('security_deposit');
            $data['bankAccountBalance']     =   BankAccount::where('company_id', $company_id)->get();
            $data['latestIncome']           =   Revenue::where('created_by', '=', $company_id)->orderBy('id', 'desc')->limit(5)->get() ?? collect();
            $data['latestExpense']          =   Expense::where('company_id', '=', $company_id)->orderBy('id', 'desc')->limit(5)->get() ?? collect();
            $data['recentInvoices']         =   RealestateInvoice::where('company_id', '=', $company_id)->get();
            $data['recentBills']            =   Invoice::where('company_id', '=', $company_id)->get();
            $data['totalStorage']           =   Company::where('user_id', $company_id)->first()->storage_capacity;
            $data['usedStorage']            =   number_format(MediaFile::where('company_id', $company_id)->sum('size') / (1024 * 1024), 2);

            $users = User::find($company_id);

            $company = $users->company;

            $planExpiryDate = Carbon::parse($company->activeSubscription->end_of_date);
            $today = Carbon::today();

            $showExpiryAlert = $planExpiryDate->isAfter($today) && $planExpiryDate->diffInDays($today) <= 7;


            return view('company.dashboard.index', $data, compact('users', 'showExpiryAlert', 'planExpiryDate'));
        }
    }

    public function getOrderChart($arrParam)
    {
        $arrDuration = [];
        if ($arrParam['duration']) {
            if ($arrParam['duration'] == 'week') {
                $previous_week = strtotime("-2 week +1 day");
                for ($i = 0; $i < 14; $i++) {
                    $arrDuration[date('Y-m-d', $previous_week)] = date('d-M', $previous_week);
                    $previous_week                              = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                }
            }
        }

        $arrTask          = [];
        $arrTask['label'] = [];
        $arrTask['data']  = [];
        foreach ($arrDuration as $date => $label) {

            $data               = Order::select(\DB::raw('count(*) as total'))->whereDate('created_at', '=', $date)->first();
            $arrTask['label'][] = $label;
            $arrTask['data'][]  = $data->total;
        }

        return $arrTask;
    }

    public function profile()
    {
        $userDetail              = Auth::user();

        return view('company.profile.index', compact('userDetail'));
    }

    public function editprofile(Request $request)
    {
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
            $file_id = $this->uploadAndSaveFile($request->profile, Auth::user()->creatorId(), 'avatar');

            $user['avatar'] =  $file_id;
        }

        $user['name']  = $request['name'];
        $user['email'] = $request['email'];
        $user->save();

        return redirect()->back()->with(
            'success',
            __('Profile successfully updated.') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : '')
        );
    }

    public function updatePassword(Request $request)
    {
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

                return redirect()->route('company.profile', $objUser->id)->with('success', __('Password successfully updated.'));
            } else {
                return redirect()->route('company.profile', $objUser->id)->with('error', __('Please enter correct current password.'));
            }
        } else {
            return redirect()->route('company.profile', \Auth::user()->id)->with('error', __('Something is wrong.'));
        }
    }




    public function planUpgrade()
    {
        // if (Auth::user()->can('company plan upgrade')) {
        $company_id         = Auth::user()->creatorId();
        $user = User::find($company_id);
        $company = $user->company;
        $plans = Plan::where('business_type', $company->bussiness_type)->get();
        $existingRequests = PlanRequest::where('company_id', $company_id)->get();
        return view('company.dashboard.upgrade-plan', compact('user', 'plans', 'existingRequests'));
        // } else {
        //     return redirect()->back()->with('error', 'Permission denied.');
        // }
    }
    public function planUpgradeStore(Request $request)
    {

        // if (Auth::user()->can('company addon features')) {
        if (isset($request->purchase) && $request->purchase != null || isset($request->renew) && $request->renew != null) {
            $company_id         = Auth::user()->creatorId();
            $new                = new PlanRequest();
            $new->company_id    = $company_id;
            $new->plan_id        = $request->purchase ?? $request->renew;
            $new->duration      = '';
            $new->save();
            return redirect()->back()->with('success', 'Plan Request Sent Successfully!');
        } else {
            return redirect()->back()->with('error', 'Invalide Request.');
        }
        // } else {
        //     return redirect()->back()->with('error', 'Permission denied.');
        // }
    }

    public function addonFeatures()
    {
        // if (Auth::user()->can('company addon features')) {
        $company_id         = Auth::user()->creatorId();
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
        $existingRequests  = SectionPlanRequest::where('company_id', $company_id)->get();

        return view('company.dashboard.addon-features', compact('existingSections', 'existingSectionIds', 'existingRequests', 'addonSections', 'user'));
        // } else {
        //     return redirect()->back()->with('error', 'Permission denied.');
        // }
    }
    public function addonFeaturesStore(Request $request)
    {
        $company_id = Auth::user()->creatorId();

        // if (Auth::user()->can('company addon features')) {
        $validator = Validator::make(
            $request->all(),
            [
                'features'     => 'required|array',
                'features.*'   => 'required|integer',
                'subtotal'     => 'nullable|numeric',
                'tax'          => 'nullable|numeric',
                'discount'     => 'nullable|numeric',
                'grandtotal'   => 'nullable|numeric',
                'coupon_code'  => 'nullable|string',
                'coupon_id'    => 'nullable|integer',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $features = $request->features ?? [];
        $featuresIds = implode(',', $features);
        $subTotal = Section::whereIn('id', $features)->sum('price');

        $coupon = Coupon::where('code', $request->coupon_code)->where('is_active', true)->first();
        $discountAmount = $coupon ? $coupon->discount : 0;

        $taxPercent = 5;
        $taxableAmount = max($subTotal - $discountAmount, 0);
        $taxAmount = ($taxableAmount * $taxPercent) / 100;
        $grandTotal = $taxableAmount + $taxAmount;

        // $features = $request->features ?? [];
        // $featuresIds = '';
        // $subTotal = 0;
        // $taxAmount = 0;
        // $finalAmount = 0;

        // if (count($features) > 0) {
        //     $featuresIds = implode(',', $features);
        //     $subTotal = Section::whereIn('id', $features)->sum('price');

        //     // Apply coupon if valid
        //     $coupon = Coupon::where('code', $request->coupon_code)
        //         ->where('is_active', true)
        //         ->first();

        //     $discount = $coupon ? $coupon->discount : ($request->discount ?? 0);
        //     $finalAmount = max($subTotal - $discount, 0);

        //     // Tax calculation (e.g., 5%)
        //     $taxRate = 0.05;
        //     $taxAmount = round($finalAmount * $taxRate, 2);
        // }

        // $grandTotal = $finalAmount + $taxAmount;

        $new = new SectionPlanRequest();
        $new->company_id    = $company_id;
        $new->section_ids   = $featuresIds;
        $new->duration      = ''; // Add logic if duration is needed
        $new->coupon        = $request->coupon_code ?? '';
        $new->coupon_id     = $request->coupon_id ?? ($coupon->id ?? null);
        $new->discount      = $discount ?? 0;
        $new->tax_total     = $taxAmount;
        $new->sub_total     = $subTotal;
        $new->grand_total   = $grandTotal;
        $new->save();

        $this->logActivity(
            'Addon Feature Request Sent Successfully',
            'Addon Feature Request',
            route('company.addon.features'),
            'Addon Feature Request Sent Successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );


        return back()->with('success', 'Request Sent Successfully!');
        // } else {
        //     return redirect()->back()->with('error', 'Permission denied.');
        // }
    }


    public function existingFeaturesRemove($company_id, $id)
    {


        // if (Auth::user()->can('company addon features')) {
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
        // } else {
        //     return redirect()->back()->with('error', 'Permission denied.');
        // }

    }

    public function couponValidate(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->where('is_active', true)->first();

        if ($coupon) {
            return response()->json([
                'success' => true,
                'amount' => $coupon->discount,
            ]);
        }

        return response()->json([
            'success' => false,
        ]);
    }



    public function planExpired()
    {
        return view('company.dashboard.plan-expired');
    }
}
