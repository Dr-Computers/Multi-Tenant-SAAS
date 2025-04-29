

@extends('layouts.company')

@section('page-title', 'User Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('company.hrms.users.index') }}">users</a></li>
    <li class="breadcrumb-item active">{{ $user->name }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        <a  href="{{ route('company.hrms.users.index') }}"  title="{{ __('Back to Users') }}" class="btn btn-sm btn-primary me-2">
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
                    <ul class="nav nav-tabs" id="userTabs">
                        @php
                            $tab = request()->get('tab', 'overview');
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'overview' ? 'active' : '' }}"
                                href="{{ route('company.hrms.users.show', $user->id) }}?tab=overview">Overview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'documents' ? 'active' : '' }}"
                                href="{{ route('company.hrms.users.show', $user->id) }}?tab=documents">Documents</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'activity_logs' ? 'active' : '' }}"
                                href="{{ route('company.hrms.users.show', $user->id) }}?tab=activity_logs">Activity Log</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'attendences' ? 'active' : '' }}"
                                href="{{ route('company.hrms.users.show', $user->id) }}?tab=attendences">Attendence</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'payrolls' ? 'active' : '' }}"
                                href="{{ route('company.hrms.users.show', $user->id) }}?tab=payrolls">Payrolls</a>
                        </li>
                    </ul>

                    {{-- Tab Content --}}
                    <div class="tab-content p-4 border border-top-0 rounded-bottom">
                        @if ($tab == 'overview')
                            @include('company.hrms.user.partials._overview', ['user' => $user])
                        @elseif($tab == 'documents')
                            @include('company.hrms.user.partials._documnets', ['user' => $user])
                        @elseif($tab == 'activity_logs')
                            @include('company.hrms.user.partials._activity_logs', ['user' => $user])
                        @elseif($tab == 'attendences')
                            @include('company.hrms.user.partials._attendence', [
                                'user' => $user,
                            ])
                        @elseif($tab == 'payrolls')
                            @include('company.hrms.user.partials._payrolls', [
                                'user' => $user,
                            ])
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
