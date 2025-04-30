@extends('layouts.admin')

@php
    $dir = asset(Storage::url('uploads/plan'));
    $admin = \App\Models\Utility::getAdminPaymentSetting();
@endphp

@section('page-title')
    {{ __('Manage Plan') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Plan') }}</li>
@endsection

@section('action-btn')
    <div class="float-end">
        @can('create plan')
            <a href="#" data-url="{{ route('admin.plans.create') }}" data-ajax-popup2="true" data-bs-toggle="tooltip"
                title="{{ __('Create') }}" data-title="{{ __('Create New Plan') }}" class="btn btn-sm btn-primary"
                data-size="lg">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection

@section('content')
 
    <ul class="nav bg-white-300 nav-pills my-3 d-flex justify-content-center" id="planTabs" role="tablist">
        @foreach ($businessTypes as $index => $type)
            <li class="nav-item mx-2" role="presentation">
                <button class="nav-link {{ $index == 0 ? 'active' : '' }}" id="tab-{{ Str::slug($type->name) }}"
                    data-bs-toggle="tab" data-bs-target="#content-{{ Str::slug($type->name) }}" type="button"
                    role="tab">
                    {{ ucfirst($type->name) }}
                </button>
            </li>
        @endforeach
    </ul>
    <hr>

    <div class="tab-content mt-4" id="planTabsContent">
        @foreach ($businessTypes as $index => $type)
            <div class="tab-pane  fade {{ $index == 0 ? 'show active' : '' }}" id="content-{{ Str::slug($type->name) }}"
                role="tabpanel">
                <div class="row justify-content-center">
                    @forelse ($plans->where('business_type', $type->id) as $plan)
                        <div class="col-lg-4 col-xl-4 col-md-6 col-sm-6 d-flex">
                            <div class="card w-100 price-card">
                                <div class="card-header border-0 pb-0">
                                    @if (Gate::check('edit user') || Gate::check('delete user'))
                                        <div class="card-header-right">
                                            <div class="btn-group card-option">
                                                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>

                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="#!" data-size="md"
                                                        data-url="{{ route('admin.plans.edit', $plan->id) }}"
                                                        data-ajax-popup="true" class="dropdown-item"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil"></i>
                                                        <span>{{ __('Edit') }}</span>
                                                    </a>
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['admin.plans.destroy', $plan->id],
                                                        'id' => 'delete-form-' . $plan->id,
                                                    ]) !!}
                                                    <a href="#!" class="dropdown-item bs-pass-para">
                                                        <i class="ti ti-archive"></i>
                                                        <span>
                                                            {{ __('Delete') }}
                                                        </span>
                                                    </a>
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <span class="price-badge bg-success rounded">{{ $plan->name }}</span>
                                    <h1 class="mb-3 f-w-600">
                                        {{ number_format($plan->price) }}
                                        {{ !empty($admin['currency_symbol']) ? $admin['currency_symbol'] : 'AED' }}
                                        <small
                                            class="text-sm">/{{ __(\App\Models\Plan::$arrDuration[$plan->duration]) }}</small>
                                    </h1>
                                    <p class="mb-0">
                                        {{ __('Duration: ') . __(\App\Models\Plan::$arrDuration[$plan->duration]) }}</p>
                                    <p class="mb-0">
                                        {{ __('Free Trial Days: ') . __($plan->trial_days ? $plan->trial_days : 0) }}</p>
                                    <ul class="list-unstyled my-4">
                                        <li>{{ $plan->max_users == -1 ? __('Unlimited') : $plan->max_users }}
                                            {{ __('Users') }}</li>
                                        <li>{{ $plan->max_customers == -1 ? __('Unlimited') : $plan->max_customers }}
                                            {{ __('Customers') }}</li>
                                        <li>{{ $plan->max_venders == -1 ? __('Unlimited') : $plan->max_venders }}
                                            {{ __('Vendors') }}</li>
                                        <li>{{ $plan->storage_limit == -1 ? __('Unlimited') : $plan->storage_limit }}
                                            {{ __('Storage Limits') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-lg-12 ">
                            <div class="text-center">
                                <h2 class="my-3">No Plans Found..</h2>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
@endsection
