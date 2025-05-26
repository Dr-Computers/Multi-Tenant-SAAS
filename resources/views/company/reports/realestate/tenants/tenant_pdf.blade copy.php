<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DSR Report</title>
    <style>
      
        body {
            margin: 0;
            padding: 0;
            background: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 190mm; /* Keep the width slightly less than A4 (210mm) */
            margin: 10mm auto; /* Add margins for centering and spacing */
            padding: 10mm;
            background: #ffffff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h4, h6 {
            margin: 0 0 10px;
            font-weight: bold;
        }

        .media {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .media img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-right: 20px;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 3px;
            color: #fff;
            text-transform: capitalize;
        }

        .badge.bg-success {
            background-color: #28a745;
        }

        .badge.bg-danger {
            background-color: #dc3545;
        }

        .badge.bg-warning {
            background-color: #ffc107;
        }

        .badge.bg-primary {
            background-color: #007bff;
        }

        .badge.bg-info {
            background-color: #17a2b8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .text-info {
            color: #17a2b8;
            font-size: 14px;
        }

        .mt-4 {
            margin-top: 20px;
        }

        .mb-4 {
            margin-bottom: 20px;
        }

        .mb-3 {
            margin-bottom: 15px;
        }

        .card {
            padding: 15px;
            margin-bottom: 15px;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .text-center {
            text-align: center;
        }
    </style>

</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="media mb-4">
          
            <div class="media-body">
                <h4 class="font-weight-bold">{{$tenant->user->name}}</h4>
                <p>
                  
                    <span class="badge 
                        @if($tenant->status_type == 'renewed') bg-warning 
                        @elseif($tenant->status_type == 'new') bg-success 
                          @elseif($tenant->status_type == 'open') bg-info 
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
            @endforeach
        @endif
    </div>
</body>
</html>
