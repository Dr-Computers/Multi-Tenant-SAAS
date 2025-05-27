@extends('layouts.company')

@section('page-title')
    {{ __('Unit Details') }}
@endsection

<style>
    .button-container {
        display: flex;
        justify-content: flex-end; /* Pushes content to the right */
    }
    .btn {
        margin-bottom: 10px; /* Adjust the space below the button */
    }
</style>

@section('content')
<div class="row">
    <div class="col-12 mb-4"> <div class="button-container">
        <a href="{{ route('units.download', $unit->id) }}">
            <button class="btn btn-primary btn-sm">Download</button>
        </a>
    </div>
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0" style="margin-bottom: 10px !important;">{{ __('Unit Details') }}</h4>
            </div>
            <div class="card-body d-flex justify-content-between align-items-start">
                <!-- Unit Details on Left -->
                <div>
                    <h4 class="mb-3">{{  $unit->name }}</h4>
                    <p><strong class="text-primary">{{ __('Rent: ') }}</strong>{{ priceFormat(($unit->latestRateChange)->unit_amount) }}</p>
                    <p><strong class="text-primary">{{ __('Property: ') }}</strong>{{ $unit->properties->name ?? '-' }}</p>
                </div>

                <!-- Tenant Details on Right -->
                @if($tenant)
                <div class="tenant-details">
                    <h5>{{ __('Tenant Details') }}</h5>
                    <p><strong>{{ __('Name:') }}</strong> {{ $tenant->user->first_name }} {{ $tenant->user->last_name }}</p>
                    <p><strong>{{ __('Email:') }}</strong> {{ $tenant->user->email }}</p>
                    <p><strong>{{ __('Phone:') }}</strong> {{ $tenant->user->phone_number ?? '-' }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Check Details Section -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header text-dark">
                <h5 class="mb-0" style="margin-bottom: 5px !important">{{ __('Cheque Details') }}</h5>
            </div>
            <div class="card-body">
                @if($checkDetails)
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>{{ __('Check Number') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Bank Name') }}</th>
                                <th>{{ __('Bank Account') }}</th>
                                <th>{{ __('Routing Number') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Notes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $checkDetails->check_number }}</td>
                                <td>{{ priceFormat($checkDetails->amount) }}</td>
                                <td>{{ $checkDetails->bank_name }}</td>
                                <td>{{ $checkDetails->bank_account_number }}</td>
                                <td>{{ $checkDetails->routing_number }}</td>
                                <td>{{ $checkDetails->status }}</td>
                                <td>{{ $checkDetails->notes ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">{{ __('No check details found') }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header text-dark">
                <h5 class="mb-0" style="margin-bottom: 5px !important">{{ __('Invoices and Payments') }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>{{ __('Invoice ID') }}</th>
                            <th>{{__('Invoice Date')}}</th>
                            <th>{{__('Invoice Period')}}</th>
                            <th>{{__('Invoice Period End')}}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Pending Amount') }}</th>
                            <th>{{ __('Payments') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                        <tr>
                            <td>{{ invoicePrefix().$invoice->invoice_id }}</td>
                            <td>{{ dateFormat($invoice->end_date) }}</td>
                            <td>{{ $invoice->invoice_period . ' years' }}</td>
                            <td>{{ $invoice->invoice_period_end_date }}</td>
                            <td>{{ priceFormat($invoice->getInvoiceSubTotalAmount()) }}</td>
                            <td>{{ priceFormat($invoice->getInvoiceDueAmount()) }}</td>
                            <td>
                                @if($invoice->payments->isEmpty())
                                    <span class="text-muted">{{ __('No payments found') }}</span>
                                @else
                                    <ul class="list-unstyled mb-0">
                                        @foreach($invoice->payments as $payment)
                                        <li>
                                            <strong class="text-info">{{ __('Reference No:') }}</strong> {{ $payment->reference_no }} <br>
                                            <strong class="text-success">{{ __('Date:') }}</strong> {{ $payment->payment_date }} <br>
                                            <strong class="text-success">{{ __('Amount:') }}</strong> {{ priceFormat($payment->amount) }}
                                        </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header text-dark">
                <h5 class="mb-0" style="margin-bottom: 5px !important">{{ __('Invoices and Payments') }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>{{ __('Invoice ID') }}</th>
                            <th>{{ __('Invoice Date') }}</th>
                            <th>{{ __('Invoice Period') }}</th>
                            <th>{{ __('Invoice Period End') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Pending Amount') }}</th>
                            <th>{{ __('Payments') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalAmount = 0;
                            $totalPending = 0;
                            $totalPayments = 0;
                        @endphp
    
                        @foreach($invoices as $invoice)
                            @php
                                $invoiceAmount = $invoice->getInvoiceSubTotalAmount();
                                $pendingAmount = $invoice->getInvoiceDueAmount();
                                $paymentAmount = $invoice->payments->sum('amount');
    
                                $totalAmount += $invoiceAmount;
                                $totalPending += $pendingAmount;
                                $totalPayments += $paymentAmount;
                            @endphp
                            <tr>
                                <td>{{ (optional($invoice->properties)->invoice_prefix ?: invoicePrefix()) .$invoice->invoice_id }}</td>
                                <td>{{ dateFormat($invoice->end_date) }}</td>
                                <td>{{ $invoice->invoice_period . ' years' }}</td>
                                <td>{{ $invoice->invoice_period_end_date }}</td>
                                <td>{{ priceFormat($invoiceAmount) }}</td>
                                <td>{{ priceFormat($pendingAmount) }}</td>
                                <td>
                                    @if($invoice->payments->isEmpty())
                                        <span class="text-muted">{{ __('No payments found') }}</span>
                                    @else
                                        <ul class="list-unstyled mb-0">
                                            @foreach($invoice->payments as $payment)
                                            <li>
                                                <strong class="text-info">{{ __('Reference No:') }}</strong> {{ $payment->reference_no }} <br>
                                                <strong class="text-success">{{ __('Date:') }}</strong> {{ $payment->payment_date }} <br>
                                                <strong class="text-success">{{ __('Amount:') }}</strong> {{ priceFormat($payment->amount) }}
                                            </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right"><strong>{{ __('Total') }}</strong></td>
                            <td><strong>{{ priceFormat($totalAmount) }}</strong></td>
                            <td><strong>{{ priceFormat($totalPending) }}</strong></td>
                            <td><strong>{{ priceFormat($totalPayments) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    

    <!-- Security Deposit Payments Section -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header text-dark">
                <h5 class="mb-0" style="margin-bottom: 5px !important">{{ __('Security Deposit Payments') }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>{{ __('Unit Name') }}</th>
                            <th>{{ __('Tenant Name') }}</th>
                            <th>{{ __('Amount Paid') }}</th>
                            <th>{{ __('Payment Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalSecurityDeposits = 0;
                        @endphp
    
                        @foreach($securityDepositPayments as $payment)
                            @php
                                $totalSecurityDeposits += $payment->amount;
                            @endphp
                            <tr>
                                <td>{{ optional($payment->unit)->name ?? 'N/A' }}</td>
                                <td>{{ optional(optional($payment->unit))->tenants()->user->first_name ?? 'N/A' }}</td>
                                <td>{{ priceFormat($payment->amount) }}</td>
                                <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-right"><strong>{{ __('Total') }}</strong></td>
                            <td colspan="2"><strong>{{ priceFormat($totalSecurityDeposits) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
    
                <!-- Paginate results -->
                {{-- {{ $securityDepositPayments->links() }} --}}
            </div>
        </div>
    </div>
    
</div>

@endsection

@push('styles')
<style>
    .tenant-details {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        width: 250px;
    }
    .tenant-details h5 {
        font-size: 1.25rem;
        color: #007bff;
        margin-bottom: 10px;
    }
   
</style>
@endpush
