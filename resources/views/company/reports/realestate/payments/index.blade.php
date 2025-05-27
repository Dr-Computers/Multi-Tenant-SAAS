@extends('layouts.company')
@section('page-title')
    {{ __('Invoice') }}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Payments Report') }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mb-4 mt-5">
            <div class="card border-light shadow-sm rounded">
                <div class="card-body d-flex align-items-center" style="background-color: #f8f9fa;">
                    <!-- SVG Icon -->
                    <div class="mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            class="bi bi-currency-dollar" viewBox="0 0 16 16">
                            <path
                                d="M8 0a2 2 0 0 1 2 2v1h2a1 1 0 0 1 0 2h-2v1h2a1 1 0 0 1 0 2h-2v1h2a1 1 0 0 1 0 2h-2v1a2 2 0 0 1-4 0v-1H2a1 1 0 0 1 0-2h2v-1H2a1 1 0 0 1 0-2h2V3H2a1 1 0 0 1 0-2h2V2a2 2 0 0 1 2-2z" />
                        </svg>
                    </div>

                    <!-- Text Area -->
                    <div class="flex-grow-1 text-left">
                        <h5 class="card-title font-weight-bold text-primary">
                            {{ __('Total Payment Amount') }}
                        </h5>
                        <p class="card-text">
                            <strong>{{ priceFormat($totalAmount) }}</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap; margin-bottom: 20px;">
            <span style="font-size: 14px; font-weight: bold;">Building:</span>
            <form method="GET" action="{{ route('company.report.payments.index') }}">
                <select id="property" name="property" style="padding: 5px; font-size: 14px;">
                    <option value="" disabled selected>--Select--</option>
                    @foreach ($filterProperty as $property)
                        <option value="{{ $property->id }}" {{ request('property') == $property->id ? 'selected' : '' }}>
                            {{ $property->name }}</option>
                    @endforeach
                </select>

                <span style="font-size: 14px; font-weight: bold;">Tenant:</span>
                <select id="tenant" name="tenant"
                    style="padding: 5px; font-size: 14px;  min-width: 150px; max-width: 200px; flex-shrink: 0;">
                    <option value="" disabled selected>--Select--</option>
                    @foreach ($filterTenant as $tenant)
                        <option value="{{ $tenant->id }}" {{ request('tenant') == $tenant->id ? 'selected' : '' }}>
                            {{ $tenant->name }}</option>
                    @endforeach
                </select>

                <label for="start_month" style="font-size: 14px; font-weight: bold;">Month Of:</label>
                <input type="month" id="start_month" name="start_month"
                    value="{{ request('start_month') ? request('start_month') : '' }}"
                    style="padding: 5px; font-size: 14px;">

                <label for="end_month" style="font-size: 14px; font-weight: bold;">To:</label>
                <input type="month" id="end_month" name="end_month"
                    value="{{ request('end_month') ? request('end_month') : '' }}" style="padding: 5px; font-size: 14px;">

                <button type="submit" class="btn btn-primary btn-sm" style="margin-right: 10px;">
                    {{ __('Filter') }}
                </button>
            </form>
            <a href="{{ route('company.report.payments.index') }}" class="btn btn-secondary btn-sm">
                {{ __('Clear') }}
            </a>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body ">
                    <div class="table-responsive">
                        <table class="table ">
                            <tr>
                                <th>{{ __('Payment Date ') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Method') }}</th>
                                <th>{{ __('Property') }}</th>
                                <th>{{ __('Invoice') }}</th>
                                <th>{{ __('Unit') }}</th>
                                <th>{{ __('Tenant') }}</th>
                                <th>{{ __('Note') }}</th>

                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr role="row">
                                        {{-- <td>{{invoicePrefix().$invoice->invoice_id}} </td>
                                <td>{{!empty($invoice->properties)?$invoice->properties->name:'-'}} </td>
                                <td>{{!empty($invoice->units)?$invoice->units->name:'-'}}  </td>
                                <td>{{date('F Y',strtotime($invoice->invoice_month))}} </td>
                                <td>{{dateFormat($invoice->end_date)}} </td>
                                <td>{{priceFormat($invoice->getInvoiceSubTotalAmount())}}</td> --}}


                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}</td>
                                        <td>{{ priceFormat($payment->amount) }}</td>
                                        <td>{{ $payment->payment_type }}</td>
                                        <td>{{ optional($payment->invoice->properties)->name ?? 'N/A' }}</td>
                                        <td>{{ (optional($payment->invoice->properties)->invoice_prefix ?: invoicePrefix()) . optional($payment->invoice)->invoice_id ?? 'N/A' }}
                                        </td>
                                        <td>{{ optional($payment->invoice->units)->name ?? 'N/A' }}</td>
                                        <td>{{ optional(optional(optional($payment->invoice)->units)->activeLease->tenant)->user->first_name ?? 'N/A' }}
                                        </td>

                                        <td>{{ $payment->notes ?? 'N/A' }}</td>


                                    </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    {{-- <th colspan="1">{{ __('Total') }}</th> --}}
                                    <th colspan="1"></th>
                                    <th colspan="1">{{ priceFormat($totalAmount) }}</th>
                                    <th colspan="5"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- Pagination links -->
                    {{-- <div class="d-flex justify-content-end" style="margin-top: 10px;">
                        {{ $payments->links() }} <!-- This will display the pagination links -->
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
