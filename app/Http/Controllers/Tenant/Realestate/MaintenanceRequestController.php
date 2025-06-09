<?php

namespace App\Http\Controllers\Owner\Realestate;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequestAttachment;
use App\Models\MaintenanceTypes;
use App\Models\MediaFile;
use App\Models\Property;
use App\Models\PropertyMaintenanceRequest;
use App\Models\RealestateInvoice;
use App\Models\RealestateInvoiceItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Traits\Media\HandlesMediaFolders;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Traits\ActivityLogger;

class MaintenanceRequestController extends Controller
{
    use HandlesMediaFolders;
    use ActivityLogger;

    public function index()
    {
        if (Auth::user()->can('maintenance requests listing')) {
            $user_id                    = Auth::user()->id;

            $allRequests = PropertyMaintenanceRequest::whereHas('property', function ($query) use ($user_id) {
                $query->where('owner_id', 'LIKE', '%' . $user_id . '%');
            })->where('company_id', Auth::user()->creatorId())->get();
            $pendingRequests = PropertyMaintenanceRequest::whereHas('property', function ($query) use ($user_id) {
                $query->where('owner_id', 'LIKE', '%' . $user_id . '%');
            })->where('status', 'pending')->where('company_id', Auth::user()->creatorId())->get();
            $InprogressRequests      = PropertyMaintenanceRequest::whereHas('property', function ($query) use ($user_id) {
                $query->where('owner_id', 'LIKE', '%' . $user_id . '%');
            })->where('status', 'inprogress')->where('company_id', Auth::user()->creatorId())->get();
            $completedRequests = PropertyMaintenanceRequest::whereHas('property', function ($query) use ($user_id) {
                $query->where('owner_id', 'LIKE', '%' . $user_id . '%');
            })->where('status', 'completed')->where('company_id', Auth::user()->creatorId())->get();
            $ungeneratedInvoices = PropertyMaintenanceRequest::whereHas('property', function ($query) use ($user_id) {
                $query->where('owner_id', 'LIKE', '%' . $user_id . '%');
            })->where('company_id', Auth::user()->creatorId())->get();
            $dueInvoices = PropertyMaintenanceRequest::whereHas('property', function ($query) use ($user_id) {
                $query->where('owner_id', 'LIKE', '%' . $user_id . '%');
            })->where('company_id', Auth::user()->creatorId())->get();
            $paidInvoices = PropertyMaintenanceRequest::whereHas('property', function ($query) use ($user_id) {
                $query->where('owner_id', 'LIKE', '%' . $user_id . '%');
            })->where('company_id', Auth::user()->creatorId())->get();
            return view('owner.realestate.maintenance-requests.index', compact('allRequests', 'InprogressRequests', 'pendingRequests', 'completedRequests', 'ungeneratedInvoices', 'dueInvoices', 'paidInvoices'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
