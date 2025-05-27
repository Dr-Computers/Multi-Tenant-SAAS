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
        <div class="col-12 mb-4">
            <div class="card border-light shadow-sm rounded">
                <div class="card-body text-center" style="background-color: #f8f9fa;">
                    <h5 class="card-title font-weight-bold text-primary">
                        {{ __('Total Properties') }}
                    </h5>
                    <p class="card-text">
                        <strong>{{ $totalProperties }}</strong>
                    </p>

                </div>
            </div>
        </div>
    

        <div class="col-12">
            <div class="card">
                <div class="card-body ">
                    <div class="table-responsive">
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th>{{ __('Property Name') }}</th>
                                    <th>{{ __('Units') }}</th>
                                    <th>{{ __('Tenants') }}</th>

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
                                                            $tenant = $unit->tenants(); // Call your tenants method
                                                        @endphp
                                                        <li>
                                                            @if ($tenant)
                                                                {{ optional($tenant->user)->first_name . ' ' . optional($tenant->user)->last_name }}
                                                            @else
                                                                {{ __('No Tenant') }}
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                {{ __('No Tenants') }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

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
