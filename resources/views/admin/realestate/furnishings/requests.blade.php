@extends('layouts.admin')
@section('page-title')
    {{ __('Requested Furnishings') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Requested Furnishings') }}</li>
@endsection


@section('action-btn')
    <div class="float-end">
        <a href="{{ route('admin.realestate.furnishings.index') }}" class="btn btn-sm btn-dark">
            <i class="ti ti-arrow-left"></i> Back
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    @can('manage furnishing request')
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('#') }} </th>
                                        <th> {{ __('Company') }}</th>
                                        <th>{{ __('Name') }} </th>
                                        <th>{{ __('Status') }} </th>
                                        <th width="150">{{ __('Action') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($furnishingRequests as $key => $furnishing)
                                        <tr class="font-style">
                                            <td class="furnishing text-capitalize">{{ $key + 1 }}</td>
                                            <td>{{ $furnishing->company->name }}</td>
                                            <td>
                                                {{ $furnishing->request_for }}
                                            </td>
                                            <td>
                                                @if ($furnishing->status == 0)
                                                    <span class="badge bg-warning p-1 px-2 rounded">
                                                        {{ ucfirst('Pending') }}</span>
                                                @elseif ($furnishing->status == 1)
                                                    <span class="badge bg-success p-1 px-2 rounded">
                                                        {{ ucfirst('Accepted') }}</span>
                                                @else
                                                    <span class="badge bg-danger p-1 px-2 rounded">
                                                        {{ ucfirst('Rejected') }}</span>
                                                @endif
                                            </td>
                                            <td class="Action">

                                                <div class="action-btn me-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bg-info"
                                                        data-bs-toggle="tooltip" title="{{ __('Show Request') }}"
                                                        data-url="{{ route('admin.realestate.furnishings.request-single', $furnishing->id) }}"
                                                        data-size="xl" data-ajax-popup="true"
                                                        data-original-title="{{ __('Show') }}">
                                                        <span> <i class="ti ti-eye text-white"></i></span>
                                                    </a>
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
