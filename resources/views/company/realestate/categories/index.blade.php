@extends('layouts.company')
@section('page-title')
    {{ __('Categories') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Category') }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        @can('manage category request')
            <button href="#" data-size="md" data-url="{{ route('company.realestate.categories.show', auth()->user()->id) }}"
                data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Requested categories list') }}"
                class="btn btn-sm btn-secondary me-2">
                <i class="ti ti-eye"></i> Requested categories
            </button>
            <button href="#" data-size="md" data-url="{{ route('company.realestate.categories.create') }}"
                data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Request a new category') }}"
                class="btn btn-sm btn-primary me-2">
                <i class="ti ti-plus"></i> Request a new category
            </button>
        @endcan
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-bUsers-style">
                    @can('category listing')
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('#') }}</th>
                                        <th>{{ __('Name') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categories ?? [] as $key => $category)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $category->name }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <h6>No owners found..!</h6>
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
