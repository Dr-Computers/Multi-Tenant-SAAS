@extends('layouts.company')
@section('page-title')
    {{ __('Invoice') }}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Insurance Expiry Report') }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">

        {{-- <div class="date-filter">
            <label for="min">From:</label>
            <input type="text" id="min" placeholder="Select Date" />
            <label for="max">To:</label>
            <input type="text" id="max" placeholder="Select Date" />
            <button id="clearDates" class="clear-btn">Clear</button>
            <button id="todayMax" class="today-btn">Today</button>
            <button id="lastMonthMax" class="last-month-btn">Last Month</button>
        </div> --}}
        <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap; margin: 20px;">
            <span style="font-size: 14px; font-weight: bold;">Building:</span>
            <form method="GET" action="">
                <select id="property" name="property" style="padding: 5px; font-size: 14px;">
                    <option value="" disabled selected>--Select--</option>
                    @foreach ($filterProperty as $property)
                        <option value="{{ $property->id }}" {{ request('property') == $property->id ? 'selected' : '' }}>
                            {{ $property->name }}</option>
                    @endforeach
                </select>

                {{-- <span style="font-size: 14px; font-weight: bold;">Tenant:</span>
            <select id="tenant" name="tenant" style="padding: 5px; font-size: 14px;  min-width: 150px; max-width: 200px; flex-shrink: 0;">
                <option value="" disabled selected>--Select--</option>
                @foreach ($filterTenant as $tenant)
                <option value="{{$tenant->id}}" {{ request('tenant') == $tenant->id ? 'selected' : '' }}>{{$tenant->user->first_name . ' ' . $tenant->user->last_name}}</option>
                @endforeach
            </select> --}}

                <label for="start_month" style="font-size: 14px; font-weight: bold;">Month Of:</label>
                <input type="month" id="start_month" name="start_month"
                    value="{{ request('start_month') ? request('start_month') : '' }}"
                    style="padding: 5px; font-size: 14px;">

                <label for="end_month" style="font-size: 14px; font-weight: bold;">To:</label>
                <input type="month" id="end_month" name="end_month"
                    value="{{ request('end_month') ? request('end_month') : '' }}" style="padding: 5px; font-size: 14px;">

                <button type="submit" class="btn btn-primary btn-sm" style="margin-right: 10px;">
                    {{ __('Filter') }}
                </button>
            </form>
            <a href="" class="btn btn-secondary btn-sm">
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
                                    <th>Insurance Expiry Date</th>
                                    <th>Property</th>
                                    <th>Insurance Start Date</th>
                                    <th>Days to Expiry</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $result)
                                    @php
                                        $expiryDate = \Carbon\Carbon::parse($result->insurance_expiry_date);
                                        $formattedDate = \Carbon\Carbon::parse($result->insurance_expiry_date)->format(
                                            'Y-m-d',
                                        );
                                        $remainingDays = $expiryDate->diffInDays(\Carbon\Carbon::now());
                                    @endphp
                                    <tr>
                                        <td>{{ $formattedDate ?? 'N/A' }}</td>
                                        <td>{{ $result->name ?? 'N/A' }}</td>
                                        <td>{{ $result->insurance_start_date ?? 'N/A' }}</td>
                                        <td>
                                            <strong
                                                @if ($remainingDays <= 0) style="color: red;"  <!-- Highlight expired in red -->
                                            @elseif($remainingDays <= 7)
                                                style="color: orange;"  <!-- Highlight within a week in orange -->
                                            @else
                                                style="color: green;"  <!-- Highlight more than 7 days in green --> @endif
                                                {{ $remainingDays }} days </strong>
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
