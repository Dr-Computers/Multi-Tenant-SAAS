
<div class="row">


    {{ Form::open(['url' => route('company.finance.realestate.invoice.payments.store', ['invoice_id' => $invoice->id]), 'method' => 'post', 'id' => 'invoice_form', 'enctype' => 'multipart/form-data', 'files' => true]) }}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="info-group">
                        <div class="row">
                            {{-- <input type="hidden" name='choose_type' value="property" id='choose_type'> --}}
                            {{-- <div class="form-group col-md-6 col-lg-4">
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
                                </div> --}}

                            <div class="form-group col-md-6 col-lg-4">
                                {{ Form::label('payment_date', __('Payment Date'), ['class' => 'form-label']) }}
                                {{ Form::date('payment_date', date('Y-m-d'), ['class' => 'form-control']) }}
                            </div>

                            <div class="form-group col-md-6 col-lg-4">
                                {{ Form::label('payment_method', __('Payment Type'), ['class' => 'form-label']) }}
                                {{ Form::select('payment_method', ['cheque' => 'Cheque', 'cash' => 'Cash', 'bank_transfer' => 'Bank Transfer'], null, ['class' => 'form-control hidesearch', 'id' => 'payment_method']) }}
                            </div>

                            <!-- Cheque Selection (hidden by default) -->
                            {{-- <div class="form-group col-md-6 col-lg-4" id="cheque_selection" style="display: none;">
                                {{ Form::label('cheque_id', __('Select Cheque'), ['class' => 'form-label']) }}
                                <div class="cheque_div">
                                    <select class="form-control hidesearch cheque" id="cheque" name="cheque_id">
                                        <option value="">{{ __('Select Cheque') }}</option>
                                    </select>
                                </div>
                            </div> --}}



                            <div class="form-group col-md-6 col-lg-4" id="account_selection">
                                {{ Form::label('account_id', __('Select Bank Account'), ['class' => 'form-label']) }}
                                <div class="cheque_div">
                                    <select class="form-control hidesearch account" id="account" name="account_id">
                                        <option value="">{{ __('Select Bank Account') }}</option>
                                        @foreach($bankAccounts as $bankAccount)
                                            <option value="{{ $bankAccount->id }}">{{ $bankAccount->holder_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-lg-4">
                                {{ Form::label('reference_no', __('Reference Number'), ['class' => 'form-label']) }}
                                {{ Form::text('reference_no', null, ['class' => 'form-control', 'autocomplete' => 'off' , 'placeholder' => __('Enter Reference Number')]) }}
                            </div>

                            <div class="form-group col-md-6 col-lg-4">
                                {{ Form::label('payment_for', __('Payment For'), ['class' => 'form-label']) }}
                                {{ Form::select(
                                    'payment_for',
                                    [
                                        'full_payment' => __('Full Payment'),
                                        'partial' => __('Partial'),
                                    ],
                                    null,
                                    ['class' => 'form-control hidesearch', 'id' => 'payment_for'],
                                ) }}
                            </div>

                            <input type="hidden" id="due_amount" value="">
                            <div class="form-group col-md-6 col-lg-4">
                                {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}
                                {{ Form::number('amount', $invoice->grand_total, ['class' => 'form-control', 'autocomplete' => 'off' , 'id' => 'amount', 'step' => '0.01', 'placeholder' => __('Enter Amount')]) }}
                            </div>


                            <div class="form-group col-md-6 col-lg-12">
                                {{ Form::label('notes', __('Notes'), ['class' => 'form-label']) }}
                                {{ Form::textarea('notes', null, ['class' => 'form-control', 'autocomplete' => 'off', 'rows' => 2, 'placeholder' => __('Enter Notes')]) }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div id="hidden-check-details"></div>
        <div class="col-lg-12">
            <div class="group-button text-center">
                {{ Form::submit(__('Create'), ['class' => 'btn btn-primary btn-rounded', 'id' => 'invoice-submit']) }}
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>



<!-- Include repeater.js and other necessary scripts -->
@push('script-page')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>

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
