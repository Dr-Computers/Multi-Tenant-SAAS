@extends('layouts.app')

@section('page-title')
    {{ __('Invoice') }}
@endsection

@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}"><h1>{{ __('Dashboard') }}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Units Report') }}</a>
        </li>
    </ul>
@endsection
<script>
    var base64Image = @json(getBase64Image());
    console.error(base64Image);
    if (base64Image) {
        console.error('Base64 image is done');
    }
</script>
@section('content')

<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-light shadow-sm rounded">
            <div class="card-body text-center" style="background-color: #f8f9fa;">
                <h5 class="card-title font-weight-bold text-primary">
                    {{ __('Total Units') }}
                </h5>
                <p class="card-text">
                    <strong>{{ $totalUnits }}</strong>
                </p>
            </div>
        </div>
    </div>
    <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap; margin-bottom: 20px;">
        <span style="font-size: 14px; font-weight: bold;">Building:</span>
        <form method="GET" action="{{ route('report.units.index') }}">
        <select id="property" name="property" style="padding: 5px; font-size: 14px;">
            <option value="" disabled selected>--Select--</option>
            @foreach($filterProperty as $property)
            <option value="{{$property->id}}" {{ request('property') == $property->id ? 'selected' : '' }}>{{$property->name}}</option>
            @endforeach
        </select>

        <span style="font-size: 14px; font-weight: bold;">Tenant:</span>
        <select id="tenant" name="tenant" style="padding: 5px; font-size: 14px;  min-width: 150px; max-width: 200px; flex-shrink: 0;">
            <option value="" disabled selected>--Select--</option>
            @foreach($filterTenant as $tenant)
            <option value="{{$tenant->id}}" {{ request('tenant') == $tenant->id ? 'selected' : '' }}>{{$tenant->user->first_name . ' ' . $tenant->user->last_name}}</option>
            @endforeach
        </select>
    
        {{-- <label for="start_month" style="font-size: 14px; font-weight: bold;">Month Of:</label>
        <input type="month" id="start_month" name="start_month" value="{{ request('start_month') ? request('start_month') : '' }}" style="padding: 5px; font-size: 14px;">
       
        <label for="end_month" style="font-size: 14px; font-weight: bold;">To:</label>
        <input type="month" id="end_month" name="end_month" value="{{ request('end_month') ? request('end_month') : '' }}" style="padding: 5px; font-size: 14px;"> --}}
    
        <button type="submit" class="btn btn-primary btn-sm" style="margin-right: 10px;">
            {{ __('Filter') }}
        </button>
        </form>
        <a href="{{ route('report.units.index') }}" class="btn btn-secondary btn-sm">
            {{ __('Clear') }}
        </a>
    </div>  
    {{-- <div class="col-12 mb-4">
        <!-- Filter Form -->
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('report.units.index') }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tenant_filter">{{ __('Filter by Tenant') }}</label>
                                <select name="tenant_id" id="tenant_filter" class="form-control">
                                    <option value="">{{ __('Select Tenant') }}</option>
                                    @foreach($tenants as $tenant)
                                        <option value="{{ $tenant->id }}" 
                                            {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                            {{ $tenant->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                            <a href="{{ route('report.units.index') }}" class="btn btn-secondary ml-2">{{ __('Reset') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="display dataTable cell-border datatbl-advance" data-report-name="Units Report">
                    <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Rent Type') }}</th>
                        <th>{{ __('Rent') }}</th>
                        {{-- <th>{{ __('Amount') }}</th> --}}
                        <th>{{ __('Rent Duration') }}</th>
                        <th>{{ __('Property') }}</th>
                        <th>{{ __('Tenant') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($units as $unit)
                        <tr>
                            <td>{{ $unit->name }}</td>
                            <td>{{ $unit->rent_type }}</td>
                            <td>{{ priceFormat(($unit->latestRateChange)->unit_amount) }}</td>
                            <td>
                                @if($unit->rent_type == 'custom')
                                    <span>{{ __('Start Date') }}: </span>{{ dateFormat($unit->start_date) }} <br>
                                    <span>{{ __('End Date') }}: </span>{{ dateFormat($unit->end_date) }} <br>
                                    <span>{{ __('Payment Due Date') }}: </span>{{ dateFormat($unit->payment_due_date) }}
                                @else
                                    {{ $unit->rent_duration }}
                                @endif
                            </td>
                            <td>{{ $unit->properties->name ?? '-' }}</td>
                            <td>{{ $unit->tenants()->user->name ?? '-' }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('units.view', $unit->id) }}" class="">
                                        <i
                                        data-feather="eye"></i>
                                    </a>
                                   
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            {{-- <th colspan="2">Total(OMR):</th> --}}
                            <th colspan="2"></th>
                            <th colspan="1">{{ priceFormat($totalAmount) }}</th>
                            <th colspan="4"></th>
                        </tr>
                    </tfoot>
                </table>
                {{-- Pagination (Optional) --}}
                {{-- <div class="d-flex justify-content-end" style="margin-top: 10px;">
                    {!! $units->links() !!}
                </div>  --}}
            </div>
        </div>
    </div>
</div>
@endsection
