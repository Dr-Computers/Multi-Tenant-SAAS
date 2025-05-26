{{ Form::model($expense, ['route' => ['expense.update', $expense->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-12 col-lg-12">
            {{ Form::label('title', __('Expense Title'), ['class' => 'form-label']) }}
            {{ Form::text('title', old('title', $expense->title), ['class' => 'form-control', 'placeholder' => __('Enter Expense Title')]) }}
        </div>
        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('expense_id', __('Expense Number'), ['class' => 'form-label']) }}
            <div class="input-group">
                <span class="input-group-text">
                    {{ expensePrefix() }}
                </span>
                {{ Form::text('expense_id', old('expense_id', $expense->expense_id), ['class' => 'form-control', 'placeholder' => __('Enter Expense Number')]) }}
            </div>
        </div>
        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('vendor', __('Vendor'), ['class' => 'form-label']) }}
            {{ Form::text('vendor', old('vendor', $expense->vendor), ['class' => 'form-control', 'placeholder' => __('Enter vendor name')]) }}
        </div>

        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('reference_no', __('Reference No'), ['class' => 'form-label']) }}
            {{ Form::text('reference_no', old('reference_no', $expense->reference_no), ['class' => 'form-control', 'placeholder' => __('Enter reference number')]) }}
        </div>

        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('expense_type', __('Expense Type'), ['class' => 'form-label']) }}
            {{ Form::select('expense_type', ['0' => 'Property', '10000' => 'Liability', '10001' => 'Tax'] + $types->toArray(), old('expense_type', $expense->expense_type), ['class' => 'form-control hidesearch', 'id' => 'expense_type', 'onchange' => 'toggleField()']) }}
        </div>

        <div class="form-group col-md-6 col-lg-6" id="propertyField"
            style="{{ old('expense_type', $expense->expense_type) == '0' ? 'display: block;' : 'display: none;' }}">
            {{ Form::label('property_id', __('Property'), ['class' => 'form-label']) }}
            {{ Form::select('property_id', $property, old('property_id', $expense->property_id), ['class' => 'form-control hidesearch', 'id' => 'property_id']) }}
        </div>

        <div id="liability_field" class="form-group col-md-6 col-lg-6" style="display: none;">
            {{ Form::label('liability_id', __('Liability'), ['class' => 'form-label']) }}
            {{ Form::select('liability_id', $liabilities->pluck('name', 'id'), null, ['class' => 'form-control hidesearch', 'id' => 'liability']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6" id="unitField"
            style="{{ old('expense_type', $expense->expense_type) == '0' ? 'display: block;' : 'display: none;' }}">
            {{ Form::label('unit_id', __('Unit'), ['class' => 'form-label']) }}
            <input type="hidden" id="edit_unit" value="{{ $expense->unit_id }}">
            <div class="unit_div">
                <select class="form-control hidesearch unit" id="unit_id" name="unit_id">
                    <option value="">{{ __('Select Unit') }}</option>
                </select>
            </div>
        </div>

        <div class="form-group col-md-6 col-lg-6" id="account_selection">
            {{ Form::label('account_id', __('Select Bank Account'), ['class' => 'form-label']) }}
            {{ Form::select('account_id', $accounts, old('account_id', $expense->bank_account_id), ['class' => 'form-control hidesearch', 'id' => 'account_id', 'onchange' => 'getAccountDetails()']) }}
        </div>

        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
            {{ Form::date('date', old('date', $expense->date), ['class' => 'form-control']) }}
        </div>

        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('tax_option', __('Tax Inclusion'), ['class' => 'form-label']) }}
            <div>
                <label>
                    <input type="radio" name="vat_included" value="included" onchange="calculateTax()"
                        {{ old('vat_included', $expense->vat_included) == 'included' ? 'checked' : '' }}>
                    {{ __('Tax Included') }}
                </label>
                <label style="margin-left: 15px;">
                    <input type="radio" name="vat_included" value="excluded" onchange="calculateTax()"
                        {{ old('vat_included', $expense->vat_included) == 'excluded' ? 'checked' : '' }}>
                    {{ __('Tax Excluded') }}
                </label>
            </div>
        </div>

        <!-- Amount -->
        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('base_amount', __('Amount'), ['class' => 'form-label']) }}
            {{ Form::number('base_amount', old('base_amount', $expense->base_amount), [
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
            {{ Form::text('vat_amount', old('vat_amount', $expense->vat_amount), ['class' => 'form-control', 'readonly', 'id' => 'tax_amount']) }}
        </div>

        <!-- Total Amount -->
        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('amount', __('Total Amount'), ['class' => 'form-label']) }}
            {{ Form::text('amount', old('amount', $expense->total_amount), ['class' => 'form-control', 'readonly', 'id' => 'total_amount']) }}
        </div>

        <div class="form-group col-md-12 col-lg-12">
            {{ Form::label('receipt', __('Receipt'), ['class' => 'form-label']) }}
            {{ Form::file('receipt', ['class' => 'form-control']) }}
        </div>

        <div class="form-group col-md-12 col-lg-12">
            {{ Form::label('notes', __('Notes'), ['class' => 'form-label']) }}
            {{ Form::textarea('notes', old('notes', $expense->notes), ['class' => 'form-control', 'rows' => 3]) }}
        </div>
    </div>
</div>

<div class="modal-footer">
    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{ Form::submit(__('Update'), ['class' => 'btn btn-primary btn-rounded']) }}
</div>

{{ Form::close() }}
@php
    $settings = settings();
    $taxRate = 0;

    if ($settings) {
        $taxTypeId = $settings['tax_type_id']; // Ensure this key exists in your settings array
        $taxType = \App\Models\TaxType::find($taxTypeId);
        $taxRate = $taxType ? $taxType->rate : 0; // Default to 0 if not found
    }
@endphp
<script>
    function toggleField() {
        var selectedValue = document.getElementById('expense_type').value;
        console.log("Selected value: " + selectedValue);

        var propertyField = document.getElementById('propertyField');
        var unitField = document.getElementById('unitField');

        if (selectedValue === '0') {
            propertyField.style.display = 'block';
            unitField.style.display = 'block';
        } else {
            propertyField.style.display = 'none';
            unitField.style.display = 'none';
        }
    }

    
    $(document).ready(function() {
        // Get the old expense type and set visibility for fields
        var oldType = '{{ old('expense_type', $expense->expense_type) }}';
        if (oldType == '0') {
            $('#propertyField').show();
            $('#unitField').show();
        } else {
            $('#propertyField').hide();
            $('#unitField').hide();
        }
        if (oldType == '10000') {
            $('#liability_field').show();
        } else {
            $('#liability_field').hide();
        }

        // Function to fetch units based on selected property
        function fetchUnits(property_id) {
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


                }
            });
        }

        // Trigger fetching units on page load if property_id is already selected
        var property_id = $('#property_id').val();
        if (property_id) {
            fetchUnits(property_id);
        }

        // Trigger fetching units on property change
        $('#property_id').on('change', function() {
            fetchUnits($(this).val());
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
