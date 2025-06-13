<form action="{{ route('company.realestate.properties.lease.store', $unit->id) }}" method="POST" enctype="multipart/form-data">
@csrf
    <div class="px-2">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="property" class="form-label">Tenant</label>
                                    <select class="form-control" name="tenant">
                                        <option value="" selected disabled>Select Tenant</option>
                                        @foreach($tenants ?? [] as $tenant)
                                            <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="property_number" class="form-label">Property number</label>
                                    <input class="form-control" placeholder="Enter Number Of Payments"
                                        name="property_number" type="text" id="property_number">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="contract_number" class="form-label">Contract number</label>
                                    <input class="form-control" placeholder="Enter Number Of Payments"
                                        name="contract_number" type="text" id="contract_number">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="no_of_payments" class="form-label">Number Of Payments</label>
                                    <input class="form-control" placeholder="Enter Number Of Payments"
                                        name="no_of_payments" type="text" id="no_of_payments">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="lease_start_date" class="form-label">Start Date</label>
                                    <input class="form-control" placeholder="Enter lease start date"
                                        name="lease_start_date" type="date" id="lease_start_date">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="lease_end_date" class="form-label">End Date</label>
                                    <span class="text-danger">*</span>
                                    <input class="form-control" placeholder="Enter lease end date" name="lease_end_date"
                                        type="date" id="lease_end_date">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="free_period_start" class="form-label">Free Period Start</label>

                                    <input class="form-control" placeholder="Select Free Period Start Date"
                                        name="free_period_start" type="date" id="free_period_start">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="free_period_end" class="form-label">Free Period End</label>

                                    <input class="form-control" placeholder="Select Free Period End Date"
                                        name="free_period_end" type="date" id="free_period_end">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>Cheque Details</h5>
                </div>

                <div class="card-body">

                    <div id="check-details-container" class="mb-3">

                        <!-- Table to display check details -->


                    </div>
                    <button type="button" class="btn btn-primary" style="width:200px;"
                        id="add-check-detail">Add</button>
                </div>
            </div>
        </div>


        <div class="col-lg-12 py-4">
            <div class="group-button text-center">
                <input class="btn btn-primary btn-rounded" id="tenant-submit" type="submit" value="Create">
            </div>
        </div>
    </div>
</form>
    <script>
        document.getElementById('add-check-detail').addEventListener('click', function() {
            const container = document.getElementById('check-details-container');

            const newDetail = `
            <div class="check-detail position-relative p-3 border rounded mb-4">
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <label class="form-label">Cheque Number <span class="text-danger">*</span></label>
                        <input class="form-control" name="check_number[]" type="text">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label class="form-label">Cheque Date <span class="text-danger">*</span></label>
                        <input class="form-control" name="check_date[]" type="date">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label class="form-label">Payee <span class="text-danger">*</span></label>
                        <input class="form-control" name="payee[]" type="text">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <input class="form-control" name="amount[]" type="number">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                        <input class="form-control" name="bank_name[]" type="text">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label class="form-label">Bank Account Number</label>
                        <input class="form-control" name="bank_account_number[]" type="text">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label class="form-label">Routing Number</label>
                        <input class="form-control" name="routing_number[]" type="text">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label class="form-label">Cheque Image</label>
                        <input class="form-control" name="check_image[]" type="file">
                    </div>
                    <div class="form-group mt-3 col-12">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes[]" rows="3" placeholder="Enter Notes"></textarea>
                    </div>
                </div>
                <button type="button" class="remove-check-detail btn btn-sm btn-outline-danger position-absolute" style="top: 10px; right: 10px;">
                    <i class="ti ti-x"></i> Remove
                </button>
            </div>
        `;

            container.insertAdjacentHTML('beforeend', newDetail);
        });

        // Event delegation for removing a check detail block
        document.getElementById('check-details-container').addEventListener('click', function(e) {
            if (e.target.closest('.remove-check-detail')) {
                e.target.closest('.check-detail').remove();
            }
        });
    </script>
