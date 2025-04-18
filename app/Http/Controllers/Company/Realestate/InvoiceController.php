<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\RealestateInvoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (\Auth::user()->can('manage invoice')) {
            if (\Auth::user()->type == 'tenant') {
              
                $tenant=User::where('type', 'tenant')->where('id', Auth::user()->id)->first();
                // $leasedUnitIds = $tenant->leases->pluck('unit_id');

                // Fetch invoices related to the tenant's leased units
                // $invoices = RealestateInvoice::whereIn('unit_id', $leasedUnitIds)
                //     ->where('invoice_type', 'property_invoice') // Filter by invoice_type 'property_invoice'
                //     ->when($request->searchInput, function ($query, $searchInput) {
                //         $searchId = str_replace('INVOICE-', '', $searchInput);
                //         return $query->where('invoice_id', $searchId);
                //     })
                //     ->when($request->property, function ($query, $property) {
                //         return $query->where('property_id', $property);
                //     })
                //     ->when($request->unit, function ($query, $unit) {
                //         return $query->where('unit_id', $unit);
                //     })
                //     ->orderBy('created_at', 'desc') // Order by created_at in descending order
                //     ->paginate(25);
            } else if (\Auth::user()->type === 'propertyowner') {
                // $invoices = Invoice::whereIn('property_id', getOwnerPropertyIds())
                //     ->where('parent_id', parentId())
                //     ->where('invoice_type', 'property_invoice') // Filter by invoice_type 'property_invoice'
                //     ->when($request->searchInput, function ($query, $searchInput) {
                //         $searchId = str_replace('INVOICE-', '', $searchInput);
                //         return $query->where('invoice_id', $searchId);
                //     })
                //     ->when($request->property, function ($query, $property) {
                //         return $query->where('property_id', $property);
                //     })
                //     ->when($request->unit, function ($query, $unit) {
                //         return $query->where('unit_id', $unit);
                //     })
                //     ->orderBy('created_at', 'desc') // Order by created_at in descending order
                //     ->paginate(25);
            } else {
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
            }

            // $filterProperty = Property::select('id', 'name')->orderBy('name', 'asc')->get();
            // $filterUnit = PropertyUnit::select('id', 'name')->orderBy('name', 'asc')->get();

            return view('company.realestate.invoices.index', compact('invoices'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
            $property = Property::where('company_id', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), '');
            // $types = Type::where('parent_id', parentId())->where('type', 'invoice')->get()->pluck('title', 'id');
    
            //$types->prepend(__('Select Type'), '');
            $types = [
                1 => 'Monthly Rent',
                2 => 'Electricity Bill',
                3 => 'Water Charges',
            ];
            
            // Convert to collection before using prepend
            $types = collect($types)->prepend(__('Select Type'), '');
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
            return view('company.realestate.invoices.form', compact( 'types','property', 'invoiceNumber', 'invoicePeriods'));
       
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
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
