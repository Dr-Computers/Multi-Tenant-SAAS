@extends('layouts.app')
@section('page-title')
    {{__('Tenant')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Tenants Report')}}</a>
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
    <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap; margin-bottom: 20px;">
         
        <form method="GET" action="{{ route('report.tenants.index') }}">
        {{--     <span style="font-size: 14px; font-weight: bold;">Building:</span>
        <select id="property" name="property" style="padding: 5px; font-size: 14px;">
            <option value="" disabled selected>--Select--</option>
            @foreach($filterProperty as $property)
            <option value="{{$property->id}}" {{ request('property') == $property->id ? 'selected' : '' }}>{{$property->name}}</option>
            @endforeach
        </select>  --}}

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
        <input type="month" id="end_month" name="end_month" value="{{ request('end_month') ? request('end_month') : '' }}" style="padding: 5px; font-size: 14px;">
     --}}
        <button type="submit" class="btn btn-primary btn-sm" style="margin-right: 10px;">
            {{ __('Filter') }}
        </button>
        </form>
        <a href="{{ route('report.tenants.index') }}" class="btn btn-secondary btn-sm">
            {{ __('Clear') }}
        </a>
    </div>
    {{-- <div class="date-filter">
        <label for="min">From (Select Lease Start Date):</label>
        <input type="text" id="min" placeholder="Select Date" />
        <label for="max">To:</label>
        <input type="text" id="max" placeholder="Select Date" />
        <button id="clearDates" class="clear-btn">Clear</button>
        <button id="todayMax" class="today-btn">Today</button>
        <button id="lastMonthMax" class="last-month-btn">Last Month</button>
    </div> --}}
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                <table class="display dataTable cell-border datatbl-advance" data-report-name="Tenants Report">
                    <thead>
                        <tr>
                            {{-- <th class="text-center">{{ __('Lease Start Date') }}</th>
                            <th class="text-center">{{ __('Lease End Date') }}</th> --}}
                            <th class="text-center">{{ __('Profile Picture') }}</th>
                            <th class="text-center">{{ __('Full Name') }}</th>
                            <th class="text-center">{{ __('Email') }}</th>
                            <th class="text-center">{{ __('Phone') }}</th>
                            {{-- <th class="text-center">{{ __('Property') }}</th>
                            <th class="text-center">{{ __('Unit') }}</th> --}}
                            {{-- <th class="text-center">{{ __('Status') }}</th> --}}
                            <th class="text-center">{{ __('Tenant Type') }}</th>
                            <th class="text-center">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tenants as $tenant)
                            <tr class="table-row">
                               
                                <td class="text-center">
                                    <img class="img-fluid rounded-circle" 
                                         src="{{ (!empty($tenant->user) && !empty($tenant->user->profile)) ? asset(Storage::url('upload/profile/'.$tenant->user->profile)) : asset(Storage::url('upload/profile/avatar.png')) }}" 
                                         alt="Profile Picture" style="width: 50px; height: 50px;">
                                </td>
                                <td class="text-center">
                                    {{ ucfirst(!empty($tenant->user) ? $tenant->user->first_name : '') . ' ' . ucfirst(!empty($tenant->user) ? $tenant->user->last_name : '') }}
                                </td>
                                <td class="text-center">{{ !empty($tenant->user) ? $tenant->user->email : '-' }}</td>
                                <td class="text-center">{{ !empty($tenant->user) ? $tenant->user->phone_number : '-' }}</td>
                              
                                {{-- <td class="text-center">
                                    <span class="badge 
                                        @if($tenant->status == 'active') bg-success 
                                        @elseif($tenant->status == 'canceled') bg-secondary 
                                        @elseif($tenant->status == 'case') bg-danger 
                                        @else bg-warning 
                                        @endif  text-white">
                                        {{ ucfirst($tenant->status) }}
                                    </span>
                                </td> --}}
                                <td class="text-center">
                                    <span class="badge 
                                        @if($tenant->status_type == 'renewed') bg-warning 
                                          @elseif($tenant->status_type == 'open') bg-info 
                                        @elseif($tenant->status_type == 'new') bg-success 
                                        @else bg-dark 
                                        @endif text-white">
                                        {{ ucfirst($tenant->status_type) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('tenant.lease-details', $tenant->id) }}" class="">
                                            <i
                                            data-feather="eye"></i>
                                        </a>
                                       
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
                {{-- <div class="d-flex justify-content-end mt-3">
                    {!! $tenants->onEachSide(2)->links() !!}
                </div> --}}
            </div>
        </div>
    </div>
    
</div>
    
    <!-- Add this CSS for styling -->
   
    
@endsection

