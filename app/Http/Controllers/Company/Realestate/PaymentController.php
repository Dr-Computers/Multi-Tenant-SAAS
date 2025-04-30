<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\RealestateChequeDetail;
use App\Models\RealestateInvoice;
use App\Models\RealestateLease;
use App\Models\RealestatePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{

    public function choosePayment()
    {
        return view('company.finance.realestate.payments.choose');
    }

    public function index(Request $request)
    {



        // Fetch all payments with related invoices, properties, and tenants
        $query = RealestatePayment::with(['invoice', 'invoice.properties', 'invoice.units', 'invoice.properties.tenant.user'])
            ->where('payment_for', '!=', 'security_deposit')
            ->where('type', 'property')->orderBy('created_at', 'DESC') // Correct method name
            ->when($request->property, function ($query, $property) {
                return $query->where('property_id', $property);
            })
            ->when($request->unit, function ($query, $unit) {
                return $query->where('unit_id', $unit);
            })
            ->when($request->tenant, function ($query, $tenant) {
                return $query->where('tenant_id', $tenant);
            })
            ->when($request->payment_from_date, function ($query, $fromDate) {
                return $query->whereDate('payment_date', '>=', $fromDate);
            })
            ->when($request->payment_to_date, function ($query, $toDate) {
                return $query->whereDate('payment_date', '<=', $toDate);
            });

        $payments = $query->paginate(20);


        // Now you can pass payments to the view
        // if (\Auth::user()->type === 'owner') { 
        //     $propertyIds = Property::where('owner_id', 1)->pluck('id')->toArray();
        //     $payments = InvoicePayment::with(['invoice', 'invoice.properties', 'invoice.units', 'invoice.properties.tenant.user'])
        //             ->whereIn('property_id', getOwnerPropertyIds())
        //             ->where('payment_for', '!=', 'security_deposit')
        //             ->orderBy('created_at', 'DESC') // Correct method name
        //             ->paginate(20);
        // }

        return view('company.finance.realestate.payments.index', compact('payments'));
    }
    public function create()
    {
        $property = Property::where('company_id', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), '');


            return view('company.finance.realestate.payments.create', compact('property'));
      
    }
    public function getChequeDetails($id)
    {
        // Find the invoice by its ID
        $invoice = RealestateInvoice::find($id);

        if ($invoice) {
            $unitId = $invoice->unit_id; // Get the unit_id from the invoice

            // Get the tenant via the lease table by unit_id
            $tenant =RealestateLease::where('unit_id', $unitId)->first();

            if ($tenant) {
                $tenantId = $tenant->tenant_id; // Get the tenant_id from the lease

                // Fetch the cheque details where tenant_id matches and status is not 'paid'
                $cheque = RealestateChequeDetail::where('tenant_id', $tenantId)
                    ->where('status', '!=', 'paid')
                    ->select('id', 'check_number', 'amount')
                    ->get();
            } else {
                // If no tenant is found for this unit_id
                return response()->json(['error' => 'Tenant not found for unit ID: ' . $unitId], 404);
            }
        } else {
            // If no invoice is found
            return response()->json(['error' => 'Invoice not found.'], 404);
        }

        // Return the cheque details as a JSON response
        return response()->json($cheque);
    }
    public function getDueAmount(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
        ]);

        // Find the invoice
        $invoice = RealestateInvoice::find($request->invoice_id);

        // Get the due amount
        $dueAmount = $invoice->getInvoiceDueAmount();

        return response()->json(['due_amount' => $dueAmount]);
    }

}
