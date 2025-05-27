@extends('layouts.company')
@section('page-title')
    {{ __('Maintenance Request') }}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Maintenance Request Report') }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-3 my-4">
                <div class="card border-light shadow-sm ">
                    <div class="card-body text-center bg-warning rounded">
                        <h5 class="card-title fw-bold text-light">
                            {{ __('Total  Requests') }}
                        </h5>
                        <p class="text-dark fw-bold ">
                            <strong>{{ $totalRequests }}</strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 my-4">
                <div class="card border-light shadow-sm ">
                    <div class="card-body text-center bg-success rounded">
                        <h5 class="card-title fw-bold text-light">
                            {{ __('Solved  Requests') }}
                        </h5>
                        <p class="text-dark fw-bold ">
                            <strong>{{ $totalRequests }}</strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 my-4">
                <div class="card border-light shadow-sm ">
                    <div class="card-body text-center bg-info rounded">
                        <h5 class="card-title fw-bold text-light">
                            {{ __('On doing  Requests') }}
                        </h5>
                        <p class="text-dark fw-bold ">
                            <strong>{{ $totalRequests }}</strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 my-4">
                <div class="card border-light shadow-sm ">
                    <div class="card-body text-center bg-danger rounded">
                        <h5 class="card-title fw-bold text-light">
                            {{ __('Pending  Requests') }}
                        </h5>
                        <p class="text-dark fw-bold ">
                            <strong>{{ $totalRequests }}</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap; margin: 20px;">

        <form method="GET" action="{{ route('company.report.maintenances.index') }}">
            <span style="font-size: 14px; font-weight: bold;">Property:</span>
            <select id="property" name="property"
                style="padding: 5px; font-size: 14px; min-width: 150px; max-width: 200px; flex-shrink: 0;">
                <option value="" disabled selected>--Select--</option>
                @foreach ($filterProperty as $property)
                    <option value="{{ $property->id }}" {{ request('property') == $property->id ? 'selected' : '' }}>
                        {{ $property->name }}</option>
                @endforeach
            </select>

            <span style="font-size: 14px; font-weight: bold;">Tenant:</span>
            <select id="tenant" name="tenant"
                style="padding: 5px; font-size: 14px;  min-width: 150px; max-width: 200px; flex-shrink: 0;">
                <option value="" disabled selected>--Select--</option>
                @foreach ($filterTenant as $tenant)
                    <option value="{{ $tenant->id }}" {{ request('tenant') == $tenant->id ? 'selected' : '' }}>
                        {{ $tenant->name }}</option>
                @endforeach
            </select>

            <label for="start_month" style="font-size: 14px; font-weight: bold;">Month Of:</label>
            <input type="month" id="start_month" name="start_month"
                value="{{ request('start_month') ? request('start_month') : '' }}"
                style="padding: 5px; font-size: 14px; min-width: 150px;">

            <label for="end_month" style="font-size: 14px; font-weight: bold;">To:</label>
            <input type="month" id="end_month" name="end_month"
                value="{{ request('end_month') ? request('end_month') : '' }}"
                style="padding: 5px; font-size: 14px; min-width: 150px;">

            <button type="submit" class="btn btn-primary btn-sm" style="margin-right: 10px;">
                {{ __('Filter') }}
            </button>
        </form>
        <a href="{{ route('company.report.maintenances.index') }}" class="btn btn-secondary btn-sm">
            {{ __('Clear') }}
        </a>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body ">
                <div class="table-responsive">
                    <table class="table ">
                        <thead>
                            <tr>
                                <th>{{ __('Request Date') }}</th>
                                <th>{{ __('Property') }}</th>
                                <th>{{ __('Unit') }}</th>
                                <th>{{ __('Issue') }}</th>
                                <th>{{ __('Maintainer') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($maintenanceRequests as $maintenance)
                                <tr role="row">
                                    <td> {{ \Carbon\Carbon::parse($maintenance->request_date)->format('Y-m-d') }} </td>
                                    <td> {{ !empty($maintenance->property) ? $maintenance->property->name : '-' }} </td>
                                    <td> {{ !empty($maintenance->unit) ? $maintenance->unit->name : '-' }} </td>
                                    <td> {{ !empty($maintenance->issue) ? $maintenance->issue->name : '-' }} </td>
                                    <td> {{ !empty($maintenance->maintainer) ? $maintenance->maintainer->name : '-' }}
                                    </td>

                                    <td>
                                        @if ($maintenance->status == 'pending')
                                            <span class="badge bg-warning rounded text-capitalize">
                                                {{ $maintenance->status }}</span>
                                        @elseif($maintenance->status == 'inprogress')
                                            <span class="badge bg-info rounded text-capitalize">
                                                {{ 'in progress' }}</span>
                                        @elseif($maintenance->status == 'completed')
                                            <span class="badge bg-success rounded text-capitalize">
                                                {{ $maintenance->status }}</span>
                                        @elseif($maintenance->status == 'rejected')
                                            <span class="badge bg-danger rounded text-capitalize">
                                                {{ $maintenance->status }}</span>
                                        @else
                                            <span class="badge bg-primary rounded text-capitalize">
                                                {{ $maintenance->status }}</span>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    </div>
@endsection
