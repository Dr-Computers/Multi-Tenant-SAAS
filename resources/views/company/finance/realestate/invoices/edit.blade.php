@extends('layouts.company')

@section('page-title')
    {{ __('Invoices') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Invoices') }}</li>
@endsection



@section('content')
    @can('edit a invoice')
        <div class="row">
            <form action="{{ route('company.finance.realestate.invoices.update', $invoice->id) }}" method="post"
                class="needs-validation" novalidate enctype="multipart/form-data">
                @csrf
                @method('PUT') <!-- Important for update -->

                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">



                                <div class="form-group col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="invoice_id" class="form-label">
                                            {{ __('Invoice Number') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="invoice_prefix">
                                                <!-- You can display prefix like this -->
                                                {{ invoicePrefix() ?? '#INVOICE' }}
                                            </span>
                                            <input type="text" name="invoice_id" id="invoice_id" class="form-control"
                                                readonly placeholder="{{ __('Enter Invoice Number') }}"
                                                value="{{ old('invoice_id') ?? $invoiceNumber }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Invoice Date -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Invoice Due Date <x-required /></label>

                                        <input type="date" name="end_date" class="form-control"
                                            value="{{ $invoice->end_date ?? date('Y-m-d') }}" required>
                                        @error('end_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">Payment For</label>
                                    <select name="invoice_purpose" class="form-control hidesearch">
                                        @foreach ($types as $key => $value)
                                            <option value="{{ $value }}"
                                                {{ $invoice->invoice_purpose == $value ? 'selected' : '' }}>
                                                {{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <h6 class="text-md fw-bold text-secondary text-sm">Invoice To</h6>
                                <div class=" my-3 d-flex gap-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" disabled
                                            {{ $invoice->invoice_type_to == 'normal' ? 'checked' : '' }} type="radio"
                                            name="invoice_type" id="normalRadio" value="normal">
                                        <label class="form-check-label" for="normalRadio">Normal</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" disabled
                                            {{ $invoice->invoice_type_to == 'owner' ? 'checked' : '' }} type="radio"
                                            name="invoice_type" id="ownerRadio" value="owner">
                                        <label class="form-check-label" for="ownerRadio">Owner</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" disabled
                                            {{ $invoice->invoice_type_to == 'tenant' ? 'checked' : '' }} type="radio"
                                            name="invoice_type" id="tenantRadio" value="tenant">
                                        <label class="form-check-label" for="tenantRadio">Tenant</label>
                                    </div>
                                </div>
                                @if ($invoice->invoice_type_to == 'normal')
                                    <div class="col-lg-12">
                                        {{-- //normal invoice add invoice to details --}}
                                        <div class="normal-section mb-3 col-lg-6">
                                            <label class="mb-1">Invoice To</label>
                                            <textarea class="form-control" rows="5" name="inovice_normal">{{ $invoice->invoice_to }}</textarea>
                                        </div>
                                    </div>
                                @elseif($invoice->invoice_type_to == 'owner')
                                    {{-- //property invoice add invoice to details --}}
                                    <div class="owner-section mb-3 col-md-6 col-lg-4">
                                        {{-- Tenant Selection --}}
                                        <div class="form-group ">
                                            <label for="owner_id" class="form-label">Owner <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" disabled
                                                value="{{ $invoice->owner ? $invoice->owner->name : '' }}" readonly>
                                        </div>

                                    </div>
                                @elseif($invoice->invoice_type_to == 'tenant')
                                    {{-- //tenant invoice add invoice to details --}}
                                    <div class="tenant-section mb-3 col-md-6 col-lg-4">
                                        {{-- Tenant Selection --}}
                                        <div class="form-group">
                                            <label for="tenant_id" class="form-label">Tenant <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" disabled
                                                value="{{ $invoice->tenant ? $invoice->tenant->name : '' }}" readonly>

                                        </div>
                                    </div>
                                @endif
                                @if ($invoice->invoice_type_to == 'owner' || $invoice->invoice_type_to == 'tenant')
                                    <div class="owner-tenant-section d-none row col-md-6 col-lg-8">
                                        <!-- Property Selection -->
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label">Property </label>
                                                <select name="property_id" class="form-control hidesearch properties"
                                                    id='property_id'>
                                                    <option value="">{{ __('Select Property') }}</option>
                                                </select>
                                                @error('property_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- Unit Selection -->
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label">Unit </label>

                                                <select class="form-control hidesearch units" id="unit" name="unit_id">
                                                    <option value="">{{ __('Select Unit') }}</option>
                                                </select>

                                                @error('unit_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Notes</label>
                                        <textarea name="notes" class="form-control" rows="2" placeholder="Enter Notes">{{ old('notes', $invoice->notes) }}</textarea>
                                        @error('notes')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-md-12 mt-4">
                                    <h6 class="text-md fw-bold text-secondary text-sm">Invoice Items</h6>
                                    <div class="repeater" data-value='{!! json_encode($invoice->types) !!}'>


                                        <table class="table" data-repeater-list="types">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Description') }}</th>
                                                    <th>{{ __('Amount') }}</th>
                                                    <th>{{ __('VAT Inclusion') }}</th>
                                                    <th class="vat-column">{{ __('VAT Amount') }}</th>
                                                    <th>{{ __('Total Amount') }}</th>
                                                    <th>#</th>
                                                </tr>

                                            </thead>
                                            <tbody data-repeater-item>
                                                <tr>
                                                    {{ Form::hidden('id', null, ['class' => 'form-control type_id']) }}

                                                    <td width="20%">
                                                        {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 1]) }}
                                                    </td>
                                                    <td>
                                                        {{ Form::number('amount', null, [
                                                            'class' => 'form-control',
                                                            'step' => '0.01',
                                                            'oninput' => 'calculateVAT(this)',
                                                        ]) }}


                                                    </td>
                                                    <td>
                                                        {{ Form::radio('vat_inclusion', 'included', null, ['onchange' => 'calculateVAT(this)']) }}
                                                        {{ __('Included') }}
                                                        {{ Form::radio('vat_inclusion', 'excluded', null, ['onchange' => 'calculateVAT(this)']) }}
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
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            data-repeater-delete>
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>

                                        </table>
                                        <div class="d-flex justify-content-end align-items-center my-3">
                                            <button type="button" class="btn btn-primary btn-sm" data-repeater-create>
                                                <i class="ti ti-plus me-1"></i> Add Type
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- Totals Section -->
                            <div class="mt-4 d-flex flex-column justify-content-end gap-1 align-items-end mb-3">
                                <div class="col-lg-3">
                                    <label class="form-label">Subtotal</label>
                                    <input type="text" value="{{ isset($invoice) ? $invoice->sub_total : '' }}"
                                        class="form-control" id="subtotal" readonly>
                                </div>

                                <div class="col-lg-3">
                                    <label class="form-label">Total VAT</label>
                                    <input type="text" value="{{ isset($invoice) ? $invoice->total_tax : '' }}"
                                        class="form-control" id="total_vat" readonly>
                                </div>

                                <div class="col-lg-3">
                                    <label class="form-label">Discount Type/Reason</label>
                                    <input type="text" value="{{ isset($invoice) ? $invoice->discount_reason : '' }}"
                                        class="form-control" name="discount_reason" id="discount_reason"
                                        value="{{ old('discount_reason', $invoice->discount_reason ?? '') }}">
                                </div>

                                <div class="col-lg-3">
                                    <label class="form-label">Discount Amount</label>
                                    <input type="number" value="{{ isset($invoice) ? $invoice->discount_amount : '' }}"
                                        class="form-control" name="discount_amount" id="discount_amount" step="0.01"
                                        value="{{ old('discount_amount', $invoice->discount_amount ?? 0) }}"
                                        oninput="calculateFinalTotal()">
                                </div>

                                <div class="col-lg-3">
                                    <label class="form-label fw-bold">Grand Total</label>
                                    <input type="text" value="{{ isset($invoice) ? $invoice->grand_total : '' }}"
                                        class="form-control fw-bold" id="grand_total" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="text-center m-5">
                            <button type="submit" class="btn btn-primary">Update Invoice</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan
@endsection


@push('script-page')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>

    <script>
        const taxRate = 0.05;

        function calculateVAT(element) {
            // Get the closest row (assuming 'tr' is the row context or replace with proper container)
            const row = element.closest('tr') || element.closest('tbody');
            // Select the amount input dynamically based on partial match in the name
            const amountInput = row.querySelector('input[name*="[amount]"]');
            if (!amountInput) {
                console.log("Amount input not found within this row.");
                return; // Exit if amountInput does not exist
            }

            const amount = parseFloat(amountInput.value) || 0;
            // Attempt to get the VAT inclusion selection
            // const vatInclusion = row.querySelector('[name*="[vat_inclusion]"]:checked');
            const vatInclusion = row.querySelector('input[type="radio"]:checked');

            if (!vatInclusion) {
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
            }

            // Optionally, calculate the total amount
            const totalAmountInput = row.querySelector('input[name*="[grand_amount]"]');
            if (totalAmountInput) {
                const totalAmount = amount + vatAmount;
                totalAmountInput.value = totalAmount.toFixed(2); // Set the grand total
            }

            calculateInvoiceSummary();
        }

        function calculateInvoiceSummary() {
            let subtotal = 0;
            let totalVAT = 0;

            $('table tbody tr').each(function() {
                const amount = parseFloat($(this).find('[name$="[amount]"]').val()) || 0;
                const vatAmount = parseFloat($(this).find('[name$="[tax_amount]"]').val()) || 0;

                subtotal += amount;
                totalVAT += vatAmount;
            });

            $('#subtotal').val(subtotal.toFixed(2));
            $('#total_vat').val(totalVAT.toFixed(2));

            calculateFinalTotal();
        }

        function calculateFinalTotal() {
            const subtotal = parseFloat($('#subtotal').val()) || 0;
            const totalVAT = parseFloat($('#total_vat').val()) || 0;
            const discount = parseFloat($('#discount_amount').val()) || 0;

            const grandTotal = subtotal + totalVAT - discount;
            $('#grand_total').val(grandTotal.toFixed(2));
        }
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
                    // Fix: set "excluded" as default checked manually
                    $(this).find('input[type="radio"][value="excluded"]').prop('checked', true);


                },
                hide: function(deleteElement) {
                    console.log('Delete triggered'); // Add this
                    if (confirm('Are you sure you want to delete this element?')) {

                        $(this).slideUp(deleteElement, function() {
                            calculateInvoiceSummary();
                        });
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
                setTimeout(() => {
                    calculateInvoiceSummary(); // ✅ recalculate after load
                }, 100);
            }
        }
    </script>
@endpush
