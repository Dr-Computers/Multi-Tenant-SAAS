@extends('layouts.company')

@section('page-title')
   {{ __('Invoices') }}
@endsection

@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
   <li class="breadcrumb-item">{{ __('Invoices') }}</li>
@endsection

@section('action-btn')
   <div class="d-flex">
       <a href="#" data-size="md" data-url="{{ route('company.realestate.invoices.create') }}" data-ajax-popup="true"
           data-bs-toggle="tooltip" title="{{ __('Create New Invoice') }}" class="btn btn-sm btn-primary me-2">
           <i class="ti ti-plus"></i>
       </a>
   </div>
@endsection

@section('content')
<div class="row">
    <form action="{{ route('company.realestate.invoices.store') }}" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        @csrf
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        

                            <h6 class="text-md fw-bold text-secondary text-sm">Invoice Details</h6>

                            <!-- Property Selection -->
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="form-label">Property <x-required /></label>
                                    <select name="property_id" class="form-control hidesearch" required>
                                        @foreach($property as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('property_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Unit Selection -->
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="form-label">Unit <x-required /></label>
                                    <div class="unit_div">
                                        <select class="form-control hidesearch unit" id="unit" name="unit_id" required>
                                            <option value="">{{ __('Select Unit') }}</option>
                                        </select>
                                    </div>
                                    @error('unit_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Invoice Number -->
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="form-label">Invoice Number <x-required /></label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="invoice_prefix"></span>
                                        <input type="text" name="invoice_id" class="form-control" placeholder="Enter Invoice Number" required>
                                    </div>
                                    @error('invoice_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Invoice Period -->
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="form-label">Invoice Period <x-required /></label>
                                    <select name="invoice_period" class="form-control" required>
                                        @foreach($invoicePeriods as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('invoice_period') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Invoice Month -->
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="form-label">Invoice Month <x-required /></label>
                                    <input type="month" name="invoice_month" class="form-control" value="{{ date('Y-m') }}" required>
                                    @error('invoice_month') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Invoice Date -->
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="form-label">Invoice Date <x-required /></label>
                                    <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    @error('end_date') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Enter Notes"></textarea>
                                    @error('notes') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                      <!-- ✅ Closing col-xs-12 col-sm-12 col-md-6 col-lg-6 -->

                        <!-- Invoice Items Repeater -->
                        <div class="col-md-12 mt-4">
                            <h6 class="text-md fw-bold text-secondary text-sm">Invoice Items</h6>
                            <div class="repeater">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Invoice Types</h5>
                                    <button type="button" class="btn btn-primary btn-sm" data-repeater-create>
                                        <i class="ti-plus me-1"></i> Add Type
                                    </button>
                                </div>

                                <table class="table" data-repeater-list="types">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>VAT Inclusion</th>
                                            <th>VAT Amount</th>
                                            <th>Total Amount</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-repeater-item>
                                            <td>
                                                <select name="invoice_type" class="form-control hidesearch">
                                                    @foreach($types as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="amount" class="form-control" step="0.01" oninput="calculateVAT(this)">
                                            </td>
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="vat_inclusion" value="included" onchange="calculateVAT(this.closest('tr'))">
                                                    <label class="form-check-label">Included</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="vat_inclusion" value="excluded" checked onchange="calculateVAT(this.closest('tr'))">
                                                    <label class="form-check-label">Excluded</label>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" name="vat_amount" class="form-control" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="grand_amount" class="form-control" step="0.01" readonly>
                                            </td>
                                            <td>
                                                <textarea name="description" class="form-control" rows="1"></textarea>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm" data-repeater-delete>
                                                    <i class="ti-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- End Invoice Items -->
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Invoice</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection



<!-- Include repeater.js and other necessary scripts -->
<script src="{{ asset('vendor/repeater/jquery.repeater.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.repeater').repeater({
            show: function () {
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
        
        // Initialize other scripts as needed
    });
    
    function calculateVAT(element) {
        // Implement your VAT calculation logic here
        const row = $(element).closest('tr');
        const amount = parseFloat(row.find('[name="amount"]').val()) || 0;
        const vatIncluded = row.find('[name="vat_inclusion"][value="included"]').is(':checked');
        
        // Example calculation - adjust as needed
        const vatRate = 0.05; // 5% VAT
        let vatAmount, grandAmount;
        
        if (vatIncluded) {
            vatAmount = amount * vatRate;
            grandAmount = amount;
        } else {
            vatAmount = amount * vatRate;
            grandAmount = amount + vatAmount;
        }
        
        row.find('[name="vat_amount"]').val(vatAmount.toFixed(2));
        row.find('[name="grand_amount"]').val(grandAmount.toFixed(2));
    }
</script>