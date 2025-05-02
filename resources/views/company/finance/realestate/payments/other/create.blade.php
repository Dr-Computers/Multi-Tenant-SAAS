@extends('layouts.company')

@section('page-title')
    {{ __('Other Payments') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Other Payments') }}</li>
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
            <a href="{{ route('company.finance.realestate.invoice.payments.index') }}" class="payment-option"
                id="invoice-payments-btn" data-bs-toggle="tooltip" title="Go to Invoice Payments">
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
            <a href="#" class="payment-option" id="other-payments-btn" data-bs-toggle="tooltip"
                title="Go to Other Payments">
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
    {{ Form::open(['url' => route('company.finance.realestate.invoice.payments.store'), 'method' => 'post', 'id' => 'invoice_form', 'enctype' => 'multipart/form-data', 'files' => true]) }}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="info-group">
                        <div class="row">

                            <input type="hidden" name='choose_type' id="choose_type" value="other">
                            <!-- Property, Unit, Invoice, Payment Date, Payment Method and Cheque fields -->



                            <div class="form-group col-md-6 col-lg-4" id="payment_date_div">
                                {{ Form::label('payment_date', __('Payment Date'), ['class' => 'form-label']) }}
                                {{ Form::date('payment_date', null, ['class' => 'form-control']) }}
                            </div>





                            <!-- Remaining fields -->
                            <div class="form-group col-md-6 col-lg-4" id="payment_for_div">
                                {{ Form::label('payment_for', __('Payment For'), ['class' => 'form-label']) }}
                                {{ Form::select('payment_for', $paymentFor, null, ['class' => 'form-control hidesearch', 'id' => 'payment_for']) }}
                            </div>

                            <div class="form-group col-md-6 col-lg-4" id="account_selection">
                                {{ Form::label('account_id', __('Select Bank Account'), ['class' => 'form-label']) }}
                                <div class="cheque_div">
                                    <select class="form-control hidesearch account" id="account" name="account_id">
                                        <option value="">{{ __('Select Bank Account') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-lg-4" id="tenant_div">
                                {{ Form::label('tenant', __('Tenant'), ['class' => 'form-label']) }}
                                {{ Form::select('tenant', $tenants, null, ['class' => 'form-control hidesearch', 'id' => 'tenant']) }}
                            </div>
                            <div class="form-group col-md-6 col-lg-4" id="invoice_div">
                                {{ Form::label('invoice_id', __('Invoice'), ['class' => 'form-label']) }}
                                {{ Form::select('invoice_id', [], null, ['class' => 'form-control hidesearch', 'id' => 'invoice_list']) }}
                            </div>


                            <div class="form-group col-md-6 col-lg-4" id="ref_div">
                                {{ Form::label('reference_no', __('Reference Number'), ['class' => 'form-label']) }}
                                {{ Form::text('reference_no', null, ['class' => 'form-control', 'placeholder' => __('Enter Reference Number')]) }}
                            </div>

                            <div class="form-group col-md-6 col-lg-4" id="amount_div">
                                {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}
                                {{ Form::number('amount', null, ['class' => 'form-control', 'id' => 'amount', 'step' => '0.01', 'placeholder' => __('Enter Amount')]) }}
                            </div>

                            <div class="form-group col-md-6 col-lg-4" id="notes_div">
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

                    $('.hidesearch').select2({
                        minimumResultsForSearch: -1
                    });
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", status, error); // Log any error
                }
            });
        });

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



        $(document).ready(function() {
            // Check if there's a previously selected property and trigger change event
            var oldInvoiceId = '{{ old('invoice_id') }}';
            var oldTenantId = '{{ old('tenant_id') }}';

            if (oldInvoiceId) {
                console.log("have id");
                "use strict";
                var invoice_id = oldInvoiceId;
                var tenant_id = oldTenantId;
                var url = '{{ route('company.finance.realestate.tenant.invoices', ':id') }}';
                url = url.replace(':id', tenant_id);

                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    success: function(data) {
                        console.log(data);

                        $('#invoice_list').empty().append(
                            '<option value="">{{ __('Select Invoice') }}</option>');

                        $.each(data, function(key, value) {
                            var oldInvoice = '{{ old('invoice_id') }}';
                            console.log("oldInvoiceId" + oldInvoice);

                            var isSelected = (key == oldInvoice) ? 'selected' :
                                ''; // Check if it matches old value
                            var invoiceWithPrefix = '{{ invoicePrefix() }}' + value;

                            $('#invoice_list').append('<option value="' + key + '" ' +
                                isSelected +
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
            $('#tenant').on('change', function() {
                "use strict";
                var tenant_id = $(this).val();
                var url = '{{ route('company.finance.realestate.tenant.invoices', ':id') }}';
                url = url.replace(':id', tenant_id);

                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    success: function(data) {
                        $('#invoice_list').empty().append(
                            '<option value="">{{ __('Select Invoice') }}</option>');
                        $.each(data, function(key, value) {
                            var formattedInvoiceId =
                                '{{ invoicePrefix() . optional('invoice_id')->invoice_id }}' +
                                value; // Adjust as necessary
                            $('#invoice_list').append('<option value="' + key + '">' +
                                formattedInvoiceId + '</option>');
                            // $('.invoice').append('<option value="' + key + '">' + value +'</option>');
                        });

                        $('.hidesearch').select2({
                            minimumResultsForSearch: -1
                        });
                    },
                });
            });
            $('#invoice_list').on('change', function() {
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
                            $('#amount').val(response.due_amount);
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
@endpush
