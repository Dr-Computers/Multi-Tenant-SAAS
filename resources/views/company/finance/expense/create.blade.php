@can('create a expense')
    {{ Form::open(['route' => 'company.finance.expense.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group  col-md-12 col-lg-12">
                {{ Form::label('title', __('Expense Title'), ['class' => 'form-label']) }}
                {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => __('Enter Expense Title')]) }}
            </div>
            <div class="form-group col-md-6 col-lg-6">
                {{ Form::label('expense_id', __('Expense Number'), ['class' => 'form-label']) }}
                <div class="input-group">
                    <span class="input-group-text ">
                        {{ expensePrefix() }}
                    </span>
                    {{ Form::text('expense_id', $billNumber, ['class' => 'form-control', 'placeholder' => __('Enter Expense Number')]) }}
                </div>
            </div>
            <div class="form-group col-md-6 col-lg-6">
                {{ Form::label('reference_no', __('Reference No'), ['class' => 'form-label']) }}
                {{ Form::text('reference_no', null, ['class' => 'form-control', 'placeholder' => __('Enter reference number')]) }}
            </div>
            <div class="form-group col-md-6 col-lg-6">
                {{ Form::label('vendor', __('Vender'), ['class' => 'form-label']) }}
                {{ Form::text('vendor', null, ['class' => 'form-control', 'placeholder' => __('Enter vendor name')]) }}
            </div>


            {{-- <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('expense_type', __('Expense Type'), ['class' => 'form-label']) }}
            {{ Form::select('expense_type', ['0' => 'Property'] + $types->toArray(), null, ['class' => 'form-control hidesearch', 'id' => 'expense_type', 'onchange' => 'toggleFields()']) }}
        </div> --}}

            <div class="form-group col-md-6 col-lg-6">
                {{ Form::label('expense_type', __('Expense Type'), ['class' => 'form-label']) }}
                {{ Form::select('expense_type', ['0' => 'Property', '10000' => 'Liability', '10001' => 'Tax'] + $types->toArray(), null, ['class' => 'form-control hidesearch', 'id' => 'expense_type', 'onchange' => 'toggleFields()']) }}
            </div>

            <!-- Liability Field (Initially Hidden) -->
            <div id="liability_field" class="form-group col-md-6 col-lg-6" style="display: none;">
                {{ Form::label('liability_id', __('Liability'), ['class' => 'form-label']) }}
                {{ Form::select('liability_id', ['' => __('Select Liability')] + $liabilities->pluck('name', 'id')->toArray(), null, ['class' => 'form-control hidesearch', 'id' => 'liability']) }}
            </div>

            <div class="form-group col-md-6 col-lg-6" id="propertyField">
                {{ Form::label('property_id', __('Property'), ['class' => 'form-label']) }}
                {{ Form::select('property_id', $property, null, ['class' => 'form-control hidesearch', 'id' => 'property_id']) }}
            </div>

            <div class="form-group col-lg-6 col-md-6" id="unitField">
                {{ Form::label('unit_id', __('Unit'), ['class' => 'form-label']) }}
                <div class="unit_div">
                    <select class="form-control hidesearch unit" id="unit_id" name="unit_id">
                        <option value="">{{ __('Select Unit') }}</option>
                    </select>
                </div>
            </div>

            {{-- <script>
            function toggleFields() {
                var selectedValue = document.getElementById('expense_type').value;
                console.log("Selected value: " + selectedValue);  // Debug log
                
                var propertyField = document.getElementById('propertyField');
                var unitField = document.getElementById('unitField');
        
                // Check if the selected value matches 'Property'
                if (selectedValue === '0') {
                    propertyField.style.display = 'block';
                    unitField.style.display = 'block';
                } else {
                    propertyField.style.display = 'none';
                    unitField.style.display = 'none';
                }
            }
        </script> --}}


            <div class="form-group col-md-6 col-lg-6" id="account_selection">
                {{ Form::label('account_id', __('Select Bank Account'), ['class' => 'form-label', 'onchange' => 'getAccountDetails()']) }}
                <div class="cheque_div">
                    <select class="form-control hidesearch account" id="account" name="account_id">
                        <option value="">{{ __('Select Bank Account') }}</option>

                    </select>
                </div>
            </div>

            <div class="form-group  col-md-6 col-lg-6">
                {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
                {{ Form::date('date', null, ['class' => 'form-control']) }}
            </div>
            {{-- <div class="form-group  col-md-6 col-lg-6">
            {{Form::label('amount',__('Amount'),array('class'=>'form-label'))}}
            {{Form::number('amount',null,array('class'=>'form-control','placeholder'=>__('Enter Expense Amount'),'step' => '0.01'))}}
        </div> --}}
            <div class="form-group col-md-6 col-lg-6">
                {{ Form::label('vat_included', __('Tax Inclusion'), ['class' => 'form-label']) }}
                <div>
                    <label>
                        <input type="radio" name="vat_included" value="included" onchange="calculateTax()" checked>
                        {{ __('Tax Included') }}
                    </label>
                    <label style="margin-left: 15px;">
                        <input type="radio" name="vat_included" value="excluded" onchange="calculateTax()">
                        {{ __('Tax Excluded') }}
                    </label>
                </div>
            </div>

            <!-- Amount -->
            <div class="form-group col-md-6 col-lg-6">
                {{ Form::label('base_amount', __('Amount'), ['class' => 'form-label']) }}
                {{ Form::number('base_amount', null, [
                    'class' => 'form-control',
                    'placeholder' => __('Enter Expense Amount'),
                    'step' => '0.01',
                    'oninput' => 'calculateTax()',
                    'id' => 'amount',
                ]) }}
            </div>

            <!-- Tax Amount -->
            <div class="form-group col-md-6 col-lg-6">
                {{ Form::label('vat_amount', __('Tax Amount'), ['class' => 'form-label']) }}
                {{ Form::text('vat_amount', null, ['class' => 'form-control', 'readonly', 'id' => 'tax_amount']) }}
            </div>

            <!-- Total Amount -->
            <div class="form-group col-md-6 col-lg-6">
                {{ Form::label('amount', __('Total Amount'), ['class' => 'form-label']) }}
                {{ Form::text('amount', null, ['class' => 'form-control', 'readonly', 'id' => 'total_amount']) }}
            </div>
            <div class="form-group  col-md-12 col-lg-12">
                {{ Form::label('receipt', __('Receipt'), ['class' => 'form-label']) }}
                {{ Form::file('receipt', ['class' => 'form-control']) }}
            </div>
            <div class="form-group  col-md-12 col-lg-12">
                {{ Form::label('notes', __('Notes'), ['class' => 'form-label']) }}
                {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 3]) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('Close') }}</button>
        {{ Form::submit(__('Create'), ['class' => 'btn btn-primary btn-rounded']) }}
    </div>
    {{ Form::close() }}
@endcan
@php
    $settings = App\Models\Utility::settings();
    $taxRate = 5;

    if ($settings) {
        $taxTypeId = $settings['tax_type_id'] ?? ''; // Ensure this key exists in your settings array
        $taxRate = 5; // Default to 0 if not found
    }
@endphp
<script>
    $('#property_id').on('change', function() {
        "use strict";

        var property_id = $(this).val();
        var url = '{{ route('company.realestate.maintenance-requests.units', ':id') }}';
        url = url.replace(':id', property_id);

        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            success: function(data) {
                $('.unit').empty();
                var unitSelect =
                    `<select class="form-control hidesearch unit" id="unit_id" name="unit_id">
                        <option value="">Select Unit</option>
                    </select>`;
                $('.unit_div').html(unitSelect);

                // Loop through response data (which is an array of unit objects)
                $.each(data, function(index, unit) {
                    $('.unit').append('<option value="' + unit.id + '">' + unit.name +
                        '</option>');
                });

                $('.hidesearch').select2({
                    minimumResultsForSearch: -1
                });
            },
            error: function() {
                alert('Failed to fetch units');
            }
        });
    });
</script>

<script>
    const taxRate = @json($taxRate);
    console.log(taxRate);

    function calculateTax() {

        const amount = parseFloat(document.getElementById('amount').value) || 0;
        const taxOption = document.querySelector('input[name="vat_included"]:checked').value;
        const taxAmountField = document.getElementById('tax_amount');
        const totalAmountField = document.getElementById('total_amount');

        let taxAmount = 0;
        let totalAmount = 0;

        if (taxOption === 'included') {
            taxAmount = 0;
            console.log("tax amount" + taxAmount);

            totalAmount = amount;
            console.log("total" + totalAmount);

        } else if (taxOption === 'excluded') {
            amount * (taxRate / 100);
            taxAmount = amount * (taxRate / 100);
            totalAmount = amount + taxAmount;
        }

        taxAmountField.value = taxAmount.toFixed(2);
        totalAmountField.value = totalAmount.toFixed(2);
    }
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

                    $.each(data, function(index, unit) {
                        $('.account').append('<option value="' + account.id + '">' +
                            account.holder_name + '</option>');
                    });

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
{{--    
        <script>
            function toggleFields() {
                // Get the selected expense type
                var expenseType = document.getElementById('expense_type').value;
                console.log(expenseType);

                // If "Liability" is selected, show the liability field
                if (expenseType === 'liability') {
                    document.getElementById('liability_field').style.display = 'block';
                } else {
                    document.getElementById('liability_field').style.display = 'none';
                }
            }
        </script> --}}
<script>
    function toggleFields() {
        // Get the selected expense type
        var selectedValue = document.getElementById('expense_type').value;
        console.log("Selected value: " + selectedValue); // Debug log

        // Get the field elements
        var propertyField = document.getElementById('propertyField');
        var unitField = document.getElementById('unitField');
        var liabilityField = document.getElementById('liability_field');

        // Check if the selected value is '0' for Property
        if (selectedValue === '0') {
            propertyField.style.display = 'block';
            unitField.style.display = 'block';
            liabilityField.style.display = 'none'; // Hide liability field when Property is selected
        }
        // Check if the selected value is 'liability'
        else if (selectedValue === '10000') {

            liabilityField.style.display = 'block';
            propertyField.style.display = 'none'; // Hide property fields when Liability is selected
            unitField.style.display = 'none'; // Hide unit field when Liability is selected
        }
        // If neither Property nor Liability is selected, hide all additional fields
        else {
            propertyField.style.display = 'none';
            unitField.style.display = 'none';
            liabilityField.style.display = 'none';
        }
    }
</script>
