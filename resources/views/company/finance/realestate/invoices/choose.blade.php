@extends('layouts.company')
@section('page-title')
    {{ __('Invoices') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Invoices') }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        <a href="{{ route('company.finance.realestate.invoices.create', 0) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
            title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
@endsection
@push('css-page')
<style>
    .payment-card {
        /* transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out; */
        border-radius: 12px;
    }
    
    .payment-card:hover {
        transform: translateY(-5px);
        /* box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1); */
    }

    .icon-box {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        margin: auto;
    }

    .payment-option {
        text-decoration: none;
        color: inherit;
    }
    /* Remove commented-out code if not needed */
.payment-card {
    border-radius: 12px;
}

/* Consider adding a subtle shadow for better visual hierarchy */
.invoice-card {
    transition: all 0.3s ease;
    border: 1px solid #e0e0e0 !important; /* better than shadow-none */
}

.invoice-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.icon-box {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    margin: auto;
    transition: all 0.3s ease;
}

.invoice-option {
    text-decoration: none;
    color: inherit;
    display: block;
    height: 100%;
}

/* Add some spacing between cards on mobile */
@media (max-width: 767px) {
    .col-md-5 {
        margin-bottom: 20px;
    }
}
</style>
@endpush
@section('content')
<div class="card mt-5">
    <div class="card-body">
<div class="text-center">
    <h4 class="fw-bold mb-4">{{ __('Select Invoice Type') }}</h4>
    <p class="text-muted mb-4">Choose between managing property invoices or other types of invoices.</p>
</div>

<div class="row justify-content-center" style="margin-top: 25px;">
    <!-- Manage Property Invoices -->
    <div class="col-md-5">
        <a href="{{ route('company.finance.realestate.invoices.index') }}" class="invoice-option">
            <div class="card invoice-card shadow-none border-0">
                <div class="card-body text-center">
                    <div class="icon-box bg-primary text-white">
                        <i data-feather="home"></i>
                    </div>
                    <h6 class="card-title mt-5 fw-bold">{{ __('Manage Property Invoices') }}</h6>
                    <p class="card-text text-muted">Track and manage invoices related to properties.</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Manage Other Invoices -->
    <div class="col-md-5">
        <a href="{{ route('company.finance.realestate.invoice-other.index') }}" class="invoice-option">
            <div class="card invoice-card shadow-none border-0">
                <div class="card-body text-center">
                    <div class="icon-box bg-success text-white">
                        <i data-feather="file-text"></i>
                    </div>
                    <h6 class="card-title mt-5 fw-bold">{{ __('Manage Other Invoices') }}</h6>
                    <p class="card-text text-muted">Manage invoices that are not related to properties.</p>
                </div>
            </div>
        </a>
    </div>
</div>
</div>
</div>
@endsection




@push('script-page')
    
    
@endpush
