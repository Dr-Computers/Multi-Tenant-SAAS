@extends('layouts.owner')
@section('page-title')
    {{ __('Furnishings') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Furnishings') }}</li>
@endsection
@section('action-btn')
    @can('create a maintenance invoice')
        <div class="d-flex">
            <button href="#" data-size="lg" data-url="{{ route('company.realestate.maintenance-requests.create') }}"
                data-ajax-popup2="true" data-bs-toggle="tooltip" title="{{ __('Create a new Request ') }}"
                class="btn btn-sm btn-primary me-2">
                <i class="ti ti-plus"></i> Create a new Request
            </button>
        </div>
    @endcan
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @can('maintenance requests listing')
                        {{-- Tabs --}}
                        {{-- <ul class="nav nav-tabs" id="ownerTabs">
                            @php
                                $tab = request()->get('tab', 'all-requests');
                            @endphp
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'all-requests' ? 'active' : '' }}"
                                    href="{{ route('owner.realestate.maintenance-requests.index') }}?tab=all-requests">All
                                    Requests</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'pending-requests' ? 'active' : '' }}"
                                    href="{{ route('owner.realestate.maintenance-requests.index') }}?tab=pending-requests">
                                    Pending Requests</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'in-progress-requests' ? 'active' : '' }}"
                                    href="{{ route('owner.realestate.maintenance-requests.index') }}?tab=in-progress-requests">
                                    In progress Requests</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'completed-requests' ? 'active' : '' }}"
                                    href="{{ route('owner.realestate.maintenance-requests.index') }}?tab=completed-requests">
                                    Completed Requests</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'ungenerated-invoices' ? 'active' : '' }}"
                                    href="{{ route('owner.realestate.maintenance-requests.index') }}?tab=ungenerated-invoices">
                                    Ungenerated Invoices</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'settlements' ? 'active' : '' }}"
                                    href="{{ route('owner.realestate.maintenance-requests.index') }}?tab=due-invoices">
                                    Due Invoices</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'activity_logs' ? 'active' : '' }}"
                                    href="{{ route('owner.realestate.maintenance-requests.index') }}?tab=paid-invoices">
                                    Paid Invoices</a>
                            </li>
                        </ul> --}}

                        {{-- Tab Content --}}
                        {{-- <div class="tab-content p-4 border border-top-0 rounded-bottom">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-body table-bUsers-style">
                                            <div class="table-responsive" style="min-height:80vh;">

                                                @if ($tab == 'all-requests') --}}
                        @include('owner.realestate.maintenance-requests.partials.request-table', [
                            'tabRequests' => $allRequests,
                            'status' => 'show',
                        ])
                        {{-- @elseif($tab == 'pending-requests')
                                                    @include(
                                                        'owner.realestate.maintenance-requests.partials.request-table',
                                                        [
                                                            'tabRequests' => $pendingRequests,
                                                        ]
                                                    )
                                                @elseif($tab == 'in-progress-requests')
                                                    @include(
                                                        'owner.realestate.maintenance-requests.partials.request-table',
                                                        [
                                                            'tabRequests' => $InprogressRequests,
                                                        ]
                                                    )
                                                @elseif($tab == 'completed-requests')
                                                    @include(
                                                        'owner.realestate.maintenance-requests.partials.request-table',
                                                        [
                                                            'tabRequests' => $completedRequests,
                                                        ]
                                                    )
                                                @elseif($tab == 'due-invoices')
                                                    @include(
                                                        'owner.realestate.maintenance-requests.partials.request-table',
                                                        [
                                                            'tabRequests' => $dueInvoices,
                                                        ]
                                                    )
                                                @elseif($tab == 'paid-invoices')
                                                    @include(
                                                        'owner.realestate.maintenance-requests.partials.request-table',
                                                        [
                                                            'tabRequests' => $paidInvoices,
                                                        ]
                                                    )
                                                @endif --}}
                        {{-- </div>
                                        </div>
                                    </div>
                                </div> --}}
                        {{-- </div>
                        </div> --}}
                    @endcan
                </div>
            </div>

        </div>
    </div>
@endsection
