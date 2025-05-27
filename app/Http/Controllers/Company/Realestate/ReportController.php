<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Models\Asset;
use App\Models\BankAccount;
use App\Models\BankTransaction;
use App\Models\ChartOfAccount;
use App\Models\CheckDetail;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Lease;
use App\Models\Liability;
use App\Models\Maintainer;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Tenant;
use App\Models\Type;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\PropertyMaintenanceRequest;
use App\Models\RealestateChequeDetail;
use App\Models\RealestateLease;
use App\Models\RealestateType;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use TCPDF;
use PDF;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

class ReportController extends Controller
{


    public function  maintenancesIndex(Request $request)
    {
        // if (\Auth::user()->can('manage maintenance request')) {
        $company_id       = Auth::user()->creatorId();
        $filterProperty = Property::where('company_id', $company_id)->get();

        $filterTenant = User::where('type', 'tenant')->where('parent', Auth::user()->creatorId())->orderBy('name')->get();


        $maintenanceRequests = PropertyMaintenanceRequest::where('company_id', $company_id);

        if ($request->has('property') && $request->property) {
            $maintenanceRequests = $maintenanceRequests->where('property_id', $request->property);
        }
        $tenantId = $request->tenant;
        if ($request->has('tenant') && $tenantId) {
            $maintenanceRequests = $maintenanceRequests->whereHas('units.leases.tenant', function ($tenantQuery) use ($tenantId) {
                $tenantQuery->where('id', $tenantId);
            });
        }

        if ($request->has('start_month') && $request->start_month && $request->has('end_month') && $request->end_month) {
            $startDate = $request->start_month . '-01';  // Set the start date to the first day of the month
            $endDate = \Carbon\Carbon::parse($request->end_month)->endOfMonth();  // Get the end date as the last day of the month

            $maintenanceRequests = $maintenanceRequests->whereBetween(
                'request_date',
                [
                    $startDate,
                    $endDate
                ]
            );
        } else if ($request->has('start_month') && $request->start_month) {
            $maintenanceRequests = $maintenanceRequests->where('request_date', 'like', $request->start_month . '%');
        } else if ($request->has('end_month') && $request->end_month) {
            $maintenanceRequests = $maintenanceRequests->where('request_date', 'like', $request->end_month);
        }

        $maintenanceRequests = $maintenanceRequests->get();

        $totalRequests = $maintenanceRequests->count();
        return view('company.reports.realestate.maintenance.index', compact('maintenanceRequests', 'totalRequests', 'filterProperty', 'filterTenant'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    public function maintainersIndex(Request $request)
    {
        $company_id       = Auth::user()->creatorId();
        // if (\Auth::user()->can('view maintainer report')) {
        $filterProperty = Property::where('company_id', $company_id)->get();

        $maintainers = User::where('type', 'maintainer')->where('parent', Auth::user()->creatorId())->orderBy('name');

        if ($request->has('property') && $request->property) {
            $maintainers = $maintainers->where('property_id', $request->property);
        }

        if (Auth::user()->type == 'propertyowner') {
            $maintainers = $maintainers->whereIn('property_id', getOwnerPropertyIds());
        }

        if ($request->has('start_month') && $request->start_month && $request->has('end_month') && $request->end_month) {
            $startDate = $request->start_month . '-01';  // Set the start date to the first day of the month
            $endDate = \Carbon\Carbon::parse($request->end_month)->endOfMonth();  // Get the end date as the last day of the month

            $maintainers = $maintainers->whereBetween(
                'created_at',
                [
                    $startDate,
                    $endDate
                ]
            );
        } else if ($request->has('start_month') && $request->start_month) {
            $maintainers = $maintainers->where('created_at', 'like', $request->start_month . '%');
        } else if ($request->has('end_month') && $request->end_month) {
            $maintainers = $maintainers->where('created_at', 'like', $request->end_month);
        }
        // $maintainers = $maintainers->paginate(3);
        $maintainers = $maintainers->get();
        return view('company.reports.realestate.maintainers.index', compact(
            'maintainers',
            'filterProperty'
        ));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    public function invoiceIndex(Request $request)
    {
        // if (\Auth::user()->can('view invoices report')) {
        $company_id       = Auth::user()->creatorId();
        $filterProperty = Property::where('company_id', $company_id)->get();

        // $filterTenant = Tenant::with('user')
        // ->join('users', 'tenants.user_id', '=', 'users.id')
        // ->orderBy('users.first_name')
        // ->orderBy('users.last_name') 
        // ->select('tenants.*', 'users.first_name', 'users.last_name') 
        // ->get();


        $invoicesQuery = Invoice::where('company_id', $company_id)
            ->orderBy('created_at', 'desc');


        if (\Auth::user()->type == 'tenant') {
            $tenant = Tenant::where('user_id', Auth::user()->id)->first();
            if ($tenant) {
                $invoicesQuery->where('property_id', $tenant->property)
                    ->where('unit_id', $tenant->unit);
            }
        } else if (\Auth::user()->type == 'propertyowner') {
            $invoicesQuery->whereIn('property_id', getOwnerPropertyIds());
        }


        if ($request->has('property') && $request->property) {
            $invoicesQuery->where('property_id', $request->property);
        }


        if ($request->has('tenant') && $request->tenant) {
            $invoicesQuery->whereHas('units.leases.tenant', function ($tenantQuery) use ($request) {
                $tenantQuery->where('id', $request->tenant);
            });
        }

        if ($request->has('start_month') && $request->start_month && $request->has('end_month') && $request->end_month) {
            $startDate = $request->start_month . '-01';
            $endDate = Carbon::parse($request->end_month)->endOfMonth();
            $invoicesQuery->whereBetween('end_date', [$startDate, $endDate]);
        } elseif ($request->has('start_month') && $request->start_month) {
            $invoicesQuery->where('end_date', 'like', $request->start_month . '%');
        } elseif ($request->has('end_month') && $request->end_month) {
            $invoicesQuery->where('end_date', 'like', $request->end_month);
        }

        $invoices = $invoicesQuery->paginate(25);
        $totalAmount = $invoices->sum(fn($invoice) => $invoice->getInvoiceSubTotalAmount());
        return view('company.reports.realestate.invoices.index', compact('invoices', 'totalAmount', 'filterProperty'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    public function paymentIndex(Request $request)
    {
        // Check if the user has permission to show invoices
        // if (\Auth::user()->can('view payments report')) {
        $company_id       = Auth::user()->creatorId();
        $filterProperty = Property::where('company_id', $company_id)->get();
        $filterTenant = User::where('type', 'tenant')->where('parent', Auth::user()->creatorId())->orderBy('name')->get();

        // Fetch all payments with related invoices, properties, and tenants
        // $payments = InvoicePayment::with(['invoice', 'invoice.properties', 'invoice.units', 'invoice.properties.tenant.user'])
        //     ->where('payment_for', '!=', 'security_Deposit')
        //     ->orderBy('created_at', 'DESC') // Correct method name
        //     ->get();

        $payments = InvoicePayment::with(['invoice', 'invoice.properties', 'invoice.units', 'invoice.properties.tenant.user'])
            // ->where('payment_for', '!=', 'security_Deposit')
            ->orderBy('created_at', 'DESC'); // Correct method name


        if ($request->has('property') && $request->property) {
            $payments = $payments->where('property_id', $request->property);
        }
        $tenantId = $request->tenant;
        if ($request->has('tenant') && $tenantId) {
            $payments = $payments->where('tenant_id', $request->tenant);
        }
        if ($request->has('start_month') && $request->start_month && $request->has('end_month') && $request->end_month) {
            $startDate = $request->start_month . '-01';  // Set the start date to the first day of the month
            $endDate = \Carbon\Carbon::parse($request->end_month)->endOfMonth();  // Get the end date as the last day of the month

            $payments = $payments->whereBetween(
                'payment_date',
                [
                    $startDate,
                    $endDate
                ]
            );
        } else if ($request->has('start_month') && $request->start_month) {
            $payments = $payments->where('payment_date', 'like', $request->start_month . '%');
        } else if ($request->has('end_month') && $request->end_month) {
            $payments = $payments->where('payment_date', 'like', $request->end_month);
        }


        if (\Auth::user()->type === 'propertyowner') {
            $payments = $payments->whereIn('property_id', getOwnerPropertyIds());
        }
        $payments = $payments->paginate(25);


        // Now you can pass payments to the view
        $totalAmount = $payments->sum('amount'); // Assuming the column for payment amount is named 'amount'
        return view('company.reports.realestate.payments.index', compact(
            'payments',
            'totalAmount',
            'filterProperty',
            'filterTenant'
        ));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }


    public function expenseIndex(Request $request)
    {
        // if (\Auth::user()->can('view expenses report')) {
        // $expenses = Expense::where('company_id', $company_id)->get();
        $company_id       = Auth::user()->creatorId();
        $filterProperty = Property::where('company_id', $company_id)->get();

        $expenses = Expense::where('company_id', $company_id);
        if ($request->has('property') && $request->property) {
            $expenses = $expenses->where('property_id', $request->property);
        }

        if ($request->has('start_month') && $request->start_month && $request->has('end_month') && $request->end_month) {
            $startDate = $request->start_month . '-01';  // Set the start date to the first day of the month
            $endDate = \Carbon\Carbon::parse($request->end_month)->endOfMonth();  // Get the end date as the last day of the month

            $expenses = $expenses->whereBetween(
                'date',
                [
                    $startDate,
                    $endDate
                ]
            );
        } else if ($request->has('start_month') && $request->start_month) {
            $expenses = $expenses->where('date', 'like', $request->start_month . '%');
        } else if ($request->has('end_month') && $request->end_month) {
            $expenses = $expenses->where('date', 'like', $request->end_month);
        }

        if (\Auth::user()->type === 'propertyowner') {
            $expenses = $expenses->whereIn('property_id', getOwnerPropertyIds());
        }

        // $expenses = $expenses->paginate(25);
        $expenses = $expenses->get();

        $totalAmount = $expenses->sum('amount');
        $baseAmount = $expenses->sum('base_amount');
        $vatAmount = $expenses->sum('vat_amount');

        return view('company.reports.realestate.expenses.index', compact('expenses', 'totalAmount', 'vatAmount', 'baseAmount', 'filterProperty'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    public function chequesIndex(Request $request)
    {
        // if (\Auth::user()->can('view cheques report')) {
        $company_id       = Auth::user()->creatorId();
        $filterProperty   = Property::where('company_id', $company_id)->get();

        $filterTenant = User::where('type', 'tenant')->where('parent', Auth::user()->creatorId())->orderBy('name')->get();

        $chequeDetails = RealestateChequeDetail::with('tenant');

        // if ($request->has('property') && $request->property) {
        //     $chequeDetails = $chequeDetails->where('property_id', $request->property);
        // }

        $tenantId = $request->tenant;
        if ($request->has('tenant') && $tenantId) {
            $chequeDetails = $chequeDetails->where('tenant_id', $tenantId);
        }

        if ($request->has('start_month') && $request->start_month && $request->has('end_month') && $request->end_month) {
            $startDate = $request->start_month . '-01';  // Set the start date to the first day of the month
            $endDate = \Carbon\Carbon::parse($request->end_month)->endOfMonth();  // Get the end date as the last day of the month

            $chequeDetails = $chequeDetails->whereBetween(
                'date',
                [
                    $startDate,
                    $endDate
                ]
            );
        } else if ($request->has('start_month') && $request->start_month) {
            $chequeDetails = $chequeDetails->where('check_date', 'like', $request->start_month . '%');
        } else if ($request->has('end_month') && $request->end_month) {
            $chequeDetails = $chequeDetails->where('check_date', 'like', $request->end_month);
        }

        // $chequeDetails = $chequeDetails->paginate(25);
        $chequeDetails = $chequeDetails->get();
        $totalAmount = $chequeDetails->sum('amount');

        return view('company.reports.realestate.cheques.index', compact('chequeDetails', 'totalAmount', 'filterProperty', 'filterTenant'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    public function tenantsIndex(Request $request)
    {
        // if (\Auth::user()->can('view tenants report')) {
        $company_id         = Auth::user()->creatorId();
        $filterTenant       = User::where('type', 'tenant')->where('parent', Auth::user()->creatorId())->orderBy('name')->get();
        $tenants            = User::where('type', 'tenant')->where('parent', Auth::user()->creatorId())->orderBy('name')->get();
        $tenantId           = $request->tenant;
        if ($request->has('tenant') && $tenantId) {
            $tenants = $tenants->where('id', $tenantId);
        }
        return view('company.reports.realestate.tenants.index', compact('tenants', 'filterTenant'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }
    public function propertiesIndex(Request $request)
    {
        // if (\Auth::user()->can('view properties report')) {
        $company_id       = Auth::user()->creatorId();

        $filterProperty = Property::where('company_id', $company_id)->get();
        // Base Query
        $query = Property::with('units')->where('company_id', $company_id);

        if ($request->has('property') && $request->property) {
            $query->where('id', $request->property);
        }


        if (\Auth::user()->type === 'propertyowner') {
            $query->whereIn('id', getOwnerPropertyIds());
        }


        $properties = $query->paginate(10);
        // Process each unit to get the tenant or display "No Tenant"
        foreach ($properties as $property) {
            foreach ($property->units as $unit) {
                // Use the existing tenants() method
                $tenant = $unit->tenants();

                // Check if the tenant exists and the lease is not canceled
                if ($tenant && (!isset($tenant->lease) || $tenant->lease->status !== 'canceled')) {
                    $unit->tenant_name = $tenant->name;
                } else {
                    $unit->tenant_name = 'No Tenant';
                }
            }
        }

        $totalProperties = $properties->total();

        return view('company.reports.realestate.properties.index', compact('properties', 'totalProperties', 'filterProperty'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    public function unitsIndex(Request $request)
    {
        // if (\Auth::user()->can('view units report')) {
        $company_id       = Auth::user()->creatorId();
        $filterProperty = Property::where('company_id', $company_id)->get();
        $filterTenant = User::where('type', 'tenant')->where('parent', Auth::user()->creatorId())->orderBy('name')->get();

        // $units = PropertyUnit::where('company_id', $company_id)->get();
        $units = PropertyUnit::where('company_id', $company_id);
        if ($request->has('property') && $request->property) {
            $units = $units->where('property_id', $request->property);
        }
        $tenantId = $request->tenant;
        if ($request->has('tenant') && $tenantId) {
            $units = $units->whereHas('leases.tenant', function ($tenantQuery) use ($tenantId) {
                $tenantQuery->where('id', $tenantId);
            });
        }

        if (\Auth::user()->type === 'propertyowner') {
            $units = $units->whereIn('property_id', getOwnerPropertyIds());
        }

        $units = $units->paginate(100);


        $totalAmount = $units->sum('rent');
        $totalUnits = $units->count();
        $tenants = User::where('type', 'tenant')->where('parent', Auth::user()->creatorId())->orderBy('name')->get();

        return view('company.reports.realestate.units.index', compact(
            'units',
            'totalUnits',
            'tenants',
            'totalAmount',
            'filterProperty',
            'filterTenant'
        ));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    public function rentCollectionSummaryReport(Request $request)
    {
        $company_id       = Auth::user()->creatorId();
        // if (\Auth::user()->can('view rent collection')) {
        $filterProperty = Property::where('company_id', $company_id)->get();
        $filterTenant = User::where('type', 'tenant')->where('parent', Auth::user()->creatorId())->orderBy('name')->get();

        $query = Invoice::where('company_id', $company_id);


        if ($request->has('property') && $request->property) {
            $query->where('property_id', $request->property);
        }

        if (\Auth::user()->type === 'propertyowner') {
            $query->whereIn('property_id', getOwnerPropertyIds());
        }

        $tenantId = $request->tenant;
        if ($request->has('tenant') && $tenantId) {
            $query->whereHas('units.leases.tenant', function ($tenantQuery) use ($tenantId) {
                $tenantQuery->where('id', $tenantId);
            });
        }

        if ($request->has('start_month') && $request->start_month && $request->has('end_month') && $request->end_month) {
            $startDate = $request->start_month . '-01';  // Set the start date to the first day of the month
            $endDate = \Carbon\Carbon::parse($request->end_month)->endOfMonth();  // Get the end date as the last day of the month

            $query->whereBetween(
                'invoice_month',
                [
                    $startDate,
                    $endDate
                ]
            );
        } else if ($request->has('start_month') && $request->start_month) {
            $query->where('invoice_month', 'like', $request->start_month . '%');
        } else if ($request->has('end_month') && $request->end_month) {
            $query->where('invoice_month', 'like', $request->end_month);
        }

        // $invoices = $query->orderBy('created_at', 'desc')->paginate(25);
        $invoices = $query->orderBy('created_at', 'desc')->get();

        $totalAmount = $invoices->sum(function ($invoice) {
            return $invoice->getInvoiceSubTotalAmount();
        });

        $paid = $invoices->sum(function ($invoice) {
            return $invoice->getInvoicePaidAmount();
        });

        $balance = $invoices->sum(function ($invoice) {
            return $invoice->getInvoiceDueAmount();
        });

        return view('company.reports.realestate.rent_collection_summary.index', compact('invoices', 'totalAmount', 'filterProperty', 'filterTenant', 'paid', 'balance'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    // public function depositPaymentIndex()
    // {
    //     // Check if the user has permission to show invoices
    //     if (\Auth::user()->can('view payments report')) {
    //         // Fetch all payments with related invoices, properties, and tenants
    //         $payments = InvoicePayment::with(['invoice', 'invoice.properties', 'invoice.units', 'invoice.properties.tenant.user'])
    //         ->where('payment_for','security_Deposit') 
    //             ->orderBy('created_at', 'DESC') // Correct method name
    //             ->get();

    //         // Now you can pass payments to the view
    //         $totalAmount = $payments->sum('amount'); // Assuming the column for payment amount is named 'amount'
    //         return view('company.reports.realestate.deposit.index', compact('payments', 'totalAmount'));
    //     } else {
    //         return redirect()->back()->with('error', __('Permission Denied!'));
    //     }
    // }
    public function depositPaymentIndex(Request $request)
    {
        // Check if the user has permission to view payments report
        // if (\Auth::user()->can('view payments report')) {
        // Start building the query
        $company_id       = Auth::user()->creatorId();
        $tenants  = User::where('type', 'tenant')->where('parent', Auth::user()->creatorId())->orderBy('name')->get();

        $properties = Property::all();
        $units = PropertyUnit::all();

        $query = InvoicePayment::with([

            'property',
            'unit',
            'unit.tenant.user'
        ])
            // ->where('payment_for', 'security_Deposit')
            ->orderBy('created_at', 'DESC');

        // Apply filters if provided
        if ($request->has('tenant_id') && $request->tenant_id != '') {

            $query->whereHas('unit.tenant', function ($q) use ($request) {

                $q->where('id', $request->tenant_id);
            });
        }

        if ($request->has('property_id') && $request->property_id != '') {

            $query->whereHas('property', function ($q) use ($request) {
                $q->where('id', $request->property_id);
            });
        }

        if ($request->has('unit_id') && $request->unit_id != '') {

            $query->whereHas('unit', function ($q) use ($request) {
                $q->where('id', $request->unit_id);
            });
        }

        if (!empty($request->date_from) && !empty($request->date_to)) {

            $query->whereBetween('payment_date', [$request->date_from, $request->date_to]);
        }

        if (\Auth::user()->type === 'propertyowner') {
            $query->whereHas('property', function ($q) use ($request) {
                $q->whereIn('id', getOwnerPropertyIds());
            });
        }

        // Fetch payments
        // $payments = $query->get();
        // $payments = $query->paginate(25);
        $payments = $query->get();


        // Calculate total amount
        $totalAmount = $payments->sum('amount');

        // Return the view with payments data
        return view('company.reports.realestate.deposit.index', compact('payments', 'totalAmount', 'tenants', 'properties', 'units'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    public function TransactionsIndex(Request $request)
    {
        $company_id       = Auth::user()->creatorId();
        // Check if the user has permission to show invoices
        // if (\Auth::user()->can('view payments report')) {
        // Fetch all payments with related invoices, properties, and tenants
        // $transactions = BankTransaction::with('account')
        //     ->orderBy('created_at', 'DESC') // Correct method name
        //     ->get();
        $transactions = BankTransaction::with('account')
            ->orderBy('created_at', 'DESC') // Correct method name

            ->paginate(25);

        // if (\Auth::user()->type === 'propertyowner') {

        // }



        $openingBalance = $transactions->sum('opening_balance');
        $transactionAmount = $transactions->sum('transaction_amount');
        $closingBalance = $transactions->sum('closing_balance');
        $bankAccounts = BankAccount::all();
        // Now you can pass payments to the view

        return view('company.reports.realestate.transactions.index', compact('transactions', 'bankAccounts', 'closingBalance', 'transactionAmount', 'openingBalance'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }


    public function chequeReturnIndex()
    {
        // Handle fetching and displaying the cheque return report
    }


    // public function propertiesIndex()
    // {
    //     if (\Auth::user()->can('view properties report')) {
    //         $properties = Property::with('units')->where('company_id', $company_id)->where('is_active', 1)->paginate(10);

    //         // Load tenant for each unit
    //         foreach ($properties as $property) {
    //             foreach ($property->units as $unit) {
    //                 $unit->tenant = $unit->tenants(); // Call your existing tenants() method
    //             }
    //         }
    //         $totalProperties = $properties->total();

    //         return view('company.reports.realestate.properties.index', compact('properties', 'totalProperties'));
    //     } else {
    //         return redirect()->back()->with('error', __('Permission Denied!'));
    //     }
    // }


   

    //     public function unitsIndex(Request $request)
    // {
    //     if (\Auth::user()->can('view units report')) {
    //         // Get the tenant filter from the request, if it exists
    //         $tenantId = $request->input('tenant_id');  // Get the tenant filter (if any)

    //         $unitsQuery = PropertyUnit::where('company_id', $company_id); // Start with the base query

    //         // Apply tenant filter if tenant_id is provided
    //         if ($tenantId) {
    //             $unitsQuery->whereHas('tenants', function ($query) use ($tenantId) {
    //                 $query->where('id', $tenantId);  // Filter by tenant ID
    //             });
    //         }

    //         // Get the units based on the query
    //         $units = $unitsQuery->get();

    //         // Get the total units count
    //         $totalUnits = $units->count();

    //         // Get the tenants for the filter dropdown
    //         $tenants = Tenant::with('user')->get();

    //         // Return the view with the filtered data
    //         return view('company.reports.realestate.units.index', compact('units', 'totalUnits', 'tenants'));
    //     } else {
    //         return redirect()->back()->with('error', __('Permission Denied!'));
    //     }
    // }


    public function view($id)
    {
        // Fetch the specific unit
        $unit = PropertyUnit::findOrFail($id);
        $company_id       = Auth::user()->creatorId();

        // Fetch all invoices associated with the unit
        $invoices = Invoice::where('unit_id', $id)
            ->with('payments') // Load related payments
            ->get();
        $currentLease = Lease::where('unit_id', $id)
            ->latest() // Get the most recent lease
            ->first();

        if ($currentLease) {
            // Fetch tenant details based on the tenant_id from the lease
            $tenant = Tenant::findOrFail($currentLease->tenant_id);

            // Fetch check details based on the lease (or any relevant association)
            $checkDetails = CheckDetail::where('tenant_id', $tenant->id)
                ->where('lease_id', $currentLease->id)
                ->latest() // Optionally, get the latest check detail
                ->first();
        } else {
            $tenant = null;
            $checkDetails = null;
        }

        // Fetch all security deposit payments (with or without invoice) and handle them
        $securityDepositPayments = InvoicePayment::with(['unit', 'unit.properties', 'unit.properties.tenant.user'])
            // ->where('payment_for', 'security_deposit')  // Filter for security deposit payments
            ->orderBy('created_at', 'DESC') // Order by created date
            // ->paginate(20); // Paginate the results
            ->get();

        // Calculate pending payments if needed
        foreach ($invoices as $invoice) {
            $totalPaid = $invoice->payments->sum('amount');
            $invoice->pending_amount = $invoice->amount - $totalPaid;
        }

        return view('company.reports.realestate.units.view', compact('unit', 'invoices', 'tenant', 'checkDetails', 'securityDepositPayments'));
    }


    public function profitLossIndex(Request $request)
    {
        // Retrieve input from the filter option and custom dates
        $filterOption = $request->input('filter_option');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $company_id       = Auth::user()->creatorId();

        // Calculate date ranges based on filter option
        if ($filterOption) {
            switch ($filterOption) {
                case 'today':
                    $startDate = $endDate = now()->format('Y-m-d');
                    break;
                case 'last_month':
                    $startDate = now()->startOfMonth()->subMonth()->format('Y-m-d');
                    $endDate = now()->subMonth()->endOfMonth()->format('Y-m-d');
                    break;
                case 'this_year':
                    $startDate = now()->startOfYear()->format('Y-m-d');
                    $endDate = now()->format('Y-m-d');
                    break;
                case 'custom':
                    // Use the custom start and end dates from the form
                    break;
            }
        }


        $invoicePayments = InvoicePayment::select(\DB::raw('SUM(amount) as total_amount'))
            ->get();



        $totalRentPayment = InvoicePayment::sum('amount');




        // Query for total security deposit payments
        $totalSecurityDepositPayment = InvoicePayment::sum('amount');


        // Query for total of all other payments excluding 'rent' and 'security_deposit'
        $totalOtherPayment = InvoicePayment::sum('amount');





        $totalIncomes = $totalRentPayment + $totalSecurityDepositPayment + $totalOtherPayment;

        $totalSecurityDepositPayments = number_format($totalSecurityDepositPayment, 2);
        $totalRentPayments = number_format($totalRentPayment, 2);
        $totalOtherPayments = number_format($totalOtherPayment, 2);

        $totalIncome = number_format($totalIncomes, 2);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Base query to calculate total expenses by type
        $expensesQuery = Expense::query();

        // Apply date filter if provided
        if ($startDate && $endDate) {
            $expensesQuery->whereBetween('date', [$startDate, $endDate]);
        }

        // Liability Expenses (Expense Type 10000)
        $liabilityExpenses = Expense::where('expense_type', 10000)->sum('amount');
        $propertyExpenses = Expense::where('expense_type', 01)->sum('amount');

        // Tax Expenses (Expense Type 10001)
        $taxExpenses = Expense::where('expense_type', 10001)->sum('amount');

        // Operational Expenses (Other Expense Types)
        $operationalExpenses = Expense::whereNotIn('expense_type', [10000, 10001]) // Exclude liability and tax expenses
            ->sum('amount');

        $expenseTypes = RealestateType::where('type', 'expense')
            ->pluck('title', 'id') // Get title and id from the types table
            ->toArray();


        $expenseSummaries = collect($expenseTypes)->map(function ($typeName, $typeId) use ($expensesQuery) {
            $amount = $expensesQuery->where('expense_type', $typeId)->sum('amount');
            return [
                'type_name' => $typeName,
                'total_amount' => $amount,
            ];
        });




        // Total Expenses (Summing all categories)
        $totalExpenses = $liabilityExpenses + $taxExpenses + $operationalExpenses;



        // Calculate net profit/loss


        return view('company.reports.realestate.profit_loss.index', compact('totalIncome', 'invoicePayments', 'totalRentPayments', 'totalSecurityDepositPayments', 'totalOtherPayments', 'liabilityExpenses', 'taxExpenses', 'operationalExpenses', 'propertyExpenses', 'expenseTypes', 'expenseSummaries', 'totalExpenses'));
    }
    public function balanceSheetIndex()
    {
        $company_id       = Auth::user()->creatorId();
        // Fetch all bank accounts with their closing balances
        $bankAccounts = BankAccount::select('id', 'holder_name', 'account_type', 'closing_balance')->get();

        // Base query for assets categorized by type
        // $assetsData = Asset::select('type', \DB::raw('SUM(current_market_value) as total_value'))
        //     ->groupBy('type')
        //     ->get();
        $assetsData = Asset::select(
            'type',
            \DB::raw('SUM(current_market_value - accumulated_depreciation) as total_value')
        )
            ->groupBy('type')
            ->get();

        // Fetch total liability
        // $depositLiability = ChartOfAccount::where('type', 'Liability')->sum('balance');
        $depositLiability  = 0;
        // Base query for liabilities categorized by type
        $liabilitiesData = Liability::select('type', \DB::raw('SUM(amount) as total_value'))
            ->groupBy('type')
            ->get();




        $totalAssetsValue = $bankAccounts->sum('closing_balance') + $assetsData->sum('total_value');
        $totalLiabilitiesValue = $depositLiability + $liabilitiesData->sum('total_value');

        // Calculate equity
        $equityValue = $totalAssetsValue - $totalLiabilitiesValue;



        // Format the numbers for display
        $totalAssets = number_format($totalAssetsValue, 2);
        $totalLiabilities = number_format($totalLiabilitiesValue, 2);
        $equity = number_format($equityValue, 2);



        // dd([
        //     'Total Assets' => $totalAssets,
        //     'Total Liabilities' => $totalLiabilities,
        //     'bank'=>$bankAccounts,
        //     'assetsData'=>$assetsData,
        //     'depositLiability'=>$depositLiability,
        //     'liabilitiesData'=>$liabilitiesData,
        // ]);
        return view('company.reports.realestate.balance_sheet.index', compact('bankAccounts', 'assetsData', 'equity', 'liabilitiesData', 'totalAssets', 'totalLiabilities', 'depositLiability'));
    }


    public function leaseExpiryReport(Request $request)
    {
        $company_id       = Auth::user()->creatorId();
        $filterProperty = Property::where('company_id', $company_id)->get();
        $filterTenant = User::where('type', 'tenant')->where('parent', Auth::user()->creatorId())->orderBy('name')->get();

        $query = RealestateLease::with(['tenant', 'unitLease.properties'])
            ->where('lease_end_date', '>=', now()) // Lease still active
            ->orderBy('lease_end_date', 'asc');

        if ($request->has('range')) {
            $range = $request->input('range');
            $query->where('lease_end_date', '<=', now()->addDays($range)); // Filter by range
        }
        if ($request->has('property') && $request->property) {
            $query->where('property_id', $request->property);
        }

        $tenantId = $request->tenant;
        if ($request->has('tenant') && $tenantId) {
            $query->where('tenant_id', $request->tenant);
        }
        if ($request->has('start_month') && $request->start_month && $request->has('end_month') && $request->end_month) {
            $startDate = $request->start_month . '-01';  // Set the start date to the first day of the month
            $endDate = \Carbon\Carbon::parse($request->end_month)->endOfMonth();  // Get the end date as the last day of the month

            $query->whereBetween(
                'lease_end_date',
                [
                    $startDate,
                    $endDate
                ]
            );
        } else if ($request->has('start_month') && $request->start_month) {
            $query->where('lease_end_date', 'like', $request->start_month . '%');
        } else if ($request->has('end_month') && $request->end_month) {
            $query->where('lease_end_date', 'like', $request->end_month);
        }
        if (\Auth::user()->type === 'propertyowner') {
            $query->whereIn('property_id', getOwnerPropertyIds());
        }
        $leases = $query->get();

        return view('company.reports.realestate.lease_expiry.index', compact(
            'leases',
            'filterProperty',
            'filterTenant'
        ));
    }

    public function fireandsafetyExpiryReport(Request $request)
    {
        $company_id       = Auth::user()->creatorId();
        $filterProperty = Property::where('company_id', $company_id)->get();
        $filterTenant = User::where('type', 'tenant')->where('parent', Auth::user()->creatorId())->orderBy('name')->get();

        $results = Property::where('fire_safty_end_date', '>=', now())->orderBy('fire_safty_end_date', 'asc')->get();

        if ($request->has('property') && $request->property) {
            $results = $results->where('id', $request->property);
        }
        if ($request->has('start_month') && $request->start_month && $request->has('end_month') && $request->end_month) {
            $startDate = $request->start_month . '-01';  // Set the start date to the first day of the month
            $endDate = \Carbon\Carbon::parse($request->end_month)->endOfMonth();  // Get the end date as the last day of the month

            $results = $results->whereBetween(
                'fire_safty_end_date',
                [
                    $startDate,
                    $endDate
                ]
            );
        } else if ($request->has('start_month') && $request->start_month) {
            $results = $results->where('fire_safty_end_date', 'like', $request->start_month . '%');
        } else if ($request->has('end_month') && $request->end_month) {
            $results = $results->where('fire_safty_end_date', 'like', $request->end_month);
        }

        if (\Auth::user()->type === 'propertyowner') {
            $results = $results->whereIn('id', getOwnerPropertyIds());
        }
        return view('company.reports.realestate.fireandsafety_expiry.index', compact(
            'results',
            'filterProperty',
            'filterTenant'
        ));
    }

    public function insuranceExpiryReport(Request $request)
    {
        $company_id       = Auth::user()->creatorId();
        $filterProperty = Property::where('company_id', $company_id)->get();
        $filterTenant = User::where('type', 'tenant')->where('parent', Auth::user()->creatorId())->orderBy('name')->get();

        $results = Property::where('insurance_end_date', '>=', now())->orderBy('insurance_end_date', 'asc')->get();
        if ($request->has('property') && $request->property) {
            $results = $results->where('id', $request->property);
        }
        if ($request->has('start_month') && $request->start_month && $request->has('end_month') && $request->end_month) {
            $startDate = $request->start_month . '-01';  // Set the start date to the first day of the month
            $endDate = \Carbon\Carbon::parse($request->end_month)->endOfMonth();  // Get the end date as the last day of the month

            $results = $results->whereBetween(
                'insurance_end_date',
                [
                    $startDate,
                    $endDate
                ]
            );
        } else if ($request->has('start_month') && $request->start_month) {
            $results = $results->where('insurance_end_date', 'like', $request->start_month . '%');
        } else if ($request->has('end_month') && $request->end_month) {
            $results = $results->where('insurance_end_date', 'like', $request->end_month);
        }

        if (\Auth::user()->type === 'propertyowner') {
            $results = $results->whereIn('id', getOwnerPropertyIds());
        }
        return view('company.reports.realestate.insurance_expiry.index', compact(
            'results',
            'filterProperty',
            'filterTenant'
        ));
    }


    public function invoiceOutstandingReport(Request $request)
    {
        $company_id       = Auth::user()->creatorId();
        // if (\Auth::user()->can('view properties report')) {
        $filterProperty = Property::where('company_id', $company_id)->get();
        // Start with the base query
        $query = Property::with('units')
            ->where('company_id', $company_id);

        // Apply filters dynamically
        if ($request->filled('property')) {
            $query->where('id', $request->property);
        }

        if (\Auth::user()->type === 'propertyowner') {
            $query->whereIn('id', getOwnerPropertyIds());
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Execute the query with pagination
        // $properties = $query->paginate(10);
        $properties = $query->get();

        return view('company.reports.realestate.properties.outstanding', compact('properties', 'filterProperty'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    // public function profitLossIndex(Request $request)


    // {
    //     // Retrieve input from the filter option and custom dates
    // $filterOption = $request->input('filter_option');
    // $startDate = $request->input('start_date');
    // $endDate = $request->input('end_date');

    // // Calculate date ranges based on filter option
    // if ($filterOption) {
    //     switch ($filterOption) {
    //         case 'today':
    //             $startDate = $endDate = now()->format('Y-m-d');
    //             break;
    //         case 'last_month':
    //             $startDate = now()->startOfMonth()->subMonth()->format('Y-m-d');
    //             $endDate = now()->subMonth()->endOfMonth()->format('Y-m-d');
    //             break;
    //         case 'this_year':
    //             $startDate = now()->startOfYear()->format('Y-m-d');
    //             $endDate = now()->format('Y-m-d');
    //             break;
    //         case 'custom':
    //             // Use the custom start and end dates from the form
    //             break;
    //     }
    // }

    //     // Base query for rental income data
    //     $rentalIncomeQuery = InvoicePayment::select('properties.name as property_name', \DB::raw('SUM(invoice_payments.amount) as total_income'))
    //         ->join('invoices', 'invoices.id', '=', 'invoice_payments.invoice_id')
    //         ->join('properties', 'properties.id', '=', 'invoices.property_id')
    //         ->where('invoice_payments.payment_for', 'rent');

    //     // Apply date filter if provided
    //     if (!empty($startDate) && !empty($endDate)) {
    //         $rentalIncomeQuery->where('invoice_payments.payment_date', '=', $startDate);

    //     }


    //     $rentalIncomeData = $rentalIncomeQuery
    //         ->groupBy('properties.name')
    //         ->with('invoice.properties') // Eager load properties if necessary
    //         ->get();



    //     // Repeat the process for additional income data
    //     $additionalIncomeQuery = InvoicePayment::select('properties.name as property_name', \DB::raw('SUM(invoice_payments.amount) as total_income'))
    //         ->join('invoices', 'invoices.id', '=', 'invoice_payments.invoice_id')
    //         ->join('properties', 'properties.id', '=', 'invoices.property_id')
    //         ->whereIn('invoice_payments.payment_for', [
    //             'service_charge',
    //             'late_fee',
    //             'application_fee',
    //             'lease_termination_fee',
    //             'pet_fee',
    //             'maintenance_fee',
    //             'parking_fee',
    //             'storage_fee',
    //             'utilities_income',
    //             'fines_for_violations',
    //             'commercial_rent',
    //             'sundry_income',
    //             'referral_fee'
    //         ]);

    //     // Apply date filter if provided
    //     if ($startDate && $endDate) {
    //         $additionalIncomeQuery->whereBetween('invoice_payments.payment_date', [$startDate, $endDate]);
    //     }

    //     $additionalIncomeData = $additionalIncomeQuery
    //         ->groupBy('properties.name')
    //         ->with('invoice.properties') // Eager load properties if necessary
    //         ->get();

    //     // Calculate total expenses with date filter
    //     $totalExpensesQuery = Expense::query();

    //     // Apply date filter if provided
    //     if ($startDate && $endDate) {
    //         $totalExpensesQuery->whereBetween('date', [$startDate, $endDate]);
    //     }

    //     $totalExpenses = $totalExpensesQuery->sum('amount');

    //     // Calculate expenses by type with date filter
    //     $expensesByTypeQuery = Expense::select('expense_type', \DB::raw('SUM(amount) as total_amount'));

    //     // Apply date filter if provided
    //     if ($startDate && $endDate) {
    //         $expensesByTypeQuery->whereBetween('date', [$startDate, $endDate]);
    //     }

    //     $expensesByType = $expensesByTypeQuery->groupBy('expense_type')->get();

    //     // Map expense type IDs to their names
    //     $expenseTypes = Type::pluck('title', 'id')->toArray();

    //     // Prepare an array to display the results
    //     $expensesSummary = $expensesByType->map(function ($expense) use ($expenseTypes) {
    //         return [
    //             'type_name' => $expenseTypes[$expense->expense_type] ?? 'Property', // Get the type name or set to 'Unknown'
    //             'total_amount' => $expense->total_amount,
    //         ];
    //     });

    //     // Calculate net profit/loss
    //     $totalIncome = $rentalIncomeData->sum('total_income') + $additionalIncomeData->sum('total_income');
    //     $netProfitLoss = $totalIncome - $totalExpenses;

    //     return view('company.reports.realestate.profit_loss.index', compact('rentalIncomeData', 'additionalIncomeData', 'totalExpenses', 'expensesSummary', 'totalIncome', 'netProfitLoss', 'startDate', 'endDate'));
    // }
 

    // public function balanceSheetIndex()
    // {
    //     $bankAssets = BankAccount::sum('closing_balance');



    //     // Base query for assets categorized by type
    //     $assetsData = Asset::select('type', \DB::raw('SUM(current_market_value) as total_value'))
    //         ->groupBy('type') // Group by asset type
    //         ->get();


    //         $depositLiability = ChartOfAccount::where('type', 'Liability')->sum('balance');

    //     // Base query for liabilities categorized by type
    //     $liabilitiesData = Liability::select('type', \DB::raw('SUM(amount) as total_value'))
    //         ->groupBy('type') // Group by liability type
    //         ->get();

    //         $totalAssets = $bankAssets + $assetsData->sum('total_value');
    //         $totalLiabilities = $depositLiability + $liabilitiesData->sum('total_value');
    //         dd([
    //             'Total Assets' => $totalAssets,
    //             'Total Liabilities' => $totalLiabilities,
    //         ]);
    //     // Calculate total assets and total liabilities
    //     // $totalAssets = $assetsData->sum('total_value');
    //     // $totalLiabilities = $liabilitiesData->sum('total_value');

    //     // Calculate owner's equity
    //     $equity = $totalAssets - $totalLiabilities;

    //     // Prepare an array to display the results for assets
    //     $assetsSummary = $assetsData->map(function ($asset) {
    //         return [
    //             'name' => $asset->asset_type,
    //             'total_value' => $asset->total_value,
    //         ];
    //     });

    //     // Prepare an array to display the results for liabilities
    //     $liabilitiesSummary = $liabilitiesData->map(function ($liability) {
    //         return [
    //             'name' => $liability->liability_type,
    //             'total_value' => $liability->total_value,
    //         ];
    //     });

    //     return view('company.reports.realestate.balance_sheet.index', compact('assetsSummary', 'liabilitiesSummary', 'totalAssets', 'totalLiabilities', 'equity'));
    // }
  



    // public function balanceSheetIndex(Request $request)
    // {
    //      // Default report date
    //      $reportDate = Carbon::now();

    //      // Handle different date filter options
    //      switch ($request->input('filter_option')) {
    //          case 'end_of_year':
    //              $reportDate = Carbon::now()->endOfYear();
    //              break;
    //          case 'end_of_quarter':
    //              $reportDate = Carbon::now()->endOfQuarter();
    //              break;
    //          case 'end_of_month':
    //              $reportDate = Carbon::now()->endOfMonth();
    //              break;
    //          case 'custom':
    //              $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null;
    //              $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : null;
    //              $reportDate = $endDate ?? Carbon::now();
    //              break;
    //          default:
    //              // Default to the current date if no filter selected
    //              $reportDate = Carbon::now();
    //              break;
    //      }

    //      // Fetch assets, liabilities, and equity data based on the report date
    //      $assets = $this->getAssets($reportDate);
    //      $liabilities = $this->getLiabilities($reportDate);
    //      $equity = $this->getEquity($reportDate);

    //      // Calculate totals
    //      $totalAssets = $assets->sum('amount');
    //      $totalLiabilities = $liabilities->sum('amount');
    //      $totalEquity = $equity->sum('amount');

    //      return view('company.reports.realestate.balance_sheet.index', compact(
    //          'assets', 'liabilities', 'equity', 'totalAssets', 'totalLiabilities', 'totalEquity', 'reportDate'
    //      ));
    //  }

    //  /**
    //   * Get assets data based on the report date.
    //   */
    //  protected function getAssets($date)
    //  {
    //      // Sample data, replace with actual database queries based on your schema
    //      return collect([
    //          ['name' => 'Cash', 'amount' => 10000],
    //          ['name' => 'Accounts Receivable', 'amount' => 5000],
    //          // Add more assets as needed
    //      ]);
    //  }

    //  /**
    //   * Get liabilities data based on the report date.
    //   */
    //  protected function getLiabilities($date)
    //  {
    //      // Sample data, replace with actual database queries based on your schema
    //      return collect([
    //          ['name' => 'Accounts Payable', 'amount' => 3000],
    //          ['name' => 'Loans', 'amount' => 2000],
    //          // Add more liabilities as needed
    //      ]);
    //  }

    //  /**
    //   * Get equity data based on the report date.
    //   */
    //  protected function getEquity($date)
    //  {
    //      // Sample data, replace with actual database queries based on your schema
    //      return collect([
    //          ['name' => 'Owner’s Equity', 'amount' => 7000],
    //          ['name' => 'Retained Earnings', 'amount' => 1500],
    //          // Add more equity as needed
    //      ]);
    //  }


  

    public function downloadPropertyReport()
    {
        $company_id       = Auth::user()->creatorId();
        // if (\Auth::user()->can('view properties report')) {
        $user = \Auth::user();
        $userName = $user->first_name . ' ' . $user->last_name;
        $properties = Property::with('units', 'units.activeLease')
            ->where('company_id', $company_id)
            //  ->whereIn('id', [39, 40, 41]) // Test purpose
            ->get();

        // Process each unit to get the tenant or display "No Tenant"
        foreach ($properties as $property) {
            foreach ($property->units as $unit) {
                // Use the existing tenants() method
                $tenant = $unit->tenants();

                // Check if the tenant exists and the lease is not canceled
                if ($tenant && (!isset($tenant->lease) || $tenant->lease->status !== 'canceled')) {
                    $unit->tenant_name = $tenant->name;
                } else {
                    $unit->tenant_name = 'No Tenant';
                }
            }
        }
        $pdf = Pdf::loadView('reports.properties.pdf.record', compact('properties'))->setPaper('a4', 'landscape'); // Use 'a4' with 'landscape';

        return $pdf->download($userName . '.pdf');
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    public function downloadUnits($id)
    {
        $company_id       = Auth::user()->creatorId();
        // Fetch the specific unit
        $unit = PropertyUnit::findOrFail($id);
        $unitName = 'Unit' . $unit->name;

        // Fetch all invoices associated with the unit
        $invoices = Invoice::where('unit_id', $id)
            ->with('payments') // Load related payments
            ->get();
        $currentLease = Lease::where('unit_id', $id)
            ->latest() // Get the most recent lease
            ->first();

        if ($currentLease) {
            // Fetch tenant details based on the tenant_id from the lease
            $tenant = Tenant::findOrFail($currentLease->tenant_id);

            // Fetch check details based on the lease (or any relevant association)
            $checkDetails = CheckDetail::where('tenant_id', $tenant->id)
                ->where('lease_id', $currentLease->id)
                ->latest() // Optionally, get the latest check detail
                ->first();
        } else {
            $tenant = null;
            $checkDetails = null;
        }

        // Fetch all security deposit payments (with or without invoice) and handle them
        $securityDepositPayments = InvoicePayment::with(['unit', 'unit.properties', 'unit.properties.tenant.user'])
            ->where('payment_for', 'security_deposit')  // Filter for security deposit payments
            ->orderBy('created_at', 'DESC') // Order by created date
            //   ->paginate(20); // Paginate the results
            ->get();

        // Calculate pending payments if needed
        foreach ($invoices as $invoice) {
            $totalPaid = $invoice->payments->sum('amount');
            $invoice->pending_amount = $invoice->amount - $totalPaid;
        }

        $pdf = new TCPDF();
        $pdf->SetPrintHeader(false);
        // Set font (use DejaVu Sans or your custom Arabic font)
        $pdf->SetFont('dejavusans', '', 12); // Use a font that supports Arabic

        // Add a page
        $pdf->AddPage();

        // Arabic content (using Blade variables)
        $html = view('reports.units.pdf.view_pdf', compact('unit', 'invoices', 'tenant', 'checkDetails', 'securityDepositPayments'))->render();
        $pdf->writeHTML($html, true, false, true, false, '');


        return response($pdf->Output($unitName . '.pdf', 'D'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $unitName . '.pdf"');
    }

   

    
}
