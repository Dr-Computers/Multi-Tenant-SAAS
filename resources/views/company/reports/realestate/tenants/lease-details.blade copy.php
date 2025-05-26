@extends('layouts.app')

@section('content')

<style>
    body{
    margin-top:20px;
    background: #f5f5f5;
}

.ui-w-100 {
    width: 100px !important;
    height: auto;
}

.card {
    background-clip: padding-box;
    box-shadow: 0 1px 4px rgba(24,28,33,0.012);
}

.user-view-table td:first-child {
    width: 9rem;
}
.user-view-table td {
    padding-right: 0;
    padding-left: 0;
    border: 0;
}

.text-light {
    color: #babbbc !important;
}

.card .row-bordered>[class*=" col-"]::after {
    border-color: rgba(24,28,33,0.075);
}    

.text-xlarge {
    font-size: 170% !important;
}
</style>

<div class="container bootdey flex-grow-1 container-p-y">

    <!-- Tenant Profile -->
     <div class="row">
        <div class="col-md-12 text-center">
            <a href="{{ route('tenant.exportPDF', $tenant->id) }}" class="btn btn-danger">Export to PDF</a>
            <a href="{{ route('tenant.exportExcel', $tenant->id) }}" class="btn btn-success">Export to Excel</a>
        </div>
    </div>
    <div class="media align-items-center py-3 mb-3">
        <img class="d-block ui-w-100 rounded-circle"
        src="{{ !empty($tenant->user) && !empty($tenant->user->profile) ? asset(Storage::url('upload/profile/' . $tenant->user->profile)) : asset(Storage::url('upload/profile/avatar.png')) }}"
        alt="" >
        <div class="media-body ml-4">
            <h4 class="font-weight-bold mb-0">{{$tenant->user->name}} 
            {{-- <div class="text-muted mb-2">Tenant ID: 3425433</div> --}}
            <p>
                <span class="badge 
                    @if($tenant->status == 'active') bg-success 
                    @elseif($tenant->status == 'canceled') bg-danger 
                    @else bg-warning 
                    @endif">
                    {{ ucfirst($tenant->status) }}
                </span>
            </p>
            
                <span class="badge 
                @if($tenant->status_type == 'renewed') bg-primary 
                @elseif($tenant->status_type == 'new') bg-info 
                @else bg-dark 
                @endif text-white">
                {{ ucfirst($tenant->status_type) }}
            </span>
        
          
            {{-- <a href="javascript:void(0)" class="btn btn-default btn-sm">Profile</a>&nbsp;
            <a href="javascript:void(0)" class="btn btn-default btn-sm icon-btn"><i class="fa fa-mail"></i></a>
      --}}
      </div> 
    </div>

    <!-- Lease Details -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <!-- User Details Table -->
                <div class="col-md-6">
                    <h6 class="mt-4 mb-3">User Details</h6>
                    <table class="table user-view-table m-0">
                        <tbody>
                            <tr>
                                <td>Email</td>
                                <td>{{ !empty($tenant->user) ? $tenant->user->email : '-' }}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>{{ !empty($tenant->user) ? $tenant->user->phone_number : '-' }}</td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td>{{ $tenant->address }}</td>
                            </tr>
                            <tr>
                                <td>TRN</td>
                                <td>{{ $tenant->trn }}</td>
                            </tr>
                            <tr>
                                <td>Country</td>
                                <td>{{ $tenant->country }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        
                <!-- Property Details Table -->
                <div class="col-md-6">
                    <h6 class="mt-4 mb-3">Property Details</h6>
                    <table class="table user-view-table m-0">
                        <tbody>
                            <tr>
                                <td>Property</td>
                                <td>{{ $currentLeases->propertyLease->name }}</td>
                            </tr>
                            <tr>
                                <td>Unit</td>
                                <td>{{ $currentLeases->unitLease->name }}</td>
                            </tr>
                            <tr>
                                <td>Monthly Rent</td>
                                <td>{{ $latestRateChange ? priceFormat($latestRateChange->unit_amount) : 'Not Available' }}</td>
                            </tr>
                            <tr>
                                <td>Lease Start Date</td>
                                <td>{{ \Carbon\Carbon::parse($currentLeases->lease_start_date)->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <td>Lease End Date</td>
                                <td>{{ \Carbon\Carbon::parse($currentLeases->lease_end_date)->format('Y-m-d') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <hr class="border-light m-0">
        <div class="table-responsive mt-5">
            @if($currentLeasesCheckDetails->isEmpty())
            <p class="text-info">No check details available for this lease.</p>
         @else
            <table class="table card-table m-0 mt-5">
                <tbody>
                    <tr>
                        <th>Check Number</th>
                        <th>Amount</th>
                        <th>Issue Date</th>
                    </tr>
                    @foreach($currentLeasesCheckDetails as $check)
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
    </div>

    <!-- Lease Payment History -->
    <div class="card" style="margin-top: 25px;">
        <hr class="border-light m-0">
    
   
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <!-- User Details Table -->
                <div class="col-md-12">
                    <h6 class="mt-4 mb-3">Previous Lease Details</h6>
    
                    @if($oldLeases->isEmpty())
                        <p class="text-info text-center">No previous leases available.</p>
                    @else
                        @foreach($oldLeases as $lease)
                            <!-- Lease Details -->
                            <table class="table user-view-table m-0" style="margin-top:35px !important;">
                                <tbody>
                                    <tr>
                                        <td>Lease Start Date</td>
                                        <td>{{ \Carbon\Carbon::parse($lease->lease_start_date)->format('Y-m-d') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Lease End Date</td>
                                        <td>{{ \Carbon\Carbon::parse($lease->lease_end_date)->format('Y-m-d') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Property</td>
                                        <td>{{ $lease->propertyLease->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Unit</td>
                                        <td>{{ $lease->unitLease->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Unit</td>
                                        <td>{{ $lease->unit_price }}</td>
                                    </tr>
                                    {{-- <tr>
                                        <td>Status</td>
                                        <td>
                                            <span class="badge 
                                                @if($lease->status == 'active') bg-success 
                                                @elseif($lease->status == 'canceled') bg-danger 
                                                @else bg-warning 
                                                @endif">
                                                {{ ucfirst($lease->status) }}
                                            </span>
                                        </td>
                                    </tr> --}}
                                </tbody>
                            </table>
    
                            <!-- Check Details for the current lease -->
                            <div class="table-responsive mt-5">
                                @php
                                    // Filter check details for this specific lease
                                    $leaseCheckDetails = $oldLeasesCheckDetails->where('lease_id', $lease->id);
                                @endphp
    
                                @if($leaseCheckDetails->isEmpty())
                                    <p class="text-info">No check details available for this lease.</p>
                                @else
                                    <table class="table card-table m-0 mt-5" style="margin-top: 15px !important">
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
    
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    

</div>

@endsection
