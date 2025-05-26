<div class="container">
    <!-- Header Section -->
    <div class="media mb-4">
        <div class="media-body">
            <h4 class="font-weight-bold">{{ $tenant->user->name }}</h4>
            <p>
                <span class="badge 
                    @if($tenant->status == 'active') bg-success 
                    @elseif($tenant->status == 'canceled') bg-danger 
                    @else bg-warning 
                    @endif">
                    {{ ucfirst($tenant->status) }}
                </span>
                <span class="badge 
                    @if($tenant->status_type == 'renewed') bg-primary 
                    @elseif($tenant->status_type == 'new') bg-info 
                    @else bg-dark 
                    @endif">
                    {{ ucfirst($tenant->status_type) }}
                </span>
            </p>
        </div>
    </div>

    <!-- User Details -->
    <h6>User Details</h6>
    <table>
        <tbody>
            <tr><td>Email</td><td>{{ $tenant->user->email ?? '-' }}</td></tr>
            <tr><td>Phone</td><td>{{ $tenant->user->phone_number ?? '-' }}</td></tr>
            <tr><td>Address</td><td>{{ $tenant->address }}</td></tr>
            <tr><td>TRN</td><td>{{ $tenant->trn }}</td></tr>
            <tr><td>Country</td><td>{{ $tenant->country }}</td></tr>
        </tbody>
    </table>

    <!-- Property Details -->
    <h6>Property Details</h6>
    <table>
        <tbody>
            <tr><td>Property</td><td>{{ $currentLease->propertyLease->name }}</td></tr>
            <tr><td>Unit</td><td>{{ $currentLease->unitLease->name }}</td></tr>
            <tr><td>Monthly Rent</td><td>{{ $latestRateChange ? priceFormat($latestRateChange->unit_amount) : 'Not Available' }}</td></tr>
            <tr><td>Lease Start Date</td><td>{{ \Carbon\Carbon::parse($currentLease->lease_start_date)->format('Y-m-d') }}</td></tr>
            <tr><td>Lease End Date</td><td>{{ \Carbon\Carbon::parse($currentLease->lease_end_date)->format('Y-m-d') }}</td></tr>
        </tbody>
    </table>

    <!-- Check Details -->
    <h6>Check Details</h6>
    @if($currentLeaseCheckDetails->isEmpty())
        <p class="text-info">No check details available for this lease.</p>
    @else
        <table>
            <thead>
                <tr><th>Check Number</th><th>Amount</th><th>Issue Date</th></tr>
            </thead>
            <tbody>
                @foreach($currentLeaseCheckDetails as $check)
                    <tr>
                        <td>{{ $check->check_number }}</td>
                        <td>{{ number_format($check->amount, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($check->issue_date)->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Previous Leases -->
    <h6>Previous Leases</h6>
    @if($oldLeases->isEmpty())
        <p class="text-center">No previous leases available.</p>
    @else
        @foreach($oldLeases as $lease)
            <table>
                <tbody>
                    <tr><td>Lease Start Date</td><td>{{ \Carbon\Carbon::parse($lease->lease_start_date)->format('Y-m-d') }}</td></tr>
                    <tr><td>Lease End Date</td><td>{{ \Carbon\Carbon::parse($lease->lease_end_date)->format('Y-m-d') }}</td></tr>
                    <tr><td>Property</td><td>{{ $lease->propertyLease->name }}</td></tr>
                    <tr><td>Unit</td><td>{{ $lease->unitLease->name }}</td></tr>
                    <tr><td>Unit Price</td><td>{{ $lease->unit_price }}</td></tr>
                </tbody>
            </table>

            <div class="table-responsive mt-5">
                @php
                    // Filter check details for this specific lease
                    $leaseCheckDetails = $oldLeasesCheckDetails->where('lease_id', $lease->id);
                @endphp

                @if($leaseCheckDetails->isEmpty())
                    <p class="text-info">No check details available for this lease.</p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Check Number</th>
                                <th>Amount</th>
                                <th>Issue Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaseCheckDetails as $check)
                                <tr>
                                    <td>{{ $check->check_number }}</td>
                                    <td>{{ number_format($check->amount, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($check->issue_date)->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <hr>
        @endforeach
    @endif
</div>
