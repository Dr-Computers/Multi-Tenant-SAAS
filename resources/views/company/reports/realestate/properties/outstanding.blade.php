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
            <a href="#">{{ __('Properties Report') }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="">
                        <div class="col-md-8 d-flex align-items-center">
                            <label for="from_date" class="form-label mb-0 me-2">{{ __('From') }}</label>
                            <input type="date" id="from_date" name="from_date" class="form-control form-control-sm me-3"
                                value="{{ request('from_date') }}">

                            <label for="to_date" class="form-label mb-0 me-2">{{ __('To') }}</label>
                            <input type="date" id="to_date" name="to_date" class="form-control form-control-sm me-3"
                                value="{{ request('to_date') }}">
                            <select name="property" id="property" class="form-select form-select-sm me-2">
                                <option value="">{{ __('All Properties') }}</option>
                                @foreach ($filterProperty as $property)
                                    <option value="{{ $property->id }}"
                                        {{ request('property') == $property->id ? 'selected' : '' }}>
                                        {{ $property->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm" style="margin-right: 10px;">
                                {{ __('Filter') }}
                            </button>
                            <a href="" class="btn btn-secondary btn-sm">
                                {{ __('Clear') }}
                            </a>

                        </div>
                    </form>
                    @php
                        $totalAmountSum = 0;
                        $outstanding = 0;
                        $amountPaid = 0;
                    @endphp

                    <div class="table-responsive mt-4">
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th>{{ __('Property Name') }}</th>
                                    <th>{{ __('Units') }}</th>
                                    {{-- <th>{{ __('Invoice') }}</th> --}}
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Outstanding') }}</th>
                                    <th>{{ __('Paid Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($properties as $property)
                                    <tr>
                                        <td>{{ $property->name }}</td> <!-- Adjust according to your property field -->
                                        <td>
                                            @if ($property->units->isNotEmpty())
                                                <ul>
                                                    @foreach ($property->units as $unit)
                                                        <li>{{ $unit->name }}</li>
                                                        <!-- Adjust according to your unit field -->
                                                    @endforeach
                                                </ul>
                                            @else
                                                {{ __('No Units') }}
                                            @endif
                                        </td>
                                        {{-- <td>
                                    @if ($property->units->isNotEmpty())
                                    <ul>
                                        @foreach ($property->units as $unit)
                                            @php
                                            $invoiceIds = $unit->invoices();  // This will give an array of invoice_ids
                                            @endphp
                                            <li>
                                                @if ($invoiceIds->isNotEmpty())  <!-- Check if there are any invoice_ids -->
                                                    @foreach ($invoiceIds as $invoiceId)
                                                        {{ invoicePrefix().$invoiceId }} <!-- Display each invoice_id -->
                                                        @if (!$loop->last), @endif  <!-- Add comma between invoice IDs, except after the last one -->
                                                    @endforeach
                                                @else
                                                    {{ __('No Invoice') }}
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    {{ __('No Invoices') }}
                                @endif
                                
                                </td> --}}
                                        <td>
                                            @if ($property->units->isNotEmpty())
                                                <ul>
                                                    @foreach ($property->units as $unit)
                                                        @php
                                                            $total = $unit->totalAmount(); // This will give an array of invoice_ids
                                                            $totalAmountSum += $total; // Add to total sum
                                                        @endphp
                                                        <li>
                                                            @if ($total)
                                                                {{ priceFormat($total) }} <!-- Display each invoice_id -->
                                                            @else
                                                                {{ '-' }}
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                {{ __('No Invoices') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($property->units->isNotEmpty())
                                                <ul>
                                                    @foreach ($property->units as $unit)
                                                        @php
                                                            $total = $unit->totalDueAmount(); // This will give an array of invoice_ids
                                                            $outstanding += $total; // Add to outstanding sum
                                                        @endphp
                                                        <li>
                                                            @if ($total != 0)
                                                                {{ priceFormat($total) }} <!-- Display each invoice_id -->
                                                            @else
                                                                {{ '-' }}
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                {{ __('No Invoices') }}
                                            @endif
                                        </td>
                                        <td>

                                            <ul>
                                                @foreach ($property->units as $unit)
                                                    @php
                                                        $paidAmount = $unit->totalAmount() - $unit->totalDueAmount();
                                                        $amountPaid += $paidAmount; // Add to paid amount sum
                                                    @endphp
                                                    <li>
                                                        @if ($paidAmount != 0)
                                                            {{ priceFormat($paidAmount) }}
                                                        @else
                                                            {{ '-' }}
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            {{-- <tfoot>
                            <tr>
                                <th colspan="3">Total(OMR):</th>
                                <th colspan="1" id="out_amount"></th> <!-- Total for Amount -->
                                <th colspan="1" id="outstanding"></th>    <!-- Total for VAT -->
                            </tr>
                        </tfoot> --}}
                            <tfoot>
                                <tr>
                                    <th colspan="2"></th>
                                    <th colspan="1">{{ priceFormat($totalAmountSum) }}</th>
                                    <th colspan="1">{{ priceFormat($outstanding) }}</th>
                                    <th colspan="1">{{ priceFormat($amountPaid) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{-- <div class="d-flex justify-content-end" style="margin-top: 10px;">
                        {!! $properties->onEachSide(2)->links() !!}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
