@extends('layouts.app')
@section('page-title')
    {{__('Maintainer')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Maintainers Report')}}</a>
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
<div class="row">
    <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap; margin-bottom: 20px;">
        <span style="font-size: 14px; font-weight: bold;">Building:</span>
        <form method="GET" action="{{ route('report.maintainers.index') }}">
        <select id="property" name="property" style="padding: 5px; font-size: 14px;">
            <option value="" disabled selected>--Select--</option>
            @foreach($filterProperty as $property)
            <option value="{{$property->id}}" {{ request('property') == $property->id ? 'selected' : '' }}>{{$property->name}}</option>
            @endforeach
        </select>

        {{-- <span style="font-size: 14px; font-weight: bold;">Tenant:</span>
        <select id="tenant" name="tenant" style="padding: 5px; font-size: 14px;  min-width: 150px; max-width: 200px; flex-shrink: 0;">
            <option value="" disabled selected>--Select--</option>
            @foreach($filterTenant as $tenant)
            <option value="{{$tenant->id}}" {{ request('tenant') == $tenant->id ? 'selected' : '' }}>{{$tenant->user->first_name . ' ' . $tenant->user->last_name}}</option>
            @endforeach
        </select> --}}
    
        <label for="start_month" style="font-size: 14px; font-weight: bold;">Month Of:</label>
        <input type="month" id="start_month" name="start_month" value="{{ request('start_month') ? request('start_month') : '' }}" style="padding: 5px; font-size: 14px;">
       
        <label for="end_month" style="font-size: 14px; font-weight: bold;">To:</label>
        <input type="month" id="end_month" name="end_month" value="{{ request('end_month') ? request('end_month') : '' }}" style="padding: 5px; font-size: 14px;">
    
        <button type="submit" class="btn btn-primary btn-sm" style="margin-right: 10px;">
            {{ __('Filter') }}
        </button>
        </form>
        <a href="{{ route('report.maintainers.index') }}" class="btn btn-secondary btn-sm">
            {{ __('Clear') }}
        </a>
    </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="display dataTable cell-border datatbl-advance" data-report-name="Maintainers Report">
                        <thead>
                            <tr>
                                <th>{{ __('Profile Picture') }}</th>
                                <th>{{ __('Full Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Phone') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Created Date') }}</th>
                                <th>{{ __('Properties') }}</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($maintainers))
                            @foreach($maintainers as $maintainer)
                                <tr class="table-row">
                                    <td class="text-center">
                                        <img class="img-fluid rounded-circle" 
                                             src="{{ (!empty($maintainer->user) && !empty($maintainer->user->profile)) ? asset(Storage::url("upload/profile/".$maintainer->user->profile)) : asset(Storage::url("upload/profile/avatar.png")) }}" 
                                             alt="" style="width: 35px; height: 35px;">
                                    </td>
                                    <td>
                                        <a class="customModal" href="#" data-size="md"
                                           data-url="{{ route('maintainer.edit', $maintainer->id) }}"  
                                           data-title="{{ __('Edit Maintainer') }}">
                                            {{ !empty($maintainer->user) ? ucfirst($maintainer->user->first_name.' '.$maintainer->user->last_name) : '-' }}
                                        </a>
                                    </td>
                                    <td>{{ !empty($maintainer->user) ? $maintainer->user->email : '-' }}</td>
                                    <td>{{ !empty($maintainer->user->phone_number) ? $maintainer->user->phone_number : '-' }}</td>
                                    <td>{{ !empty($maintainer->types) ? $maintainer->types->title : '-' }}</td>
                                    <td>{{ dateFormat($maintainer->created_at) }}</td>
                                    <td>
                                        @foreach($maintainer->properties() as $property)
                                        @if (!empty($property))
                                            {{ $property->name }}<br>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    {{-- <div class="d-flex justify-content-end" style="margin-top: 10px;">
                        {!! $maintainers->onEachSide(2)->links() !!}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    
@endsection

