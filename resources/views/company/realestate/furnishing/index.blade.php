@extends('layouts.company')
@section('page-title')
    {{ __('Furnishings') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Furnishings') }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        <button href="#" data-size="md" data-url="{{ route('company.realestate.furnishing.show', auth()->user()->id) }}"
            data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Requested furnishings list') }}"
            class="btn btn-sm btn-secondary me-2">
            <i class="ti ti-eye"></i> Requested furnishings
        </button>
        <button href="#" data-size="md" data-url="{{ route('company.realestate.furnishing.create') }}"
            data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Request a new furnishing') }}"
            class="btn btn-sm btn-primary me-2">
            <i class="ti ti-plus"></i> Request a new furnishing
        </button>
    </div>
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
                                    <th>{{ __('#') }}</th>
                                    <th>{{ __('Name') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($furnishings ?? [] as $key => $furnishing)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $furnishing->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <h6>No furnishings found..!</h6>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
