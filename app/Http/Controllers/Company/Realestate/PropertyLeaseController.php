<?php

namespace App\Http\Controllers\Company\Realestate;

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
        $leasing_units              = PropertyUnit::where('status', 'leasing')->where('company_id', $company_id)->get();
        $unleashed_units            = PropertyUnit::where('status', 'unleashed')->where('company_id', $company_id)->get();
        $leasing_cancelled_units    = PropertyUnit::where('status', 'canceled')->where('company_id', $company_id)->get();
        $in_hold_units              = PropertyUnit::where('status', 'case')->where('company_id', $company_id)->get();
        return view('company.realestate.properties.leasing.index', compact('leasing_units', 'unleashed_units', 'leasing_cancelled_units', 'in_hold_units'));
    }

    public function create($unit)
    {
        $company_id = Auth::user()->creatorId();
        $tenants     = User::where('type', 'tenant')->where('parent', $company_id)->get();
        $unit       = PropertyUnit::where('id', $unit)->where('company_id', $company_id)->first();
        return view('company.realestate.properties.leasing.form', compact('unit', 'tenants'));
    }

    //new lease request
    public function store(Request $request, $unit)
    {
        $validated = $request->validate([
            'no_of_payments' => 'required|numeric',
            'lease_start_date' => 'required|date',
            'lease_end_date' => 'required|date|after_or_equal:lease_start_date',
            'free_period_start' => 'nullable|date',
            'free_period_end' => 'nullable|date|after_or_equal:free_period_start',
            'check_number.*' => 'required|string',
            'check_date.*' => 'required|date',
            'payee.*' => 'required|string',
            'amount.*' => 'required|numeric',
            'bank_name.*' => 'required|string',
            'bank_account_number.*' => 'nullable|string',
            'routing_number.*' => 'nullable|string',
            'check_image.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'notes.*' => 'nullable|string',
        ]);

        DB::beginTransaction();

        $property           = Property::where('id', $unit->id)->where('company_id', $company_id)->first();

        $company_id         = Auth::user()->creatorId();
        $unit               = PropertyUnit::where('id', $unit->id)->where('company_id', $company_id)->first();

        try {
            // `property`, 
            // `unit`, 
            $lease                      = new RealestateLease();
            $lease->property_id         = $unit->property_id;
            $lease->unit_id             = $unit->id;
            $lease->company_id          = $company_id;
            $lease->tenant_id           = $request->tenant;
            $lease->lease_start_date    = $request->lease_start_date;
            $lease->lease_end_date      = $request->lease_end_date;
            $lease->free_period_start   = $request->free_period_start;
            $lease->free_period_end     = $request->free_period_end;
            $lease->unit_price          = $unit->price;
            $lease->created_by          = Auth::user()->id;
            $lease->updated_by          = NULL;
            $lease->no_of_payments      = $request->no_of_payments;
            $lease->security_deposit    = $unit->deposit_amount;
            $lease->cancellation_date   = $request->lease_end_date;
            $lease->property_number     = $request->property_number;
            $lease->contract_number     = $request->contract_number;
            if ($property->ownerDetail->is_tenants_approval == '0') {
                $lease->status              = 'active';
            } else {
                $lease->status              = 'under review';
            }

            $lease->save();

            //   `cancellation_date`, 
            //   `renewal_status`, 
            //   `previous_lease_id`, 
            //   `renewal_option`, 
            //   `rent_increase`, 
            //   `payment_frequency`, 
            //   `notes`,  
            //   `cheque_payment_fee`, 
            //   `tawtheeq_fees`, 
            //   `new_managemenmt_contract_fees`, 



            // 2. Loop through cheque entries
            foreach ($request->check_number as $index => $checkNumber) {
                $chequeData = [
                    'tenant_id' => $request->tenant,
                    'lease_id'  => $lease->id,
                    'cheque_number' => $checkNumber,
                    'cheque_date' => $request->check_date[$index],
                    'payee' => $request->payee[$index],
                    'amount' => $request->amount[$index],
                    'bank_name' => $request->bank_name[$index],
                    'bank_account_number' => $request->bank_account_number[$index] ?? null,
                    'routing_number' => $request->routing_number[$index] ?? null,
                    'notes' => $request->notes[$index] ?? null,
                    'status' => 0,
                ];



                // Handle image upload if available
                if ($request->hasFile("check_image.$index")) {
                    $file = $request->file("check_image.$index");
                    if (!($file instanceof \Illuminate\Http\UploadedFile) || !$file->isValid()) {
                        continue;
                    }

                    $folderPath = ['uploads', 'company_' . $company_id, 'properties', 'lease', 'cheques'];

                    $result = $this->directoryCheckAndStoreFile($file, $company_id, $folderPath,);

                    $chequeData['cheque_image'] = $result->id;
                }

                RealestateChequeDetail::create($chequeData);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Lease Submitted,Tenant and cheque details saved successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    //lease updating
    public function update(Request $request, PropertyUnit $unit) {}

    //lease status updating
    public function statusUpdate(Request $request, PropertyUnit $unit) {}


    public function destroy(PropertyUnit $unit)
    {
        DB::beginTransaction();

        try {
            $storageDisk = config('filesystems.default');
            $company_id         = Auth::user()->creatorId();

            $lease   = RealestateLease::where('unit_id', $unit->id)->where('company_id', $company_id)->first() ?? abort(404);
            $cheques = RealestateChequeDetail::where('lease_id', $lease->id)->get();
            foreach ($cheques ?? [] as $cheque) {

                $chequeImage = MediaFile::where('id', $cheque->cheque_image)->first();
                if ($chequeImage) {
                    if (Storage::disk($storageDisk)->exists($chequeImage->file_url)) {
                        unlink('storage/' . $chequeImage->file_url);
                    }
                    $chequeImage->delete();
                }

                $cheque->delete();
            }
            $lease->delete();
            $unit->status = 'unleashed';
            $unit->save();

            DB::commit();

            return redirect()->back()->with('success', 'Lease request deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
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
