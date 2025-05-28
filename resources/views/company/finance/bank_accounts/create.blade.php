@extends('layouts.company')

@section('page-title')
    {{ __('Create Bank Account') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('company.finance.bank-accounts.index') }}">{{ __('Bank Accounts') }}</a>
    </li>
    <li class="breadcrumb-item">{{ __('Create') }}</li>
@endsection

@section('action-btn')
    <div class="d-flex">
        <a href="{{ route('company.finance.bank-accounts.index') }}" class="btn btn-sm btn-primary me-2"
            data-bs-toggle="tooltip" title="{{ __('Back') }}">
            <i class="ti ti-arrow-left"></i>
        </a>
    </div>
@endsection

@section('content')
    @can('create a bank account')
        <div class="row">
            <form action="{{ route('company.finance.bank-accounts.store') }}" method="POST" class="needs-validation"
                novalidate>
                @csrf
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-md fw-bold text-secondary text-sm mb-4">Bank Account Details</h6>
                            <div class="row">

                                <!-- Holder Name -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Holder Name <x-required /></label>
                                        <input type="text" name="holder_name" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Bank Name -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Bank Name <x-required /></label>
                                        <input type="text" name="bank_name" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Account Number -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Account Number <x-required /></label>
                                        <input type="text" name="account_number" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Account Type -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Account Type</label>
                                        <select name="account_type" class="form-control">
                                            <option value="">Select Type</option>
                                            <option value="savings">Savings</option>
                                            <option value="current">Current</option>
                                            <option value="business">Business</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Chart Account ID -->
                                {{-- <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="form-label">Chart Account</label>
                                    <select name="chart_account_id" class="form-control">
                                        @foreach ($chartAccounts as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                                <!-- Opening Balance -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Opening Balance</label>
                                        <input type="number" name="opening_balance" class="form-control" step="0.01">
                                    </div>
                                </div>

                                <!-- Closing Balance -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Closing Balance</label>
                                        <input type="number" name="closing_balance" class="form-control" step="0.01">
                                    </div>
                                </div>

                                <!-- Contact Number -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Contact Number</label>
                                        <input type="text" name="contact_number" class="form-control">
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="phone" class="form-control">
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control">
                                    </div>
                                </div>

                                <!-- Bank Address -->
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">Bank Address</label>
                                        <textarea name="bank_address" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>

                                <!-- Bank Branch -->
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">Bank Branch</label>
                                        <input type="text" name="bank_branch" class="form-control">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer m-4">
                            <button type="submit" class="btn btn-primary">Create Bank Account</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan
@endsection
