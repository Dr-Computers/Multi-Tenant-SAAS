@extends('layouts.company')

@section('page-title')
    {{ __('Invoices') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Invoices') }}</li>
@endsection

@section('action-btn')
    @can('create a invoice')
        <div class="d-flex">
            <a href="{{ route('company.finance.realestate.invoices.index') }}" title="{{ __('Back to invoice') }}"
                class="btn btn-sm btn-primary">
                <i class="ti ti-arrow-left"> Back</i>
            </a>
        </div>
    @endcan
@endsection

@section('content')
    @can('create a invoice')
        <div class="row">
            <form action="{{ route('company.finance.realestate.invoices.store') }}" method="post" class="needs-validation"
                novalidate enctype="multipart/form-data">
                @csrf
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
                                        <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}"
                                            required>
                                        @error('end_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">Payment For</label>
                                    <select name="invoice_purpose" class="form-control hidesearch">
                                        @foreach ($types as $key => $value)
                                            <option value="{{ $value }}">{{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <h6 class="text-md fw-bold text-secondary text-sm">Invoice To</h6>
                                <div class=" my-3 d-flex gap-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" checked type="radio" name="invoice_type"
                                            id="normalRadio" value="normal">
                                        <label class="form-check-label" for="normalRadio">Normal</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="invoice_type" id="ownerRadio"
                                            value="owner">
                                        <label class="form-check-label" for="ownerRadio">Owner</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="invoice_type" id="tenantRadio"
                                            value="tenant">
                                        <label class="form-check-label" for="tenantRadio">Tenant</label>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    {{-- //normal invoice add invoice to details --}}
                                    <div class="normal-section mb-3 col-lg-6">
                                        <label class="mb-1">Invoice To</label>
                                        <textarea class="form-control" rows="5" name="inovice_normal"></textarea>
                                    </div>
                                </div>

                                {{-- //property invoice add invoice to details --}}
                                <div class="owner-section d-none mb-3 col-md-6 col-lg-4">
                                    {{-- Tenant Selection --}}
                                    <div class="form-group ">
                                        <label for="owner_id" class="form-label">Owner <span
                                                class="text-danger">*</span></label>
                                        <select id="owner_id" name="inovice_owner"
                                            class="form-control hidesearch text-capitalize">
                                            <option value="">{{ __('Select Owner') }}</option>
                                            @foreach ($owners as $id => $owner)
                                                <option class="text-capitalize" value="{{ $owner->id }}">
                                                    {{ htmlspecialchars($owner->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                {{-- //tenant invoice add invoice to details --}}
                                <div class="tenant-section d-none mb-3 col-md-6 col-lg-4">
                                    {{-- Tenant Selection --}}
                                    <div class="form-group">
                                        <label for="tenant_id" class="form-label">Tenant <span
                                                class="text-danger">*</span></label>
                                        <select id="tenant_id" name="inovice_tenant"
                                            class="form-control hidesearch text-capitalize">
                                            <option value="">{{ __('Select Tenant') }}</option>
                                            @foreach ($tenants as $id => $tenant)
                                                <option class="text-capitalize" value="{{ $tenant->id }}">
                                                    {{ htmlspecialchars($tenant->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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

                                <!-- Notes -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Notes</label>
                                        <textarea name="notes" class="form-control" rows="2" placeholder="Enter Notes"></textarea>
                                        @error('notes')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Invoice Items Repeater -->
                                <div class="col-md-12 mt-4">
                                    <h6 class="text-md fw-bold text-secondary text-sm">Invoice Items</h6>
                                    <div class="repeater">


                                        <table class="table" data-repeater-list="types">
                                            <thead>
                                                <tr>
                                                    <th>Description</th>
                                                    <th>Amount</th>
                                                    <th>VAT Inclusion</th>
                                                    <th>VAT Amount</th>
                                                    <th>Total Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr data-repeater-item>
                                                    <td width="20%">
                                                        <textarea name="description" class="form-control"></textarea>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="amount" class="form-control"
                                                            step="0.01" oninput="calculateVAT(this)">
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="vat_inclusion" value="included"
                                                                onchange="calculateVAT(this)" data-vat="included">
                                                            <label class="form-check-label">Included</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="vat_inclusion" value="excluded" checked
                                                                onchange="calculateVAT(this)" data-vat="excluded">
                                                            <label class="form-check-label">Excluded</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="vat_amount" class="form-control"
                                                            readonly>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="grand_amount" class="form-control"
                                                            step="0.01" readonly>
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
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <button type="button" class="btn btn-primary btn-sm" data-repeater-create>
                                                <i class="ti ti-plus me-1"></i> Add Field
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
                            <button type="submit" class="btn btn-primary ">Create Invoice</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan
@endsection


@push('script-page')
    <!-- Include repeater.js and other necessary scripts -->
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const invoiceRadios = document.querySelectorAll("input[name='invoice_type']");

            function toggleSections(type) {
                document.querySelector(".normal-section").classList.add("d-none");
                document.querySelector(".owner-section").classList.add("d-none");
                document.querySelector(".tenant-section").classList.add("d-none");
                document.querySelector(".owner-tenant-section").classList.add("d-none");

                if (type === "normal") {
                    document.querySelector(".normal-section").classList.remove("d-none");
                } else if (type === "owner") {
                    document.querySelector(".owner-section").classList.remove("d-none");
                    document.querySelector(".owner-tenant-section").classList.remove("d-none");
                } else if (type === "tenant") {
                    document.querySelector(".tenant-section").classList.remove("d-none");
                    document.querySelector(".owner-tenant-section").classList.remove("d-none");
                }
            }

            invoiceRadios.forEach((radio) => {
                radio.addEventListener("change", function() {
                    toggleSections(this.value);
                });
            });

            // Trigger initial state
            const checkedRadio = document.querySelector("input[name='invoice_type']:checked");
            if (checkedRadio) toggleSections(checkedRadio.value);
        });

        $('#owner_id').on('change', function() {
            let ownerId = $(this).val();

            $.get(`/company/ajax/owner-properties/${ownerId}`, function(data) {
                var $propertySelect = $('.properties');
                $propertySelect.empty().append('<option value="">Select Property</option>');

                $.each(data, function(index, property) {
                    $propertySelect.append('<option value="' + property.id + '">' + property.name +
                        '</option>');
                });
                $('#unit').empty();
            });
        });


        $('#tenant_id').on('change', function() {
            let ownerId = $(this).val();
            $.get(`/company/ajax/tenant-properties/${ownerId}`, function(data) {
                var $propertySelect = $('.properties');
                $('#property_id').empty().append('<option value="">Select Property</option>');
                $.each(data, function(index, property) {
                    $propertySelect.append('<option value="' + property.id + '">' + property.name +
                        '</option>');
                });
                $('#unit').empty();
            });
        });

        $('#property_id').on('change', function() {
            let propertyId = $(this).val();
            $.get(`/company/ajax/property-units/${propertyId}`, function(data) {
                $('#unit').empty().append('<option value="">Select Unit</option>');

                $.each(data, function(index, unit) {
                    $('#unit').append('<option value="' + unit.id + '">' + unit.name +
                        '</option>');
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.repeater').repeater({
                show: function() {
                    $(this).slideDown();
                    // Fix: set "excluded" as default checked manually
                      $(this).find('input[type="radio"][value="excluded"]').prop('checked', true);

                },
                hide: function(deleteElement) {
                    // $(this).slideUp(deleteElement);
                    $(this).slideUp(deleteElement, function() {
                        calculateInvoiceSummary();
                    });
                    $(this).remove();

                }
            });

            // Initialize other scripts as needed
        });

        function calculateVAT(element) {
            console.log("called");

            const row = $(element).closest('tr');
            const amount = parseFloat(row.find('[name$="[amount]"]').val()) || 0;
            const vatIncluded = row.find('[name$="[vat_inclusion]"][value="included"]').is(':checked');

            const vatRate = 0.05; // 5% VAT
            let vatAmount, grandAmount;

            if (vatIncluded) {
                console.log("inc");
                vatAmount = 0;
                grandAmount = amount + vatAmount;
            } else {
                console.log("exc");
                vatAmount = amount * vatRate;;
                grandAmount = amount + vatAmount;
            }

            row.find('[name$="[vat_amount]"]').val(vatAmount.toFixed(2));
            row.find('[name$="[grand_amount]"]').val(grandAmount.toFixed(2));
            calculateInvoiceSummary();
        }

        function calculateInvoiceSummary() {
            let subtotal = 0;
            let totalVAT = 0;

            $('table tbody tr').each(function() {
                const amount = parseFloat($(this).find('[name$="[amount]"]').val()) || 0;
                const vatAmount = parseFloat($(this).find('[name$="[vat_amount]"]').val()) || 0;

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
        $(document).ready(function() {
            // Initially Hide Invoice Period and Invoice Month 


            $('#invoice_period').hide();
            $('#invoice_month').hide();

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

                        // var unit =
                        //     `<select class="form-control hidesearch unit" id="unit" name="unit_id"></select>`;
                        // $('.unit_div').html(unit);

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



            // $('#property_id').on('change', function() {
            //     console.log("called function");

            //     "use strict";
            //     var property_id = $(this).val();
            //     var url = '{{ route('company.realestate.property.unit', ':id') }}';
            //     url = url.replace(':id', property_id);

            //     $.ajax({
            //         url: url,
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         data: {
            //             property_id: property_id,
            //         },
            //         contentType: false,
            //         processData: false,
            //         type: 'GET',
            //         success: function(data) {
            //             console.log(data); // Debugging: Check the full response

            //             $('.units').empty();

            //             //         var unitDropdown = `
        //         // <select class="form-control hidesearch unit" id="unit" name="unit_id">
        //         // </select>`;
            //             //         $('.unit_div').html(unitDropdown);

            //             $.each(data.units, function(key, value) {
            //                 var oldUnit = '{{ old('unit_id') }}';
            //                 var isSelected = (key == oldUnit) ? 'selected' : '';
            //                 $('#unit').append('<option value="' + key + '" ' +
            //                     isSelected + '>' + value + '</option>');
            //             });

            //             console.log("Unit dropdown updated.");

            //             // Update Invoice Prefix and Number
            //             if (data.invoice_prefix) {
            //                 $('#invoice_prefix').text(data.invoice_prefix);
            //             }
            //             if (data.invoice_number) {
            //                 $('#invoice_id').val(data.invoice_number);
            //             }

            //             // Initially trigger rent type logic for the default selected unit
            //             var selectedUnit = $('#unit').val();
            //             // fetchAndDisplayRentType(selectedUnit);

            //             // When the unit dropdown changes, re-check rent type
            //             $('#units').on('change', function() {
            //                 var newSelectedUnit = $(this).val();
            //             });
            //         },
            //     });
            // });

            // Function to fetch rent type and update UI
            // function fetchAndDisplayRentType(unitId) {


            //     $('#invoice_period').hide();
            //     var unitUrl = '{{ route('company.realestate.unit.rent_type', ':id') }}';
            //     unitUrl = unitUrl.replace(':id', unitId);

            //     $.ajax({
            //         url: unitUrl,
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         data: {
            //             unit_id: unitId,
            //         },
            //         contentType: false,
            //         processData: false,
            //         type: 'GET',
            //         success: function(response) {
            //             // First, hide both fields
            //             $('#invoice_month_block').hide();
            //             $('#invoice_period_block').hide();

            //             if (response === 'monthly') {
            //                 $('#invoice_month_block').show();
            //                 console.log("Rent type: monthly");
            //             } else if (response === 'yearly') {
            //                 $('#invoice_period_block').show();
            //                 console.log("Rent type: yearly");
            //             } else {
            //                 console.log("Rent type: not found");
            //             }
            //         },
            //     });
            // }

            // $(document).on('change', '#units', function() {
            //     "use strict";
            //     var unit_id = $(this).val();
            //     var url = '{{ route('company.realestate.unit.rent_type', ':id') }}';
            //     url = url.replace(':id', unit_id);
            //     $.ajax({
            //         url: url,
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         data: {
            //             unit_id: unit_id,
            //         },
            //         contentType: false,
            //         processData: false,
            //         type: 'GET',
            //         success: function(response) {
            //             $('#invoice_month_block').hide();
            //             $('#invoice_period_block').hide();
            //             if (response == 'monthly') {
            //                 $('#invoice_month_block').show();
            //             } else if (response == 'yearly') {
            //                 $('#invoice_period_block').show();
            //             }
            //         },
            //     });
            // });
        });
    </script>
@endpush
