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
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\RealestatePaymentsPayable;
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
            $user_id = auth()->user()->id;
            $data['total_units']            = PropertyUnit::with([
                                                                'lease' => function ($query) use ($user_id) {
                                                                    $query->where('tenant_id', '=', $user_id);
                                                                }
                                                            ])->count();

            $data['total_propeties']        = Property::with([
                                                                'lease' => function ($query) use ($user_id) {
                                                                    $query->where('tenant_id', '=', $user_id);
                                                                }
                                                            ])->count();
              
            $data['total_amount']           = RealestatePaymentsPayable::where('user_id', $user_id)->sum('amount');

            $users = User::find(Auth::user()->creatorId());

            return view('tenant.dashboard.index', $data, compact('users'));
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
