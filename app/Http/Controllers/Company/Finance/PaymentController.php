<?php

namespace App\Http\Controllers\Company\Finance;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\BankTransaction;
use App\Models\Property;
use App\Models\RealestateChequeDetail;
use App\Models\RealestateInvoice;
use App\Models\RealestateLease;
use App\Models\RealestatePayment;
use App\Models\RealestateType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function choosePayment()
    {
        return view('company.finance.realestate.payments.choose');
    }

    public function index(Request $request)
    {
        // Fetch all payments with related invoices, properties, and tenants
        $query = RealestatePayment::with(['invoice', 'invoice.properties', 'invoice.units', 'invoice.properties.leases.tenant'])
            // ->where('payment_for', '!=', 'security_deposit')
            // ->where('type', 'property')
            ->orderBy('created_at', 'DESC') // Correct method name
            ->where('parent_id', creatorId()) // ← This line adds the check
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

        return view('company.finance.realestate.payments.index', compact('payments'));
    }
    public function create($invoice_id)
    {
        $property = Property::where('company_id', Auth::user()->creatorId())->get()->pluck('name', 'id');
        $property->prepend(__('Select Property'), '');
        $invoice     = RealestateInvoice::where('id', $invoice_id)->where('company_id', Auth::user()->creatorId())->first();
        $bankAccounts = BankAccount::where('company_id', Auth::user()->creatorId())->get();
        if ($invoice) {
            return view('company.finance.realestate.payments.create', compact('property', 'invoice', 'bankAccounts'));
        } else {
            return redirect()->back()->with('error', 'Invalid Attempt.');
        }
    }

    public function getChequeDetails($id)
    {
        // Find the invoice by its ID
        $invoice = RealestateInvoice::find($id);

        if ($invoice) {
            $unitId = $invoice->unit_id; // Get the unit_id from the invoice

            // Get the tenant via the lease table by unit_id
            $tenant = RealestateLease::where('unit_id', $unitId)->first();

            if ($tenant) {
                $tenantId = $tenant->tenant_id; // Get the tenant_id from the lease

                // Fetch the cheque details where tenant_id matches and status is not 'paid'
                $cheque = RealestateChequeDetail::where('tenant_id', $tenantId)
                    ->where('status', '!=', 'paid')
                    ->select('id', 'cheque_number', 'amount')
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
            'invoice_id' => 'required|exists:realestate_invoices,id',
        ]);

        // Find the invoice
        $invoice = RealestateInvoice::find($request->invoice_id);

        // Get the due amount
        $dueAmount = $invoice->getInvoiceDueAmount();

        return response()->json(['due_amount' => $dueAmount]);
    }

    public function store(Request $request, $invoice_id)
    {




        $validator = \Validator::make(
            $request->all(),
            [
                'payment_date' => 'required',
                'payment_method' => 'required_if:choose_type,property', // Required only if type is property
                // 'cheque_id' => 'required_if:choose_type,property|required_if:payment_method,cheque', // Req
                // 'cheque_id' => Rule::requiredIf(
                //     fn() =>
                //     $request->input('choose_type') === 'property' &&
                //         $request->input('payment_method') === 'cheque'
                // ),

                'account_id' => 'required',
                'amount' => 'required',
                'notes' => 'nullable',
                'payment_for' => 'required',
                'reference_no' => 'nullable|string|max:255',
            ]
        );


        // if ($validator->fails()) {
        //     $messages = $validator->getMessageBag();
        //     return redirect()->back()->with('error', $messages->first());
        // }


        if ($validator->fails()) {

            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first())->withInput(); // Add withInput() here
        }


        // receipt		


        $invoice = RealestateInvoice::where('id', $invoice_id)->first();
        $payment = new RealestatePayment();
        $payment->invoice_id = $invoice->id;
        $payment->type = $invoice->invoice_type;
        $payment->payment_date = $request->payment_date;
        $payment->unit_id = $invoice->property_id;
        $payment->property_id = $invoice->unit_id;
        $payment->payment_type = $request->payment_method;
        $payment->receipt_number = $this->generateReceiptNumber();
        $payment->transaction_id = substr(uniqid(), -4);
        $payment->notes = $request->notes;
        $payment->bank_account_id = $request->account_id;
        $payment->reference_no = $request->reference_no;
        $payment->amount = $request->amount;
        $payment->parent_id = creatorId();
        $payment->payment_for = $request->payment_for;
        $payment->tenant_id = null;
        $payment->cheque_id =  null;
        $payment->save();

        if ($payment->payment_for == 'full_payment') {
            $invoice->status = 'paid';
            $invoice->save();
        } else {
            $invoice->status = 'partial_paid';
            $invoice->save();
        }

        // Check if payment method is bank transfer and update bank account balance
        if ($request->payment_method === 'bank_transfer' && !empty($request->account_id)) {
            $this->updateBankAccountBalance($request->account_id, $request->amount, 'deposit', $request->invoice_id);
        }
        if ($payment) {
            $this->updateBankAccountBalance($request->account_id, $request->amount, 'deposit', $request->invoice_id);
        }

        return redirect()->route('company.finance.realestate.invoices.index')->with('success', __('Payment successfully created.'));

        // if ($request->choose_type == 'property') {
        //     $invoice = RealestateInvoice::find($request->invoice_id);
        //     if (!$invoice) {
        //         return redirect()->back()->with('error', __('Invoice not found'))->withInput();
        //     }

        //     if ($invoice->getInvoiceDueAmount() <= 0) {
        //         return redirect()->back()->with('error', __('The invoice is already paid. No further payments can be made.'))->withInput();
        //     }

        //     $lease = RealestateLease::where('property_id', $request->property_id)
        //         ->where('unit_id', $request->unit_id)
        //         // ->whereDate('lease_start_date', '<=', now())
        //         ->whereDate('lease_end_date', '>=', now())
        //         ->first();

        //     // Check if lease exists and is active
        //     if (!$lease) {
        //         return redirect()->back()->with('error', __('No active lease found for the selected unit.'))->withInput();
        //     }

        //     // Extract tenant_id from the lease
        //     $tenantId = $lease->tenant_id;


        //     // Check the invoice due amount

        //     $payment = new RealestatePayment();
        //     $payment->invoice_id = $request->invoice_id;
        //     $payment->type = $request->choose_type;
        //     $payment->payment_date = $request->payment_date;
        //     $payment->unit_id = $request->unit_id;
        //     $payment->property_id = $request->property_id;
        //     $payment->payment_type = $request->payment_method;
        //     $payment->receipt_number = $this->generateReceiptNumber();
        //     // $payment->transaction_id = md5(time());
        //     $payment->transaction_id = substr(uniqid(), -4);
        //     $payment->notes = $request->notes;
        //     $payment->bank_account_id = $request->account_id;
        //     $payment->reference_no = $request->reference_no;
        //     $payment->amount = $request->amount;
        //     $payment->parent_id = creatorId();
        //     $payment->payment_for = $request->payment_for;
        //     $payment->tenant_id = $tenantId;
        //     $payment->cheque_id = !empty($request->cheque_id) ? $request->cheque_id : null;
        //     $paymentSave = $payment->save();


        //     // Check if payment method is bank transfer and update bank account balance
        //     //  if ($request->payment_method === 'bank_transfer' && !empty($request->account_id)) {
        //     //     $this->updateBankAccountBalance($request->account_id, $request->amount, 'deposit', $request->invoice_id);
        //     // }
        //     if ($paymentSave) {
        //         $this->updateBankAccountBalance($request->account_id, $request->amount, 'deposit', $request->invoice_id);
        //     }

        //     RealestateChequeDetail::where('id', $payment->cheque_id)
        //         ->update(['status' => 'paid']);  // Change status to 'Paid'
        //     $invoice = RealestateInvoice::find($payment->invoice_id);
        //     if ($invoice->getInvoiceDueAmount() <= 0) {
        //         $status = 'paid';
        //     } else {
        //         $status = 'partial_paid';
        //     }
        //     RealestateInvoice::statusChange($invoice->id, $status);
        //     return redirect()->route('company.finance.realestate.invoice.payments.index')->with('success', __('Payment successfully created.'));
        // } elseif ($request->choose_type == 'other' && $request->invoice_id) {
        //     $invoice = RealestateInvoice::find($request->invoice_id);
        //     if (!$invoice) {
        //         return redirect()->back()->with('error', __('Invoice not found'))->withInput();
        //     }

        //     if ($invoice->getInvoiceDueAmount() <= 0) {
        //         return redirect()->back()->with('error', __('The invoice is already paid. No further payments can be made.'))->withInput();
        //     }

        //     $tenant = User::where('id', $invoice->tenant_id)->first();



        //     // Extract tenant_id from the lease
        //     $tenantId = $tenant->id;


        //     // Check the invoice due amount

        //     $payment = new RealestatePayment();
        //     $payment->invoice_id = $request->invoice_id;
        //     $payment->type = $request->choose_type;
        //     $payment->payment_date = $request->payment_date;
        //     $payment->unit_id = null;
        //     $payment->property_id = null;
        //     $payment->payment_type = null;
        //     $payment->receipt_number = $this->generateReceiptNumber();
        //     // $payment->transaction_id = md5(time());
        //     $payment->transaction_id = substr(uniqid(), -4);
        //     $payment->notes = $request->notes;
        //     $payment->bank_account_id = $request->account_id;
        //     $payment->reference_no = $request->reference_no;
        //     $payment->amount = $request->amount;
        //     $payment->parent_id = creatorId();
        //     $payment->payment_for = $request->payment_for;
        //     $payment->tenant_id = $request->tenant;
        //     $payment->cheque_id =  null;
        //     $paymentSave = $payment->save();


        //     // Check if payment method is bank transfer and update bank account balance
        //     //  if ($request->payment_method === 'bank_transfer' && !empty($request->account_id)) {
        //     //     $this->updateBankAccountBalance($request->account_id, $request->amount, 'deposit', $request->invoice_id);
        //     // }
        //     if ($paymentSave) {
        //         $this->updateBankAccountBalance($request->account_id, $request->amount, 'deposit', $request->invoice_id);
        //     }


        //     $invoice = RealestateInvoice::find($payment->invoice_id);
        //     if ($invoice->getInvoiceDueAmount() <= 0) {
        //         $status = 'paid';
        //     } else {
        //         $status = 'partial_paid';
        //     }
        //     RealestateInvoice::statusChange($invoice->id, $status);
        //     return redirect()->route('company.finance.realestate.other.payments.index')->with('success', __('Payment successfully created.'));
        // } else {

        //     $payment = new RealestatePayment();
        //     $payment->invoice_id = null;
        //     $payment->type = $request->choose_type;
        //     $payment->payment_date = $request->payment_date;
        //     $payment->unit_id = null;
        //     $payment->property_id = null;
        //     $payment->payment_type = null;
        //     $payment->receipt_number = $this->generateReceiptNumber();
        //     // $payment->transaction_id = md5(time());
        //     $payment->transaction_id = substr(uniqid(), -4);
        //     $payment->notes = $request->notes;
        //     $payment->bank_account_id = $request->account_id;
        //     $payment->reference_no = $request->reference_no;
        //     $payment->amount = $request->amount;
        //     $payment->parent_id = creatorId();
        //     $payment->payment_for = $request->payment_for;
        //     $payment->tenant_id = $request->tenant;
        //     $payment->cheque_id =  null;
        //     $paymentSave = $payment->save();


        //     // Check if payment method is bank transfer and update bank account balance
        //     //  if ($request->payment_method === 'bank_transfer' && !empty($request->account_id)) {
        //     //     $this->updateBankAccountBalance($request->account_id, $request->amount, 'deposit', $request->invoice_id);
        //     // }
        //     if ($paymentSave) {
        //         $this->updateBankAccountBalance($request->account_id, $request->amount, 'deposit', $request->invoice_id);
        //     }

        //     return redirect()->route('company.finance.realestate.other.payments.index')->with('success', __('Payment successfully created.'));
        // }
    }


    public function destroy($id)
    {
        if (Auth::user()->can('delete a invoice')) {
            RealestatePayment::where('id', $id)->delete();
            // $this->logActivity(

            //     'Delete a Invoice',

            //     'Payment Id ' . $id,
            //     route('company.finance.realestate.invoices.index'),

            //     'A Invoice deleted successfully',

            //     Auth::user()->creatorId(),

            //     Auth::user()->id

            // );

            return redirect()->route('company.finance.realestate.invoice.payments.index')->with('success', __('Payment successfully deleted.'));
        } else {

            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }
    private function updateBankAccountBalance($bankAccountId, $amount, $transactionType, $reference = null)
    {

        // Fetch the last transaction for this bank account to get the last closing balance
        $lastTransaction = BankTransaction::where('bank_account_id', $bankAccountId)
            ->orderBy('transaction_date', 'desc') // First, order by date
            ->orderBy('id', 'desc') // Then, order by ID to get the latest record
            ->first();

        // If no previous transaction exists, get the initial balance from the bank_accounts table
        if ($lastTransaction) {

            $openingBalance = $lastTransaction->closing_balance;
        } else {

            // Fetch the initial balance from the bank_accounts table
            $bankAccount = BankAccount::find($bankAccountId);
            $openingBalance = $bankAccount ? $bankAccount->opening_balance : 0;
        }

        // Calculate the new closing balance based on the transaction type
        if ($transactionType === 'withdrawal') {
            $closingBalance = $openingBalance - $amount;
        } else if ($transactionType === 'deposit') {
            $closingBalance = $openingBalance + $amount;
        }
        $transactionId = \Str::random(10); // Generate a random transaction ID
        // Create a new bank transaction entry
        BankTransaction::create([
            'bank_account_id' => $bankAccountId,
            'opening_balance' => $openingBalance,
            'transaction_id' => $transactionId,
            'transaction_amount' => $amount,
            'closing_balance' => $closingBalance,
            'transaction_type' => $transactionType,
            'transaction_date' => now(),
            'reference' => $reference, // Adding the reference field here
            'description' => 'Payment update via bank transfer',
        ]);
        $account = BankAccount::find($bankAccountId);

        if ($account) {

            $account->closing_balance = $closingBalance; // Update the correct field
            if (!$account->save()) {
                dd('Failed to update account balance.'); // Check if save is successful
            }
        } else {
            dd('Bank account not found.');
        }
    }

    protected function generateReceiptNumber()
    {
        // Define the prefix
        $prefix = 'REC-';

        // Fetch the latest receipt number from the payments table
        $lastReceipt = RealestatePayment::latest('id')->first(['receipt_number']);

        if ($lastReceipt && $lastReceipt->receipt_number) {
            // Extract the sequence part of the receipt number
            $lastNumber = $lastReceipt->receipt_number;
            $lastSequence = (int) substr($lastNumber, strlen($prefix));
        } else {
            // If no previous receipt number exists, start from 0
            $lastSequence = 0;
        }

        // Increment the sequence number
        $newSequence = $lastSequence + 1;

        // Format the new receipt number
        $newReceiptNumber = $prefix . str_pad($newSequence, 4, '0', STR_PAD_LEFT);

        return $newReceiptNumber;
    }
    public function edit($id)
    {
        $property = Property::where('company_id', Auth::user()->creatorId())->get()->pluck('name', 'id');
        $property->prepend(__('Select Property'), '');
        $payment = RealestatePayment::with('account', 'invoice', 'invoice.properties', 'invoice.units', 'invoice.chequeDetails')->where('id', $id)->first();

        return view('company.finance.realestate.payments.edit', compact('property', 'payment',));
    }
    public function update(Request $request, RealestatePayment $payment)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'choose_type' => 'required',
                'payment_date' => 'required',


                'cheque_id' => Rule::requiredIf(
                    fn() =>
                    $request->input('choose_type') === 'property' &&
                        $request->input('payment_method') === 'cheque'
                ),
                'account_id' => 'required_if:payment_method,bank_transfer',
                'amount' => 'required',
                'payment_for' => 'nullable',
                'reference_no' => 'nullable|string|max:255',


            ]
        );

        if ($validator->fails()) {

            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first())->withInput(); // Add withInput() here
        }

        if ($request->choose_type == 'property') {

            $oldAmount = $payment->amount;
            $payment->invoice_id = $request->invoice_id;

            $payment->payment_date = $request->payment_date;
            $payment->payment_type = $request->payment_type;
            $payment->payment_for = $request->payment_for;

            $payment->reference_no = $request->reference_no;
            $payment->notes = $request->notes;
            $payment->parent_id = creatorId();
            $payment->amount = $request->amount;

            $payment->save();
            $invoice = RealestateInvoice::find($payment->invoice_id);
            if ($invoice->getInvoiceDueAmount() <= 0) {
                $status = 'paid';
            } else {
                $status = 'partial_paid';
            }
            RealestateInvoice::statusChange($invoice->id, $status);
            return redirect()->route('company.finance.realestate.invoice.payments.index')->with('success', __('Payment successfully updated.'));
        } else {
            $payment->invoice_id = null;

            $payment->payment_date = $request->payment_date;

            $payment->payment_for = $request->payment_for;
            $payment->tenant_id = $request->tenant;
            $payment->reference_no = $request->reference_no;
            $payment->notes = $request->notes;
            $payment->parent_id = creatorId();
            $payment->amount = $request->amount;

            $payment->save();
            return redirect()->route('company.finance.realestate.index')->with('success', __('Payment successfully updated.'));
        }
    }

    //other Payments
    public function otherIndex(Request $request)
    {
        // Fetch all payments with related invoices, properties, and tenants
        $payments = RealestatePayment::where('payment_for', '!=', 'security_deposit')->where('type', 'other')->where('parent_id', creatorId()) // ← This line adds the check
            ->orderBy('created_at', 'DESC') // Correct method name
            ->when($request->tenant, function ($query, $tenant) {
                return $query->where('tenant_id', $tenant);
            })
            ->when($request->payment_from_date, function ($query, $fromDate) {
                return $query->whereDate('payment_date', '>=', $fromDate);
            })
            ->when($request->payment_to_date, function ($query, $toDate) {
                return $query->whereDate('payment_date', '<=', $toDate);
            })
            ->paginate(20);


        return view('company.finance.realestate.payments.other.index', compact('payments'));
    }
    public function otherCreate()
    {

        $types = [
            '' => __('Choose Type'),
            'type_invoice' => 'Invoice',
            'type_property' => 'Property',
            'type_other' => 'Other',
        ];
        // $paymentFor = PaymentFor::pluck('title', 'slug');
        $paymentFor = RealestateType::where('parent_id', creatorId())->where('type', 'payment')->get()->pluck('title', 'id');
        $paymentFor->prepend(__('Select Type'), '');

        $tenants = User::where('parent', creatorId())->where('type', 'tenant')
            ->get()
            ->mapWithKeys(function ($tenant) {
                return [
                    $tenant->id => $tenant->name
                ];
            })
            ->toArray();

        return view('company.finance.realestate.payments.other.create', compact('types', 'paymentFor', 'tenants'));
    }
    public function otherEdit($id)
    {
        $payment = RealestatePayment::with('account')->where('id', $id)->first();
        $paymentFor = RealestateType::where('parent_id', creatorId())->where('type', 'payment')->get()->pluck('title', 'id');
        $paymentFor->prepend(__('Select Type'), '');


        $tenants = User::where('parent', creatorId())->where('type', 'tenant')
            ->get()
            ->mapWithKeys(function ($tenant) {
                return [
                    $tenant->id => $tenant->name
                ];
            })
            ->toArray();

        return view('company.finance.realestate.payments.other.edit', compact('payment', 'paymentFor', 'tenants'));
    }
    public function getInvoices($id)
    {
        $invoices = RealestateInvoice::where('tenant_id', $id)
            ->get() // Get all invoices for the unit
            ->filter(function ($invoice) {
                // Only include invoices with a pending amount (i.e., due amount > 0)
                return $invoice->getInvoiceDueAmount() > 0;
            })
            ->pluck('invoice_id', 'id'); // Pluck the necessary columns for response

        return response()->json($invoices);
    }


    public function otherDestroy($id, $invoice_id = null)
    {


        $payment = RealestatePayment::find($id);
        if (!$payment) {
            return redirect()->back()->with('error', __('Payment not found!'));
        }

        if ($invoice_id) {
            $invoice = RealestateInvoice::find($invoice_id);

            // Check if payment was made via cheque and update cheque status
            if (!empty($payment->payment_type) && $payment->payment_type == 'cheque') {
                RealestateChequeDetail::where('id', $payment->cheque_id)
                    ->update(['status' => 'unpaid']);
            }

            // Update bank account balance before deleting the payment
            $this->updateBankAccountBalance($payment->bank_account_id, $payment->amount, 'withdrawal', $invoice_id);


            // **Delete the payment first**
            $payment->delete();

            // **Now update invoice status after deletion**
            if ($invoice->getInvoiceDueAmount() <= 0) {
                $status = 'paid';
            } elseif ($invoice->getInvoiceDueAmount() == $invoice->getInvoiceSubTotalAmount()) {
                $status = 'open';
            } else {
                $status = 'partial_paid';
            }

            RealestateInvoice::statusChange($invoice->id, $status);
        } else {
            // Update bank balance for non-invoice payments
            $this->updateBankAccountBalance($payment->bank_account_id, $payment->amount, 'withdrawal', 'others');

            // **Delete the payment**
            $payment->delete();
        }

        return redirect()->back()->with('success', __('Invoice payment successfully deleted.'));
    }
}
