@extends('layouts.company')

@section('page-title')
    {{ __('Other Invoices') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Other Invoices') }}</li>
@endsection



@section('content')
    <div class="row">
        <form action="{{ route('company.finance.realestate.invoice-other.update', $invoice->id) }}" method="post"
            class="needs-validation" novalidate enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Important for update -->

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <h6 class="text-md fw-bold text-secondary text-sm">Invoice Details</h6>

                         
                            
                           <!-- Tenant Selection -->
<div class="form-group col-md-6 col-lg-4">
    <label for="tenant_id" class="form-label">Tenant <span class="text-danger">*</span></label>
    <select id="tenant_id" name="tenant_id" class="form-control hidesearch">
        <option value="">Select Tenant</option>
        <?php foreach ($tenants as $id => $name): ?>
            <option 
                value="<?= $id ?>" 
                <?= (old('tenant_id', $invoice->tenant_id ?? '') == $id) ? 'selected' : '' ?>>
                <?= htmlspecialchars($name) ?>
            </option>
        <?php endforeach; ?>
    </select>
    @error('tenant_id')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<!-- Invoice Number -->
<div class="form-group col-md-6 col-lg-4">
    <div class="form-group">
        <label for="invoice_id" class="form-label">
            {{ __('Invoice Number') }}
            <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text">
                {{ invoicePrefixOther() }}
            </span>
            <input 
                type="text" 
                name="invoice_id" 
                id="invoice_id" 
                class="form-control"
                placeholder="{{ __('Enter Invoice Number') }}"
                value="{{ old('invoice_id', $invoiceNumber ?? '') }}"
            >
        </div>
        @error('invoice_id')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<!-- Invoice Date -->
<div class="col-md-6 col-lg-4">
    <div class="form-group">
        <label class="form-label">Invoice Date <x-required /></label>
        <input 
            type="date" 
            name="end_date" 
            class="form-control" 
            value="{{ old('end_date', $invoice->end_date ?? date('Y-m-d')) }}"
            required
        >
        @error('end_date')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<!-- Notes -->
<div class="col-md-12">
    <div class="form-group">
        <label class="form-label">Notes</label>
        <textarea 
            name="notes" 
            class="form-control" 
            rows="2" 
            placeholder="Enter Notes"
        >{{ old('notes', $invoice->notes ?? '') }}</textarea>
        @error('notes')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>


                            <div class="col-md-12 mt-4">
                                <h6 class="text-md fw-bold text-secondary text-sm">Invoice Items</h6>
                                <div class="repeater" data-value='{!! json_encode($invoice->types) !!}'>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Invoice Types</h5>
                                        <button type="button" class="btn btn-primary btn-sm" data-repeater-create>
                                            <i class="ti-plus me-1"></i> Add Type
                                        </button>
                                    </div>


                                    <input type="hidden" name="tax_type" id="tax_type" value="included">

                                    <table class="table" data-repeater-list="types">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Type') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('VAT Inclusion') }}</th>
                                                <th class="vat-column">{{ __('VAT Amount') }}</th>
                                                <th>{{ __('Total Amount') }}</th>
                                                <th>{{ __('Description') }}</th>
                                                <th>#</th>
                                            </tr>

                                        </thead>
                                        <tbody data-repeater-item>
                                            <tr>
                                                {{ Form::hidden('id', null, ['class' => 'form-control type_id']) }}
                                                <td width="20%">
                                                    {{ Form::select('invoice_type', $types, null, ['class' => 'form-control hidesearch']) }}
                                                </td>
                                                <td>
                                                    {{ Form::number('amount', null, ['class' => 'form-control', 'step' => '0.01', 'oninput' => 'calculateVAT(this)', 'id' => 'total_amount']) }}
                                                </td>
                                                <td>
                                                    <input type="radio" name="vat_inclusion" value="included"
                                                        {{ old('vat_inclusion') == 'included' ? 'checked' : '' }}
                                                        onchange="calculateVAT(this.closest('tr'))">
                                                    {{ __('Included') }}
                                                    <input type="radio" name="vat_inclusion" value="excluded"
                                                        {{ old('vat_inclusion') == 'excluded' ? 'checked' : '' }}
                                                        onchange="calculateVAT(this.closest('tr'))">
                                                    {{ __('Excluded') }}
                                                </td>

                                                <!-- VAT Amount (Readonly) -->
                                                <td class="vat-column">
                                                    <input type="text" name="tax_amount" class="form-control"
                                                        value="{{ old('tax_amount', $type['tax_amount'] ?? '') }}"
                                                        readonly />
                                                </td>
                                                <td>
                                                    {{ Form::number('grand_amount', null, ['class' => 'form-control', 'step' => '0.01', 'id' => 'grand_amount']) }}
                                                </td>
                                                <td>
                                                    {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 1]) }}
                                                </td>
                                                <td>
                                                    <a class="text-danger" data-repeater-delete data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Detete') }}" href="#"> <i
                                                            data-feather="trash-2"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>

                                    </table>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer m-5">
                        <a href="{{ route('company.finance.realestate.invoices.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Invoice</button>
                    </div>
                </div>
            </div>
        </form>


    </div>
@endsection


@push('script-page')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>



    <script>
       const taxRate = {{ companyTaxRate() }}; // Ensures float, converts % to decimal 

        function calculateVAT(element) {
            // Get the closest row (assuming 'tr' is the row context or replace with proper container)
            const row = element.closest('tr') || element.closest('tbody');
          

            // Select the amount input dynamically based on partial match in the name
            const amountInput = row.querySelector('input[name*="[amount]"]');
           amountInput is null or defined

            if (!amountInput) {
                console.log("Amount input not found within this row.");
                return; // Exit if amountInput does not exist
            }

            const amount = parseFloat(amountInput.value) || 0;
           

            // Attempt to get the VAT inclusion selection
            const vatInclusion = row.querySelector('[name*="[vat_inclusion]"]:checked');
            if (!vatInclusion) {
                console.log("VAT inclusion option not found or not selected.");
                return;
            }

          

            // Proceed with VAT calculation logic

            let vatAmount = 0;
            if (vatInclusion.value === 'excluded') {
                vatAmount = amount * (taxRate / 100);
            }
         


            // Target the VAT display element in the current row
            // Find the VAT column and set the VAT amount

            row.querySelector('input[name*="[tax_amount]"]')
            const vatColumn = row.querySelector('input[name*="[tax_amount]"]');
          

            if (vatColumn) {
                vatColumn.value = vatAmount.toFixed(2); // Set the VAT amount in the input field
               
            } else {
                console.log("VAT column element not found in this row.");
            }

            // Optionally, calculate the total amount
            const totalAmountInput = row.querySelector('input[name*="[grand_amount]"]');
            if (totalAmountInput) {
                const totalAmount = amount + vatAmount;
                totalAmountInput.value = totalAmount.toFixed(2); // Set the grand total
               
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            // Initially Hide Invoice Period and Invoice Month
            // $('#invoice_period').hide();
            //  $('#invoice_month').hide();
            $(document).on('change', '#unit', function() {
                "use strict";
                var unit_id = $(this).val();
                var url = '{{ route('company.realestate.unit.rent_type', ':id') }}';
                url = url.replace(':id', unit_id);
                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        unit_id: unit_id,
                    },
                    contentType: false,
                    processData: false,
                    type: 'GET',
                    success: function(response) {
                        $('#invoice_month').hide();
                        $('#invoice_period').hide();
                        if (response == 'monthly') {
                            $('#invoice_month').show();
                        } else if (response == 'yearly') {
                            $('#invoice_period').show();
                        }
                    },
                });
            });
        });
    </script>
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

                    $.each(data.units, function(key, value) {
                        var oldUnit = '{{ old('unit_id') }}';
                        var isSelected = (key == oldUnit) ? 'selected' :
                            ''; // Check if it matches old value
                        $('.unit').append('<option value="' + key + '" ' +
                            isSelected + '>' + value + '</option>');
                    });

                    // $('.hidesearch').select2({
                    //     minimumResultsForSearch: -1
                    // });
                    if (data.invoice_prefix) {
                        $('#invoice_prefix').text(data.invoice_prefix);
                    }
                },

            });
        });

        $('#property_id').trigger('change');
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
                    // $('.hidesearch').select2({
                    //     minimumResultsForSearch: -1
                    // });
                    $(this).slideDown();

                },
                hide: function(deleteElement) {
                    console.log('Delete triggered'); // Add this
                    if (confirm('Are you sure you want to delete this element?')) {
                        var el = $(this).parent().parent();
                        var id = $(el.find('.type_id')).val();
                        $.ajax({
                            url: '{{ route('company.finance.realestate.invoice.type.destroy') }}',
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'id': id
                            },
                            cache: false,
                            success: function(data) {
                                $(this).slideUp(deleteElement);
                                $(this).remove();
                            },
                        });


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
@endpush
