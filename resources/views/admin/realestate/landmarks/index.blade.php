@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Landmarks') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Landmarks') }}</li>
@endsection


@section('action-btn')
    <div class="float-end">
        <a href="{{ route('admin.realestate.landmarks.create') }}" class="btn btn-sm btn-dark">
            <i class="ti ti-help"></i> Requests From Company
        </a>
        <a href="#" data-size="xl" data-url="{{ route('admin.realestate.landmarks.create') }}" data-ajax-popup="true"
            data-bs-toggle="tooltip" title="{{ __('Create') }}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i> Create Landmark
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
                                @foreach ($landmarks as $key => $landmark)
                                    <tr class="font-style">
                                        <td class="landmark text-capitalize">{{ $key + 1 }}</td>
                                        <td class="Permission">
                                            {{ $landmark->name }}
                                        </td>
                                        <td class="Action">
                                            <span>
                                                <div class="action-btn me-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bg-warning"
                                                        data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                        data-url="{{ route('admin.realestate.landmarks.edit', $landmark->id) }}"
                                                        data-size="xl" data-ajax-popup="true"
                                                        data-original-title="{{ __('Edit') }}">
                                                        <span> <i class="ti ti-pencil text-white"></i></span>
                                                    </a>
                                                </div>

                                                <div class="action-btn">
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['admin.realestate.landmarks.destroy', $landmark->id],
                                                        'id' => 'delete-form-' . $landmark->id,
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
