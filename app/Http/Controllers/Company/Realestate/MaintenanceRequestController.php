<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\InvoiceSetting;
use App\Models\MaintenanceRequestAttachment;
use App\Models\MaintenanceTypes;
use App\Models\MediaFile;
use App\Models\Property;
use App\Models\PropertyMaintenanceRequest;
use App\Models\RealestateInvoice;
use App\Models\RealestateInvoiceItem;
use App\Models\RealestateLease;
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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Svg\Tag\Rect;

class MaintenanceRequestController extends Controller
{
    use HandlesMediaFolders;
    use ActivityLogger;

    public function index()
    {
        if (Auth::user()->can('maintenance requests listing')) {
            $allRequests = PropertyMaintenanceRequest::where('company_id', Auth::user()->creatorId())->get();
            $pendingRequests = PropertyMaintenanceRequest::where('status', 'pending')->where('company_id', Auth::user()->creatorId())->get();
            $InprogressRequests      = PropertyMaintenanceRequest::where('status', 'inprogress')->where('company_id', Auth::user()->creatorId())->get();
            $completedRequests = PropertyMaintenanceRequest::where('status', 'completed')->where('company_id', Auth::user()->creatorId())->get();
            $ungeneratedInvoices = PropertyMaintenanceRequest::where('company_id', Auth::user()->creatorId())->where('status', 'completed')->where('invoice_id', '0')->get();
            $paidInvoices = PropertyMaintenanceRequest::where('company_id', Auth::user()->creatorId())
                ->whereHas('invoice', function ($query) {
                    $query->where('status', 'closed');
                })
                ->get();

            // Due Invoices (status != 'closed' and end_date <= today)
            $dueInvoices = PropertyMaintenanceRequest::where('company_id', Auth::user()->creatorId())
                ->whereHas('invoice', function ($query) {
                    $query
                        // ->where('status', '!=', 'closed')
                        ->whereDate('end_date', '<=', Carbon::today());
                })
                ->get();
            return view('company.realestate.maintenance-requests.index', compact('allRequests', 'InprogressRequests', 'pendingRequests', 'completedRequests', 'ungeneratedInvoices', 'dueInvoices', 'paidInvoices'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
    public function create()
    {
        if (Auth::user()->can('create a maintenance request')) {
            $issues =  MaintenanceTypes::get();
            $properties        = Property::where('company_id', Auth::user()->creatorId())->get();
            $maintainers       = User::where('type', 'maintainer')->where('parent', Auth::user()->creatorId())->get();
            return view('company.realestate.maintenance-requests.form', compact('issues', 'properties', 'maintainers'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
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
        if (Auth::user()->can('create a maintenance request')) {
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

                $new->maintenanceRequestAttachments()->sync($files);

                Session::flash('success_msg', 'New request created.');
                DB::commit();

                $this->logActivity(
                    'Create a Maintenance Request',
                    'Request Id ' . $new->id,
                    route('company.realestate.maintenance-requests.index'),
                    'A Maintenance Request created successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );

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
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function show($id)
    {
        $maintenanceRequest = PropertyMaintenanceRequest::where('company_id', Auth::user()->creatorId())->where('id', $id)->first();

        return view('company.realestate.maintenance-requests.show', compact('maintenanceRequest'));
    }
    public function edit($id)
    {
        if (Auth::user()->can('edit a maintenance request')) {
            $issues             =  MaintenanceTypes::get();
            $properties         = Property::where('company_id', Auth::user()->creatorId())->get();
            $maintainers        = User::where('type', 'maintainer')->where('parent', Auth::user()->creatorId())->get();
            $maintenance        = PropertyMaintenanceRequest::where('id', $id)->where('company_id', Auth::user()->creatorId())->first() ?? abort(404);
            $property           = Property::where('company_id', Auth::user()->creatorId())->where('id', $maintenance->property_id)->first();
            $units              = $property->units()->select('id', 'name')->get();
            return view('company.realestate.maintenance-requests.form', compact('issues', 'properties', 'maintainers', 'maintenance', 'units'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit a maintenance request')) {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'property'      => 'required',
                'unit'          => 'required',
                'issue'         => 'required',
                'maintainer'    => 'required',
                'request_date'  => 'required',
                'status'        => 'required'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $companyId = Auth::user()->creatorId();
            $record = PropertyMaintenanceRequest::where('id', $id)->where('company_id', $companyId)->first() ?? abort(404);

            $record->company_id     = $companyId;
            $record->property_id    = $request->property;
            $record->unit_id        = $request->unit;
            $record->issue_type     = $request->issue;
            $record->maintainer_id  = $request->maintainer;
            $record->request_date   = $request->request_date;
            $record->notes          = $request->notes;
            $record->status         = $request->status;

            if ($request->status == 'completed') {
                $record->fixed_date = now();
            } else {
                $record->fixed_date = NULL;
            }

            $storageDisk = config('filesystems.default');

            // Store new files
            $newFiles = [];
            if ($request->hasFile('documents')) {
                $newFiles = $this->storeFiles($request->file('documents', [])); // returns [1, 2, 3]
            }

            // Sync existing + new
            $existingFiles = $request->existingImage ?? []; // should be array of IDs
            $syncFiles = array_merge($existingFiles, $newFiles);
            $record->maintenanceRequestAttachments()->sync($syncFiles);

            // Delete removed files (compare old vs new)
            $currentFileIds = $record->maintenanceRequestAttachments()->pluck('media_files.id')->toArray();
            $removedFileIds = array_diff($currentFileIds, $syncFiles);

            foreach ($removedFileIds as $fileId) {
                $media = MediaFile::find($fileId);
                if ($media) {
                    $filePath = $media->url ?? null;
                    if ($filePath && Storage::disk($storageDisk)->exists($filePath)) {
                        Storage::disk($storageDisk)->delete($filePath); // safer than unlink()
                    }
                    $media->delete();
                }
            }

            try {
                $record->save();
                DB::commit();

                Session::flash('success_msg', 'Successfully Updated');

                $this->logActivity(
                    'Update a Maintenance Request',
                    'Request Id ' . $record->id,
                    route('company.realestate.maintenance-requests.index'),
                    'A Maintenance Request updated successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );

                return response()->json([
                    'status' => 'success',
                    'message' => 'Successfully Updated',
                    'redirect' => route('company.realestate.maintenance-requests.index')
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 500);
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }




    public function destroy($id)
    {
        if (Auth::user()->can('delete a maintenance request')) {
            try {
                DB::beginTransaction();
                $Mrequest         = PropertyMaintenanceRequest::where('id', $id)->where('company_id', Auth::user()->creatorId())->first() ?? abort(404);

                $storageDisk     = config('filesystems.default');
                $requestImages   = $Mrequest->maintenanceRequestAttachments;
                foreach ($requestImages ?? [] as $img) {

                    if (Storage::disk($storageDisk)->exists($img->file_url)) {
                        unlink('storage/' . $img->file_url);
                    }
                    MediaFile::where('id', $img->id)->delete();
                }
                $Mrequest->maintenanceRequestAttachments()->detach();
                $Mrequest->delete();

                $this->logActivity(
                    'Delete a Maintenance Request',
                    'Request Id ' . $Mrequest->id,
                    route('company.realestate.maintenance-requests.index'),
                    'A Maintenance Request deleted successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );

                DB::commit();
                return redirect()->back()->with('success', 'Successfully Deleted.');
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function invoiceCreate($id)
    {
        if (Auth::user()->can('create a maintenance invoice')) {
            $Mrequest         = PropertyMaintenanceRequest::where('id', $id)->where('company_id', Auth::user()->creatorId())->first() ?? abort(404);
            $invoicePeriods = [
                '1' => '1 Year',
                '2' => '2 Years',
                '3' => '3 Years',
                '4' => '4 Years',
                '5' => '5 Years',
                '6' => '6 Years',
                '7' => '7 Year',
                '8' => '8 Years',
                '9' => '9 Years',
                '10' => '10 Years',
                // Add other periods as needed
            ];
            $invoiceNumber = $this->invoiceNumber();
            return view('company.realestate.maintenance-requests.invoice-form', compact('Mrequest', 'invoiceNumber', 'invoicePeriods'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
    public function invoiceNumber()
    {
        $latest = RealestateInvoice::where('parent_id', Auth::user()->creatorId())->latest()->first();
        if ($latest == null) {
            return 1;
        } else {
            return $latest->invoice_id + 1;
        }
    }

    public function invoiceStore(Request $request, $id)
    {
        if (Auth::user()->can('create a maintenance invoice')) {
            DB::beginTransaction();

            try {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'end_date' => 'required|date',
                        'invoice_id' => 'required|string',
                        'invoice_period' => 'nullable|string',
                        'invoice_period_end_date' => 'nullable|date',
                        'types.*.invoice_type' => 'required|string',
                        'types.*.description' => 'nullable|string',
                        'types.*.amount' => 'required|numeric|min:0',
                        'types.*.grand_amount' => 'required|numeric|min:0',
                        'types.*.vat_amount' => 'nullable|numeric|min:0',
                        'types.*.vat_inclusion' => 'required|in:included,excluded',
                        'discount_amount' => 'nullable|numeric|min:0',
                        'discount_reason' => 'nullable|string',
                        'tax_type' => 'nullable|string'
                    ],
                    ['end_date.required' => 'The end date field is required.']
                );

                if ($validator->fails()) {
                    return redirect()->back()->with('error', $validator->errors()->first())->withInput();
                }

                $Mrequest = PropertyMaintenanceRequest::where('id', $id)
                    ->where('company_id', Auth::user()->creatorId())
                    ->firstOrFail();

                $lease   = RealestateLease::where('unit_id', $Mrequest->unit_id)->first();



                $invoice                    = new RealestateInvoice();
                $invoice->company_id        =  Auth::user()->creatorId();
                $invoice->invoice_id        = $request->invoice_id;
                $invoice->property_id       = $Mrequest->property_id;
                $invoice->unit_id           = $Mrequest->unit_id;
                $invoice->created_in_month  = now()->format('Y-m');
                $invoice->invoice_month     = now()->format('F Y');
                $invoice->end_date          = $request->end_date;
                $invoice->invoice_to        = $lease->tenant_id;
                $invoice->invoice_purpose   = $request->invoice_purpose ?? 'Service';
                $invoice->invoice_type_to   = 'tenant';
                $invoice->invoice_type      = 'maintenance_invoice';
                $invoice->notes             = $request->notes;
                $invoice->invoice_period    = $request->invoice_period ?? NULL;
                $invoice->invoice_period_end_date = $request->invoice_period_end_date ?? NULL;
                $invoice->status            = 'open';
                $invoice->tax_type          = $request->tax_type ?? '';
                $invoice->parent_id         = Auth::user()->creatorId();
                $invoice->discount_reason   = $request->discount_reason ?? '';
                $invoice->discount_amount   = $request->discount_amount ?? 0;
             

                $invoice->save();
                $types                      = $request->types;
                $subTotal = 0;
                $totalTax = 0;
                $grandTotal = 0;

                foreach ($types as $type) {
                    $amount = (float) $type['amount'];
                    $vatAmount = (float) $type['vat_amount'];
                    $grandAmount = (float) $type['grand_amount'];

                    $subTotal += $amount;
                    $totalTax += $vatAmount;
                    $grandTotal += $grandAmount;

                    $invoiceItem = new RealestateInvoiceItem();
                    $invoiceItem->invoice_id = $invoice->id;
                    $invoiceItem->invoice_type = $type['invoice_type'];
                    $invoiceItem->description = $type['description'];
                    $invoiceItem->amount = $amount;
                    $invoiceItem->tax_amount = $vatAmount;
                    $invoiceItem->grand_amount = $grandAmount;
                    $invoiceItem->vat_inclusion = $type['vat_inclusion'];
                    $invoiceItem->save();
                }

                // Apply discount
                $discountAmount = (float) ($request->discount_amount ?? 0);
                $finalGrandTotal = max($grandTotal - $discountAmount, 0); // prevent negative total

                // Update invoice totals
                $invoice->sub_total = $subTotal;
                $invoice->total_tax = $totalTax;
                $invoice->grand_total = $finalGrandTotal;
                $invoice->save();


                $Mrequest->invoice_published = now();
                $Mrequest->invoice_id        = $invoice->id;
                $Mrequest->save();

                $this->logActivity(
                    'Create a Maintenance Request Invoice',
                    'Request Id ' . $Mrequest->id,
                    route('company.realestate.maintenance-requests.index'),
                    'A Maintenance Request Invoice created successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );

                DB::commit();
                return redirect()->back()->with('success', __('Invoice successfully created.'));
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', $e->getMessage())->withInput();
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function  invoiceEdit($id)
    {
        if (Auth::user()->can('create a maintenance invoice')) {
            $Mrequest         = PropertyMaintenanceRequest::where('id', $id)->where('company_id', Auth::user()->creatorId())->first() ?? abort(404);
            $invoicePeriods = [
                '1' => '1 Year',
                '2' => '2 Years',
                '3' => '3 Years',
                '4' => '4 Years',
                '5' => '5 Years',
                '6' => '6 Years',
                '7' => '7 Year',
                '8' => '8 Years',
                '9' => '9 Years',
                '10' => '10 Years',
                // Add other periods as needed
            ];
            $invoiceNumber = $this->invoiceNumber();

            $maintenanceInvoice = RealestateInvoice::where('id', $Mrequest->invoice_id)->first();
            return view('company.realestate.maintenance-requests.invoice-form', compact('Mrequest', 'invoiceNumber', 'invoicePeriods', 'maintenanceInvoice'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
    public function invoiceUpdate($id, Request $request)
    {

        if (Auth::user()->can('create a maintenance invoice')) {
            DB::beginTransaction();
            $Mrequest = PropertyMaintenanceRequest::where('id', $id)
                ->where('company_id', Auth::user()->creatorId())
                ->firstOrFail();

            $invoice = RealestateInvoice::where('company_id', Auth::user()->creatorId())
                ->where('id', $Mrequest->invoice_id)
                ->first();

            try {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'end_date' => 'required|date',
                        'invoice_id' => 'required|string|unique:realestate_invoices,invoice_id,' . $invoice->id,
                        'invoice_period' => 'nullable|string',
                        'invoice_period_end_date' => 'nullable|date',
                        'types.*.invoice_type' => 'required|string',
                        'types.*.description' => 'nullable|string',
                        'types.*.amount' => 'required|numeric|min:0',
                        'types.*.grand_amount' => 'required|numeric|min:0',
                        'types.*.vat_amount' => 'nullable|numeric|min:0',
                        // 'types.*.vat_inclusion' => 'required|in:included,excluded', // ✅ Fixed VAT inclusion validation
                        'discount_amount' => 'nullable|numeric|min:0',
                        'discount_reason' => 'nullable|string',
                        'tax_type' => 'nullable|string'
                    ],
                    ['end_date.required' => 'The end date field is required.']
                );

                if ($validator->fails()) {
                    return redirect()->back()->with('error', $validator->errors()->first())->withInput();
                }


                $invoice->end_date = $request->end_date;
                $invoice->notes = $request->notes;
                $invoice->invoice_period = $request->invoice_period ?? NULL;
                $invoice->invoice_period_end_date = $request->invoice_period_end_date ?? NULL;
                $invoice->status = 'open';
                $invoice->tax_type = $request->tax_type ?? '';
                $invoice->parent_id = Auth::user()->creatorId();
                $invoice->discount_reason = $request->discount_reason ?? '';
                $invoice->discount_amount = $request->discount_amount ?? 0;
                $types = $request->types;

                $subTotal = 0;
                $totalTax = 0;
                $grandTotal = 0;

                $invoice->save();

                RealestateInvoiceItem::where('invoice_id', $invoice->id)->delete();

                foreach ($types ?? [] as $type) {
                    $amount = (float) $type['amount'];
                    $vatAmount = (float) $type['vat_amount'];
                    $grandAmount = (float) $type['grand_amount'];

                    $subTotal += $amount;
                    $totalTax += $vatAmount;
                    $grandTotal += $grandAmount;

                    $invoiceItem = new RealestateInvoiceItem();
                    $invoiceItem->invoice_id = $invoice->id;
                    $invoiceItem->invoice_type = $type['invoice_type'];
                    $invoiceItem->description = $type['description'] ?? null;
                    $invoiceItem->amount = $amount;
                    $invoiceItem->tax_amount = $vatAmount;
                    $invoiceItem->grand_amount = $grandAmount;
                    $invoiceItem->vat_inclusion = isset($type['vat_inclusion']) ? $type['vat_inclusion'] : 'excluded';
                    $invoiceItem->save();
                }

                // Apply discount
                $discountAmount = (float) ($request->discount_amount ?? 0);
                $finalGrandTotal = max($grandTotal - $discountAmount, 0); // prevent negative total

                // Update invoice totals
                $invoice->sub_total = $subTotal;
                $invoice->total_tax = $totalTax;
                $invoice->grand_total = $finalGrandTotal;
                $invoice->save();

                $this->logActivity(
                    'Update a Maintenance Request Invoice',
                    'Request Id ' . $Mrequest->id,
                    route('company.realestate.maintenance-requests.index'),
                    'A Maintenance Request Invoice Updated successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );

                DB::commit();
                return redirect()->back()->with('success', __('Invoice successfully updated.'));
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', $e->getMessage())->withInput();
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function invoiceDownload($id)
    {
        $companyTemplate = InvoiceSetting::where('user_id', Auth::user()->creatorId())->first();
        $invoice         = RealestateInvoice::where('company_id', Auth::user()->creatorId())->first();

        // return view('pdf.invoices.partial.customer-invoice', compact('invoice', 'companyTemplate'));
        $pdf = PDF::loadView('pdf.invoices.partial.customer-invoice', compact('invoice', 'companyTemplate'))->setPaper('a4', 'portrait');

        // Save PDF to temporary location
        $relativePath = 'public/uploads/invoices/invoice-' . $invoice->order_id . '.pdf';
        $absolutePath = storage_path('app/' . $relativePath);

        File::ensureDirectoryExists(dirname($absolutePath));
        $pdf->save($absolutePath);
        $this->logActivity(
            'Maintaince Invoice Downloded',
            'Maintaince Number ' . $id,
            route('admin.realestate.maintenance-requests.index'),
            'Maintaince  Invoice Downloded successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );

        // Return the file as download and delete after response
        return response()->download($absolutePath)->deleteFileAfterSend(true);
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
