@extends('layouts.app')
@section('page-title')
    {{ __('Asset') }}
@endsection
@push('script-page')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
    <script>
        $('#property_id').on('change', function() {
            "use strict";
            var property_id = $(this).val();
            var url = '{{ route('property.unit', ':id') }}';
            url = url.replace(':id', property_id);

            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                success: function(data) {
                    $('.unit').empty().append('<option value="">{{ __('Select Unit') }}</option>');
                    $.each(data, function(key, value) {
                        $('.unit').append('<option value="' + key + '">' + value + '</option>');
                    });

                    $('.hidesearch').select2({
                        minimumResultsForSearch: -1
                    });
                },
            });
        });



        // Listen for changes in the unit dropdown
        $('#unit').on('change', function() {
            "use strict";
            var unit_id = $(this).val();
            var url = '{{ route('unit.invoice', ':id') }}';
            url = url.replace(':id', unit_id);

            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                success: function(data) {
                    $('.invoice').empty().append(
                        '<option value="">{{ __('Select Invoice') }}</option>');
                    $.each(data, function(key, value) {
                        var formattedInvoiceId =
                            '{{ invoicePrefix() . optional('invoice_id')->invoice_id }}' +
                            value; // Adjust as necessary
                        $('.invoice').append('<option value="' + key + '">' +
                            formattedInvoiceId + '</option>');
                        // $('.invoice').append('<option value="' + key + '">' + value +'</option>');
                    });

                    $('.hidesearch').select2({
                        minimumResultsForSearch: -1
                    });
                },
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Check if there's a previously selected property and trigger change event
            var oldInvoiceId = '{{ old('invoice_id') }}';
            var oldUnitId = '{{ old('unit_id') }}';

            if (oldInvoiceId) {
                console.log("have id");
                "use strict";
                var invoice_id = oldInvoiceId;
                var unit_id = oldUnitId;
                var url = '{{ route('unit.invoice', ':id') }}';
                url = url.replace(':id', unit_id);

                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    success: function(data) {
                        console.log(data);

                        $('.invoice').empty().append(
                            '<option value="">{{ __('Select Invoice') }}</option>');

                        $.each(data, function(key, value) {
                            var oldInvoice = '{{ old('invoice_id') }}';
                            console.log("oldInvoiceId" + oldInvoice);

                            var isSelected = (key == oldInvoice) ? 'selected' :
                                ''; // Check if it matches old value
                            var invoiceWithPrefix = '{{ invoicePrefix() }}' + value;

                            $('.invoice').append('<option value="' + key + '" ' + isSelected +
                                '>' + invoiceWithPrefix + '</option>');


                            // $('.invoice').append('<option value="' + key + '">' + value +'</option>');
                        });

                        $('.hidesearch').select2({
                            minimumResultsForSearch: -1
                        });
                    },
                });

                // Set the old property ID and trigger change to load units

            }
        });
    </script>
    <script>
        $(document).ready(function() {
            // Check if there's a previously selected property and trigger change event
            var oldPropertyId = '{{ old('property_id') }}';
            var oldUnitId = '{{ old('unit_id') }}';

            if (oldPropertyId) {
                console.log("have id");
                "use strict";
                var property_id = oldPropertyId;
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
                            $('.unit').append('<option value="' + key + '" ' + isSelected +
                                '>' + value + '</option>');

                            // $('.unit').append('<option value="' + key + '">' + value +'</option>');
                        });
                        $('.hidesearch').select2({
                            minimumResultsForSearch: -1
                        });
                    },

                });

                // Set the old property ID and trigger change to load units

            }
        });
    </script>




    <script>
        $(document).ready(function() {
            // Hide or show cheque selection based on the payment method


            $('#payment_method').on('change', function() {
                console.log("payment method called");

                var paymentMethod = $(this).val();
                var invoiceId = $('#invoice').val();

                if (paymentMethod === 'cheque') {
                    console.log(invoiceId);

                    getChequeDetails(invoiceId);
                // } else if (paymentMethod === 'bank_transfer') {
                //     console.log("hello");

                //     getAccountDetails();
                } else {
                    $('#cheque_selection').hide();
                    $('#amount').val('');

                    console.log("No invoice selected or payment method is not cheque");
                }
            });
            $('#invoice').on('change', function() {
                var invoiceId = $(this).val();


                getChequeDetails(invoiceId);


            });

            function getChequeDetails(invoiceId) {
                "use strict";

                var url = '{{ route('payment.cheque', ':id') }}';


                $.ajax({
                    url: url.replace(':id', invoiceId),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    success: function(data) {
                        console.log(data);

                        $('.cheque').empty().append(
                            '<option value="">{{ __('Select Cheque') }}</option>');
                        $.each(data, function(index, cheque) {
                            // $('#account_selection').hide();
                            // $('#account_selection').val();

                            $('#cheque_selection').show();
                            $('.cheque').append('<option value="' + cheque.id +
                                '" data-amount="' + cheque.amount + '">' + cheque
                                .check_number + '</option>');

                            // $('#amount').val(cheque.amount);
                        });

                        $('.hidesearch').select2({
                            minimumResultsForSearch: -1
                        });
                    },
                });
            }

            // function getAccountDetails(invoiceId) {
            //     "use strict";

            //     var url = '{{ route('bank-account.fetchdetails') }}';
            //     console.log("AJAX call to URL: " + url); // Debugging statement

            //     $.ajax({
            //         url: url,
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         type: 'GET',
            //         success: function(data) {
            //             console.log("Response data: ", data);

            //             $('.account').empty().append(
            //                 '<option value="">{{ __('Select Bank Account') }}</option>');
            //             $.each(data.accounts, function(index, account) {
            //                 $('#cheque_selection').hide();
            //                 $('#cheque_selection').val();
            //                 $('#account_selection').show();



            //                 $('.account').append('<option value="' + account.id + '">' + account
            //                     .account_name + '</option>');


            //                 // $('#amount').val(cheque.amount);
            //             });

            //             $('.hidesearch').select2({
            //                 minimumResultsForSearch: -1
            //             });
            //         },
            //         error: function(xhr, status, error) {
            //             console.error("AJAX Error: ", status, error); // Log any error
            //         }
            //     });
            // }
            

            // Trigger change event to show/hide cheque selection if a method is pre-selected
            $('#payment_method').trigger('change');
        });


        $('#cheque').on('change', function() {
            var cheque = $(this).val(); // Get the selected cheque value
            var chequeAmount = $(this).find('option:selected').data('amount');
            $('#amount').val(chequeAmount);
        });

    </script>
    <script>
         $(document).ready(function() {
            var url = '{{ route('bank-account.fetchdetails') }}';
            console.log("AJAX call to URL: " + url); // Debugging statement

            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                success: function(data) {
                    console.log("Response data: ", data);

                    $('.account').empty().append(
                        '<option value="">{{ __('Select Bank Account') }}</option>'
                    );

                    $.each(data.accounts, function(index, account) {
                        $('#cheque_selection').hide();
                        $('#cheque_selection').val();
                        $('#account_selection').show();

                        $('.account').append('<option value="' + account.id + '">' + account.account_name + '</option>');
                    });

                    $('.hidesearch').select2({
                        minimumResultsForSearch: -1
                    });
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", status, error); // Log any error
                }
            });
        });
    </script>


    <script>
        var selector = "body";
        if ($(selector + " .repeater").length) {
            var $dragAndDrop = $("body .repeater tbody").sortable({

                handle: '.sort-handler'
            });
            var $repeater = $(selector + ' .repeater').repeater({

                initEmpty: false,
                defaultValues: {
                    'status': 1
                },
                show: function() {
                    $('.hidesearch').select2({
                        minimumResultsForSearch: -1
                    });
                    $(this).slideDown();

                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                        $(this).remove();

                    }
                },
                ready: function(setIndexes) {
                    $dragAndDrop.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });
            var value = $(selector + " .repeater").attr('data-value');
            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                $repeater.setList(value);
            }
        }
    </script>
    <script>
        $('#invoice').on('change', function() {
            var invoiceId = $(this).val(); // Get the selected invoice ID

            if (invoiceId) {
                $.ajax({
                    url: '{{ route('invoice.due.amount') }}',
                    method: 'POST',
                    data: {
                        invoice_id: invoiceId,
                        _token: '{{ csrf_token() }}' // Include CSRF token
                    },
                    success: function(response) {
                        // Store the due amount for further validation
                        $('#due_amount').val(response.due_amount);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            }
        });

        $('#amount').on('input', function() {
            console.log("called")
            var amount = parseFloat($(this).val());
            var dueAmount = parseFloat($('#due_amount').val()); // Assume you store the due amount in a hidden input
console.log(dueAmount);
console.log(amount);


            // Check if the amount exceeds the due amount
            if (amount > dueAmount) {
                alert('Payment amount cannot exceed the due amount of ' + dueAmount);
                $(this).val(''); // Clear the amount field
            }
        });
    </script>
@endpush
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('assets.index') }}">{{ __('Assets') }}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Create') }}</a>
        </li>
    </ul>
@endsection

@section('content')
{{ Form::open(['url' => route('assets.store'), 'method' => 'post', 'id' => 'asset_form', 'enctype' => 'multipart/form-data', 'files' => true]) }}
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="info-group">
                    <div class="row">
                        <div class="form-group col-md-6 col-lg-4">
                            {{ Form::label('name', __('Asset Name/Description'), ['class' => 'form-label']) }}
                            <span class="text-danger">*</span>
                            {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Asset Name')]) }}
                        </div>
                        
                        <div class="form-group col-md-6 col-lg-4">
                            {{ Form::label('type', __('Asset Type'), ['class' => 'form-label', 'escape' => false]) }}
                            <span class="text-danger">*</span>
                           {{ Form::select('type', [
                                'fixed_asset' => __('Fixed Asset'), 
                                'current_asset' => __('Current Asset'), 
                                'bank' => __('Bank')
                            ], null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Asset Type')]) }}
                        </div>
                        

                        <div class="form-group col-md-6 col-lg-4">
                            {{ Form::label('property_id', __('Property ID'), ['class' => 'form-label']) }}
                            {{ Form::select('property_id', $properties, null, ['class' => 'form-control hidesearch', 'placeholder' => __('Select Property')]) }}
                        </div>

                        <div class="form-group col-md-6 col-lg-4">
                            {{ Form::label('location', __('Location'), ['class' => 'form-label']) }}
                            {{ Form::text('location', null, ['class' => 'form-control', 'placeholder' => __('Enter Location')]) }}
                        </div>

                        <div class="form-group col-md-6 col-lg-4">
                            {{ Form::label('purchase_date', __('Purchase Date'), ['class' => 'form-label']) }}
                            {{ Form::date('purchase_date', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group col-md-6 col-lg-4">
                            {{ Form::label('purchase_price', __('Purchase Price'), ['class' => 'form-label']) }}
                            {{ Form::number('purchase_price', null, ['class' => 'form-control', 'step' => '0.01', 'placeholder' => __('Enter Purchase Price')]) }}
                        </div>

                        <div class="form-group col-md-6 col-lg-4">
                            {{ Form::label('vendor_name', __('Vendor Name'), ['class' => 'form-label']) }}
                            {{ Form::text('vendor_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Vendor Name')]) }}
                        </div>

                        <div class="form-group col-md-6 col-lg-4">
                            {{ Form::label('initial_value', __('Initial Value '), ['class' => 'form-label']) }}
                            <span class="text-danger">*</span>
                            {{ Form::number('initial_value', null, ['class' => 'form-control', 'required' => 'required', 'step' => '0.01', 'placeholder' => __('Enter Initial Value')]) }}
                        </div>

                        <div class="form-group col-md-6 col-lg-4">
                            {{ Form::label('current_market_value', __('Current Market Value'), ['class' => 'form-label']) }}
                            <span class="text-danger">*</span>
                            {{ Form::number('current_market_value', null, ['class' => 'form-control', 'step' => '0.01','required' => 'required', 'placeholder' => __('Enter Current Market Value')]) }}
                        </div>

                        <div class="form-group col-md-6 col-lg-4">
                            {{ Form::label('accumulated_depreciation', __('Accumulated Depreciation'), ['class' => 'form-label']) }}
                            {{ Form::number('accumulated_depreciation', null, ['class' => 'form-control', 'step' => '0.01', 'placeholder' => __('Enter Accumulated Depreciation')]) }}
                        </div>

                        <div class="form-group col-md-6 col-lg-4">
                            {{ Form::label('owner_name', __('Owner Name'), ['class' => 'form-label']) }}
                            {{ Form::text('owner_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Owner Name')]) }}
                        </div>

                        <div class="form-group col-md-6 col-lg-4">
                            {{ Form::label('title_deed_number', __('Title Deed Number'), ['class' => 'form-label']) }}
                            {{ Form::text('title_deed_number', null, ['class' => 'form-control', 'placeholder' => __('Enter Title Deed Number')]) }}
                        </div>

                        <div class="form-group col-md-6 col-lg-4">
                            {{ Form::label('condition', __('Asset Condition'), ['class' => 'form-label']) }}
                            {{ Form::text('condition', null, ['class' => 'form-control', 'placeholder' => __('Enter Asset Condition')]) }}
                        </div>

                        <div class="form-group col-md-6 col-lg-4">
                            {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
                            {{ Form::select('status', ['active' => 'Active', 'sold' => 'Sold', 'under_maintenance' => 'Under Maintenance'], null, ['class' => 'form-control hidesearch', 'placeholder' => __('Select Status')]) }}
                        </div>

                        <div class="form-group col-md-6 col-lg-4">
                            {{ Form::label('notes', __('Additional Notes'), ['class' => 'form-label']) }}
                            {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 2, 'placeholder' => __('Enter Additional Notes')]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-12">
        <div class="group-button text-end">
            {{ Form::submit(__('Create Asset'), ['class' => 'btn btn-primary btn-rounded']) }}
        </div>
    </div>
</div>
{{ Form::close() }}


@endsection
