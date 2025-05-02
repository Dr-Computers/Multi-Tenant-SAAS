@extends('layouts.company')

@section('page-title')
    {{ __('Invoices') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Invoices') }}</li>
@endsection


<style>
    .readonly-field {
        background-color: #f0f0f0 !important;
        cursor: not-allowed;
        /* Optional, to show it's not editable */
    }
</style>


@section('content')
    <div class="row">

        {{ Form::model($payment, ['route' => ['company.finance.realestate.invoice.payments.update', $payment->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="info-group">
                            <div class="row">
                                <input type="hidden" name='choose_type' value="property" id='choose_type'>
                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('property_id', __('Property'), ['class' => 'form-label']) }}

                                    {{ Form::text('property_id', $payment->invoice->properties->name ?? null, ['class' => 'form-control readonly-field', 'readonly' => true]) }}
                                </div>
                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('unit_id', __('Unit'), ['class' => 'form-label']) }}

                                    {{-- <input type="hidden" id="edit_unit" value="{{ $payment->invoice->units->id }}"> --}}
                                    {{ Form::text('unit_id', $payment->invoice->units->name ?? null, ['class' => 'form-control readonly-field', 'readonly' => true]) }}
                                </div>
                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('invoice_id', __('Invoice'), ['class' => 'form-label']) }}
                                    <input type="hidden" id="edit_invoice" name="invoice_id"
                                        value="{{ $payment->invoice->id }}">

                                    {{ Form::text('invoice', invoicePrefix() . $payment->invoice->invoice_id ?? null, ['class' => 'form-control readonly-field', 'readonly' => true]) }}
                                </div>

                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('payment_date', __('Payment Date'), ['class' => 'form-label']) }}
                                    {{ Form::date('payment_date', $payment->payment_date ?? null, ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('payment_type', __('Payment Method'), ['class' => 'form-label']) }}
                                    {{ Form::text('payment_type', $payment->invoice->payment_type ?? null, ['class' => 'form-control readonly-field', 'readonly' => true]) }}
                                </div>
                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('payment_for', __('Payment For'), ['class' => 'form-label']) }}
                                    {{ Form::text('payment_for', str_replace('_', ' ', $payment->payment_for) ?? null, ['class' => 'form-control readonly-field', 'readonly' => true]) }}


                                </div>

                                <div class="form-group col-md-6 col-lg-4" id="cheque_selection" style="display: none;">
                                    {{ Form::label('cheque_id', __('Select Cheque'), ['class' => 'form-label']) }}
                                    <input type="hidden" id="check_id" name="check_id"
                                        value="{{ $payment->invoice->cheque_id }}">

                                    {{ Form::text('cheque_id', $payment->invoice->chequeDetails->first()->cheque_number ?? null, ['class' => 'form-control readonly-field', 'readonly' => true]) }}
                                </div>

                                <div class="form-group col-md-6 col-lg-4" id="account_selection">
                                    {{ Form::label('account_id', __('Select Bank Account'), ['class' => 'form-label']) }}
                                    <input type="hidden" id="account_id" name="account_id"
                                        value="{{ $payment->account->id ?? '' }}">

                                    {{ Form::text('cheque_id', $payment->account->holder_name ?? null, ['class' => 'form-control readonly-field', 'readonly' => true]) }}
                                </div>
                                <div class="form-group col-md-6 col-lg-4" id="reference_selection">
                                    {{ Form::label('reference_no', __('Reference Number'), ['class' => 'form-label']) }}
                                    {{ Form::text('reference_no', $payment->reference_no ?? null, ['class' => 'form-control ']) }}
                                </div>


                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}
                                    {{ Form::number('amount', $payment->amount ?? null, ['class' => 'form-control readonly-field', 'id' => 'amount', 'step' => '0.01', 'placeholder' => __('Enter Amount'), 'readonly' => true]) }}
                                </div>

                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('notes', __('Notes'), ['class' => 'form-label']) }}
                                    {{ Form::textarea('notes', old('notes', $payment->notes ?? null), ['class' => 'form-control', 'rows' => 2, 'placeholder' => __('Enter Notes')]) }}

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-12">
                <div class="group-button text-end">
                    {{ Form::submit(__('Update'), ['class' => 'btn btn-primary btn-rounded', 'id' => 'invoice-submit']) }}
                </div>
            </div>
        </div>
        {{ Form::close() }}

    </div>
@endsection


@push('script-page')
    <script>
        $(document).ready(function() {
            // Check if there's a previously selected property and trigger change event
            var oldPaymentMethod = '{{ $payment->payment_type }}';


            console.log(oldPaymentMethod);


            if (oldPaymentMethod == 'cheque') {

                var oldchequeId = '{{ $payment->cheque_id }}';
                console.log("have id" + oldchequeId);
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
                var paymentMethod = $(this).val();
                var invoiceId = $('#edit_invoice').val();

                if (paymentMethod === 'cheque') {
                    console.log(invoiceId);
                    $('#amount').val('')
                    getChequeDetails(invoiceId);
                } else {
                    $('#cheque_selection').hide();
                    // $('#amount').val('');

                    console.log("No invoice selected or payment method is not cheque");
                }
            });
            $('#invoice').on('change', function() {
                var invoiceId = $(this).val();
                getChequeDetails(invoiceId);


            });

            function getChequeDetails(invoiceId) {
                console.log("called");

                "use strict";

                var url = '{{ route('company.finance.realestate.payments.cheque', ':id') }}';
                url = url.replace(':id', invoiceId);

                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    success: function(data) {
                        $('.cheque').empty().append(
                            '<option value="">{{ __('Select Cheque') }}</option>');
                        $.each(data, function(index, cheque) {
                            $('#cheque_selection').show();
                            $('.cheque').append('<option value="' + cheque.id +
                                '" data-amount="' + cheque.amount + '">' + cheque
                                .chque_number + '</option>');

                            $('#amount').val(cheque.amount);
                        });

                        $('.hidesearch').select2({
                            minimumResultsForSearch: -1
                        });
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
        $(document).ready(function() {
            var oldPaymentMethod =
                '{{ old('payment_method', $payment->payment_type) }}'; // Get old value for payment method

            // Check if the old payment method is 'cheque' and show the div if true
            if (oldPaymentMethod === 'cheque') {
                $('#cheque_selection').show();
            } else if (oldPaymentMethod === 'bank_transfer') {
                $('#account_selection').show();
            }

            // Handle changes to the payment method select element
            $('#payment_method').change(function() {
                if ($(this).val() === 'cheque') {
                    $('#cheque_selection').show(); // Show div if cheque is selected
                } else {
                    $('#cheque_selection').hide(); // Hide div if not
                }
            });
        });
    </script>
@endpush
