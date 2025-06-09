<!-- resources/views/pdf/property_sample.blade.php -->
<!DOCTYPE html>
<html>
    <head>
        <title></title>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record List</title>
    <style>
         .table-container {
        text-align: center; /* Center aligns content, including h2 and table */
    }

    table {
        margin: 0 auto; /* Center table horizontally */
        border-collapse: collapse;
    }
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%; /* Adjust width based on content */
            border-collapse: collapse;
            margin: 0 auto;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 1px 3px; /* Minimal padding to reduce cell size */
            text-align: center; /* Align text in the center */
            font-size: 10px; /* Smaller font size */
            line-height: 1; /* Reduce line height to shrink height */
            min-width: 30px; /* Minimal width */
            height: 15px; /* Minimal height */
        }
        h2 {
        text-align: center;
        }
       /* Green highlight with dark green text */
        .highlight-green {
            background-color: #d4edda; /* Light green background */
            color: #155724;            /* Dark green text for contrast */
            font-weight: bold;         /* Make the text stand out */
        }

        /* Red highlight with white text */
        .highlight-red {
            background-color: #b91e1e; /* Red background */
            color: white;              /* White text for better readability */
            font-weight: bold;         /* Bold text */
        }

        /* Orange highlight with dark text */
        .highlight-orange {
            background-color: orange; /* Orange background */
            color: #fff;              /* White text */
            font-weight: bold;        /* Bold text */
        }

        /* Blue highlight with white text */
        .highlight-blue {
            background-color: blue;  /* Blue background */
            color: white;             /* White text for contrast */
            font-weight: bold;        /* Bold text */
        }

        /* Pink highlight with dark text */
        .highlight-pink {
            background-color: pink;  /* Pink background */
            color: #000;              /* Black text */
            font-weight: bold;        /* Bold text */
        }

        /* Grey highlight with dark text */
        .highlight-grey {
            background-color: grey;  /* Grey background */
            color: white;             /* White text */
            font-weight: bold;        /* Bold text */
        }

    </style>
    
    
</head>
    </head>
<body>
    <div style="text-align: center; margin-bottom: 20px;">
        {{-- <h1>Property Listings</h1> --}}
        <img src="{{storage_path('app/public/upload/logo/pdf_logo.png')}}" alt="Company Logo">
    </div>

    @foreach ($properties as $property)
    @if ($property->units->isNotEmpty())
    <div class="property-details" style="page-break-after: always;">
        <h2>{{ $property->name ?? 'No Name Available' }}</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>S.N</th>
                    <th>Unit No.</th>
                    <th>Unit Reg.No</th>
                    <th>Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Value</th>
                    <th>No.of Payments</th>
                    <th>Payment Details</th>
                    <th>Contact Number</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($property->units as $unit)
                    @php
                        $tenant = $unit->tenants(); // Call your tenants method
                        $lease = $unit->activeLease;
                        if ($lease) {
                            $checkDate =   App\Models\CheckDetail::where('lease_id', $lease->id)->value('check_date');
                            if ($checkDate) {
                                $paymentDetails = $checkDate;
                            } else {
                                $paymentDetails = 'N/A';
                            }
                        }
                        $currentMonth = \Carbon\Carbon::now()->format('Y-m');
                        $unitVacant = false; 
                        $chequeDueMonth = false;  
                        $underManagementStatus = false;  
                        $procedureStillPendingStatus = false;  
                        $renewalStatus = false;  
                        $caseStatus = false;  
                        if (!$lease) {   // IF ACTIVE LEASE DOES NOT EXIST MEANS UNIT IS VACANT
                            $unitVacant = true;
                        } else {
                             // Check conditions based on the lease
                            $caseStatus = $lease && $lease->status == 'case';
                            $chequeDueMonth = $lease && \Carbon\Carbon::parse($lease->lease_end_date)->format('Y-m') === $currentMonth;
                            $underManagementStatus = $lease && $lease->status == 'under_management';
                            $procedureStillPendingStatus = $lease && $lease->status == 'procedure_still_pending';
                            $renewalStatus = $lease && (
                                            \Carbon\Carbon::parse($lease->free_period_end)->format('Y-m') === $currentMonth ||
                                            \Carbon\Carbon::parse($lease->lease_end_date)->format('Y-m') === $currentMonth);
                                         
                             
                        }

                        $classes = '';

                        if ($caseStatus) {   
                            $classes = 'highlight-red';
                        }
                        elseif ($underManagementStatus) {
                            $classes = 'highlight-blue';
                        } elseif ($procedureStillPendingStatus) {
                            $classes = 'highlight-pink';
                        } 
                        elseif ($chequeDueMonth) {   
                            $classes = 'highlight-green';
                        }
                        elseif ($renewalStatus) {   
                            $classes = 'highlight-orange';
                        } 
                        elseif ($unitVacant) {   
                            $classes = 'highlight-grey';
                        } 
                        
                    @endphp
                        <tr class="{{ $classes }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $unit->name }}</td>
                        <td>{{ $unit->unit_registration_number ?? 'N/A' }}</td>
                        <td>{{ $tenant && $tenant->user ? $tenant->user->first_name . ' ' . $tenant->user->last_name : 'N/A' }}</td>
                        <td>{{ $lease && $lease->lease_start_date ? dateFormat($lease->lease_start_date) : 'N/A' }}</td>
                        <td>{{ $lease && $lease->lease_end_date ? dateFormat($lease->lease_end_date) : 'N/A' }}</td>
                        <td>{{ ($unit->latestRateChange)->unit_amount }}</td>
                        <td>{{ $lease?->no_of_payments ?? 'N/A' }}</td>
                        <td>{{ $paymentDetails ? dateFormat($paymentDetails) : 'N/A' }}</td>
                        <td>{{ $tenant ? $tenant->user->phone_number ?? 'N/A' : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- @dd('hi'); --}}
    </div>
    <br/>
    @endif
    @endforeach

</body>
</html>
