@extends('layouts.company')

@section('page-title')
    {{ __('Invoice Payments') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Invoice Payments') }}</li>
@endsection

@section('action-btn')
    <div class="d-flex">
        <a href="#" data-size="md" data-url="{{ route('company.finance.realestate.invoices.create') }}"
            data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create New Invoice') }}"
            class="btn btn-sm btn-primary me-2">
            <i class="ti ti-plus"></i>
        </a>
    </div>
@endsection

@section('content')
    
<div class="row justify-content-center" style="margin-top: 10px; margin-bottom: 10px;">
    <!-- Invoice Payments Button -->
    <div class="col-md-5 mb-2">
        <a href="{{ route('company.finance.realestate.invoice.payments.index') }}" class="payment-option" id="invoice-payments-btn"
            data-bs-toggle="tooltip" title="Go to Invoice Payments">
            <div class="card payment-card shadow-sm border-0 text-center" style="padding: 5px;">
                <div class="card-body p-2">
                    <div class="icon-box text-primary" style="font-size: 14px;">
                        <i class="ti ti-file" style="font-size: 16px;"></i>
                    </div>
                    <h6 class="card-title mt-1 mb-0 fw-bold" style="font-size: 10px;">{{ __('Invoice Payments') }}</h6>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-5 mb-2">
        <a href="#" class="payment-option" id="other-payments-btn"
            data-bs-toggle="tooltip" title="Go to Other Payments">
            <div class="card payment-card shadow-sm border-0 text-center" style="padding: 5px;">
                <div class="card-body p-2">
                    <div class="icon-box text-danger" style="font-size: 14px;">
                        <i class="ti ti-credit-card" style="font-size: 16px;"></i>
                    </div>
                    <h6 class="card-title mt-1 mb-0 fw-bold" style="font-size: 10px;">{{ __('Other Payments') }}</h6>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="row">

        
        {{ Form::open(['url' => route('company.finance.realestate.invoice.payments.store'), 'method' => 'post', 'id' => 'invoice_form', 'enctype' => 'multipart/form-data', 'files' => true]) }}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="info-group">
                            <div class="row">
                                <input type="hidden" name='choose_type' value="property" id='choose_type'>
                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('property_id', __('Property'), ['class' => 'form-label']) }}
                                    {{ Form::select('property_id', $property, null, ['class' => 'form-control hidesearch']) }}
                                </div>
                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('unit_id', __('Unit'), ['class' => 'form-label']) }}
                                    <div class="unit_div">
                                        <select class="form-control hidesearch unit" id="unit" name="unit_id">
                                            <option value="">{{ __('Select Unit') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('invoice_id', __('Invoice'), ['class' => 'form-label']) }}
                                    <div class="invoice_div">
                                        <select class="form-control hidesearch invoice" id="invoice" name="invoice_id">
                                            <option value="">{{ __('Select Invoice') }}</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('payment_date', __('Payment Date'), ['class' => 'form-label']) }}
                                    {{ Form::date('payment_date', date('Y-m-d'), ['class' => 'form-control']) }}
                                </div>

                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('payment_method', __('Payment Type'), ['class' => 'form-label']) }}
                                    {{ Form::select('payment_method', ['cheque' => 'Cheque', 'cash' => 'Cash', 'bank_transfer' => 'Bank Transfer'], null, ['class' => 'form-control hidesearch', 'id' => 'payment_method']) }}
                                </div>

                                <!-- Cheque Selection (hidden by default) -->
                                <div class="form-group col-md-6 col-lg-4" id="cheque_selection" style="display: none;">
                                    {{ Form::label('cheque_id', __('Select Cheque'), ['class' => 'form-label']) }}
                                    <div class="cheque_div">
                                        <select class="form-control hidesearch cheque" id="cheque" name="cheque_id">
                                            <option value="">{{ __('Select Cheque') }}</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('payment_for', __('Payment For'), ['class' => 'form-label']) }}
                                    {{ Form::select(
                                        'payment_for',
                                        [
                                            'rent' => __('Rent'),
                                            'service_charge' => __('Service Charge'),
                                    
                                            'late_fee' => __('Late Fee'),
                                            'application_fee' => __('Application Fee'),
                                            'lease_termination_fee' => __('Lease Termination Fee'),
                                            'pet_fee' => __('Pet Fee'),
                                            'maintenance_fee' => __('Maintenance Fee'),
                                            'parking_fee' => __('Parking Fee'),
                                            'storage_fee' => __('Storage Fee'),
                                            'utilities_income' => __('Utilities Income'),
                                            'fines_for_violations' => __('Fines for Violations'),
                                            'commercial_rent' => __('Commercial Rent'),
                                            'sundry_income' => __('Sundry Income'),
                                            'referral_fee' => __('Referral Fee'),
                                            'vat_amount' => __('VAT Amount'),
                                        ],
                                        null,
                                        ['class' => 'form-control hidesearch', 'id' => 'payment_for'],
                                    ) }}
                                </div>

                                <div class="form-group col-md-6 col-lg-4" id="account_selection">
                                    {{ Form::label('account_id', __('Select Bank Account'), ['class' => 'form-label']) }}
                                    <div class="cheque_div">
                                        <select class="form-control hidesearch account" id="account" name="account_id">
                                            <option value="">{{ __('Select Bank Account') }}</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('reference_no', __('Reference Number'), ['class' => 'form-label']) }}
                                    {{ Form::text('reference_no', null, ['class' => 'form-control', 'placeholder' => __('Enter Reference Number')]) }}
                                </div>
                                <input type="hidden" id="due_amount" value="">
                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}
                                    {{ Form::number('amount', null, ['class' => 'form-control', 'id' => 'amount', 'step' => '0.01', 'placeholder' => __('Enter Amount')]) }}
                                </div>


                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('notes', __('Notes'), ['class' => 'form-label']) }}
                                    {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 2, 'placeholder' => __('Enter Notes')]) }}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div id="hidden-check-details"></div>
            <div class="col-lg-12">
                <div class="group-button text-end">
                    {{ Form::submit(__('Create'), ['class' => 'btn btn-primary btn-rounded', 'id' => 'invoice-submit']) }}
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@endsection



    <!-- Include repeater.js and other necessary scripts -->
    @push('script-page')
        <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
        <script>
            $('#property_id').on('change', function() {
                "use strict";
                var property_id = $(this).val();
                var url = '{{ route('company.realestate.property.unit', ':id') }}';
                url = url.replace(':id', property_id);

                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    success: function(data) {
                        $('.unit').empty().append('<option value="">{{ __('Select Unit') }}</option>');
                        $.each(data.units, function(key, value) {
                            $('.unit').append('<option value="' + key + '">' + value + '</option>');
                        });

                      
                    },
                });
            });




            $('#unit').on('change', function() {
                "use strict";
                var unit_id = $(this).val();
                var url = '{{ route('company.finance.realestate.unit.invoice', ':id') }}';
                url = url.replace(':id', unit_id);

                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    success: function(data) {
                        $('.invoice').empty().append(
                            '<option value="">{{ __('Select Invoice') }}</option>'
                        );

                        // Get the invoice prefix from the response
                        var invoicePrefix = data.invoice_prefix || '';

                        // Loop through the invoices data (data.invoices)
                        $.each(data.invoices, function(key, value) {
                            // Key is the invoice ID, value is the invoice_id from database
                            var formattedInvoiceId = invoicePrefix + value;

                            $('.invoice').append('<option value="' + key + '">' +
                                formattedInvoiceId + '</option>');
                        });

                        
                    }
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
                    var url = '{{ route('company.finance.realestate.unit.invoice', ':id') }}';
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
                    var url = '{{ route('company.realestate.property.unit', ':id') }}';
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

                    var url = '{{ route('company.finance.realestate.payments.cheque', ':id') }}';


                    $.ajax({
                        url: url.replace(':id', invoiceId),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'GET',
                        success: function(data) {
                            console.log("detaisl"+ data);

                            // $('.cheque').empty().append(
                            //     '<option value="">{{ __('Select Cheque') }}</option>');
                            // $.each(data, function(index, cheque) {
                            //     // $('#account_selection').hide();
                            //     // $('#account_selection').val();

                            //     $('#cheque_selection').show();
                            //     $('.cheque').append('<option value="' + cheque.id +
                            //         '" data-amount="' + cheque.amount + '">' + cheque
                            //         .check_number + '</option>');

                            //     // $('#amount').val(cheque.amount);
                            // });
                            // Clear the cheque dropdown
                            $('.cheque').empty().append(
                                '<option value="">{{ __('Select Cheque') }}</option>'
                            );

                            // Check if data has any cheques
                            if (data.length > 0) {
                                $('#cheque_selection').show(); // Show the selection dropdown
                                $.each(data, function(index, cheque) {
                                    $('.cheque').append('<option value="' + cheque.id +
                                        '" data-amount="' + cheque.amount + '">' + cheque
                                        .cheque_number + '</option>');
                                });
                            } else {
                                // If there are no cheques, show a message
                                $('#cheque_selection').show(); // Show the selection dropdown
                                $('.cheque').append(
                                    '<option value="">{{ __('No Pending Cheques') }}</option>');

                            }

                            
                        },

                    });
                }


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
                var url = '{{ route('company.finance.bank-account.fetchdetails') }}';
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

                            $('.account').append('<option value="' + account.id + '">' + account
                                .holder_name + '</option>');
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
                        url: '{{ route('company.finance.realestate.invoice.due.amount') }}',
                        method: 'POST',
                        data: {
                            invoice_id: invoiceId,
                            _token: '{{ csrf_token() }}' // Include CSRF token
                        },
                        success: function(response) {
                            // Store the due amount for further validation
                            console.log("due", response); // comma, not +

                            
                            $('#due_amount').val(response.due_amount);
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });

            $('#amount').on('input', function() {
                var amount = parseFloat($(this).val());
                var dueAmount = parseFloat($('#due_amount').val()); // Assume you store the due amount in a hidden input
                var paymentFor = $('#payment_for').val(); // Get the value of the payment_for select

                // Debugging logs
                console.log("Due Amount:", dueAmount);
                console.log("Entered Amount:", amount);
                console.log("Payment For:", paymentFor);

                // Check if the amount exceeds the due amount, regardless of payment type
                if (amount > dueAmount) {
                    alert('Payment amount cannot exceed the due amount of ' + dueAmount);
                    $(this).val(''); // Clear the amount field
                }
            });


            // JavaScript to disable the "Other Payments" button based on the hidden input value
            document.addEventListener('DOMContentLoaded', function() {
                const chooseType = document.getElementById('choose_type').value;


                const otherPaymentsBtn = document.getElementById('other-payments-btn');
                const invoicePaymentsBtn = document.getElementById('invoice-payments-btn');



                if (chooseType === 'other') {
                    // Disable the button if the value is "other"
                    otherPaymentsBtn.style.pointerEvents = 'none';
                    otherPaymentsBtn.style.opacity = '0.5'; // Make it look disabled
                }
                if (chooseType === 'property') {
                    // Disable the button if the value is "other"
                    invoicePaymentsBtn.style.pointerEvents = 'none';
                    invoicePaymentsBtn.style.opacity = '0.5'; // Make it look disabled
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        </script>
    @endpush

