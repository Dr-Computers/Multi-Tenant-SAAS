@extends('layouts.admin')

@php
    $dir = asset(Storage::url('uploads/plan'));
    $admin = \App\Models\Utility::getAdminPaymentSetting();
    $currency_symbol = $admin['currency_symbol'] ?? 'AED';

@endphp

@section('page-title')
    {{ __('Manage Section') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Sections') }}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        @can('section listing')
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('#') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Price') }}</th>
                                        <th class="text-center">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sections as $key => $section)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $section->category }}</td>
                                            <td>{{ $section->name }}</td>
                                            <td>{{ $section->price . ' ' . $currency_symbol }}</td>
                                            <td class="text-center">
                                                @can('edit section')
                                                    <a href="#!" data-size="md" 
                                                        data-url="{{ route('admin.plans.section-edit', $section->id) }}"
                                                        data-ajax-popup="true" class="btn-sm btn btn-info"
                                                        data-bs-original-title="{{ __('Edit Section') }}">
                                                        <i class="ti ti-pencil"></i>
                                                        <span>{{ __('Edit') }}</span>
                                                    </a>
                                                @endcan
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
