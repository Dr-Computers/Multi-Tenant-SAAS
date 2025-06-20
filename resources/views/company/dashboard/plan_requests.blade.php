@extends('layouts.company')
@section('page-title')
    {{ __('Plan Request') }}
@endsection
@php
    $admin = \App\Models\Utility::getAdminPaymentSetting();
    $currency_symbol = $admin['currency_symbol'] ?? 'AED';
@endphp

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Plan Request') }}</li>
@endsection

@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{ __('Plan Request') }}</h5>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        @can('manage requested plans')
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('Plan Name') }}</th>
                                        {{-- <th>{{ __('Duration') }}</th> --}}
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Price') }}</th>
                                        <th>{{ __('Status') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($plan_requests->count() > 0)
                                        @foreach ($plan_requests as $prequest)
                                            <tr>
                                                <td>
                                                    <div class="font-style font-weight-bold">{{ $prequest->plan->name }}</div>
                                                </td>
                                                {{-- <td>
                                                    <div class="font-style font-weight-bold">
                                                        {{ $prequest->plan->duration }}
                                                    </div>
                                                </td> --}}
                                                <td>{{ App\Models\Utility::getDateFormated($prequest->created_at, true) }}</td>
                                                <td>{{ adminPrice() }} {{ $prequest->plan->price }}</td>
                                                <td>
                                                    @if ($prequest->status === 'approved')
                                                        <span class="badge bg-success text-white fw-bold">Approved</span>
                                                    @elseif($prequest->status === 'pending')
                                                        <span
                                                            class="badge bg-warning text-dark text-white fw-bold">Pending</span>
                                                    @elseif($prequest->status === 'rejected')
                                                        <span class="badge bg-danger text-white fw-bold">Rejected</span>
                                                    @else
                                                        <span class="badge bg-secondary text-white fw-bold">Unknown</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <th scope="col" colspan="7">
                                                <h6 class="text-center">{{ __('No Manually Plan Request Found.') }}</h6>
                                            </th>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
