@extends('layouts.owner')
@section('page-title')
    {{ __('Properties') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Properties') }}</li>
@endsection
@section('action-btn')
    
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-bUsers-style">
                    @can('properties listing')
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('#') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Units') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($properties ?? [] as $key => $property)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $property->name }}</td>
                                            <td>{{ $property->categories->pluck('name')->first() }}</td>
                                            <td>{{ $property->purpose_type }}<br>
                                                <span class="badge text-capitalize bg-dark">
                                                    <i class="rounded"></i>
                                                    {{ $property->mode }}</span>
                                            </td>
                                            <td>

                                                <a
                                                    href="{{ route('owner.realestate.property.units.index', $property->id) }}">
                                                    {{ $property->units ? count($property->units) : 0 }}
                                                </a>
                                            </td>
                                            <td>
                                                @if ($property->moderation_status == '1')
                                                    <span class="badge bg-success p-1 px-3 rounded">
                                                        {{ ucfirst('Enabled') }}</span>
                                                @else
                                                    <span class="badge bg-danger p-1 px-3 rounded">
                                                        {{ ucfirst('Disabled') }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                <div class="btn-group card-option">

                                                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        @can('unit listing')
                                                            <a class="dropdown-item"
                                                                href="{{ route('owner.realestate.property.units.index', $property->id) }}">
                                                                <span> <i class="ti ti-plus text-dark"></i>
                                                                    {{ __('Units') }}</span>
                                                            </a>
                                                        @endcan
                                                        @can('property details')
                                                            <a class="dropdown-item"
                                                                href="{{ route('owner.realestate.properties.show', $property->id) }}">
                                                                <span> <i class="ti ti-eye text-dark"></i>
                                                                    {{ __('View') }}</span>
                                                            </a>
                                                        @endcan
                                                       
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <h6>No properties found..!</h6>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
