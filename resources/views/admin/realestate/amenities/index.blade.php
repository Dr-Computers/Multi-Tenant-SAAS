@extends('layouts.admin')
@section('page-title')
    {{ __('Manage amenities') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('amenities') }}</li>
@endsection


@section('action-btn')
    <div class="float-end">

        <a href="#" data-size="xl" data-url="{{ route('admin.realestate.amenities.create') }}" data-ajax-popup="true"
            data-bs-toggle="tooltip" title="{{ __('Create') }}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('#') }} </th>
                                    <th>{{ __('Name') }} </th>
                                    <th width="150">{{ __('Action') }} </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($amenities as $key => $amenity)
                                    <tr class="font-style">
                                        <td class="amenity text-capitalize">{{ $key + 1 }}</td>
                                        <td class="Permission">
                                            {{ $amenity->name }}
                                        </td>
                                        <td class="Action">
                                            <span>
                                                <div class="action-btn me-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bg-warning"
                                                        data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                        data-url="{{ route('admin.realestate.amenities.edit', $amenity->id) }}"
                                                        data-size="xl" data-ajax-popup="true"
                                                        data-original-title="{{ __('Edit') }}">
                                                        <span> <i class="ti ti-pencil text-white"></i></span>
                                                    </a>
                                                </div>

                                                <div class="action-btn">
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['admin.realestate.amenities.destroy', $amenity->id],
                                                        'id' => 'delete-form-' . $amenity->id,
                                                    ]) !!}
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm  align-items-center bs-pass-para bg-danger"
                                                        data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                                        <i class="ti ti-trash text-white text-white"></i>
                                                    </a>
                                                    {!! Form::close() !!}
                                                </div>
                                            </span>
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
