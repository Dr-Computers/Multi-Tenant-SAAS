@extends('layouts.company')
@section('page-title')
    {{ __('Amenities') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Amenities') }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        @can('manage amenity request')
            <button href="#" data-size="md" data-url="{{ route('company.realestate.amenities.show', auth()->user()->id) }}"
                data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Requested amenities list') }}"
                class="btn btn-sm btn-secondary me-2">
                <i class="ti ti-eye"></i> Requested amenities
            </button>
            <button href="#" data-size="md" data-url="{{ route('company.realestate.amenities.create') }}"
                data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Request a new amenity') }}"
                class="btn btn-sm btn-primary me-2">
                <i class="ti ti-plus"></i> Request a new amenity
            </button>
        @endcan
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-bUsers-style">
                    @can('manage amenity request')
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('#') }}</th>
                                        <th>{{ __('Name') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($amenities ?? [] as $key => $amenity)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $amenity->name }}</td>
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
