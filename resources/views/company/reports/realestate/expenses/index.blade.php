@extends('layouts.company')
@section('page-title')
    {{ __('Expense') }}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Expenses Report') }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 ">
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
                            {{ __('Total Expense Amount') }}
                        </h5>
                        <p class="card-text">
                            <strong>{{ priceFormat($totalAmount) }}</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        {{--         
        <div class="date-filter">
            <label for="min">From:</label>
            <input type="text" id="min" placeholder="Select Date" />
            <label for="max">To:</label>
            <input type="text" id="max" placeholder="Select Date" />
            <button id="clearDates" class="clear-btn">Clear</button>
            <button id="todayMax" class="today-btn">Today</button>
            <button id="lastMonthMax" class="last-month-btn">Last Month</button>
        </div> --}}
        <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap; margin: 20px;">

            <form method="GET" action="{{ route('company.report.expenses.index') }}">
                <span style="font-size: 14px; font-weight: bold;">Building:</span>
                <select id="property" name="property"
                    style="padding: 5px; font-size: 14px; min-width: 150px; max-width: 200px; flex-shrink: 0;">
                    <option value="" disabled selected>--Select--</option>
                    @foreach ($filterProperty as $property)
                        <option value="{{ $property->id }}" {{ request('property') == $property->id ? 'selected' : '' }}>
                            {{ $property->name }}</option>
                    @endforeach
                </select>

                {{-- <span style="font-size: 14px; font-weight: bold;">Tenant:</span>
            <select id="tenant" name="tenant" style="padding: 5px; font-size: 14px;  min-width: 150px; max-width: 200px; flex-shrink: 0;">
                <option value="" disabled selected>--Select--</option>
                @foreach ($filterTenant as $tenant)
                <option value="{{$tenant->id}}" {{ request('tenant') == $tenant->id ? 'selected' : '' }}>{{$tenant->user->first_name . ' ' . $tenant->user->last_name}}</option>
                @endforeach
            </select> --}}

                <label for="start_month" style="font-size: 14px; font-weight: bold;">Month Of:</label>
                <input type="month" id="start_month" name="start_month"
                    value="{{ request('start_month') ? request('start_month') : '' }}"
                    style="padding: 5px; font-size: 14px; min-width: 150px;">

                <label for="end_month" style="font-size: 14px; font-weight: bold;">To:</label>
                <input type="month" id="end_month" name="end_month"
                    value="{{ request('end_month') ? request('end_month') : '' }}"
                    style="padding: 5px; font-size: 14px; min-width: 150px;">

                <button type="submit" class="btn btn-primary btn-sm" style="margin-right: 10px;">
                    {{ __('Filter') }}
                </button>
            </form>
            <a href="{{ route('company.report.expenses.index') }}" class="btn btn-secondary btn-sm">
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
                                    {{-- <th>{{__('Tenant')}}</th> --}}
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Expense') }}</th>
                                    <th>{{ __('Reference No') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Vendor') }}</th>
                                    <th>{{ __('Property') }}</th>
                                    <th>{{ __('Unit') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Base Amount') }}</th>
                                    <th>{{ __('VAT Amount') }}</th>
                                    <th>{{ __('Total Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalBaseAmount = 0;
                                    $totalVatAmount = 0;
                                    $grandTotalAmount = 0;
                                @endphp
                                @foreach ($expenses as $expense)
                                    @php
                                        $totalBaseAmount += $expense->base_amount;
                                        $totalVatAmount += $expense->vat_amount;
                                        $grandTotalAmount += $expense->amount;
                                    @endphp
                                    <tr role="row">
                                        {{-- <td>{{ $expense->units->tenants()->user->name ?? '-' }}</td> --}}
                                        <td>{{ \Carbon\Carbon::parse($expense->date)->format('Y-m-d') }}</td>
                                        <td>{{ expensePrefix() . $expense->expense_id }}</td>
                                        <td>{{ $expense->reference_no }}</td>
                                        <td>{{ $expense->title }}</td>
                                        <td>{{ $expense->vendor }}</td>
                                        <td>{{ !empty($expense->properties) ? $expense->properties->name : '-' }}</td>
                                        <td>{{ !empty($expense->units) ? $expense->units->name : '-' }}</td>
                                        <td>{{ !empty($expense->types) ? $expense->types->title : '-' }}</td>
                                        <td>{{ priceFormat($expense->base_amount) }}</td>
                                        <td>{{ priceFormat($expense->vat_amount) }}</td>
                                        <td>{{ priceFormat($expense->amount) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    {{-- <th colspan="8">Total(OMR):</th> --}}
                                    <th colspan="8"></th>
                                    <th colspan="1" id="amount">{{ priceFormat($baseAmount) }}</th>
                                    <!-- Total for Amount -->
                                    <th colspan="1" id="vat">{{ priceFormat($vatAmount) }}</th>
                                    <!-- Total for VAT -->
                                    <th colspan="1" id="total">{{ priceFormat($totalAmount) }}</th>
                                    <!-- Total for Total -->
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- Pagination links -->
                    {{-- <div class="d-flex justify-content-end" style="margin-top: 10px;">
                        {{ $expenses->links() }} <!-- This will display the pagination links -->
                    </div> --}}

                </div>
            </div>
        </div>
    </div>
@endsection
