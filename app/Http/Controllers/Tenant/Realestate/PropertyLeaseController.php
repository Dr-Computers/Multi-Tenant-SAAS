<?php

namespace App\Http\Controllers\Tenant\Realestate;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\RealestateChequeDetail;
use App\Models\RealestateLease;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Traits\Media\HandlesMediaFolders;
use Illuminate\Support\Facades\Storage;


class PropertyLeaseController extends Controller
{

    use HandlesMediaFolders;
    public function index()
    {
        $company_id                 = Auth::user()->creatorId();
        $user_id                    = Auth::user()->id;
        $leasing_units              = PropertyUnit::whereHas('property', function ($query) use ($user_id) {
            $query->where('owner_id', 'LIKE', '%' . $user_id . '%');
        })->where('company_id', $company_id)->where('status', 'leasing')->get();
        
        return view('tenant.realestate.properties.leasing.index', compact('leasing_units'));
    }

    public function cancel(PropertyUnit $unit)
    {
        DB::beginTransaction();

        try {
            $company_id         = Auth::user()->creatorId();
            $lease   = RealestateLease::where('unit_id', $unit->id)->where('company_id', $company_id)->first() ?? abort(404);
            $lease->status = 'canceled';
            $lease->save();

            $unit->status = 'canceled';
            $unit->save();
            DB::commit();

            return redirect()->back()->with('success', 'Lease  cancelation successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function inHold(PropertyUnit $unit)
    {
        DB::beginTransaction();

        try {
            $company_id         = Auth::user()->creatorId();
            $lease   = RealestateLease::where('unit_id', $unit->id)->where('company_id', $company_id)->first() ?? abort(404);
            $lease->status = 'case';
            $lease->save();

            $unit->status = 'case';
            $unit->save();
            DB::commit();

            return redirect()->back()->with('success', 'Lease in case successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function approve(PropertyUnit $unit)
    {
        DB::beginTransaction();

        try {
            $company_id         = Auth::user()->creatorId();
            $lease   = RealestateLease::where('unit_id', $unit->id)->where('company_id', $company_id)->first() ?? abort(404);
            $lease->status = 'active';
            $lease->save();

            $unit->status = 'leasing';
            $unit->save();
            DB::commit();

            return redirect()->back()->with('success', 'Lease in approved successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
