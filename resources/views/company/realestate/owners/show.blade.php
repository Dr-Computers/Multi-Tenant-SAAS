@extends('layouts.company')

@section('page-title', 'Owner Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('company.realestate.owners.index') }}">Owners</a></li>
    <li class="breadcrumb-item active">{{ $owner->name }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        <a  href="{{ route('company.realestate.owners.index') }}"  title="{{ __('Back to Owners') }}" class="btn btn-sm btn-primary me-2">
            <i class="ti ti-arrow-left"></i>
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    {{-- Tabs --}}
                    <ul class="nav nav-tabs" id="ownerTabs">
                        @php
                            $tab = request()->get('tab', 'overview');
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'overview' ? 'active' : '' }}"
                                href="{{ route('company.realestate.owners.show', $owner->id) }}?tab=overview">Overview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'properties' ? 'active' : '' }}"
                                href="{{ route('company.realestate.owners.show', $owner->id) }}?tab=properties">Properties</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'documents' ? 'active' : '' }}"
                                href="{{ route('company.realestate.owners.show', $owner->id) }}?tab=documents">Documents</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'requests' ? 'active' : '' }}"
                                href="{{ route('company.realestate.owners.show', $owner->id) }}?tab=requests">Requests for
                                Approval</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'settlements' ? 'active' : '' }}"
                                href="{{ route('company.realestate.owners.show', $owner->id) }}?tab=settlements">Settlement</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'activity_logs' ? 'active' : '' }}"
                                href="{{ route('company.realestate.owners.show', $owner->id) }}?tab=activity_logs">Activity Log</a>
                        </li>
                    </ul>

                    {{-- Tab Content --}}
                    <div class="tab-content p-4 border border-top-0 rounded-bottom">
                        @if ($tab == 'overview')
                            @include('company.realestate.owners.partials._overview', ['owner' => $owner])
                        @elseif($tab == 'properties')
                            @include('company.realestate.owners.partials._properties', ['owner' => $owner])
                        @elseif($tab == 'requests')
                            @include('company.realestate.owners.partials._requests', ['owner' => $owner])
                        @elseif($tab == 'settlements')
                            @include('company.realestate.owners.partials._settlements', [
                                'owner' => $owner,
                            ])
                        @elseif($tab == 'documents')
                            @include('company.realestate.owners.partials._documnets', ['owner' => $owner])
                        @elseif($tab == 'activity_logs')
                            @include('company.realestate.owners.partials._activity_logs', ['owner' => $owner])
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
