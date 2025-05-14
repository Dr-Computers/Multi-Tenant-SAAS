@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Estimate Templates') }}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{ __('Estimate Templates') }}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Estimate Template') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @can('create estimate template')
            <a href="#" data-size="lg" data-url="{{ route('admin.templates.estimates.create') }}" data-ajax-popup2="true"
                data-bs-toggle="tooltip" title="{{ __('Create') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i> Create new one
            </a>
        @endcan
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    @can('create estimate template')
                        <div class="table-responsive">
                            <table class="table datatable" id="pc-dt-simple">
                                <thead>
                                    <tr>
                                        <th scope="col" class="sort" data-sort="section">#</th>
                                        <th scope="col" class="sort" data-sort="name"> {{ __('image') }}</th>
                                        <th scope="col" class="sort" data-sort="section"> {{ __('Template Name') }}</th>
                                        <th class="text-end">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($templates ?? [] as $key => $template)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td> <img src="{{ asset('storage/' . $template->image) }}" class="w-20"> </td>
                                            <td>{{ $template->name }}</td>
                                            <td>
                                                <div class="dt-buttons">
                                                    <div class="text-end">
                                                        @can('show estimate template')
                                                            <div class="action-btn me-2">
                                                                <a href="{{ asset('storage/' . $template->image) }}" target="_new"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center  bg-info">
                                                                    <span class="text-white"><i class="ti ti-eye"></i></span>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('edit estimate template')
                                                            <div class="action-btn me-2">
                                                                <a href="#"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bg-warning"
                                                                    data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                    data-url="{{ route('admin.templates.estimates.edit', $template->id) }}"
                                                                    data-size="xl" data-ajax-popup2="true"
                                                                    data-original-title="{{ __('Edit') }}">
                                                                    <span> <i class="ti ti-pencil text-white"></i></span>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('delete estimate template')
                                                            <div class="action-btn">
                                                                {!! Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['admin.templates.estimates.destroy', $template->id],
                                                                    'id' => 'delete-form-' . $template->id,
                                                                ]) !!}
                                                                <a href="#"
                                                                    class="mx-3 btn btn-sm  align-items-center bs-pass-para bg-danger"
                                                                    data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                                                    <i class="ti ti-trash text-white text-white"></i>
                                                                </a>
                                                                {!! Form::close() !!}
                                                            </div>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
