@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Furnishings') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Furnishings') }}</li>
@endsection


@section('action-btn')
    <div class="float-end">
        @can('manage furnishing request')
            <a href="{{ route('admin.realestate.furnishings.requests') }}" class="btn btn-sm btn-dark">
                <i class="ti ti-help"></i> Requests From Company
            </a>
        @endcan
        @can('create furnishing')
            <a href="#" data-size="xl" data-url="{{ route('admin.realestate.furnishings.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="{{ __('Create') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i> Create Furnishings
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        @can('furnishing listing')
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('#') }} </th>
                                        <th>{{ __('Name') }} </th>
                                        <th width="150">{{ __('Action') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($furnishings as $key => $furnishing)
                                        <tr class="font-style">
                                            <td class="furnishing text-capitalize">{{ $key + 1 }}</td>
                                            <td class="Permission">
                                                {{ $furnishing->name }}
                                            </td>
                                            <td class="Action">
                                                <span>
                                                    @can('edit furnishing')
                                                        <div class="action-btn me-2">
                                                            <a href="#"
                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center bg-warning"
                                                                data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                data-url="{{ route('admin.realestate.furnishings.edit', $furnishing->id) }}"
                                                                data-size="xl" data-ajax-popup="true"
                                                                data-original-title="{{ __('Edit') }}">
                                                                <span> <i class="ti ti-pencil text-white"></i></span>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('delete furnishing')
                                                        <div class="action-btn">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['admin.realestate.furnishings.destroy', $furnishing->id],
                                                                'id' => 'delete-form-' . $furnishing->id,
                                                            ]) !!}
                                                            <a href="#"
                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para bg-danger"
                                                                data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                                                <i class="ti ti-trash text-white text-white"></i>
                                                            </a>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    @endcan
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
