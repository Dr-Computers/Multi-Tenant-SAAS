<?php

namespace App\Http\Controllers\Company\Finance;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\RealestateInvoice;
use App\Models\RealestateInvoiceItem;
use App\Models\RealestateLease;
use App\Models\RealestateType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ActivityLogger;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use ActivityLogger;

    public function chooseInvoice()
    {
        if (\Auth::user()->can('create a invoice')) {
            return view('company.finance.realestate.invoices.choose');
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }
    public function index(Request $request)
    {
        if (Auth::user()->can('invoice listing')) {
            $invoices = RealestateInvoice::where('parent_id', creatorId())
                ->where('invoice_type', 'property_invoice') // Filter by invoice_type 'property_invoice'
                ->when($request->searchInput, function ($query, $searchInput) {
                    $searchId = str_replace('INVOICE-', '', $searchInput);
                    return $query->where('invoice_id', $searchId);
                })
                ->when($request->property, function ($query, $property) {
                    return $query->where('property_id', $property);
                })
                ->when($request->unit, function ($query, $unit) {
                    return $query->where('unit_id', $unit);
                })
                ->orderBy('created_at', 'desc') // Order by created_at in descending order
                ->paginate(25);


            // $filterProperty = Property::select('id', 'name')->orderBy('name', 'asc')->get();
            // $filterUnit = PropertyUnit::select('id', 'name')->orderBy('name', 'asc')->get();

            return view('company.finance.realestate.invoices.index', compact('invoices'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->can('create a invoice')) {
            $property = Property::where('company_id', creatorId())->get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), '');


            $types = RealestateType::where('type', 'invoice')->get()->pluck('title', 'id');
            $types->prepend(__('Select Type'), '');
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
            return view('company.finance.realestate.invoices.form', compact('types', 'property', 'invoiceNumber', 'invoicePeriods'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
    public function invoiceNumber()
    {
        $latest = RealestateInvoice::where('parent_id', creatorId())->latest()->first();
        if ($latest == null) {
            return 1;
        } else {
            return $latest->invoice_id + 1;
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->can('create a invoice')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'property_id' => 'required',
                    'unit_id' => 'required',
                    'invoice_month' => 'required',
                    'end_date' => 'required',
                    'invoice_period' => 'required',
                    'types.*.amount' => 'required|numeric|min:0', // Amount for each type
                    'types.*.grand_amount' => 'required|numeric|min:0', // Grand amount for each type
                    'types.*.vat_amount' => 'nullable|numeric|min:0', // VAT amount for each type
                    'types.*.vat_inclusion' => 'required|in:included,excluded', // VAT inclusion status

                    // 'tax_type' => 'required|string',

                ],
                ['end_date' => 'The date field is required.',]
            );


            if ($validator->fails()) {

                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first())->withInput();
            }

            $currentMonth = Carbon::parse($request->invoice_month)->format('Y-m');
            $types = $request->types;
            $existingInvoice = false; // Initialize as false
            for ($i = 0; $i < count($types); $i++) {
                $invoice = RealestateInvoice::where('property_id', $request->property_id)
                    ->where('unit_id', $request->unit_id)
                    ->where('created_in_month',  $currentMonth)
                    ->first();
                if ($invoice) {
                    $existingInvoice = RealestateInvoiceItem::where('invoice_id', $invoice->id)->where('invoice_type', $types[$i]['invoice_type'])->exists();
                }
            }

            if ($existingInvoice) {

                return redirect()->back()->with('error', __('Invoice already created for this unit in this month.'));
            } else {



                $lease = RealestateLease::where('property_id', $request->property_id)
                    ->where('unit_id', $request->unit_id)
                    // ->whereDate('lease_start_date', '<=', now())
                    ->whereDate('lease_end_date', '>=', now())
                    ->first();

                // Check if lease exists and is active
                if (!$lease) {
                    return redirect()->back()->with('error', __('No active lease found for the selected unit.'))->withInput();
                }

                // Extract tenant_id from the lease
                $tenantId = $lease->tenant_id;

                // Fetch the last invoice for this unit
                $lastInvoice = RealestateInvoice::where('unit_id', $request->unit_id)
                    ->orderBy('invoice_period_end_date', 'desc')
                    ->first();

                $startDate = $lastInvoice
                    ? Carbon::parse($lastInvoice->invoice_period_end_date)->addDay()
                    : Carbon::parse($lease->lease_start_date);


                // Initialize the invoice period end date
                $invoicePeriodEndDate = null;

                // Handle different invoice periods
                switch ($request->invoice_period) {
                    case '1':
                        $invoicePeriodEndDate = $startDate->copy()->addYear(); // 1 Year
                        break;
                    case '2':
                        $invoicePeriodEndDate = $startDate->copy()->addYears(2); // 2 Years
                        break;
                    case '3':
                        $invoicePeriodEndDate = $startDate->copy()->addYears(3); // 3 Years
                        break;
                    case '4':
                        $invoicePeriodEndDate = $startDate->copy()->addYears(4); // 4 Years
                        break;
                    case '5':
                        $invoicePeriodEndDate = $startDate->copy()->addYears(5); // 5 Years
                        break;
                    default:
                        return redirect()->back()->with('error', __('Invalid invoice period selected.'));
                }


                $invoice = new RealestateInvoice();
                $invoice->invoice_id = $request->invoice_id;
                $invoice->property_id = $request->property_id;
                $invoice->unit_id = $request->unit_id;
                $invoice->created_in_month = $currentMonth;
                $invoice->invoice_month = $request->invoice_month . '-01';
                $invoice->end_date = $request->end_date;
                $invoice->invoice_type = 'property_invoice';
                $invoice->notes = $request->notes;
                $invoice->invoice_period = $request->invoice_period;
                $invoice->invoice_period_end_date = $invoicePeriodEndDate;
                $invoice->status = 'open';
                $invoice->tax_type = $request->tax_type;
                $invoice->parent_id = creatorId();


                $invoice->save();
                $types = $request->types;



                for ($i = 0; $i < count($types); $i++) {
                    // Get values sent from the frontend
                    $vat_inclusion = $types[$i]['vat_inclusion'];
                    $amount = $types[$i]['amount'];
                    $vatAmount = $types[$i]['vat_amount'];
                    $grandAmount = $types[$i]['grand_amount'];
                    $description = $types[$i]['description'];
                    // Get the tax type (included or excluded)




                    // Create and save the invoice item
                    $invoiceItem = new RealestateInvoiceItem();
                    $invoiceItem->invoice_id = $invoice->id;
                    $invoiceItem->invoice_type = $types[$i]['invoice_type'];
                    $invoiceItem->amount = $amount;
                    $invoiceItem->tax_amount = $vatAmount;
                    $invoiceItem->grand_amount = $grandAmount;
                    $invoiceItem->vat_inclusion = $vat_inclusion;
                    $invoiceItem->description = $description;
                    $invoiceItem->save();
                }

                $this->logActivity(
                    'Create a Invoice',
                    'Invoice Id ' . $invoice->invoice_id,
                    route('company.finance.realestate.invoices.index'),
                    'New Staff User Created successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );


                return redirect()->route('company.finance.realestate.invoices.index')->with('success', __('Invoice successfully created.'));
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (\Auth::user()->can('edit a invoice')) {
            $invoice = RealestateInvoice::with('types')->findOrFail($id);
            $property = property::where('company_id', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), '');
            // $types = Type::where('parent_id', parentId())->where('type', 'invoice')->get()->pluck('title', 'id');
            // $types->prepend(__('Select Type'), '');

            $types = [
                1 => 'Monthly Rent',
                2 => 'Electricity Bill',
                3 => 'Water Charges',
            ];

            // Convert to collection before using prepend
            $types = collect($types)->prepend(__('Select Type'), '');

            $invoiceNumber = $invoice->invoice_id;
            // $checkDetails = CheckDetail::where('invoice_id', $invoice->id)->get();
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

            return view('company.finance.realestate.invoices.edit', compact('types', 'property', 'invoiceNumber', 'invoice', 'invoicePeriods'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $invoice = RealestateInvoice::findOrFail($id);

        if (\Auth::user()->can('edit a invoice')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'property_id' => 'required',
                    'unit_id' => 'required',
                    'invoice_month' => 'required',
                    'end_date' => 'required',
                    'types.*.amount' => 'required|numeric|min:0', // Amount for each type
                    'types.*.grand_amount' => 'required|numeric|min:0', // Grand amount for each type
                    'types.*.vat_amount' => 'nullable|numeric|min:0', // VAT amount for each type
                    'types.*.vat_inclusion' => 'required|in:included,excluded', // VAT inclusion status
                ],
                ['end_date' => 'The date field is required.',]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $lease = RealestateLease::where('property_id', $request->property_id)
                ->where('unit_id', $request->unit_id)->whereDate('lease_end_date', '>=', now())
                ->first();

            if (!$lease) {
                return redirect()->back()->with('error', __('No active lease found for the selected tenant, property, and unit.'))->withInput();
            }

            // Fetch the last invoice for this unit
            $lastInvoice = RealestateInvoice::where('unit_id', $request->unit_id)
                ->where('id', '!=', $invoice->id) // Exclude the current invoice
                ->orderBy('invoice_period_end_date', 'desc')
                ->first();

            // Determine the start date
            $startDate = $lastInvoice
                ? Carbon::parse($lastInvoice->invoice_period_end_date)->addDay()
                : Carbon::parse($lease->lease_start_date);


            // Initialize the invoice period end date
            $invoicePeriodEndDate = null;

            // Handle different invoice periods
            switch ($request->invoice_period) {
                case '1':
                    $invoicePeriodEndDate = $startDate->copy()->addYear(); // 1 Year
                    break;
                case '2':
                    $invoicePeriodEndDate = $startDate->copy()->addYears(2); // 2 Years
                    break;
                case '3':
                    $invoicePeriodEndDate = $startDate->copy()->addYears(3); // 3 Years
                    break;
                case '4':
                    $invoicePeriodEndDate = $startDate->copy()->addYears(4); // 4 Years
                    break;
                case '5':
                    $invoicePeriodEndDate = $startDate->copy()->addYears(5); // 5 Years
                    break;
                case '6':
                    $invoicePeriodEndDate = $startDate->copy()->addYears(6); // 6 Years
                    break;
                case '7':
                    $invoicePeriodEndDate = $startDate->copy()->addYears(7); // 7 Years
                    break;
                case '8':
                    $invoicePeriodEndDate = $startDate->copy()->addYears(8); // 8 Years
                    break;
                case '9':
                    $invoicePeriodEndDate = $startDate->copy()->addYears(9); // 9 Years
                    break;
                case '10':
                    $invoicePeriodEndDate = $startDate->copy()->addYears(10); // 10 Years
                    break;
                default:
                    return redirect()->back()->with('error', __('Invalid invoice period selected.'));
            }

            // Update invoice fields
            $invoice->property_id = $request->property_id;
            $invoice->invoice_period = $request->invoice_period;
            $invoice->invoice_period_end_date = $invoicePeriodEndDate;
            $invoice->unit_id = $request->unit_id;
            $invoice->invoice_month = $request->invoice_month . '-01';
            $invoice->end_date = $request->end_date;
            $invoice->notes = $request->notes;
            $invoice->tax_type = $request->tax_type;


            $invoice->save();

            $types = $request->types;
            for ($i = 0; $i < count($types); $i++) {
                // Check if the invoice item exists based on the ID passed in the request
                $invoiceItem = RealestateInvoiceItem::find($types[$i]['id']);

                if ($invoiceItem == null) {
                    // If it doesn't exist, create a new InvoiceItem
                    $invoiceItem = new RealestateInvoiceItem();
                    $invoiceItem->invoice_id = $invoice->id; // Assign the invoice ID
                }

                // Set the values from the request
                $invoiceItem->invoice_type = $types[$i]['invoice_type'];
                $invoiceItem->amount = $types[$i]['amount'];
                $invoiceItem->tax_amount = $types[$i]['tax_amount'];
                $invoiceItem->grand_amount = $types[$i]['grand_amount'];
                $invoiceItem->vat_inclusion = $types[$i]['vat_inclusion'];

                $invoiceItem->description = $types[$i]['description'];



                // Save the invoice item
                $invoiceItem->save();
            }


            $this->logActivity(
                'Updated a Invoice',
                'Invoice Id: ' . $invoice->id,
                route('company.finance.realestate.invoices.index'),
                'A Invoice updated successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );



            return redirect()->route('company.finance.realestate.invoices.index')->with('success', __('Invoice successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (\Auth::user()->can('delete a invoice')) {
            $invoice = RealestateInvoice::find($id);
            RealestateInvoiceItem::where('invoice_id', $invoice->id)->delete();
            // InvoicePayment::where('invoice_id', $invoice->id)->delete();
            $invoice->delete();
            $this->logActivity(
                'Delete a Invoice',
                'Invoice Id ' . $invoice->id,
                route('company.finance.realestate.invoices.index'),
                'A Invoice deleted successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );
            return redirect()->route('company.finance.realestate.invoices.index')->with('success', __('Invoice successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }
    public function invoiceTypeDestroy(Request $request)
    {
        // if (\Auth::user()->can('delete invoice type')) {
        $invoiceType = RealestateInvoiceItem::find($request->id);
        $invoiceType->delete();





        return response()->json([
            'status' => 'success',
            'msg' => __('Property successfully updated.'),
        ]);
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }
    public function getUnitinvoice($id)
    {
        // Find the unit
        $unit = PropertyUnit::find($id);


        // Get the related property
        $property = $unit ? $unit->properties : null;

        // Determine the invoice prefix (use database value or fallback to default)
        $prefix = invoicePrefix();

        $invoicePrefix = $property && $property->invoice_prefix ? $property->invoice_prefix : $prefix;

        // Fetch invoices for the given unit ID and filter by due amount
        $invoices = RealestateInvoice::where('unit_id', $id)
            ->get()
            ->filter(function ($invoice) {
                return $invoice->getInvoiceDueAmount() > 0; // Only include invoices with pending amounts
            })
            ->pluck('invoice_id', 'id'); // Pluck the necessary columns for response
        return response()->json([
            'invoice_prefix' => $invoicePrefix, // Include the invoice prefix
            'invoices' => $invoices, // Invoice data
        ]);
    }
}
