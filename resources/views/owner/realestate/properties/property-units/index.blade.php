@extends('layouts.owner')
@section('page-title', __('Property Units'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('owner.realestate.properties.index') }}">{{ __('Properties') }}</a></li>
    <li class="breadcrumb-item">{{ __('Units') }}</li>
@endsection

@section('action-btn')
    @can('create a unit')
        <a href="{{ route('owner.realestate.property.units.create', ['property_id' => $property->id]) }}"
            class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i> {{ __('Add Unit') }}
        </a>
    @endcan
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @can('unit listing')
                <div class="card">
                    <div class="card-body table-bUsers-style">
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-start">Name</th>
                                        <th class="text-start">Rent Type</th>
                                        <th class="text-start">Price</th>
                                        <th class="text-center">Rooms</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($units as $key => $unit)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td class="text-start text-capitalize">
                                                <a title="{{ $unit->name }}"
                                                    href="{{ route('owner.realestate.property.units.show', [$property->id, $unit->id]) }}">
                                                    {{ $unit->name }}
                                                </a>
                                            </td>
                                            <td class="text-start text-capitalize">{{ $unit->rent_type }}</td>
                                            <td class="text-start">{{ $unit->price }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-dark p-1 px-3 rounded">Bedrooms :
                                                    {{ $unit->bed_rooms }}</span>
                                                <span class="badge bg-dark p-1 px-3 rounded">Bathrooms :
                                                    {{ $unit->bath_rooms }}</span><br>
                                                <span class="badge bg-dark p-1 px-3 rounded">Kitchen :
                                                    {{ $unit->kitchen }}</span>
                                            </td>
                                            <td class="text-center">
                                              
                                                    <span class="badge bg-info p-1 px-3 rounded">
                                                        {{ ucfirst($unit->status) }}</span>
                                               
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group card-option">
                                                    <button type="button" class="btn dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        @can('unit details')
                                                            <a href="{{ route('owner.realestate.property.units.show', [$property->id, $unit->id]) }}"
                                                                class="dropdown-item">
                                                                <i class="ti ti-eye text-dark"></i> {{ __('Show') }}
                                                            </a>
                                                        @endcan
                                                        @can('edit a unit')
                                                            <a href="{{ route('owner.realestate.property.units.edit', [$property->id, $unit->id]) }}"
                                                                class="dropdown-item">
                                                                <i class="ti ti-pencil text-dark"></i> {{ __('Edit') }}
                                                            </a>
                                                        @endcan
                                                        @can('delete a unit')
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['owner.realestate.property.units.destroy', $property->id, $unit->id],
                                                                'id' => 'delete-form-' . $unit->id,
                                                            ]) !!}
                                                            <a href="#" class="dropdown-item bs-pass-para"
                                                                onclick="event.preventDefault(); document.getElementById('delete-form-{{ $unit->id }}').submit();">
                                                                <i class="ti ti-trash text-dark"></i> {{ __('Delete') }}
                                                            </a>
                                                            {!! Form::close() !!}
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection
