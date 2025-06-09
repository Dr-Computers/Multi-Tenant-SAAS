<?php

namespace App\Http\Controllers\Owner;

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

            return view('owner.invoices.index', compact('invoices'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
