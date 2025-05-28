@extends('layouts.company')
@section('page-title')
    {{ __('Properties') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Properties') }}</li>
@endsection
@section('action-btn')
    @can('create a property')
        <div class="d-flex">
            <a href="{{ route('company.realestate.properties.create') }}" title="{{ __('Create New Property') }}"
                class="btn btn-sm btn-primary me-2">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endcan
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
                                        <th>{{ __('Owner') }}</th>
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
                                            <td>{{ $property->owner->name }}</td>
                                            <td>{{ $property->categories->pluck('name')->first() }}</td>
                                            <td>{{ $property->purpose_type }}<br>
                                                <span class="badge text-capitalize bg-dark">
                                                    <i class="rounded"></i>
                                                    {{ $property->mode }}</span>
                                            </td>
                                            <td>

                                                <a
                                                    href="{{ route('company.realestate.property.units.index', $property->id) }}">
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
                                                                href="{{ route('company.realestate.property.units.index', $property->id) }}">
                                                                <span> <i class="ti ti-plus text-dark"></i>
                                                                    {{ __('Units') }}</span>
                                                            </a>
                                                        @endcan
                                                        @can('property details')
                                                            <a class="dropdown-item"
                                                                href="{{ route('company.realestate.properties.show', $property->id) }}">
                                                                <span> <i class="ti ti-eye text-dark"></i>
                                                                    {{ __('View') }}</span>
                                                            </a>
                                                        @endcan
                                                        @can('edit a property')
                                                            <a class="dropdown-item"
                                                                href="{{ route('company.realestate.properties.edit', $property->id) }}">
                                                                <span> <i class="ti ti-pencil text-dark"></i>
                                                                    {{ __('Edit') }}</span>
                                                            </a>
                                                        @endcan
                                                        @can('delete a property')
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['company.realestate.properties.destroy', $property->id],
                                                                'id' => 'delete-form-' . $property->id,
                                                            ]) !!}
                                                            <a href="#" class="dropdown-item bs-pass-para "
                                                                data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                                                <i class="ti ti-trash text-dark "></i> {{ __('Delete') }}</a>

                                                            {!! Form::close() !!}
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
