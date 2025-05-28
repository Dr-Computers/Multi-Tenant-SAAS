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
                <i class="ti ti-plus"></i> {{ __('Create New Plan') }}
            </a>
        @endcan
    </div>
@endsection

@section('content')

    @can('plan listing')
        <ul class="nav bg-white-300 nav-pills my-3 d-flex justify-content-center" id="planTabs" role="tablist">
            @foreach ($businessTypes as $index => $type)
                <li class="nav-item mx-2" role="presentation">
                    <button class="nav-link  {{ $index == 0 ? 'active' : '' }}" id="tab-{{ Str::slug($type->name) }}"
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

                                        <div class="card-header-right">
                                            <div class="btn-group card-option">
                                                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>

                                                <div class="dropdown-menu dropdown-menu-end">
                                                    @can('edit plan')
                                                        <a href="#!" data-size="lg"
                                                            data-url="{{ route('admin.plans.edit', $plan->id) }}"
                                                            data-ajax-popup2="true" class="dropdown-item"
                                                            data-bs-original-title="{{ __('Edit Plan') }}">
                                                            <i class="ti ti-pencil"></i>
                                                            <span>{{ __('Edit Plan') }}</span>
                                                        </a>
                                                    @endcan
                                                    @can('delete plan')
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
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <span class="price-badge bg-success rounded">{{ $plan->name }}</span>
                                        <h1 class="mb-3 f-w-600">
                                            {{ number_format($plan->price) }}
                                            {{ !empty($admin['currency_symbol']) ? $admin['currency_symbol'] : 'AED' }}
                                            <small
                                                class="text-sm">/{{ __(\App\Models\Plan::$arrDuration[$plan->duration]) }}</small>
                                        </h1>
                                        <p class="mb-0 fw-bold">
                                            {{ __('Duration: ') . __(\App\Models\Plan::$arrDuration[$plan->duration]) }}</p>
                                        <p class="mb-0 fw-bold">
                                            {{ __('Free Trial Days: ') . __($plan->trial_days ? $plan->trial_days : 0) }}</p>
                                        <ul class="list-unstyled my-4">
                                            <li class="fw-semibold">{{ $plan->max_users == -1 ? __('Unlimited') : $plan->max_users }}
                                                {{ __('Staff Users') }}</li>
                                            <li class="fw-semibold">{{ $plan->max_tenats == -1 ? __('Unlimited') : $plan->max_tenants }}
                                                {{ __('Tenants') }}</li>
                                            <li class="fw-semibold">{{ $plan->max_owners == -1 ? __('Unlimited') : $plan->max_owners }}
                                                {{ __('Owners') }}</li>
                                            <li class="fw-semibold">{{ $plan->storage_limit == -1 ? __('Unlimited') : $plan->storage_limit }}
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
    @endcan
@endsection
