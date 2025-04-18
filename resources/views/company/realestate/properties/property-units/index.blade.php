@extends('layouts.company')
@section('page-title', __('Property Units'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('company.realestate.properties.index') }}">{{ __('Properties') }}</a></li>
    <li class="breadcrumb-item">{{ __('Units') }}</li>
@endsection

@section('action-btn')
    <a href="{{ route('company.realestate.property.units.create', $property->id) }}" class="btn btn-sm btn-primary">
        <i class="ti ti-plus"></i> {{ __('Add Unit') }}
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-bUsers-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Kitchen</th>
                                    <th>Bath Rooms</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($units as $key => $unit)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $unit->name }}</td>
                                        <td>{{ $unit->kitchen }}</td>
                                        <td>{{ $unit->bath_rooms }}</td>
                                        <td>{{ $unit->status ? 'Active' : 'Inactive' }}</td>
                                        <td>
                                            <div class="btn-group card-option">
                                                <button type="button" class="btn dropdown-toggle"
                                                    data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="{{ route('company.realestate.property.units.edit', [$property->id, $unit->id]) }}"
                                                        class="dropdown-item">
                                                        <i class="ti ti-pencil text-dark"></i> {{ __('Edit') }}
                                                    </a>
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['company.realestate.property.units.destroy', $property->id, $unit->id],
                                                        'id' => 'delete-form-' . $unit->id,
                                                    ]) !!}
                                                    <a href="#" class="dropdown-item bs-pass-para"
                                                        onclick="event.preventDefault(); document.getElementById('delete-form-{{ $unit->id }}').submit();">
                                                        <i class="ti ti-trash text-dark"></i> {{ __('Delete') }}
                                                    </a>
                                                    {!! Form::close() !!}
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
        </div>
    </div>
@endsection
