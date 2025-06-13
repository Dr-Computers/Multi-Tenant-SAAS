<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\RealestateInvoice;
use App\Models\RealestateInvoiceItem;
use App\Models\RealestateLease;
use App\Models\RealestatePaymentsPayable;
use App\Models\RealestateType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ActivityLogger;

class PayableController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use ActivityLogger;

    public function index(Request $request)
    {
        if (Auth::user()->can('invoice listing')) {
            $payments = RealestatePaymentsPayable::where('pay_to', auth()->user()->id)
                // ->where('invoice_type', 'property_invoice') // Filter by invoice_type 'property_invoice'
              
                ->orderBy('created_at', 'desc') // Order by created_at in descending order
                ->paginate(25);


            // $filterProperty = Property::select('id', 'name')->orderBy('name', 'asc')->get();
            // $filterUnit = PropertyUnit::select('id', 'name')->orderBy('name', 'asc')->get();

            return view('owner.receivable.index', compact('payments'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
