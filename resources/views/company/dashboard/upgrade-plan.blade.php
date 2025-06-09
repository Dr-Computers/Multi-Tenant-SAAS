@extends('layouts.company')

@section('page-title')
    {{ __('Upgrade Plan') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Upgrade Plan') }}</li>
@endsection

@section('action-btn')
    <div class="float-end">
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('Back') }}">
            <i class="ti ti-arrow-back"></i> {{ __('Back') }}
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @foreach ($plans as $plan)
                            <div class="col-lg-4 mb-3">
                                <div class="card w-100 price-card">
                                    <div class="card-body text-center">
                                        <span
                                            class="price-badge p-2 shadow-lg small fw-bold text-capitalize bg-info rounded">
                                            {{ $plan->name }}
                                        </span>

                                        <h1 class="mb-3 f-w-600 mt-3 fw-bold">
                                            {{ number_format($plan->price) }}
                                            {{ $admin['currency_symbol'] ?? 'AED' }}
                                            <small class="text-sm">/
                                                {{ __(\App\Models\Plan::$arrDuration[$plan->duration]) }}</small>
                                        </h1>

                                        <p class="mb-0 fw-bold">
                                            {{ __('Duration: ') . __(\App\Models\Plan::$arrDuration[$plan->duration]) }}
                                        </p>

                                        <p class="mb-0 fw-bold">
                                            {{ __('Free Trial Days: ') . ($plan->trial_days ?? 0) }}
                                        </p>

                                        <ul class="list-unstyled my-4 text-start">
                                            <li class="fw-bold">
                                                {{ $plan->max_users == -1 ? __('Unlimited') : $plan->max_users }}
                                                {{ __('Staff Users') }}</li>
                                            <li class="fw-bold">
                                                {{ $plan->max_customers == -1 ? __('Unlimited') : $plan->max_customers }}
                                                {{ __('Tenants') }}</li>
                                            <li class="fw-bold">
                                                {{ $plan->max_venders == -1 ? __('Unlimited') : $plan->max_venders }}
                                                {{ __('Owners') }}</li>
                                            <li class="fw-bold">
                                                {{ $plan->storage_limit == -1 ? __('Unlimited') : $plan->storage_limit }}
                                                {{ __('Storage Limit') }}</li>
                                        </ul>

                                    </div>

                                    <div class="card-footer p-2 text-center">
                                        <form action="{{ route('company.plan.upgrade.store', ['id' => $plan->id]) }}"
                                            method="POST">
                                            @csrf
                                            @php
                                                $currentPlanId = $user->company->activeSubscription->plan_id ?? null;
                                            @endphp
                                            @if ($currentPlanId == $plan->id)
                                                @if ($user->company->activeSubscription)
                                                    <p class="my-2">
                                                        Your subscription plan will expire on
                                                        <strong>{{ date('d M, Y', strtotime($user->company->activeSubscription->end_of_date)) }}</strong>
                                                    </p>
                                                @endif
                                                <button class="btn btn-sm btn-success"
                                                    disabled>{{ __('Current Plan') }}</button>
                                                <input type="hidden" name="renew" value="{{ $plan->id }}">
                                                <button type="submit"
                                                    class="btn btn-sm btn-warning">{{ __('Renew') }}</button>
                                            @elseif($existingRequests->pluck('plan_id')->contains($plan->id))
                                                <button class="btn btn-sm btn-danger"
                                                    disabled>{{ __('Allready Requested') }}</button>
                                            @elseif($user->company->planOrders->pluck('plan_id')->contains($plan->id))
                                                <input type="hidden" name="renew" value="{{ $plan->id }}">
                                                <button type="submit"
                                                    class="btn btn-sm btn-warning">{{ __('Renew') }}</button>
                                            @else
                                                <input type="hidden" name="purchase" value="{{ $plan->id }}">
                                                <button type="submit"
                                                    class="btn btn-sm btn-primary">{{ __('Purchase') }}</button>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div> <!-- end row -->
                </div>
            </div>
        </div>
    </div>
@endsection
