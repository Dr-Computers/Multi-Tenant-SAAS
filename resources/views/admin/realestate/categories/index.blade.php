@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Categories') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Categories') }}</li>
@endsection


@section('action-btn')
    <div class="float-end">
        @can('manage category request')
            <a href="{{ route('admin.realestate.categories.requests') }}" class="btn btn-sm btn-dark">
                <i class="ti ti-help"></i> Requests From Company
            </a>
        @endcan
        @can('create category')
            <a href="#" data-size="xl" data-url="{{ route('admin.realestate.categories.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" data-original-title="{{ __('Create Category') }}" title="{{ __('Create Category') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i> Create Category
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    @can('category listing')
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
                                    @foreach ($categories as $key => $category)
                                        <tr class="font-style">
                                            <td class="category text-capitalize">{{ $key + 1 }}</td>
                                            <td class="Permission">
                                                {{ $category->name }}
                                            </td>
                                            <td class="Action">
                                                <span>
                                                    @can('edit category')
                                                        <div class="action-btn me-2">
                                                            <a href="#"
                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center bg-warning"
                                                                data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                data-url="{{ route('admin.realestate.categories.edit', $category->id) }}"
                                                                data-size="xl" data-ajax-popup="true"
                                                                data-original-title="{{ __('Edit Category') }}">
                                                                <span> <i class="ti ti-pencil text-white"></i></span>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('delete category')
                                                        <div class="action-btn">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['admin.realestate.categories.destroy', $category->id],
                                                                'id' => 'delete-form-' . $category->id,
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
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
