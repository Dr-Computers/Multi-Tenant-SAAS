<?php

namespace App\Http\Controllers\Company\Finance;

use App\Models\BankAccount;
use App\Models\BankTransaction;
use App\Models\Expense;
use App\Models\Liability;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\RealestateType;
use Illuminate\Support\Facades\Auth;
use App\Traits\Media\HandlesMediaFolders;

class ExpenseController extends Controller
{

    use HandlesMediaFolders;
    public function index(Request $request)
    {
        // if (\Auth::user()->can('manage expense')) {
        $expenses = Expense::with('account')
            ->where('company_id', Auth::user()->creatorId())
            ->orderBy('created_at', 'DESC')
            ->when($request->bank, function ($query, $bank) {
                return $query->where('bank_account_id', $bank);
            })
            ->when($request->property, function ($query, $property) {
                return $query->where('property_id', $property);
            })
            ->when($request->unit, function ($query, $unit) {
                return $query->where('unit_id', $unit);
            })
            ->when($request->searchInput, function ($query, $searchInput) {
                $searchId = str_replace('#EXP-', '', $searchInput);
                return $query->where('expense_id', $searchId);
            })
            ->paginate(25);
        $filterBank = BankAccount::select('id', 'holder_name')->where('company_id', Auth::user()->creatorId())->orderBy('holder_name', 'asc')->get();
        $filterProperty = Property::select('id', 'name')->where('company_id', Auth::user()->creatorId())->orderBy('name', 'asc')->get();
        $filterUnit = PropertyUnit::select('id', 'name')->where('company_id', Auth::user()->creatorId())->orderBy('name', 'asc')->get();
        return view('company.finance.expense.index', compact('expenses', 'filterBank', 'filterProperty', 'filterUnit'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    public function create()
    {
        // if (\Auth::user()->can('create expense')) {
        $property = Property::where('company_id', Auth::user()->creatorId())->get()->pluck('name', 'id');
        $property->prepend(__('Select Property'), '');
        $types = RealestateType::where('type', 'expense')->get()->pluck('title', 'id');
        // $types->prepend(__('Select Type'), '');
        $liabilities = Liability::all();
        $billNumber = $this->expenseNumber();
        return view('company.finance.expense.create', compact('types', 'property', 'billNumber', 'liabilities'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    public function store(Request $request)
    {


        // if (\Auth::user()->can('create expense')) {
        $validator = \Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'property_id' => 'required_if:expense_type,0',
                'unit_id' => 'required_if:expense_type,0',
                'liability_id' => 'required_if:expense_type,10000',
                'expense_type' => 'required',
                'amount' => 'required',
                'base_amount' => 'required',
                'vat_amount' => 'required',
                'date' => 'required',
                'vat_included' => 'required|in:included,excluded',
                'vendor' => 'nullable',
                'reference_no' => 'nullable',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $company_id = Auth::user()->creatorId();

        if (!empty($request->receipt)) {
            if ($request->hasFile("receipt")) {

                $file = $request->file("receipt");
                if (($file instanceof \Illuminate\Http\UploadedFile) || $file->isValid()) {



                    $folderPath = ['uploads', 'company_' . $company_id, 'expense-receipt'];

                    $result = $this->directoryCheckAndStoreFile($file, $company_id, $folderPath,);

                    $receiptFileId = $result->id;
                }
            }
        }

        $expense = new Expense();
        $expense->title = $request->title;
        $expense->expense_id = $request->expense_id;
        $expense->property_id = !empty($request->property_id) ? $request->property_id : null;
        $expense->liability_id = !empty($request->liability_id) ? $request->liability_id : null;
        $expense->unit_id = !empty($request->unit_id) ? $request->unit_id : null;
        $expense->expense_type = $request->expense_type;
        $expense->bank_account_id = $request->account_id;
        $expense->vendor = $request->vendor ?: null;
        $expense->reference_no = $request->reference_no ?: null;

        $expense->amount = $request->amount;
        $expense->base_amount = $request->base_amount;
        $expense->vat_amount = $request->vat_amount;
        $expense->vat_included = $request->vat_included;


        $expense->date = $request->date;
        $expense->receipt = !empty($request->receipt) ? $receiptFileId : '';
        $expense->notes = $request->notes;
        $expense->company_id = $company_id;
        $expenseSave = $expense->save();

        if ($expenseSave) {

            if ($request->expense_type == '10000') {
                // Fetch the existing liability record
                $existingLiability = Liability::find($request->liability_id);
                if ($existingLiability) {
                    // Update both initial and current liability amounts
                    if ($existingLiability->current_amount > 0) {
                        // Decrease the current amount by the expense amount
                        $existingLiability->current_amount -= $request->amount;
                        $existingLiability->save(); // Save the updated liability
                    } else {
                        return redirect()->back()->with('error', __('The liability has already been fully paid.'));
                    }
                }
            }

            $this->updateBankAccountBalance($request->account_id, $request->amount, 'withdrawal', expensePrefix() . $request->expense_id, 'expense added');
        }
        return redirect()->back()->with('success', __('Expense successfully created.'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
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

            $openingBalance = $lastTransaction->closing_balance ?? 0;
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
            'opening_balance' => $openingBalance ?? 0,
            'transaction_amount' => $amount,
            'transaction_id' => Str::random(10),
            'closing_balance' => $closingBalance ?? 0,
            'transaction_type' => $transactionType,
            'transaction_date' => now(),
            'reference' => $reference, // Adding the reference field here
            'description' =>  $description ?: 'Transaction for expense management', // Default description if none provided,
        ]);

        $account = BankAccount::find($bankAccountId);
        if ($account) {
            $account->closing_balance = $closingBalance;
            $account->save();
        }
    }


    public function show(Expense $expense)
    {
        if (\Auth::user()->can('show expense')) {
            return view('expense.show', compact('expense'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function edit(Expense $expense)
    {
        if (\Auth::user()->can('edit expense')) {
            $property = Property::where('company_id', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), '');
            $types = Type::where('company_id', Auth::user()->creatorId())->where('type', 'expense')->get()->pluck('title', 'id');

            $accounts = BankAccount::pluck('account_name', 'id');
            $liabilities = Liability::all();

            $billNumber = $expense->expense_id;
            return view('expense.edit', compact('liabilities', 'types', 'property', 'billNumber', 'expense', 'accounts'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    // public function update(Request $request, Expense $expense)
    // {
    //   if (\Auth::user()->can('edit expense')) {
    //         $validator = \Validator::make(
    //             $request->all(), [
    //             'title' => 'required',
    //             'property_id' => 'required_if:expense_type,0',
    //             'unit_id' => 'required_if:expense_type,0',
    //             'expense_type' => 'required',
    //             'amount' => 'required',
    //             'date' => 'required',
    //         ]
    //         );
    //         if ($validator->fails()) {
    //             $messages = $validator->getMessageBag();

    //             return redirect()->back()->with('error', $messages->first());
    //         }

    //         if (!empty($request->receipt)) {
    //             $receiptFilenameWithExt = $request->file('receipt')->getClientOriginalName();
    //             $receiptFilename = pathinfo($receiptFilenameWithExt, PATHINFO_FILENAME);
    //             $receiptExtension = $request->file('receipt')->getClientOriginalExtension();
    //             $receiptFileName = $receiptFilename . '_' . time() . '.' . $receiptExtension;
    //             $dir = storage_path('upload/receipt');
    //             if (!file_exists($dir)) {
    //                 mkdir($dir, 0777, true);
    //             }
    //             $request->file('receipt')->storeAs('upload/receipt/', $receiptFileName);
    //             $expense->receipt = !empty($request->receipt) ? $receiptFileName : '';
    //         }
    //         $oldAmount = $expense->amount;
    //         $expense->title = $request->title;
    //         $expense->expense_id = $request->expense_id;
    //         $expense->property_id = !empty($request->property_id) ? $request->property_id : null;
    //         $expense->unit_id = !empty($request->unit_id) ? $request->unit_id : null;
    //         $expense->expense_type = $request->expense_type;
    //         $expense->bank_account_id = $request->account_id;

    //         $expense->amount = $request->amount;
    //         $expense->date = $request->date;
    //         $expense->notes = $request->notes;
    //         $expenseSave=$expense->save();
    //         if($expenseSave)
    //         {

    //             $amountDifference = $oldAmount - $request->amount;
    //              // If the expense amount is reduced, create a deposit transaction
    //              if ($amountDifference > 0) {
    //                 // Add a deposit transaction to reflect the decrease in the expense amount
    //                 $transaction = new BankTransaction();
    //                 $transaction->bank_account_id = $expense->bank_account_id;
    //                 $transaction->transaction_amount = $amountDifference; // Deposit amount
    //                 $transaction->transaction_type = 'deposit';
    //                 $transaction->description = 'Adjustment due to update of expense #' . $expense->id;
    //                 $transaction->reference = $expense->id;
    //                 $transaction->save();

    //                 // Update the account balance
    //                 $account = BankAccount::find($expense->bank_account_id);
    //                 $account->balance += $amountDifference;
    //                 $account->save();
    //             }
    //             else if ($amountDifference < 0) {
    //                 // Add a deposit transaction to reflect the decrease in the expense amount
    //                 $transaction = new BankTransaction();
    //                 $transaction->bank_account_id = $expense->bank_account_id;
    //                 $transaction->transaction_amount = $amountDifference; // Deposit amount
    //                 $transaction->transaction_type = 'withdrawl';
    //                 $transaction->description = 'Adjustment due to update of expense #' . $expense->id;
    //                 $transaction->reference = $expense->id;
    //                 $transaction->save();

    //                 // Update the account balance
    //                 $account = BankAccount::find($expense->account_id);
    //                 $account->balance -= $amountDifference;
    //                 $account->save();
    //             }
    //             else
    //             {

    //             }
    //         }



    //         return redirect()->back()->with('success', __('Expense successfully updated.'));
    //     } else {
    //         return redirect()->back()->with('error', __('Permission Denied!'));
    //     }
    // }
    public function update(Request $request, Expense $expense)
    {

        // if (\Auth::user()->can('edit expense')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'property_id' => 'required_if:expense_type,0',
                    'unit_id' => 'required_if:expense_type,0',
                    'liability_id' => 'required_if:expense_type,10000',
                    'expense_type' => 'required',
                    'amount' => 'required',
                    'date' => 'required',
                    'base_amount' => 'required',
                    'vat_amount' => 'required',
                    'date' => 'required',
                    'vat_included' => 'required|in:included,excluded',
                    'vendor' => 'nullable',
                    'reference_no' => 'nullable',

                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            // Handle receipt upload if it exists
            if (!empty($request->receipt)) {
                $receiptFilenameWithExt = $request->file('receipt')->getClientOriginalName();
                $receiptFilename = pathinfo($receiptFilenameWithExt, PATHINFO_FILENAME);
                $receiptExtension = $request->file('receipt')->getClientOriginalExtension();
                $receiptFileName = $receiptFilename . '_' . time() . '.' . $receiptExtension;
                $dir = storage_path('upload/receipt');
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $request->file('receipt')->storeAs('upload/receipt/', $receiptFileName);
                $expense->receipt = $receiptFileName;
            }
            $oldBankAccountId = $expense->bank_account_id;
            $oldLiabiltyId = $expense->liability_id;
            $oldAmount = $expense->amount;

            $expense->title = $request->title;
            $expense->expense_id = $request->expense_id;
            $expense->property_id = $request->property_id ?: null;
            $expense->liability_id = $request->liability_id ?: null;
            $expense->unit_id = $request->unit_id ?: null;
            $expense->expense_type = $request->expense_type;
            $expense->bank_account_id = $request->account_id;
            $expense->vendor = $request->vendor ?: null;
            $expense->reference_no = $request->reference_no ?: null;
            $expense->amount = $request->amount;
            $expense->base_amount = $request->base_amount;
            $expense->vat_amount = $request->vat_amount;
            $expense->vat_included = $request->vat_included;

            $expense->date = $request->date;
            $expense->notes = $request->notes;
            $expenseSave = $expense->save();

            if ($expenseSave) {

                $oldAmount = floatval($oldAmount);
                $newAmount = floatval($request->amount);
                $amountDifference = $newAmount - $oldAmount;
            }


            if ($request->expense_type == '10000') {

                if ((int)$oldLiabiltyId !== (int)$request->liability_id) {

                    $oldLiability = Liability::find($oldLiabiltyId);

                    $oldLiability->current_amount += $oldAmount;
                    $oldLiability->save(); // Save the updated liability

                    $newLiability = Liability::find($request->liability_id);
                    if ($newLiability) {
                        // Update both initial and current liability amounts
                        if ($newLiability->current_amount > 0) {
                            // Decrease the current amount by the expense amount
                            $newLiability->current_amount -= $request->amount;
                            $newLiability->save(); // Save the updated liability
                        } else {
                            return redirect()->back()->with('error', __('The liability has already been fully paid.'));
                        }
                    }
                } else {

                    $existingLiability = Liability::find($request->liability_id);

                    if ($existingLiability) {

                        // Update both initial and current liability amounts
                        if ($existingLiability->current_amount > 0) {
                            // Decrease the current amount by the expense amount
                            $existingLiability->current_amount -= $amountDifference;
                            $existingLiability->save(); // Save the updated liability
                        } else {
                            return redirect()->back()->with('error', __('The liability has already been fully paid.'));
                        }
                    }
                }
            }
            // Debugging: Check the values after the calculation
            if ((int)$oldBankAccountId !== (int)$expense->bank_account_id) {
                // Reverse the amount from the old bank account
                $this->updateBankAccountBalance($oldBankAccountId, $oldAmount, 'deposit', expensePrefix() . $expense->id, 'Amount Reversed due to expense updation');


                $this->updateBankAccountBalance($expense->bank_account_id, $expense->amount, 'withdrawal', expensePrefix() . $expense->id);
            } else {


                $bankAccountId = $expense->bank_account_id;
                $transactionType = $amountDifference > 0 ? 'withdrawal' : 'deposit';
                $amount = abs($amountDifference);

                $description = 'Adjustment due to update of expense #' . expensePrefix() . $expense->id;

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
                $closingBalance = $transactionType === 'withdrawal'
                    ? $openingBalance - $amount
                    : $openingBalance + $amount;

                // Create a new bank transaction entry
                BankTransaction::create([
                    'bank_account_id' => $bankAccountId,
                    'opening_balance' => $openingBalance,
                    'transaction_amount' => $amount,
                    'transaction_id' => Str::random(10),
                    'closing_balance' => $closingBalance,
                    'transaction_type' => $transactionType,
                    'transaction_date' => now(),
                    'reference' => $expense->id, // Using the expense ID as reference
                    'description' => $description, // Description for the transaction
                ]);

                // Update the account balance in the BankAccount table
                $account = BankAccount::find($bankAccountId);
                if ($account) {
                    $account->closing_balance = $closingBalance;
                    $account->save();
                }
            }




            return redirect()->back()->with('success', __('Expense successfully updated.'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }


   
    public function destroy(Expense $expense)
    {
        // if (\Auth::user()->can('delete expense')) {
            $bankAccountId = $expense->bank_account_id;
            $amount = $expense->amount;
            $transactionType = 'deposit'; // Since we're reversing the expense, it should be treated as a deposit.
            $description = 'Reversal due to deletion of expense #' . $expense->id;

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
                'transaction_id' => Str::random(10),
                'closing_balance' => $closingBalance,
                'transaction_type' => $transactionType,
                'transaction_date' => now(),
                'reference' => $expense->id, // Using the expense ID as reference
                'description' => $description, // Description for the transaction
            ]);

            // Update the account balance in the BankAccount table
            $account = BankAccount::find($bankAccountId);
            if ($account) {
                $account->closing_balance = $closingBalance;
                $account->save();
            }

            // Delete the expense
            $expense->delete();

            return redirect()->back()->with('success', __('Expense successfully deleted.'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }


    public function expenseNumber()
    {
        $latest = Expense::where('company_id', Auth::user()->creatorId())->latest()->first();
        if ($latest == null) {
            return 1;
        } else {
            return $latest->expense_id + 1;
        }
    }
}
