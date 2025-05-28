@extends('layouts.company')

@section('page-title', 'tenant Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('company.realestate.tenants.index') }}">tenants</a></li>
    <li class="breadcrumb-item active">{{ $tenant->name }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        <a href="{{ route('company.realestate.tenants.index') }}" title="{{ __('Back to tenants') }}"
            class="btn btn-sm btn-primary me-2">
            <i class="ti ti-arrow-left"></i>
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @can('tenant user details')
                        {{-- Tabs --}}
                        <ul class="nav nav-tabs" id="tenantTabs">
                            @php
                                $tab = request()->get('tab', 'overview');
                            @endphp
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'overview' ? 'active' : '' }}"
                                    href="{{ route('company.realestate.tenants.show', $tenant->id) }}?tab=overview">Overview</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'properties' ? 'active' : '' }}"
                                    href="{{ route('company.realestate.tenants.show', $tenant->id) }}?tab=properties">Leasing
                                    Properties</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'documents' ? 'active' : '' }}"
                                    href="{{ route('company.realestate.tenants.show', $tenant->id) }}?tab=documents">Documents</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'requests' ? 'active' : '' }}"
                                    href="{{ route('company.realestate.tenants.show', $tenant->id) }}?tab=requests">Requests
                                    for
                                    Approval</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'settlements' ? 'active' : '' }}"
                                    href="{{ route('company.realestate.tenants.show', $tenant->id) }}?tab=settlements">Settlement</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'activity_logs' ? 'active' : '' }}"
                                    href="{{ route('company.realestate.tenants.show', $tenant->id) }}?tab=activity_logs">Activity
                                    Log</a>
                            </li>
                        </ul>

                        {{-- Tab Content --}}
                        <div class="tab-content p-4 border border-top-0 rounded-bottom">
                            @if ($tab == 'overview')
                                @include('company.realestate.tenants.partials._overview', [
                                    'tenant' => $tenant,
                                ])
                            @elseif($tab == 'properties')
                                @include('company.realestate.tenants.partials._properties', [
                                    'tenant' => $tenant,
                                ])
                            @elseif($tab == 'requests')
                                @include('company.realestate.tenants.partials._requests', [
                                    'tenant' => $tenant,
                                ])
                            @elseif($tab == 'settlements')
                                @include('company.realestate.tenants.partials._settlements', [
                                    'tenant' => $tenant,
                                ])
                            @elseif($tab == 'documents')
                                @include('company.realestate.tenants.partials._documnets', [
                                    'tenant' => $tenant,
                                ])
                            @elseif($tab == 'activity_logs')
                                @include('company.realestate.tenants.partials._activity_logs', [
                                    'tenant' => $tenant,
                                ])
                            @endif
                        </div>
                    @endcan
                </div>
            </div>

        </div>
    </div>
@endsection
