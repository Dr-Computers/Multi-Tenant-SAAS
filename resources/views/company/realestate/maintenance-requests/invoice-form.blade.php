{{-- @canany(['create a invoice', 'edit a invoice'])
    <div class="row">
        <form action="{{ route('company.realestate.maintenance-requests.store-invoice', $Mrequest->id) }}" method="post"
            class="needs-validation" novalidate enctype="multipart/form-data">
            @csrf
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6 col-lg-6">
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
                                            placeholder="{{ __('Enter Invoice Number') }}"
                                            value="{{ old('invoice_id', $invoiceNumber) }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Invoice Date -->
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">Invoice Date <x-required /></label>
                                    <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}"
                                        required>
                                    @error('end_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
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
                                                <th>Type</th>
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
                                                    <input name="invoice_type" class="form-control hidesearch">
                                                </td>

                                                <td>
                                                    <textarea name="description" class="form-control" rows="1"></textarea>
                                                </td>
                                                <td>
                                                    <input type="number" name="amount" class="form-control" step="0.01"
                                                        oninput="calculateVAT(this)">
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="vat_inclusion"
                                                            value="included" onchange="calculateVAT(this)">
                                                        <label class="form-check-label">Included</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="vat_inclusion"
                                                            value="excluded" checked onchange="calculateVAT(this)">
                                                        <label class="form-check-label">Excluded</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" name="vat_amount" class="form-control" readonly>
                                                </td>
                                                <td>
                                                    <input type="number" name="grand_amount" class="form-control"
                                                        step="0.01" readonly>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-repeater-delete>
                                                        <i class="ti ti-x"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end mt-4 align-items-center mb-3">
                                        <button type="button" class="btn btn-primary btn-sm" data-repeater-create>
                                            <i class="ti ti-plus me-1"></i> Add field
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class=" mt-4 d-flex flex-column justify-content-end gap-3 align-items-end mb-3">
                                <div class="col-lg-3">
                                    <label class="form-label">Subtotal</label>
                                    <input type="text" class="form-control" id="subtotal" readonly>
                                </div>

                                <div class="col-lg-3">
                                    <label class="form-label">Total VAT</label>
                                    <input type="text" class="form-control" id="total_vat" readonly>
                                </div>

                                <div class="col-lg-3">
                                    <label class="form-label">Discount Type</label>
                                    <input type="text" class="form-control" name="discount_reason" id="discount_reason">
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label">Discount Amount</label>
                                    <input type="number" class="form-control" name="discount_amount"
                                        id="discount_amount" step="0.01" value="0"
                                        oninput="calculateFinalTotal()">
                                </div>

                                <div class="col-lg-3">
                                    <label class="form-label fw-bold">Grand Total</label>
                                    <input type="text" class="form-control fw-bold" id="grand_total" readonly>
                                </div>

                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary ">Create Invoice</button>
                        </div>
                    </div>


                </div>
            </div>
        </form>
    </div>
@endcanany
--}}
<!-- Include repeater.js and other necessary scripts -->
<script src="{{ asset('js/jquery.repeater.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.repeater').repeater({
            show: function() {
                $(this).slideDown();
                $(this).find('input[type="radio"][value="excluded"]').prop('checked', true);

            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement, function() {
                    calculateInvoiceSummary();
                });
            }
        });

        // Recalculate totals when any input changes
        $(document).on('input change', '[name$="[amount]"], [name$="[vat_inclusion]"]', function() {
            calculateVAT(this);
        });


    });

    function calculateVAT(element) {
        const row = $(element).closest('tr');
        const amount = parseFloat(row.find('[name$="[amount]"]').val()) || 0;
        const vatIncluded = row.find('[name$="[vat_inclusion]"][value="included"]').is(':checked');
        const vatRate = 0.05;
        let vatAmount, grandAmount;
        if (vatIncluded) {
            vatAmount = amount * 0;
            grandAmount = amount;
        } else {
            vatAmount = amount * vatRate;
            grandAmount = amount;
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

@canany(['create a invoice', 'edit a invoice'])
    <div class="row">

        <form
            action="{{ isset($maintenanceInvoice)
                ? route('company.realestate.maintenance-requests.update-invoice', $Mrequest->id)
                : route('company.realestate.maintenance-requests.store-invoice', $Mrequest->id) }}"
            method="post" class="needs-validation" novalidate enctype="multipart/form-data">
            @csrf
            {{-- @if (isset($maintenanceInvoice))
            @method('PUT')
        @endif --}}

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!-- Invoice ID -->
                            <div class="form-group col-md-6 col-lg-6">
                                <label for="invoice_id" class="form-label">
                                    {{ __('Invoice Number') }}<span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="invoice_prefix">
                                        {{ invoicePrefix() ?? '#INVOICE' }}
                                    </span>
                                    <input type="text" name="invoice_id" id="invoice_id" class="form-control"
                                        placeholder="{{ __('Enter Invoice Number') }}"
                                        value="{{ old('invoice_id', $maintenanceInvoice->invoice_id ?? $invoiceNumber) }}">
                                </div>
                            </div>

                            <!-- Invoice Date -->
                            <div class="col-md-6 col-lg-6">
                                <label class="form-label">Invoice Date <x-required /></label>
                                <input type="date" name="end_date" class="form-control"
                                    value="{{ old('end_date', isset($maintenanceInvoice) ? date('Y-m-d', strtotime($maintenanceInvoice->end_date)) : date('Y-m-d')) }}"
                                    required>
                                @error('end_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="col-md-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Enter Notes">{{ old('notes', $maintenanceInvoice->notes ?? '') }}</textarea>
                                @error('notes')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Invoice Items -->
                            <div class="col-md-12 mt-4">
                                <h6 class="text-md fw-bold text-secondary text-sm">Invoice Items</h6>
                                <div class="repeater">
                                    <table class="table" data-repeater-list="types">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <th>Amount</th>
                                                <th>VAT Inclusion</th>
                                                <th>VAT Amount</th>
                                                <th>Total Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (isset($maintenanceInvoice))
                                                @foreach ($maintenanceInvoice->invoiceItems ?? [] as $keey => $item)
                                                    <tr data-repeater-item>
                                                        <td><input name="invoice_type" class="form-control hidesearch"
                                                                value="{{ $item->invoice_type }}"></td>
                                                        <td>
                                                            <textarea name="description" class="form-control" rows="1">{{ $item->description }}</textarea>
                                                        </td>
                                                        <td><input type="number" name="amount" class="form-control"
                                                                step="0.01" value="{{ $item->amount }}"
                                                                oninput="calculateVAT(this)"></td>
                                                        <td>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                    name="vat_inclusion" value="included"
                                                                    onchange="calculateVAT(this)"
                                                                    {{ $item->vat_inclusion == 'included' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Included</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">

                                                                <input class="form-check-input" type="radio"
                                                                    name="vat_inclusion" value="excluded"
                                                                    onchange="calculateVAT(this)"
                                                                    {{ $item->vat_inclusion == 'excluded' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Excluded</label>
                                                            </div>
                                                        </td>
                                                        <td><input type="text" name="vat_amount" class="form-control"
                                                                readonly value="{{ $item->tax_amount }}"></td>
                                                        <td><input type="number" name="grand_amount" class="form-control"
                                                                step="0.01" readonly value="{{ $item->grand_amount }}">
                                                        </td>
                                                        <td><button type="button" class="btn btn-danger btn-sm"
                                                                data-repeater-delete><i class="ti ti-x"></i></button></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr data-repeater-item>
                                                    <td><input name="invoice_type" class="form-control hidesearch"></td>
                                                    <td>
                                                        <textarea name="description" class="form-control" rows="1"></textarea>
                                                    </td>
                                                    <td><input type="number" name="amount" class="form-control"
                                                            step="0.01" oninput="calculateVAT(this)"></td>
                                                    <td>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="vat_inclusion" value="included"
                                                                onchange="calculateVAT(this)">
                                                            <label class="form-check-label">Included</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="vat_inclusion" value="excluded" checked
                                                                onchange="calculateVAT(this)">
                                                            <label class="form-check-label">Excluded</label>
                                                        </div>
                                                    </td>
                                                    <td><input type="text" name="vat_amount" class="form-control"
                                                            readonly></td>
                                                    <td><input type="number" name="grand_amount" class="form-control"
                                                            step="0.01" readonly></td>
                                                    <td><button type="button" class="btn btn-danger btn-sm"
                                                            data-repeater-delete><i class="ti ti-x"></i></button></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end mt-4 align-items-center mb-3">
                                        <button type="button" class="btn btn-primary btn-sm" data-repeater-create>
                                            <i class="ti ti-plus me-1"></i> Add field
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Totals Section -->
                            <div class="mt-4 d-flex flex-column justify-content-end gap-3 align-items-end mb-3">
                                <div class="col-lg-3">
                                    <label class="form-label">Subtotal</label>
                                    <input type="text"
                                        value="{{ isset($maintenanceInvoice) ? $maintenanceInvoice->sub_total : '' }}"
                                        class="form-control" id="subtotal" readonly>
                                </div>

                                <div class="col-lg-3">
                                    <label class="form-label">Total VAT</label>
                                    <input type="text"
                                        value="{{ isset($maintenanceInvoice) ? $maintenanceInvoice->total_tax : '' }}"
                                        class="form-control" id="total_vat" readonly>
                                </div>

                                <div class="col-lg-3">
                                    <label class="form-label">Discount Type/Reason</label>
                                    <input type="text"
                                        value="{{ isset($maintenanceInvoice) ? $maintenanceInvoice->discount_reason : '' }}"
                                        class="form-control" name="discount_reason" id="discount_reason"
                                        value="{{ old('discount_reason', $maintenanceInvoice->discount_reason ?? '') }}">
                                </div>

                                <div class="col-lg-3">
                                    <label class="form-label">Discount Amount</label>
                                    <input type="number"
                                        value="{{ isset($maintenanceInvoice) ? $maintenanceInvoice->discount_amount : '' }}"
                                        class="form-control" name="discount_amount" id="discount_amount" step="0.01"
                                        value="{{ old('discount_amount', $maintenanceInvoice->discount_amount ?? 0) }}"
                                        oninput="calculateFinalTotal()">
                                </div>

                                <div class="col-lg-3">
                                    <label class="form-label fw-bold">Grand Total</label>
                                    <input type="text"
                                        value="{{ isset($maintenanceInvoice) ? $maintenanceInvoice->grand_total : '' }}"
                                        class="form-control fw-bold" id="grand_total" readonly>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary ">
                                    {{ isset($maintenanceInvoice) ? 'Update Invoice' : 'Create Invoice' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endcanany
