<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequestAttachment;
use App\Models\MaintenanceTypes;
use App\Models\Property;
use App\Models\PropertyMaintenanceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MaintenanceRequestController extends Controller
{
    public function index()
    {
        $allRequests = PropertyMaintenanceRequest::where('company_id', Auth::user()->creatorId())->get();
        $pendingRequests = PropertyMaintenanceRequest::where('company_id', Auth::user()->creatorId())->get();
        $completedRequests = PropertyMaintenanceRequest::where('company_id', Auth::user()->creatorId())->get();
        $ungeneratedInvoices = PropertyMaintenanceRequest::where('company_id', Auth::user()->creatorId())->get();
        $dueInvoices = PropertyMaintenanceRequest::where('company_id', Auth::user()->creatorId())->get();
        $paidInvoices = PropertyMaintenanceRequest::where('company_id', Auth::user()->creatorId())->get();
        $InprogressRequests = PropertyMaintenanceRequest::where('company_id', Auth::user()->creatorId())->get();
        return view('company.realestate.maintenance-requests.index', compact('allRequests', 'InprogressRequests', 'pendingRequests', 'completedRequests', 'ungeneratedInvoices', 'dueInvoices', 'paidInvoices'));
    }
    public function create()
    {
        $issues =  MaintenanceTypes::get();
        $properties        = Property::where('company_id', Auth::user()->creatorId())->get();
        $maintainers       = User::where('type', 'maintainer')->where('parent', Auth::user()->creatorId())->get();
        return view('company.realestate.maintenance-requests.form', compact('issues', 'properties', 'maintainers'));
    }

    public function getUnits($id)
    {
        $property     = Property::where('company_id', Auth::user()->creatorId())->where('id', $id)->first();
        if (!$property) {
            return response()->json(['error' => 'Property not found'], 404);
        }

        $units = $property->units()->select('id', 'name')->get();

        return response()->json($units);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property' => 'required',
            'unit' => 'required',
            'issue' => 'required',
            'maintainer' => 'required',
            'request_date' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        $companyId              = Auth::user()->creatorId();
        $new                    = new PropertyMaintenanceRequest();
        $new->company_id        = $companyId;
        $new->property_id        = $request->property;
        $new->unit_id            = $request->unit;
        $new->issue_type        = $request->issue;
        $new->maintainer_id        = $request->maintainer;
        $new->request_date        = $request->request_date;
        $new->notes                = $request->notes;
        $new->status            = $request->status;
        $new->save();

            $files                  = $request->file('documents', []);
        foreach ($files ?? [] as $file) {
            $fileId                 = $this->uploadAndSaveFile($file, $companyId, $folder->name ?? null);
            $attachment 		    = new MaintenanceRequestAttachment();
            $attachment->file_id    = $fileId;
            $attachment->request_id = $new->id;
            $attachment->save();
        }

        Session::flash('success_msg', 'New request created.');

        return response()->json([
            'status' => 'success',
            'message' => 'New request created.',
            'redirect' => route('company.realestate.maintaince-requests.index')
        ]);
    }
    public function show() {}
    public function edit() {}
    public function destroy() {}
}
