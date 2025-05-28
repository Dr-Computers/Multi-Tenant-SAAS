<?php

namespace App\Http\Controllers\Company\Finance;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ActivityLogger;

class BankAccountController extends Controller
{
    use ActivityLogger;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->can('bank account lising')) {
            $bankAccounts = BankAccount::where('company_id', creatorId())->get();
            return view('company.finance.bank_accounts.index', compact('bankAccounts'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->can('create a bank account')) {
            // $chartAccounts = ChartAccount::pluck('name', 'id'); // or however you define chart accounts 
            return view('company.finance.bank_accounts.create');
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (Auth::user()->can('create a bank account')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'holder_name'      => 'required|string|max:255',
                    'bank_name'        => 'required|string|max:255',
                    'account_number'   => 'required|string|max:255|unique:bank_accounts,account_number',
                    'account_type'     => 'nullable|string',
                    'chart_account_id' => 'nullable|exists:chart_accounts,id',
                    'opening_balance'  => 'nullable|numeric',
                    'closing_balance'  => 'nullable|numeric',
                    'contact_number'   => 'nullable|string|max:20',
                    'phone'            => 'nullable|string|max:20',
                    'email'            => 'nullable|email|max:255',
                    'bank_address'     => 'nullable|string',
                    'bank_branch'      => 'nullable|string|max:255',
                ]
            );
            if ($validator->fails()) {

                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first())->withInput(); // Add withInput() here
            }


            $bankAccount = new BankAccount();
            $bankAccount->holder_name      = $request->holder_name;
            $bankAccount->bank_name        = $request->bank_name;
            $bankAccount->account_number   = $request->account_number;
            $bankAccount->account_type     = $request->account_type;
            // $bankAccount->chart_account_id = $request->chart_account_id;
            $bankAccount->opening_balance  = $request->opening_balance ?? 0;
            $bankAccount->closing_balance  = $request->closing_balance ?? 0;
            $bankAccount->contact_number   = $request->contact_number;
            $bankAccount->phone            = $request->phone;
            $bankAccount->email            = $request->email;
            $bankAccount->bank_address     = $request->bank_address;
            $bankAccount->bank_branch      = $request->bank_branch;
            $bankAccount->created_by = Auth::user()->id;
            $bankAccount->company_id = creatorId();

            $bankAccount->save();

            $this->logActivity(
                'Create a Bank Account',
                'Holder Name ' . $bankAccount->holder_name,
                route('company.finance.bank_accounts.index'),
                'A New Bank Account Created successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );


            return redirect()->route('company.finance.bank-accounts.index')->with('success', __('Bank Account successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(BankAccount $bankAccount)
    {

        if (Auth::user()->can('edit a bank account')) {
            return view('company.finance.bank_accounts.edit', compact('bankAccount'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('edit a bank account')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'holder_name'      => 'required|string|max:255',
                    'bank_name'        => 'required|string|max:255',
                    'account_number'   => 'required|string|max:255|unique:bank_accounts,account_number,' . $id,
                    'account_type'     => 'nullable|string',
                    'chart_account_id' => 'nullable|exists:chart_accounts,id',
                    'opening_balance'  => 'nullable|numeric',
                    'closing_balance'  => 'nullable|numeric',
                    'contact_number'   => 'nullable|string|max:20',
                    'phone'            => 'nullable|string|max:20',
                    'email'            => 'nullable|email|max:255',
                    'bank_address'     => 'nullable|string',
                    'bank_branch'      => 'nullable|string|max:255',
                ]
            );
            if ($validator->fails()) {

                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first())->withInput(); // Add withInput() here
            }

            $bankAccount = BankAccount::findOrFail($id);
            $bankAccount->holder_name      = $request->holder_name;
            $bankAccount->bank_name        = $request->bank_name;
            $bankAccount->account_number   = $request->account_number;
            $bankAccount->account_type     = $request->account_type;
            // $bankAccount->chart_account_id = $request->chart_account_id;
            $bankAccount->opening_balance  = $request->opening_balance ?? 0;
            $bankAccount->closing_balance  = $request->closing_balance ?? 0;
            $bankAccount->contact_number   = $request->contact_number;
            $bankAccount->phone            = $request->phone;
            $bankAccount->email            = $request->email;
            $bankAccount->bank_address     = $request->bank_address;
            $bankAccount->bank_branch      = $request->bank_branch;
            $bankAccount->save();


            $this->logActivity(
                'Update a Bank Account',
                'Holder Name ' . $bankAccount->holder_name,
                route('company.finance.bank_accounts.index'),
                'A Bank Account Updated successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );
            return redirect()->route('company.finance.bank-accounts.index')->with('success', __('Bank Account successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (Auth::user()->can('delete a bank account')) {
            $bankAccount = BankAccount::find($id);
            $bankAccount->delete();

            $this->logActivity(
                'Delete a Bank Account',
                'Holder Name ' . $bankAccount->holder_name,
                route('company.finance.bank_accounts.index'),
                'A Bank Account Deleted successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );
            return redirect()->back()->with('success', 'Bank Account successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }
    public function getAccountDetails()
    {
        // Assuming you have a bank account model and you're fetching the name by some identifier

        $accounts = BankAccount::where('company_id', creatorId())
            ->get(['id', 'holder_name', 'account_type', 'account_number']);


        if ($accounts->isNotEmpty()) {
            return response()->json(['accounts' => $accounts], 200);
        } else {
            return response()->json(['error' => 'No bank accounts found'], 404);
        }
    }
}
