<?php

namespace App\Http\Controllers\Company\Finance;

use App\Http\Controllers\Controller;
use App\Models\RealestateInvoice;
use App\Models\RealestateInvoiceItem;
use App\Models\RealestateType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtherInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // if (\Auth::user()->can('manage invoice')) {
        if (\Auth::user()->type == 'tenant') {
            $tenant = Tenant::where('user_id', \Auth::user()->id)->first();
            $tenantId = $tenant->id;

            // Query for invoices with the additional condition where invoice_type is 'other'
            $invoices = RealestateInvoice::where('tenant_id', $tenantId)
                ->where('invoice_type', 'other') // Condition for invoice type being 'other'
                ->when($request->searchInput, function ($query, $searchInput) {
                    $searchId = str_replace('INVOICE-', '', $searchInput);
                    return $query->where('invoice_id', $searchId);
                })
                ->orderBy('created_at', 'desc') // Order by created_at in descending order
                ->paginate(25);
        } else if (\Auth::user()->type === 'propertyowner') {
            return redirect()->back()->with('error', __('Permission Denied!'));
        } else {
            // If the user type is not tenant, the query checks for a parent_id condition
            $invoices = RealestateInvoice::where('parent_id', creatorId())
                ->where('invoice_type', 'other') // Condition for invoice type being 'other'
                ->when($request->searchInput, function ($query, $searchInput) {
                    $searchId = str_replace('INVOICE-', '', $searchInput);
                    return $query->where('invoice_id', $searchId);
                })
                ->orderBy('created_at', 'desc') // Order by created_at in descending order
                ->paginate(25);
        }

        return view('company.finance.realestate.invoices.other.index', compact('invoices'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if (\Auth::user()->can('create invoice')) {

        $tenants = User::where('type', 'tenant')->where('parent', Auth::user()->creatorId())->get()->pluck('name', 'id');
        $tenants->prepend(__('Select Tenant'), '');

        $types = RealestateType::where('parent_id', creatorId())->where('type', 'invoice')->get()->pluck('title', 'id');
        $types->prepend(__('Select Type'), '');
        $invoiceNumber = $this->invoiceNumber();
        return view('company.finance.realestate.invoices.other.create', compact('invoiceNumber', 'tenants', 'types'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // if (\Auth::user()->can('create invoice')) {
        $validator = \Validator::make(
            $request->all(),
            [

                'tenant_id' => 'required',
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
            return redirect()->back()->with('error', $messages->first())->withInput(); // Add withInput() here
        }

        $types = $request->types;
        $existingInvoice = false; // Initialize as false

        $invoice = new RealestateInvoice();
        $invoice->invoice_id = $request->invoice_id;
        $invoice->tenant_id = $request->tenant_id;
        $invoice->invoice_type = 'other';
        $invoice->end_date = $request->end_date;
        $invoice->notes = $request->notes;
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
        return redirect()->route('company.finance.realestate.invoice-other.index')->with('success', __('Invoice successfully created.'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }


    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($id)
    // {
    //     if (\Auth::user()->can('show invoice')) {

    //         $invoice = Invoice::find($id);
    //         $invoiceNumber = $invoice->invoice_id;









    //         $tenant = Tenant::with('user')->find($invoice->tenant_id);


    //         $invoiceEndDate = Carbon::parse($invoice->end_date); // Parse the end date to a Carbon instance


    //         // Calculate the start date by subtracting the period from the end date


    //         $invoicePaymentSettings = invoicePaymentSettings($invoice->parent_id);

    //         return view('invoice.other.show', compact('invoiceNumber', 'invoice',  'tenant', 'invoicePaymentSettings'));
    //     } else {
    //         return redirect()->back()->with('error', __('Permission Denied!'));
    //     }
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function edit($id)
    {
        // if (\Auth::user()->can('edit invoice')) {
        $invoice = RealestateInvoice::findOrFail($id);
        $tenants = User::where('type', 'tenant')->where('parent', Auth::user()->creatorId())->get()->pluck('name', 'id');
        $tenants->prepend(__('Select Tenant'), '');



        $types = RealestateType::where('parent_id', creatorId())->where('type', 'invoice')->get()->pluck('title', 'id');
        $types->prepend(__('Select Type'), '');

        $invoiceNumber = $invoice->invoice_id;

        return view('company.finance.realestate.invoices.other.edit', compact('types', 'tenants', 'invoiceNumber', 'invoice'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }
    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function update(Request $request, $id)
    {
        // if (\Auth::user()->can('edit invoice')) {
        $validator = \Validator::make(
            $request->all(),
            [
                'tenant_id' => 'required',

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
        $invoice = RealestateInvoice::find($id);
        $invoice->tenant_id = $request->tenant_id;
        $invoice->invoice_type = 'other';


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



        return redirect()->route('company.finance.realestate.invoice-other.index')->with('success', __('Invoice successfully updated.'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }


    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function destroy($id)
    {
        // if (\Auth::user()->can('delete invoice')) {
        $invoice = RealestateInvoice::find($id);
        RealestateInvoiceItem::where('invoice_id', $invoice->id)->delete();
        // InvoicePayment::where('invoice_id', $invoice->id)->delete();
        $invoice->delete();
        return redirect()->route('company.finance.realestate.invoice-other.index')->with('success', __('Invoice successfully deleted.'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }
    public function invoiceNumber()
    {
        $latest = RealestateInvoice::where('parent_id', creatorId())
            ->where('invoice_type', 'other') // Ensure it only fetches 'other' type invoices
            ->latest('invoice_id') // Sort by invoice_id in descending order
            ->first();

        return $latest ? $latest->invoice_id + 1 : 1;
    }
}
