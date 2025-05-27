@extends('layouts.company')
@section('page-title')
    {{ __('Tenant') }}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Tenants Report') }}</a>
        </li>
    </ul>
@endsection
@section('content')
    <div class="row">
        <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap; margin: 20px;">

            <form method="GET" action="{{ route('company.report.tenants.index') }}">
                {{--     <span style="font-size: 14px; font-weight: bold;">Building:</span>
        <select id="property" name="property" style="padding: 5px; font-size: 14px;">
            <option value="" disabled selected>--Select--</option>
            @foreach ($filterProperty as $property)
            <option value="{{$property->id}}" {{ request('property') == $property->id ? 'selected' : '' }}>{{$property->name}}</option>
            @endforeach
        </select>  --}}

                <span style="font-size: 14px; font-weight: bold;">Tenant:</span>
                <select id="tenant" name="tenant"
                    style="padding: 5px; font-size: 14px;  min-width: 150px; max-width: 200px; flex-shrink: 0;">
                    <option value="" disabled selected>--Select--</option>
                    @foreach ($filterTenant as $tenant)
                        <option value="{{ $tenant->id }}" {{ request('tenant') == $tenant->id ? 'selected' : '' }}>
                            {{ $tenant->name }}</option>
                    @endforeach
                </select>


                <button type="submit" class="btn btn-primary btn-sm" style="margin-right: 10px;">
                    {{ __('Filter') }}
                </button>
            </form>
            <a href="{{ route('company.report.tenants.index') }}" class="btn btn-secondary btn-sm">
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
                                    {{-- <th class="text-center">{{ __('Lease Start Date') }}</th>
                            <th class="text-center">{{ __('Lease End Date') }}</th> --}}
                                    <th class="text-center">{{ __('Profile Picture') }}</th>
                                    <th class="text-center">{{ __('Full Name') }}</th>
                                    <th class="text-center">{{ __('Email') }}</th>
                                    <th class="text-center">{{ __('Phone') }}</th>
                                    {{-- <th class="text-center">{{ __('Property') }}</th>
                            <th class="text-center">{{ __('Unit') }}</th> --}}
                                    <th class="text-center">{{ __('Status') }}</th>
                                    {{-- <th class="text-center">{{ __('Tenant Type') }}</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tenants as $tenant)
                                    <tr class="table-row">

                                        <td class="text-center">
                                            <img src="{{ asset('storage/' . $tenant->avatar_url) }}"
                                                class="h-10 w-auto border mb-1 img-fluid rounded-circle mx-auto">

                                        </td>
                                        <td class="text-center">
                                            {{ ucfirst($tenant->name) }}
                                        </td>
                                        <td class="text-center">{{ $tenant->email }}
                                        </td>
                                        <td class="text-center">
                                            {{ $tenant->mobile }}
                                        </td>

                                        {{-- <td class="text-center">
                                    <span class="badge 
                                        @if ($tenant->status == 'active') bg-success 
                                        @elseif($tenant->status == 'canceled') bg-secondary 
                                        @elseif($tenant->status == 'case') bg-danger 
                                        @else bg-warning 
                                        @endif  text-white">
                                        {{ ucfirst($tenant->status) }}
                                    </span>
                                </td> --}}
                                        <td class="text-center">
                                            <span
                                                class="badge 
                                        @if ($tenant->status_type == 'renewed') bg-warning 
                                          @elseif($tenant->status_type == 'open') bg-info 
                                        @elseif($tenant->status_type == 'new') bg-success 
                                        @else bg-dark @endif text-white">
                                                {{ ucfirst($tenant->status_type) }}
                                            </span>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
