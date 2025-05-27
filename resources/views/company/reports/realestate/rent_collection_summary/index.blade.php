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
            <a href="#">{{ __('Rent Collection Summary Report') }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap; margin: 20px;">
            <span style="font-size: 14px; font-weight: bold;">Building:</span>
            <form method="GET" action="{{ route('company.report.rent_collection.index') }}">
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
            <a href="{{ route('company.report.rent_collection.index') }}" class="btn btn-secondary btn-sm">
                {{ __('Clear') }}
            </a>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body ">
                    <div class="table-responsive">
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th>{{ __('Tenant') }}</th>
                                    <th>{{ __('Invoice') }}</th>
                                    <th>{{ __('Month of') }}</th>
                                    <th>{{ __('Property') }}</th>
                                    <th>{{ __('Unit') }}</th>
                                    <th>{{ __('Expected') }}</th>
                                    <th>{{ __('Paid') }}</th>
                                    <th>{{ __('Balance') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr role="row">
                                        <td>{{ $invoice->units->tenants()->user->name ?? '-' }}</td>
                                        <td>{{ (optional($invoice->properties)->invoice_prefix ?: invoicePrefix()) . $invoice->invoice_id }}
                                        </td>
                                        <td>{{ date('F Y', strtotime($invoice->invoice_month)) }} </td>
                                        <td>{{ !empty($invoice->properties) ? $invoice->properties->name : '-' }} </td>
                                        <td>{{ !empty($invoice->units) ? $invoice->units->name : '-' }} </td>
                                        @php
                                            $amount = $invoice->getInvoiceSubTotalAmount();
                                        @endphp
                                        <td>{{ priceFormat($invoice->getInvoiceSubTotalAmount()) }}</td>
                                        <td>{{ priceFormat($invoice->getInvoicePaidAmount()) }}</td>
                                        <td>{{ priceFormat($invoice->getInvoiceDueAmount()) }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5"></th>
                                    <th colspan="1">{{ priceFormat($totalAmount) }}</th>
                                    <th colspan="1">{{ priceFormat($paid) }}</th>
                                    <th colspan="1">{{ priceFormat($balance) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{-- <div class="d-flex justify-content-end" style="margin-top: 10px;">
                        {{ $invoices->links() }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
