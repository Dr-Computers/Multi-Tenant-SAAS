@extends('layouts.app')
@section('page-title')
    {{__('Maintenance Request')}}
@endsection
@push('script-page')

@endpush
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Maintenance Request Report')}}</a>
        </li>
    </ul>
@endsection
<script>
    var base64Image = @json(getBase64Image());
    if (base64Image) {
        console.error('Base64 image is done');
    }
</script>
@section('content')
<div class="col-12 mb-4">
    <div class="card border-light shadow-sm rounded">
        <div class="card-body text-center" style="background-color: #f8f9fa;">
            <h5 class="card-title font-weight-bold text-primary">
                {{ __('Total Maintenance Requests') }}
            </h5>
            <p class="card-text">
                <strong>{{ $totalRequests }}</strong>
            </p>
        </div>
    </div>
</div>
<div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap; margin-bottom: 20px;">
            
    <form method="GET" action="{{ route('report.maintenances.index') }}">
    <span style="font-size: 14px; font-weight: bold;">Building:</span>
    <select id="property" name="property" style="padding: 5px; font-size: 14px; min-width: 150px; max-width: 200px; flex-shrink: 0;">
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

    <label for="start_month" style="font-size: 14px; font-weight: bold;">Month Of:</label>
    <input type="month" id="start_month" name="start_month" value="{{ request('start_month') ? request('start_month') : '' }}" style="padding: 5px; font-size: 14px; min-width: 150px;">
   
    <label for="end_month" style="font-size: 14px; font-weight: bold;">To:</label>
    <input type="month" id="end_month" name="end_month" value="{{ request('end_month') ? request('end_month') : '' }}" style="padding: 5px; font-size: 14px; min-width: 150px;">

    <button type="submit" class="btn btn-primary btn-sm" style="margin-right: 10px;">
        {{ __('Filter') }}
    </button>
    </form>
    <a href="{{ route('report.maintenances.index') }}" class="btn btn-secondary btn-sm">
        {{ __('Clear') }}
    </a>
</div>


        
        {{-- <div class="date-filter">
            <label for="min">From:</label>
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
                    <table class="display dataTable cell-border datatbl-advance" data-report-name="Maintenances Report">
                        <thead>
                        <tr>
                            <th>{{__('Request Date')}}</th>
                            <th>{{__('Property')}}</th>
                            <th>{{__('Unit')}}</th>
                            <th>{{__('Issue')}}</th>
                            <th>{{__('Maintainer')}}</th>
                        
                            <th>{{__('Status')}}</th>
                            <th>{{__('Attachment')}}</th>
                            @if(Gate::check('edit maintenance request') || Gate::check('delete maintenance request') || Gate::check('show maintenance request'))
                                <th class="text-right">{{__('Action')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($maintenanceRequests as $request)
                            <tr role="row">
                                <td> {{\Carbon\Carbon::parse($request->request_date)->format('Y-m-d')}} </td>
                                <td> {{!empty($request->properties)?$request->properties->name:'-'}} </td>
                                <td> {{!empty($request->units)?$request->units->name:'-'}} </td>
                                <td> {{!empty($request->types)?$request->types->title:'-'}} </td>
                                <td> {{!empty($request->maintainers)?$request->maintainers->name:'-'}} </td>
                               
                                <td>
                                    @if($request->status=='pending')
                                        <span
                                            class="badge badge-warning"> {{\App\Models\MaintenanceRequest::$status[$request->status]}}</span>
                                    @elseif($request->status=='in_progress')
                                        <span
                                            class="badge badge-info"> {{\App\Models\MaintenanceRequest::$status[$request->status]}}</span>
                                    @else
                                        <span
                                            class="badge badge-primary"> {{\App\Models\MaintenanceRequest::$status[$request->status]}}</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($request->issue_attachment))
                                        <a href="{{asset(Storage::url('upload/issue_attachment')).'/'.$request->issue_attachment}}"
                                           download="download"><i data-feather="download"></i></a>
                                    @else
                                        -
                                    @endif
                                </td>
                                @if(Gate::check('edit maintenance request') || Gate::check('delete maintenance request') || Gate::check('show maintenance request'))
                                    <td class="text-right">
                                        <div class="cart-action">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['maintenance-request.destroy', $request->id]]) !!}
                                            @can('show maintenance request')
                                                <a class="text-warning customModal" data-size="lg"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('View')}}" href="#"
                                                   data-url="{{ route('maintenance-request.show',$request->id) }}"
                                                   data-title="{{__('Maintenance Request Details')}}"> <i
                                                        data-feather="eye"></i></a>
                                            @endcan
                                            @can('edit maintenance request')
                                                <a class="text-success customModal" data-size="lg"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('Edit')}}" href="#"
                                                   data-url="{{ route('maintenance-request.edit',$request->id) }}"
                                                   data-title="{{__('Maintenance Request')}}"> <i
                                                        data-feather="edit"></i></a>
                                            @endcan
                                            @can('delete maintenance request')
                                                <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('Detete')}}" href="#"> <i
                                                        data-feather="trash-2"></i></a>
                                            @endcan
                                            @if(\Auth::user()->type=='maintainer')
                                                <a class="text-success customModal" data-size="lg"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('Status Update')}}" href="#"
                                                   data-url="{{ route('maintenance-request.action',$request->id) }}"
                                                   data-title="{{__('Maintenance Request Status')}}"> <i data-feather="check-square"></i></a>
                                            @endif
                                            {!! Form::close() !!}
                                        </div>

                                    </td>
                                @endif
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

