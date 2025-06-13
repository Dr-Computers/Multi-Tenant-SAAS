<?php

namespace App\Http\Controllers\Company\Finance;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\BankTransaction;
use App\Models\RealestateInvoice;
use App\Models\RealestatePaymentsPayable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentPayableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        $payments = RealestatePaymentsPayable::when($request->user, function ($query, $user) {
            return $query->where('user_id', $user);
        })
            ->when($request->bank, function ($query, $bank) {
                return $query->where('bank_account_id', $bank);
            })
            ->paginate(25);

        // $filterUser = User::select('id', 'first_name')->orderBy('first_name', 'asc')->get();
        // $filterBank = BankAccount::select('id', 'account_name')->orderBy('account_name', 'asc')->get();

        return view('company.finance.realestate.payables.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $bankaccount = BankAccount::get()->pluck('holder_name', 'id');
    
            return view('company.finance.realestate.payables.create', compact('bankaccount'));

    }
    public function store(Request $request)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'date' => 'required|date', // Ensure it's a valid date
                'pay_to' => 'required',
                'user_id' => 'required|exists:users,id',
                'amount' => 'required|numeric|min:0', // Ensure it's a positive number
                'reason_for' => 'required|string|max:255', // String field with max length
                'from' => 'required|exists:bank_accounts,id', // Ensure the bank account exists
                'note' => 'nullable|string|max:255', // Optional field with max length
            ],
            [
                'date.required' => 'The date is required.',
                'user_id.required' => 'The user is required.',
                'amount.required' => 'The amount is required.',
                'amount.min' => 'The amount must be a positive number.',
                'reason_for.required' => 'The reason for the payment is required.',
                'from.required' => 'The bank account is required.',
                'note.max' => 'The note cannot be longer than 255 characters.',
            ]
        );

        if ($validator->fails()) {

            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first())->withInput(); // Add withInput() here
        }
        $payableSave = RealestatePaymentsPayable::create([
            'date' => $request->date,
            'pay_to' => $request->pay_to,
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'for_reason' => $request->reason_for,
            'bank_account_id' => $request->from, //bank_account_id
            'notes' => $request->note,
            'company_id'=> creatorId(),
        ]);

        if ($payableSave) {
            $this->updateBankAccountBalance($request->from, $request->amount, 'withdrawal');
        }

        return redirect()->route('company.finance.realestate.payments.payables.index')->with('success', __(' Payment Payable Created Successfully!'));
    }

    public function destroy(RealestatePaymentsPayable $payable)
    {
        $bankAccountId = $payable->bank_account_id;
        $amount = $payable->amount;
        $transactionType = 'deposit';
        $description = 'Reversal due to deletion of payable #' . $payable->id;

        // Fetch the last transaction for the bank account
        $lastTransaction = BankTransaction::where('bank_account_id', $bankAccountId)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        // Determine the opening balance based on the last transaction
        if ($lastTransaction) {
            $openingBalance = $lastTransaction->closing_balance;
        } else {
            // Fetch the initial balance from the bank account if no previous transaction exists
            $bankAccount = BankAccount::find($bankAccountId);
            $openingBalance = $bankAccount ? $bankAccount->balance : 0;
        }

        // Calculate the new closing balance based on the transaction type
        $closingBalance = $openingBalance + $amount;

        // Create a new bank transaction entry for the reversal
        BankTransaction::create([
            'bank_account_id' => $bankAccountId,
            'opening_balance' => $openingBalance,
            'transaction_amount' => $amount,
            'transaction_id' => \Str::random(10),
            'closing_balance' => $closingBalance,
            'transaction_type' => $transactionType,
            'transaction_date' => now(),
            'reference' => $payable->id, // Using the expense ID as reference
            'description' => $description, // Description for the transaction
        ]);

        // Update the account balance in the BankAccount table
        $account = BankAccount::find($bankAccountId);
        if ($account) {
            $account->closing_balance = $closingBalance;
            $account->save();
        }

        // Delete the expense
        $payable->delete();

        return redirect()->back()->with('success', __('Payment Payables successfully deleted.'));
    }




    private function updateBankAccountBalance($bankAccountId, $amount, $transactionType, $reference = null, $description = null)
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
            $openingBalance = $bankAccount ? $bankAccount->balance : 0;
        }

        // Calculate the new closing balance based on the transaction type
        if ($transactionType === 'withdrawal') {
            $closingBalance = $openingBalance - $amount;
        } else if ($transactionType === 'deposit') {

            $closingBalance = $openingBalance + $amount;
        }


        // Create a new bank transaction entry
        BankTransaction::create([
            'bank_account_id' => $bankAccountId,
            'opening_balance' => $openingBalance,
            'transaction_amount' => $amount,
            'transaction_id' => \Str::random(10),
            'closing_balance' => $closingBalance,
            'transaction_type' => $transactionType,
            'transaction_date' => now(),
            'reference' => $reference, // Adding the reference field here
            'description' =>  $description ?: 'Transaction for payments payable', // Default description if none provided,
        ]);

        $account = BankAccount::find($bankAccountId);
        if ($account) {
            $account->closing_balance = $closingBalance;
            $account->save();
        }
    }
    public function fetchUsersByType($type)
    {
        $users = User::where('type', $type)->where('parent',creatorId())->where('is_active', 1)->get()->pluck('name', 'id');
        return response()->json($users);
    }
}
