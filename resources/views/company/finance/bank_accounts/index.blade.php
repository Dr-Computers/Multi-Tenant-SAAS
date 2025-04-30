@extends('layouts.company')
@section('page-title')
    {{ __('Bank Accounts') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Bank Accounts') }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        <a href="{{ route('company.finance.bank-accounts.create') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
            title="{{ __('Add Bank Account') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="row">
                @forelse ($bankAccounts as $account)
                    <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                        <div class="card bank-card h-100">
                            <div class="card-header bg-primary text-white py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-white text-truncate">{{ $account->bank_name ?? __('Unnamed Bank') }}</h6>
                                    <span class="badge bg-white text-primary">{{ ucfirst($account->account_type) }}</span>
                                </div>
                            </div>

                            <div class="card-body p-2">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-center px-2 py-1 mb-1">
                                        <i class="ti ti-user text-primary me-2" style="font-size: 1rem;"></i>
                                        <div class="text-truncate"><span class="me-1">Holder:</span>
                                        <span>{{ $account->holder_name }}</span></div>
                                    </li>

                                    <li class="list-group-item d-flex align-items-center px-2 py-1 mb-1">
                                        <i class="ti ti-home text-primary me-2" style="font-size: 1rem;"></i>
                                        <div class="text-truncate"><span class="me-1">Bank:</span>
                                        <span>{{ $account->bank_name }}</span></div>
                                    </li>

                                    <li class="list-group-item d-flex align-items-center px-2 py-1 mb-1">
                                        <i class="ti ti-map text-primary me-2" style="font-size: 1rem;"></i>
                                        <div class="text-truncate"><span class="me-1">Branch:</span>
                                        <span>{{ $account->bank_branch }}</span></div>
                                    </li>

                                    <li class="list-group-item d-flex align-items-center px-2 py-1 mb-1">
                                        <i class="ti ti-credit-card text-primary me-2" style="font-size: 1rem;"></i>
                                        <div class="text-truncate"><span class="me-1">Account No:</span>
                                        <span>{{ $account->account_number }}</span></div>
                                    </li>

                                    <li class="list-group-item d-flex align-items-center px-2 py-1 mb-1">
                                        <i class="ti ti-wallet text-primary me-2" style="font-size: 1rem;"></i>
                                        <div class="text-truncate"><span class="me-1">Open Bal:</span>
                                        <span>{{ $account->opening_balance }}</span></div>
                                    </li>

                                    <li class="list-group-item d-flex align-items-center px-2 py-1 mb-1">
                                        <i class="ti ti-wallet text-primary me-2" style="font-size: 1rem;"></i>
                                        <div class="text-truncate"><span class="me-1">Close Bal:</span>
                                        <span>{{ $account->closing_balance }}</span></div>
                                    </li>

                                    <li class="list-group-item d-flex align-items-center px-2 py-1 mb-1">
                                        <i class="ti ti-phone text-primary me-2" style="font-size: 1rem;"></i>
                                        <div class="text-truncate"><span class="me-1">Contact:</span>
                                        <span>{{ $account->contact_number }}</span></div>
                                    </li>

                                    <li class="list-group-item d-flex align-items-center px-2 py-1 mb-1">
                                        <i class="ti ti-device-mobile text-primary me-2" style="font-size: 1rem;"></i>
                                        <div class="text-truncate"><span class="me-1">Phone:</span>
                                        <span>{{ $account->phone }}</span></div>
                                    </li>

                                    <li class="list-group-item d-flex align-items-center px-2 py-1 mb-1">
                                        <i class="ti ti-mail text-primary me-2" style="font-size: 1rem;"></i>
                                        <div class="text-truncate"><span class="me-1">Email:</span>
                                        <span>{{ $account->email }}</span></div>
                                    </li>

                                    <li class="list-group-item d-flex align-items-center px-2 py-1">
                                        <i class="ti ti-map-pin text-primary me-2" style="font-size: 1rem;"></i>
                                        <div class="text-truncate"><span class="me-1">Address:</span>
                                        <span>{{ $account->bank_address }}</span></div>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-footer bg-transparent p-2">
                                <div class="d-flex justify-content-between">
                                    
                                    <!-- Edit Button -->
                                    <a href="{{ route('company.finance.bank-accounts.edit', $account->id) }}"
                                       class="btn btn-sm btn-outline-secondary"
                                       data-bs-toggle="tooltip" title="{{ __('Edit') }}">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                            
                                    <!-- Delete Button -->
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['company.finance.bank-accounts.destroy', $account->id], 'id' => 'delete-form-' . $account->id]) !!}
                                        <a href="#"
                                           class="btn btn-sm btn-outline-danger"
                                           data-bs-toggle="tooltip" title="{{ __('Delete') }}"
                                           onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this bank account?')) document.getElementById('delete-form-{{ $account->id }}').submit();">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    {!! Form::close() !!}
                            
                                </div>
                            </div>
                            
                        </div>
                    </div>
                @empty
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="ti ti-bank-off text-muted" style="font-size: 48px;"></i>
                                <h5 class="mt-3">{{ __('No Bank Accounts Found') }}</h5>
                                <p class="text-muted">{{ __('Add your first bank account to get started') }}</p>
                                <a href="{{ route('company.bank-accounts.create') }}" class="btn btn-primary mt-3">
                                    <i class="ti ti-plus me-2"></i>{{ __('Add Bank Account') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .bank-card {
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            font-size: 0.95rem;
        }

        .bank-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            border-radius: 8px 8px 0 0 !important;
            padding: 1rem  0.8rem;
        }

        .list-group-item {
            background: #f9f9f9;
            padding: 0.5rem 0.8rem;
            border-bottom: 1px solid #eee;
            margin-bottom: 0.3rem;
            border-radius: 4px;
            font-size: 0.95rem;
        }

        .list-group-item i {
            min-width: 24px;
            text-align: center;
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.3em 0.6em;
        }

        .btn-sm {
            padding: 0.3rem 0.6rem;
            font-size: 0.85rem;
        }
        
        .card-body {
            padding: 0.6rem;
        }
        
        .card-footer {
            padding: 0.6rem;
        }
        
        h6 {
            font-size: 1rem;
            margin-bottom: 0;
        }
        
        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 180px;
            display: inline-block;
            font-size: 0.95rem;
        }
        
        .mb-1 {
            margin-bottom: 0.3rem !important;
        }
    </style>
@endpush

@push('script-page')
    <script>
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush