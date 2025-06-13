@extends('layouts.company')

@section('page-title')
    {{ __('Payments Payables') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Payments Payables') }}</li>
@endsection

@section('action-btn')
    <div class="d-flex">
        <a href="{{ route('company.finance.realestate.payments.payables.index') }}"
             title="{{ __('Back to Invoice') }}"
            class="btn btn-sm btn-primary me-2">
            <i class="ti ti-arrow-left"></i> Back
        </a>
    </div>
@endsection

@section('content')
    {{ Form::open(['route' => 'company.finance.realestate.payments.payables.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'payable_form']) }}

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
                            <span class="text-danger">*</span>
                            {{ Form::date('date', null, ['class' => 'form-control', 'placeholder' => __('Select Date')]) }}
                        </div>
                        <div class="form-group col-lg-6 col-md-6">
                            {{ Form::label('pay_to', __('Pay To'), ['class' => 'form-label']) }}
                            <span class="text-danger">*</span>
                            {{ Form::select(
                                'pay_to',
                                [
                                    '' => '--Select--',
                                    'tenant' => __('Tenants'),
                                    'owner' => __('Owner'),
                                    'maintainer' => __('Maintenars'),
                                    'company-staff' => __('Staff'),
                                ],
                                null,
                                ['class' => 'form-control hidesearch', 'id' => 'pay_to'],
                            ) }}
                        </div>

                        <div class="form-group col-lg-6 col-md-6" id="select_user_div">
                            {{ Form::label('user', __('Client List'), ['class' => 'form-label']) }}
                            <span class="text-danger">*</span>
                            <div class="user_div">
                                <select class="form-control hidesearch user" id="user" name="user_id">
                                    <option value="">{{ __('') }}</option>

                                </select>
                            </div>
                        </div>

                        <div class="form-group col-lg-6 col-md-6">
                            {{ Form::label('reason_for', __('For/Reason'), ['class' => 'form-label']) }}
                            <span class="text-danger">*</span>
                            {{ Form::text('reason_for', null, ['class' => 'form-control', 'placeholder' => __('Enter For/Reason')]) }}
                        </div>
                        <div class="form-group col-lg-6 col-md-6">
                            {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}
                            <span class="text-danger">*</span>
                            {{ Form::number('amount', null, ['class' => 'form-control', 'placeholder' => __('Enter Amount')]) }}
                        </div>
                        <div class="form-group col-lg-6 col-md-6">
                            {{ Form::label('from', __('From'), ['class' => 'form-label']) }}
                            <span class="text-danger">*</span>
                            {{ Form::select('from', $bankaccount, null, ['class' => 'form-control hidesearch', 'id' => 'from']) }}
                        </div>

                        <div class="form-group col-lg-6 col-md-6">
                            {{ Form::label('note', __('Note'), ['class' => 'form-label']) }}
                            {{ Form::textarea('note', null, ['class' => 'form-control', 'rows' => 4, 'placeholder' => __('Enter Note')]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="group-button text-end">
                {{ Form::submit(__('Create'), ['class' => 'btn btn-primary btn-rounded', 'id' => 'payable-submit']) }}
            </div>
        </div>
    </div>
    {{ Form::close() }}
@endsection



<!-- Include repeater.js and other necessary scripts -->
@push('script-page')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Hide the user div on page load
            $('#select_user_div').hide();
            // Check if there's a previously selected payto and trigger change event
            var oldPayToId = '{{ old('pay_to') }}';

            if (oldPayToId) {
                console.log("have id");
                "use strict";
                var pay_to_type = oldPayToId;
                var url = '{{ route('company.finance.realestate.user.type', ':id') }}';
                url = url.replace(':id', pay_to_type);
                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        type: pay_to_type,
                    },
                    contentType: false,
                    processData: false,
                    type: 'GET',
                    success: function(data) {
                        if (pay_to_type === 'tenant') {
                            $('label[for="user"]').text('Client List');
                        } else if (pay_to_type === 'owner') {
                            $('label[for="user"]').text('Owner');
                        } else if (pay_to_type === 'maintainer') {
                            $('label[for="user"]').text('Maintenars');
                        } else if (pay_to_type === 'staff') {
                            $('label[for="user"]').text('Staff');
                        }
                        $('.user').empty(); // Clear existing options

                        var user =
                            `<select class="form-control hidesearch user" id="user" name="user_id"></select>`;
                        $('.user_div').html(user);

                        if ($.isEmptyObject(data)) {
                            // If no data is found, append "No result found"
                            $('.user').append('<option value="">{{ __('No result found') }}</option>');
                        } else {
                            $.each(data, function(key, value) {
                                var oldUser = '{{ old('user_id') }}';
                                var isSelected = (key == oldUser) ? 'selected' : '';
                                $('.user').append('<option value="' + key + '" ' + isSelected +
                                    '>' + value + '</option>');
                            });
                        }

                        if (pay_to_type !== '') {
                            $('#select_user_div').show(); // Show the div when a valid value is selected
                        }

                        $('.hidesearch').select2({
                            minimumResultsForSearch: -1
                        });
                    },

                });

                // Set the old property ID and trigger change to load units

            }

            //  });
            $('#pay_to').on('change', function() {
                "use strict";
                var pay_to_type = $(this).val();
                var url = '{{ route('company.finance.realestate.user.type', ':id') }}';
                url = url.replace(':id', pay_to_type);
                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        type: pay_to_type,
                    },
                    contentType: false,
                    processData: false,
                    type: 'GET',
                    success: function(data) {
                        if (pay_to_type === 'tenant') {
                            $('label[for="user"]').text('Client List');
                        } else if (pay_to_type === 'owner') {
                            $('label[for="user"]').text('Owner');
                        } else if (pay_to_type === 'maintainer') {
                            $('label[for="user"]').text('Maintenars');
                        } else if (pay_to_type === 'staff') {
                            $('label[for="user"]').text('Staff');
                        }
                        $('.user').empty(); // Clear existing options

                        var user =
                            `<select class="form-control hidesearch user" id="user" name="user_id"></select>`;
                        $('.user_div').html(user);

                        if ($.isEmptyObject(data)) {
                            // If no data is found, append "No result found"
                            $('.user').append(
                                '<option value="">{{ __('No result found') }}</option>');
                        } else {
                            $.each(data, function(key, value) {
                                var oldUser = '{{ old('user_id') }}';
                                var isSelected = (key == oldUser) ? 'selected' : '';
                                $('.user').append('<option value="' + key + '" ' +
                                    isSelected + '>' + value + '</option>');
                            });
                        }

                        if (pay_to_type !== '') {
                            $('#select_user_div')
                                .show(); // Show the div when a valid value is selected
                        }

                        $('.hidesearch').select2({
                            minimumResultsForSearch: -1
                        });
                    }
                });
            });
        });
    </script>
@endpush
