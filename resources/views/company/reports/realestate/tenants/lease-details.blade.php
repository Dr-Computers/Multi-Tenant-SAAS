@extends('layouts.app')
@section('page-title')
    {{ __('Tenant Details') }}
@endsection
@section('page-class')
    cdxuser-profile
@endsection
@push('script-page')

<script>

$(document).on('click', '.cancel-tenant', function(event) {
            event.preventDefault(); // Prevent the default anchor click behavior

            var tenantId = $(this).data('tenant-id'); // Get the tenant ID from the data attribute

            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to cancel this tenant.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, mark as cancel',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('tenant.cancel', ':tenantId') }}'.replace(':tenantId',
                            tenantId), // Use the route helper
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}' // Include CSRF token
                        },
                        success: function(response) {
                            Swal.fire(
                                'Success!',
                                response.message,
                                'success'
                            );
                            location.reload();
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                xhr.responseJSON.message,
                                'error'
                            );
                        }
                    });
                }
            }); // Close Swal promise handling
        }); // Close the .on('click') event handler

        $(document).on('click', '.active-tenant', function(event) {
            event.preventDefault(); // Prevent the default anchor click behavior

            var tenantId = $(this).data('tenant-id'); // Get the tenant ID from the data attribute

            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to activate this tenant.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, activate',
                cancelButtonText: 'No, cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('tenant.activate', ':tenantId') }}'.replace(':tenantId',
                            tenantId), // Use the route helper
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}' // Include CSRF token
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                            location.reload(); // Refresh the page to see updated tenant status
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: xhr.responseJSON.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            }); // Close the Swal promise handling
        });

        $(document).on('click', '.case-tenant', function(event) {
            event.preventDefault(); // Prevent default anchor click behavior

            var tenantId = $(this).data('tenant-id'); // Get the tenant ID from the data attribute

            // SweetAlert2 confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to mark this tenant as a case.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, mark as case',
                cancelButtonText: 'No, cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with the AJAX request to mark the tenant as a case
                    $.ajax({
                        url: '{{ route('tenant.case', ':tenantId') }}'.replace(':tenantId',
                            tenantId),
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}' // Include CSRF token
                        },
                        success: function(response) {
                            Swal.fire(
                                'Success!',
                                response.message,
                                'success'
                            );
                            location.reload(); // Refresh the page to reflect changes
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                xhr.responseJSON.message,
                                'error'
                            );
                        }
                    });
                }
            });
        });
</script>
@endpush
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('tenant.index') }}">{{ __('Tenant') }}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Details') }}</a>
        </li>
    </ul>
@endsection
<style>
    <style>.active-card {
        background-color: #f8f9fa;
        /* Light color for active tenants */
        border: 1px solid #28a745;
        /* Green border for active */

    }

    .canceled-card {
        background-color: #f8d7da;
        /* Light red color for canceled tenants */
        border: 1px solid #dc3545;
        /* Red border for canceled */
    }

    .case-card {
        background-color: #f8d7da;
        /* Light red color for canceled tenants */
        border: 1px solid orangered;
        /* Red border for canceled */
    }

    .status-label {
        display: inline-block;
        margin-top: 5px;
        padding: 3px 10px;
        font-size: 0.8rem;
        font-weight: bold;
        /* border-radius: 12px;
        border: 1px solid; */
    }

    .active-status {
        color: #28a745;
        /* Green for active */
        border-color: #28a745;
    }

    .canceled-status {
        color: #dc3545;
        /* Red for canceled */
        border-color: #dc3545;
    }

    .case-status {
        color: orangered;
        /* Red for canceled */
        border-color: orangered;
    }

    .renew-type {
        background-color: #4caf50;
        /* Green */
        color: white;
        padding: 2px 8px;
        border-radius: 3px;
    }

    .new-type {
        background-color: #ff9800;
        /* Orange */
        color: white;
        padding: 2px 8px;
        border-radius: 3px;
    }

    .small-table-header {
        background: gray !important;
        color: #000 !important;
        font-size: 11px !important;
        padding: 2px !important;
        line-height: 0.5 !important;
    }
    /* Ensure dropdown is positioned correctly */
.dropdown-menu {
    position: absolute !important;
   
    top: -100% !important; /* Adjust this value as needed */
    left: 0;
    z-index: 9999; /* Ensure it appears above other content */
}

</style>
</style>
@section('content')
<div class="d-flex justify-content-end mb-3">
    {{-- <a href="{{ route('tenant.exportExcel', $tenant->id) }}" class="btn btn-outline-success mr-5 mb-5">Excel</a> --}}
    <a href="{{route('tenant.exportPDF', $tenant->id) }}" class="btn btn-outline-primary mb-5">PDF</a>
</div>

    <div class="row">
        <div class="col-xl-3 cdx-xxl-30 cdx-xl-40">
            <div class="row">
                <div class="col-xl-12 col-md-6">
                    <div class="card user-card">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <div class="user-imgwrap">
                                <img class="img-fluid rounded-50"
                                    src="{{ !empty($tenant->user) && !empty($tenant->user->profile) ? asset(Storage::url('upload/profile/' . $tenant->user->profile)) : asset(Storage::url('upload/profile/avatar.png')) }}"
                                    alt="">
                            </div>
                            <div class="user-detailwrap">
                                <h3>{{ ucfirst(!empty($tenant->user) ? $tenant->user->first_name : '') . ' ' . ucfirst(!empty($tenant->user) ? $tenant->user->last_name : '') }}
                                </h3>
                                <h6>{{ !empty($tenant->user) ? $tenant->user->email : '-' }}</h6>
                                <h6>{{ !empty($tenant->user) ? $tenant->user->phone_number : '-' }} </h6>
                                <span class="status-label">
                           
                            
                
                                    @if ($tenant->status_type === 'renewed')
                                        <span class="status-type renew-type">{{ __('Renewed') }}</span>
                                    @elseif($tenant->status_type === 'open')
                                        <span class="status-type open-type">{{ __('Open') }}</span>
                                    @elseif($tenant->status_type === 'new')
                                        <span class="status-type new-type">{{ __('New') }}</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-9 cdx-xxl-70 cdx-xl-60">
            <div class="row">
                <div class="col-12">
                    <div class="card support-inboxtbl">
                        <div class="card-header">
                            <h4>{{ __('Additional Information') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6>{{ __('TRN') }}</h6>
                                            <p class="text-light">{{ $tenant->trn }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6>{{ __('Country') }}</h6>
                                            <p class="text-light">{{ $tenant->country }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6>{{ __('State') }}</h6>
                                            <p class="text-light">{{ $tenant->state }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6>{{ __('City') }}</h6>
                                            <p class="text-light">{{ $tenant->city }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6>{{ __('Zip Code') }}</h6>
                                            <p class="text-light">{{ $tenant->zip_code }}</p>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6>{{ __('Property') }}</h6>
                                            <p class="text-light">
                                                {{ !empty($tenant->properties) ? $tenant->properties->name : '-' }}</p>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6>{{ __('Unit') }}</h6>
                                            <p class="text-light">{{ !empty($tenant->units) ? $tenant->units->name : '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6>{{ __('Lease Start Date') }}</h6>
                                            @if ($tenant->latestLease && $tenant->latestLease->lease_start_date)
                                                <p class="text-light">
                                                    {{ dateFormat($tenant->latestLease->lease_start_date) }}</p>
                                            @else
                                                <p class="text-light">N/A</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6>{{ __('Lease End Date') }}</h6>
                                            @if ($tenant->latestLease && $tenant->latestLease->lease_end_date)
                                                <p class="text-light">
                                                    {{ dateFormat($tenant->latestLease->lease_end_date) }}</p>
                                            @else
                                                <p class="text-light">N/A</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6>{{ __('Free Period Start Date') }}</h6>
                                            @if ($tenant->latestLease && $tenant->latestLease->free_period_start)
                                                <p class="text-light">
                                                    {{ dateFormat($tenant->latestLease->free_period_start) }}</p>
                                            @else
                                                <p class="text-light">N/A</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6>{{ __('Free Period End Date') }}</h6>
                                            @if ($tenant->latestLease && $tenant->latestLease->free_period_end)
                                                <p class="text-light">
                                                    {{ dateFormat($tenant->latestLease->free_period_end) }}</p>
                                            @else
                                                <p class="text-light">N/A</p>
                                            @endif
                                        </div>
                                    </div>
                                </div> --}}
                                @if (!empty($tenant->documents))
                                    <div class="col-md-3 col-lg-3 mb-20">
                                        <div class="media">
                                            <div class="media-body">
                                                <h6>{{ __('Documents') }}</h6>
                                                @foreach ($tenant->documents as $doc)
                                                    <a href="{{ asset(Storage::url('upload/tenant')) . '/' . $doc->document }}"
                                                        class="text-light" target="_blank"><i
                                                            data-feather="download"></i></a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-6 col-lg-6 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6>{{ __('Address') }}</h6>
                                            <p class="text-light">{{ $tenant->address }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row mt-4">
            <div class="col-12">
                <di<div class="card">
                    <div class="card-header">
                        <h4>{{ __('Cheque Details') }}</h4>
                    </div>
                    <div class="card-body">
                        @if ($tenant->cheques->isNotEmpty())
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="background:#ffffff !important;
                                        color: #000 !important;
                                        font-size: 11px !important;
                                        padding: 10px !important;
                                        line-height: 1 !important;">{{ __('Cheque Number') }}</th>
                                        <th style="background:#ffffff !important;
                                        color: #000 !important;
                                        font-size: 11px !important;
                                        padding: 10px !important;
                                        line-height: 1 !important;">{{ __('Cheque Date') }}</th>
                                        <th style="background:#ffffff !important;
                                        color: #000 !important;
                                        font-size: 11px !important;
                                        padding: 10px !important;
                                        line-height: 1 !important;">{{ __('Payee') }}</th>
                                        <th style="background:#ffffff !important;
                                        color: #000 !important;
                                        font-size: 11px !important;
                                        padding: 10px !important;
                                        line-height: 1 !important;">{{ __('Amount') }}</th>
                                        <th style="background:#ffffff !important;
                                        color: #000 !important;
                                        font-size: 11px !important;
                                        padding: 10px !important;
                                        line-height: 1 !important;">{{ __('Bank Name') }}</th>
                                        <th style="background:#ffffff !important;
                                        color: #000 !important;
                                        font-size: 11px !important;
                                        padding: 10px !important;
                                        line-height: 1 !important;">{{ __('Cheque Image') }}</th> <!-- New column for the image -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tenant->cheques as $cheque)
                                        <tr>
                                            <td>{{ $cheque->check_number }}</td>
                                            <td>{{ $cheque->check_date }}</td>
                                            <td>{{ $cheque->payee }}</td>
                                            <td>{{ priceFormat($cheque->amount) }}</td>
                                            <td>{{ $cheque->bank_name }}</td>
                                            <td>
                                                @if ($cheque->check_image)
                                                    <a href="{{ asset('storage/' . $cheque->check_image) }}"
                                                        target="_blank">
                                                        <img src="{{ asset('storage/' . $cheque->check_image) }}"
                                                            alt="Cheque Image" style="width: 50px; height: auto;">
                                                    </a>
                                                @else
                                                    {{ __('No Image') }}
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>{{ __('No cheque details found for this tenant.') }}</p>
                        @endif
                    </div>
            </div>
        </div> --}}
    </div>
    <di<div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Tenant Leases') }}</h4>
                </div>
                <div class="card-body">
                    @if ($tenant->leases->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Lease Start Date') }}</th>
                                    <th>{{ __('Lease End Date') }}</th>
                                    <th>{{ __('Free Period  Start Date') }}</th>
                                    <th>{{ __('free Period End Date') }}</th>
                                    <th>{{ __('Unit') }}</th>
                                    <th>{{ __('Property') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    {{-- <th>{{ __('Action') }}</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                              
                                @foreach ($currentLeases as $lease)
                              
                                    <tr
                                        class="{{ $lease->status === 'active' ? 'active-card' : ($lease->status === 'canceled' ? 'canceled-card' : 'case-card') }}">
                                        <td>{{ dateFormat($lease->lease_start_date) }}</td>
                                        <td>{{ dateFormat($lease->lease_end_date) }}</td>
                                        <td>
                                            @if($lease->free_period_start)
                                                {{ dateFormat($lease->free_period_start) }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @if($lease->free_period_end)
                                                {{ dateFormat($lease->free_period_end) }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        
                                        <td>{{ $lease->unitLease->name ?? '-' }}</td>
                                        <td>{{ $lease->propertyLease->name ?? '-' }}</td>
                                        <td>
                                            <span
                                                class="status-label {{ $lease->status === 'active' ? 'active-status' : ($lease->status === 'canceled' ? 'canceled-status' : 'case-status') }}">
                                                {{ ucfirst($lease->status) }}
                                            </span>
                                        </td>
                                        {{-- <td>
                                            <!-- Edit Button with Icon -->
                                            <a href="{{ route('lease.edit', $lease->id) }}" class="btn btn-sm text-success" style="border: none; padding: 0; font-size: 16px; margin-right: 20px;">
                                                <i class="ti-pencil" style="font-size: 18px;"></i>
                                            </a>
                                            
                                            <!-- Delete Button with Icon -->
                                            <form action="{{ route('lease.destroy', $lease->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm text-danger" 
                                                    style="border: none; background: none; padding: 0; font-size: 16px;" 
                                                    onclick="return confirm('{{ __('Are you sure you want to delete this lease?') }}');">
                                                    <i class="ti-trash" style="font-size: 18px;"></i>
                                                </button>
                                            </form>
                                        
                                            <!-- Dropdown Button for Status Change (With Dropup) -->
                                            @can('edit tenant')
                                            <div class="dropdown dropup" style="display: inline-block;">
                                                <button class="btn outline-primary btn-sm dropdown-toggle text-primary" type="button"
                                                        id="tenantActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                    {{ __('Change Status') }}
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="tenantActionsDropdown">
                                                    @if ($lease->status === 'active')
                                                        <li>
                                                            <a href="#" class="dropdown-item cancel-tenant" style="font-size: 13px !important;"
                                                               data-tenant-id="{{ $lease->id }}">
                                                                <i class="ti-na" style="font-size: 13px !important;"></i> {{ __('Cancel Tenant') }}
                                                            </a>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <a href="#" class="dropdown-item active-tenant" style="font-size: 13px !important;"
                                                               data-tenant-id="{{ $lease->id }}">
                                                                <i class="ti-check" style="font-size: 13px !important;"></i> {{ __('Activate Tenant') }}
                                                            </a>
                                                        </li>
                                                    @endif
                                        
                                                    <li>
                                                        <a href="#" class="dropdown-item case-tenant" style="font-size: 13px !important;"
                                                           data-tenant-id="{{ $lease->id }}">
                                                            <i class="ti-alert" style="font-size: 13px !important;"></i> {{ __('Mark as Case') }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            @endcan
                                        </td> --}}
                                        
                                        
                                    </tr>

                                    <!-- Display check details for the current lease -->
                                    @if ($currentLeaseCheckDetails->where('lease_id', $lease->id)->isNotEmpty())
                                        <tr>
                                            <td colspan="8">
                                                <table class="table table-bordered mt-3 ">
                                                    <thead>
                                                        <tr>
                                                            <th class="small-table-header"
                                                                style="background:#ffffff !important;
                                            color: #000 !important;
                                            font-size: 11px !important;
                                            padding: 10px !important;
                                            line-height: 1 !important;">
                                                                {{ __('Cheque Number') }}</th>
                                                            <th class="small-table-header"
                                                                style="background:#ffffff !important;
                                            color: #000 !important;
                                            font-size: 11px !important;
                                            padding: 10px !important;
                                            line-height: 1 !important;">
                                                                {{ __('Cheque Date') }}</th>
                                                            <th class="small-table-header"
                                                                style="background:#ffffff !important;
                                            color: #000 !important;
                                            font-size: 11px !important;
                                            padding: 10px !important;
                                            line-height: 1!important;">
                                                                {{ __('Payee') }}</th>
                                                            <th class="small-table-header"
                                                                style="background:#ffffff !important;
                                            color: #000 !important;
                                            font-size: 11px !important;
                                            padding: 10px !important;
                                            line-height:  !important;">
                                                                {{ __('Amount') }}</th>
                                                            <th class="small-table-header"
                                                                style="background:#ffffff !important;
                                            color: #000 !important;
                                            font-size: 11px !important;
                                            padding: 10px !important;
                                            line-height: 1 !important;">
                                                                {{ __('Bank Name') }}</th>
                                                            <th class="small-table-header"
                                                                style="background:#ffffff !important;
                                            color: #000 !important;
                                            font-size: 11px !important;
                                            padding: 10px !important;
                                            line-height: 1 !important;">
                                                                {{ __('Cheque Image') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($currentLeaseCheckDetails->where('lease_id', $lease->id) as $check)
                                                            <tr>
                                                                <td>{{ $check->check_number }}</td>
                                                                <td>{{ dateFormat($check->check_date) }}</td>
                                                                <td>{{ $check->payee }}</td>
                                                                <td>{{ priceFormat($check->amount) }}</td>
                                                                <td>{{ $check->bank_name }}</td>
                                                                <td>
                                                                    @if ($check->check_image)
                                                                        <a href="{{ asset('storage/' . $check->check_image) }}"
                                                                            target="_blank">
                                                                            <img src="{{ asset('storage/' . $check->check_image) }}"
                                                                                alt="Cheque Image"
                                                                                style="width: 50px; height: auto;">
                                                                        </a>
                                                                    @else
                                                                        {{ __('No Image') }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <p>{{ __('No lease details found for this tenant.') }}</p>
                    @endif
                </div>
            </div>
        </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Tenant Expired Leases') }}</h4>
                    </div>
                    <div class="card-body">
                        @if ($tenant->leases->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('Lease Start Date') }}</th>
                                        <th>{{ __('Lease End Date') }}</th>
                                        <th>{{ __('Free Period  Start Date') }}</th>
                                        <th>{{ __('free Period End Date') }}</th>
                                        <th>{{ __('Unit') }}</th>
                                        <th>{{ __('Property') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($oldLeases as $lease)
                                        <tr
                                            class="{{ $lease->status === 'active' ? 'active-card' : ($lease->status === 'canceled' ? 'canceled-card' : 'case-card') }}">
                                            <td>{{ dateFormat($lease->lease_start_date) }}</td>
                                            <td>{{ dateFormat($lease->lease_end_date) }}</td>
                                            <td>{{ dateFormat($lease->free_period_start) }}</td>
                                            <td>{{ dateFormat($lease->free_period_end) }}</td>
                                            <td>{{ $lease->unitLease->name ? $lease->unitLease->name : '-' }}</td>
                                            <!-- Check if unit exists -->
                                            <td>{{ $lease->propertyLease->name ? $lease->propertyLease->name : '-' }}</td>
                                            <!-- Check if unit and properties exist -->
                                            <td>
                                                <span
                                                    class="status-label 
                                            {{ $lease->status === 'active' ? 'active-status' : ($lease->status === 'canceled' ? 'canceled-status' : 'case-status') }}">
                                                    {{ ucfirst($lease->status) }}
                                                </span>
                                            </td>
                                            
                                            
                                        </tr>
                                    
                                        @if ($oldLeasesCheckDetails->where('lease_id', $lease->id)->count() > 0)
     <tr>
                                            <td colspan="7">
                                                <table class="table table-bordered mt-3 ">
                                                    <thead>
                                                        <tr>
                                                            <th class="small-table-header"
                                                                style="background:#ffffff !important;
                                            color: #000 !important;
                                            font-size: 11px !important;
                                            padding: 10px !important;
                                            line-height: 1 !important;">
                                                                {{ __('Cheque Number') }}</th>
                                                            <th class="small-table-header"
                                                                style="background:#ffffff !important;
                                            color: #000 !important;
                                            font-size: 11px !important;
                                            padding: 10px !important;
                                            line-height: 1 !important;">
                                                                {{ __('Cheque Date') }}</th>
                                                            <th class="small-table-header"
                                                                style="background:#ffffff !important;
                                            color: #000 !important;
                                            font-size: 11px !important;
                                            padding: 10px !important;
                                            line-height: 1!important;">
                                                                {{ __('Payee') }}</th>
                                                            <th class="small-table-header"
                                                                style="background:#ffffff !important;
                                            color: #000 !important;
                                            font-size: 11px !important;
                                            padding: 10px !important;
                                            line-height:  !important;">
                                                                {{ __('Amount') }}</th>
                                                            <th class="small-table-header"
                                                                style="background:#ffffff !important;
                                            color: #000 !important;
                                            font-size: 11px !important;
                                            padding: 10px !important;
                                            line-height: 1 !important;">
                                                                {{ __('Bank Name') }}</th>
                                                            <th class="small-table-header"
                                                                style="background:#ffffff !important;
                                            color: #000 !important;
                                            font-size: 11px !important;
                                            padding: 10px !important;
                                            line-height: 1 !important;">
                                                                {{ __('Cheque Image') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($oldLeasesCheckDetails->where('lease_id', $lease->id) as $check)
                                                            <tr>
                                                                <td>{{ $check->check_number }}</td>
                                                                <td>{{ dateFormat($check->check_date) }}</td>
                                                                <td>{{ $check->payee }}</td>
                                                                <td>{{ priceFormat($check->amount) }}</td>
                                                                <td>{{ $check->bank_name }}</td>
                                                                <td>
                                                                    @if ($check->check_image)
                                                                        <a href="{{ asset('storage/' . $check->check_image) }}"
                                                                            target="_blank">
                                                                            <img src="{{ asset('storage/' . $check->check_image) }}"
                                                                                alt="Cheque Image"
                                                                                style="width: 50px; height: auto;">
                                                                        </a>
                                                                    @else
                                                                        {{ __('No Image') }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @endif
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        @else
                            <p>{{ __('No lease details found for this tenant.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        </div>
    @endsection
