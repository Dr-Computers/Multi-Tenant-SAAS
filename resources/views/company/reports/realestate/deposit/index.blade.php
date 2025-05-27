@extends('layouts.company')
@section('page-title')
    {{ __('Securit Deposiot') }}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Security Deposit Report') }}</a>
        </li>
    </ul>
@endsection

{{-- <script>
       $('#property_id').on('change', function() {
                console.log("called fucntion");

                "use strict";
                var property_id = $(this).val();
                var url = '{{ route('property.unit', ':id') }}';
                url = url.replace(':id', property_id);
                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        property_id: property_id,
                    },
                    contentType: false,
                    processData: false,
                    type: 'GET',
                    success: function(data) {
                        $('.unit').empty();

                        var unit =
                            `<select class="form-control hidesearch unit" id="unit" name="unit_id"></select>`;
                        $('.unit_div').html(unit);

                        $.each(data, function(key, value) {
                            var oldUnit = '{{ old('unit_id') }}';
                            console.log(oldUnit);
                            // Get the old value from PHP
                            var isSelected = (key == oldUnit) ? 'selected' :
                            ''; // Check if it matches old value

                            // Append options
                            $('.unit').append('<option value="' + key + '" ' +
                                isSelected + '>' + value + '</option>');

                            // $('.unit').append('<option value="' + key + '">' + value +'</option>');
                        });
                        $('.hidesearch').select2({
                            minimumResultsForSearch: -1
                        });
                    },

                });
            });
      
    </script> --}}

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('company.report.deposit.payments.index') }}">
                        <div class="row">
                            <!-- Tenant Filter -->
                            <div class="col-md-3">
                                <select name="tenant_id" class="form-control">
                                    <option value="">Select Tenant</option>
                                    @foreach ($tenants as $tenant)
                                        <option value="{{ $tenant->id }}"
                                            {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                            {{ $tenant->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Property Filter -->
                            <div class="col-md-3">
                                <select name="property_id" class="form-control" id="property_id">
                                    <option value="">Select Property</option>
                                    @foreach ($properties as $property)
                                        <option value="{{ $property->id }}"
                                            {{ request('property_id') == $property->id ? 'selected' : '' }}>
                                            {{ $property->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Unit Filter -->
                            <div class="col-md-3">
                                <select name="unit_id" class="form-control" id="unit_id">
                                    <option value="">Select Unit</option>

                                    <option value="">{{ __('Select Unit') }}</option>

                                </select>
                            </div>


                            <!-- Date Filters -->
                            <div class="col-md-3">
                                <input type="date" name="date_from" class="form-control"
                                    value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>

                            <!-- Submit Button -->
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-sm mt-3">Apply Filters</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive mt-4">
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th>{{ __('Payment Date') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Property') }}</th>
                                    <th>{{ __('Unit') }}</th>
                                    <th>{{ __('Tenant') }}</th>
                                    <th>{{ __('Note') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}</td>
                                        <td>{{ priceFormat($payment->amount) }}</td>
                                        <td>
                                            @if ($payment->payment_type == 'cash')
                                                <span class="badge badge-success">Cash</span>
                                            @elseif($payment->payment_type == 'cheque')
                                                <span class="badge badge-info">Cheque</span>
                                            @elseif($payment->payment_type == 'bank_transfer')
                                                <span class="badge badge-secondary">Bank Transfer</span>
                                            @else
                                                <span
                                                    class="badge badge-light text-dark">{{ $payment->payment_type }}</span>
                                            @endif
                                        </td>
                                        <td>{{ optional($payment->property)->name ?? 'N/A' }}</td>
                                        <td>{{ optional($payment->unit)->name ?? 'N/A' }}</td>
                                        <td>{{ optional(optional(optional($payment->unit))->tenant)->user->first_name ?? 'N/A' }}
                                        </td>
                                        <td>{{ $payment->notes ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                {{-- <tr>
                                    <th colspan="1">Total(OMR):</th>
                                    <th colspan="6">{{ priceFormat($totalAmount) }}</th>
                                </tr> --}}
                                <tr>
                                    {{-- <th colspan="1">Total(OMR):</th> --}}
                                    <th colspan="1"></th>
                                    <th colspan="1">{{ priceFormat($totalAmount) }}</th>
                                    <th colspan="4"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{-- <div class="d-flex justify-content-end" style="margin-top: 10px;">
                            {!! $payments->links() !!}
                        </div> --}}
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
