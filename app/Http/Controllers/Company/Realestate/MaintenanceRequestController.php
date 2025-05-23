<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequestAttachment;
use App\Models\MaintenanceTypes;
use App\Models\MediaFile;
use App\Models\Property;
use App\Models\PropertyMaintenanceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Traits\Media\HandlesMediaFolders;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MaintenanceRequestController extends Controller
{
    use HandlesMediaFolders;
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
        DB::beginTransaction();
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
        $new->property_id       = $request->property;
        $new->unit_id           = $request->unit;
        $new->issue_type        = $request->issue;
        $new->maintainer_id     = $request->maintainer;
        $new->request_date      = $request->request_date;
        $new->notes             = $request->notes;
        $new->status            = $request->status;
        try {
            $new->save();
            $files                  = $this->storeFiles($request->file('documents', []));
            $new->maintenanceRequestAttachments($files);
            Session::flash('success_msg', 'New request created.');
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'New request created.',
                'redirect' => route('company.realestate.maintenance-requests.index')
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            // Return error response if something goes wrong
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function show() {}
    public function edit($id)
    {
        $issues             =  MaintenanceTypes::get();
        $properties         = Property::where('company_id', Auth::user()->creatorId())->get();
        $maintainers        = User::where('type', 'maintainer')->where('parent', Auth::user()->creatorId())->get();
        $maintenance        = PropertyMaintenanceRequest::where('id', $id)->where('company_id', Auth::user()->creatorId())->first() ?? abort(404);
        $property           = Property::where('company_id', Auth::user()->creatorId())->where('id', $maintenance->property_id)->first();
        $units              = $property->units()->select('id', 'name')->get();
        return view('company.realestate.maintenance-requests.form', compact('issues', 'properties', 'maintainers', 'maintenance', 'units'));
    }
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $Mrequest         = PropertyMaintenanceRequest::where('id', $id)->where('company_id', Auth::user()->creatorId())->first() ?? abort(404);

            $storageDisk     = config('filesystems.default');
            // $requestImages   = $Mrequest->maintenanceRequestAttachments;
            // foreach ($requestImages ?? [] as $img) {

            //     if (Storage::disk($storageDisk)->exists($img->file_url)) {
            //         unlink('storage/' . $img->file_url);
            //     }
            //     MediaFile::where('id', $img->id)->delete();
            // }
            $Mrequest->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Successfully Deleted.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    protected function storeFiles($files)
    {
        $filePaths = [];
        $company_id       = Auth::user()->creatorId();

        foreach ($files ?? [] as $index => $file) {

            if (!($file instanceof \Illuminate\Http\UploadedFile) || !$file->isValid()) {
                continue;
            }


            $folderPath = ['uploads', 'company_' . $company_id, 'maintenance-requests'];

            $result = $this->directoryCheckAndStoreFile($file, $company_id, $folderPath,);

            if ($result) {
                $filePaths[$index + 1] = $result->id;
            } else {
                continue;
                // throw new \Exception("Failed to upload file: " . $file->getClientOriginalName());
            }
        }

        return $filePaths;
    }
}
