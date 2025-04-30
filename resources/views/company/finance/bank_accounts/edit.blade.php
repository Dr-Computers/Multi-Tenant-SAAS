@extends('layouts.company')

@section('page-title')
    {{ __('Bank Accounts') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Bank Accounts') }}</li>
@endsection



@section('content')

<div class="row">
    <form action="{{ route('company.finance.bank-accounts.update', $bankAccount->id) }}" method="POST" class="needs-validation" novalidate>
        @csrf
        @method('PUT')
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-md fw-bold text-secondary text-sm mb-4">Edit Bank Account Details</h6>
                    <div class="row">

                        <!-- Holder Name -->
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Holder Name <x-required /></label>
                                <input type="text" name="holder_name" class="form-control" value="{{ old('holder_name', $bankAccount->holder_name) }}" required>
                            </div>
                        </div>

                        <!-- Bank Name -->
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Bank Name <x-required /></label>
                                <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $bankAccount->bank_name) }}" required>
                            </div>
                        </div>

                        <!-- Account Number -->
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Account Number <x-required /></label>
                                <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $bankAccount->account_number) }}" required>
                            </div>
                        </div>

                        <!-- Account Type -->
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Account Type</label>
                                <select name="account_type" class="form-control">
                                    <option value="">Select Type</option>
                                    <option value="savings" {{ old('account_type', $bankAccount->account_type) == 'savings' ? 'selected' : '' }}>Savings</option>
                                    <option value="current" {{ old('account_type', $bankAccount->account_type) == 'current' ? 'selected' : '' }}>Current</option>
                                    <option value="business" {{ old('account_type', $bankAccount->account_type) == 'business' ? 'selected' : '' }}>Business</option>
                                </select>
                            </div>
                        </div>

                        <!-- Opening Balance -->
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Opening Balance</label>
                                <input type="number" name="opening_balance" class="form-control" step="0.01" value="{{ old('opening_balance', $bankAccount->opening_balance) }}">
                            </div>
                        </div>

                        <!-- Closing Balance -->
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Closing Balance</label>
                                <input type="number" name="closing_balance" class="form-control" step="0.01" value="{{ old('closing_balance', $bankAccount->closing_balance) }}">
                            </div>
                        </div>

                        <!-- Contact Number -->
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Contact Number</label>
                                <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $bankAccount->contact_number) }}">
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $bankAccount->phone) }}">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $bankAccount->email) }}">
                            </div>
                        </div>

                        <!-- Bank Address -->
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="form-label">Bank Address</label>
                                <textarea name="bank_address" class="form-control" rows="2">{{ old('bank_address', $bankAccount->bank_address) }}</textarea>
                            </div>
                        </div>

                        <!-- Bank Branch -->
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="form-label">Bank Branch</label>
                                <input type="text" name="bank_branch" class="form-control" value="{{ old('bank_branch', $bankAccount->bank_branch) }}">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer m-4">
                    <a href="{{ route('company.finance.bank-accounts.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Bank Account</button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection


@push('script-page')
 
    
@endpush
