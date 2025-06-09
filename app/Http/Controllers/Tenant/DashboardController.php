<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\BalanceSheet;
use App\Models\BankAccount;
use App\Models\Bill;
use App\Models\Goal;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\ProductServiceCategory;
use App\Models\ProductServiceUnit;
use App\Models\Revenue;
use App\Models\SupportTicket;
use App\Models\Tax;
use App\Models\Utility;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\Media\HandlesMediaFolders;

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

            $data['total_staff_users']      = User::where('parent', Auth::user()->creatorId())->count();
            $data['total_owners']           = User::where('parent', Auth::user()->creatorId())->count();
            $data['total_tenants']          = User::where('parent', Auth::user()->creatorId())->count();
            $data['total_propeties']        = User::where('parent', Auth::user()->creatorId())->count();
            $data['total_ticket']           = SupportTicket::count();
            $data['open_ticket']            = SupportTicket::where('status', 0)->count();
            $data['close_ticket']           = SupportTicket::where('status', 1)->count();
            $data['bankAccountBalance']            = BankAccount::where('company_id', Auth::user()->creatorId())->sum('current_balance');
            $data['latestIncome']  = Revenue::where('created_by', '=', Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get() ?? collect();
            $data['latestExpense'] = Payment::where('created_by', '=', Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get() ?? collect();
            $data['incExpBarChartData']  = Auth::user()->getincExpBarChartData();
            $data['incExpLineChartData'] = Auth::user()->getIncExpLineChartDate();

            $incomeCategory = ProductServiceCategory::where('created_by', '=', Auth::user()->creatorId())->where('type', '=', 'income')->get();
            $inColor        = array();
            $inCategory     = array();
            $inAmount       = array();
            for ($i = 0; $i < count($incomeCategory); $i++) {
                $inColor[]    = $incomeCategory[$i]->color;
                $inCategory[] = $incomeCategory[$i]->name;
                $inAmount[]   = $incomeCategory[$i]->incomeCategoryRevenueAmount();
            }


            $data['incomeCategoryColor'] = $inColor;
            $data['incomeCategory']      = $inCategory;
            $data['incomeCatAmount']     = $inAmount;



            $expenseCategory = ProductServiceCategory::where('created_by', '=', Auth::user()->creatorId())->where('type', '=', 'expense')->get();
            $exColor         = array();
            $exCategory      = array();
            $exAmount        = array();
            for ($i = 0; $i < count($expenseCategory); $i++) {
                $exColor[]    = $expenseCategory[$i]->color;
                $exCategory[] = $expenseCategory[$i]->name;
                $exAmount[]   = $expenseCategory[$i]->expenseCategoryAmount();
            }

            $data['expenseCategoryColor'] = $exColor;
            $data['expenseCategory']      = $exCategory;
            $data['expenseCatAmount']     = $exAmount;



            $data['currentYear']  = date('Y');
            $data['currentMonth'] = date('M');

            $constant['taxes']         = Tax::where('created_by', Auth::user()->creatorId())->count();
            $constant['category']      = ProductServiceCategory::where('created_by', Auth::user()->creatorId())->count();
            $constant['units']         = ProductServiceUnit::where('created_by', Auth::user()->creatorId())->count();
            $constant['bankAccount']   = BankAccount::where('created_by', Auth::user()->creatorId())->count();
            $data['constant']          = $constant;
            $data['bankAccountDetail'] = BankAccount::where('created_by', '=', Auth::user()->creatorId())->get();
            $data['recentInvoice']     = Invoice::where('created_by', '=', Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();
            $data['weeklyInvoice']     = Auth::user()->weeklyInvoice();
            $data['monthlyInvoice']    = Auth::user()->monthlyInvoice();
            $data['recentBill']        = Bill::where('created_by', '=', Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();
            $data['weeklyBill']        = Auth::user()->weeklyBill();
            $data['monthlyBill']       = Auth::user()->monthlyBill();
            $data['goals']             = Goal::where('created_by', '=', Auth::user()->creatorId())->where('is_display', 1)->get();



            $users = User::find(Auth::user()->creatorId());
            $plan = Plan::find($users->company->plan_id);
            if ($plan && $plan->storage_limit > 0) {
                $storage_limit = ($users->storage_limit / $plan->storage_limit) * 100;
            } else {
                $storage_limit = 0;
            }

            return view('tenant.dashboard.index', $data, compact('users', 'plan', 'storage_limit'));
        }
    }



    public function profile()
    {
        $userDetail              = Auth::user();

        return view('tenant.profile.index', compact('userDetail'));
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

                return redirect()->route('tenant.profile', $objUser->id)->with('success', __('Password successfully updated.'));
            } else {
                return redirect()->route('tenant.profile', $objUser->id)->with('error', __('Please enter correct current password.'));
            }
        } else {
            return redirect()->route('tenant.profile', \Auth::user()->id)->with('error', __('Something is wrong.'));
        }
    }
}
