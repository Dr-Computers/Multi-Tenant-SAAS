<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceTypes;
use App\Models\Property;
use App\Models\PropertyMaintenanceRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MaintainceRequestController extends Controller
{
    public function index()
    {
        $requests = PropertyMaintenanceRequest::where('company_id', Auth::user()->creatorId())->get();
        return view('company.realestate.maintenance-requests.index', compact('requests'));
    }
    public function create()
    {
        $issues =  MaintenanceTypes::get();
        $properties        = Property::where('company_id', Auth::user()->creatorId())->get();
        $maintainers       = User::where('type', 'maintainer')->where('parent', Auth::user()->creatorId())->get();
        return view('company.realestate.maintenance-requests.form', compact('issues', 'properties', 'maintainers'));
    }

    public function getUnits($id) {
        $property     = Property::where('company_id', Auth::user()->creatorId())->where('id',$id)->first();
        if (!$property) {
            return response()->json(['error' => 'Property not found'], 404);
        }
 
        $units = $property->units()->select('id', 'name')->get();

        return response()->json($units);

    }
    public function show() {}
    public function edit() {}
    public function destroy() {}
}
