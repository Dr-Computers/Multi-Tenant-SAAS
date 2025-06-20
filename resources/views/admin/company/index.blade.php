@extends('layouts.admin')
@php
    $profile = asset(Storage::url('uploads/avatar/'));
@endphp
@section('page-title')
    {{ __('Manage Companies') }}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">{{ __('Companies') }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        @can('create company')
            <a href="#" data-size="md" data-url="{{ route('admin.company.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="{{ __('Create New Company') }}" class="btn btn-sm btn-primary me-2">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xxl-12">
            <div class="row">
                @can('company listing')
                    @forelse ($users as $user)
                        <div class="col-md-4 mb-4">
                            <div class="card text-center card-2">
                                <div class="card-header border-0 pb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <div class="badge bg-primary rounded p-2 px-3 ">
                                                {{ !empty($user->currentPlan) && $user->currentPlan->plan ? $user->currentPlan->plan->name : '' }}
                                            </div>
                                            @if ($user->is_enable_login == 1)
                                                <div class="badge bg-success rounded p-2 px-3 ">
                                                    Login Enabled
                                                </div>
                                            @else
                                                <div class="badge bg-danger rounded p-2 px-3 ">
                                                    Login Disabled
                                                </div>
                                            @endif
                                        </h6>
                                    </div>
                                    <div class="card-header-right">
                                        <div class="btn-group card-option">
                                            @if ($user->is_active == 1 && $user->is_disable == 1)
                                                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>

                                                <div class="dropdown-menu dropdown-menu-end">
                                                    @can('company details')
                                                        <a href="#" data-url="{{ route('admin.company.info', $user->id) }}"
                                                            data-size="lg" data-ajax-popup="true" class="dropdown-item"
                                                            data-title="{{ __('Company Info') }}">
                                                            <i class="ti ti-info-circle"></i>
                                                            {{ __('Company Info') }}</a>
                                                    @endcan
                                                    @can('company addon features')
                                                        <a href="{{ route('admin.company.addon-features', $user->id) }}"
                                                            class="dropdown-item">
                                                            <i class="ti ti-plus"></i>
                                                            {{ __('Addon Features') }}</a>
                                                    @endcan
                                                    @can('company plan upgrade')
                                                        <a href="{{ route('admin.company.plan.upgrade', $user->id) }}"
                                                            class="dropdown-item">
                                                            <i class="ti ti-coin"></i>
                                                            {{ __('Upgrade Plan') }}</a>
                                                    @endcan
                                                    @can('company details')
                                                        <a href="{{ route('admin.company.reset-permissions', $user->id) }}"
                                                            class="dropdown-item">
                                                            <i class="ti ti-refresh"></i>
                                                            {{ __('Reset Permissions') }}</a>
                                                    @endcan
                                                    @can('login as company')
                                                        <a href="{{ route('admin.company.login', $user->id) }}"
                                                            class="dropdown-item"
                                                            data-bs-original-title="{{ __('Login as Company') }}">
                                                            <i class="ti ti-replace"></i>
                                                            <span> {{ __('Login as Company') }}</span>
                                                        </a>
                                                    @endcan
                                                    @can('edit company')
                                                        <a href="#!"
                                                            data-url="{{ route('admin.company.reset', \Crypt::encrypt($user->id)) }}"
                                                            data-ajax-popup="true" data-size="md" class="dropdown-item"
                                                            data-bs-original-title="{{ __('Reset Password') }}">
                                                            <i class="ti ti-adjustments"></i>
                                                            <span> {{ __('Reset Password') }}</span>
                                                        </a>
                                                    @endcan
                                                    @can('login company disable')
                                                        @if ($user->is_enable_login == 1)
                                                            <a href="{{ route('admin.company.login_fn', \Crypt::encrypt($user->id)) }}"
                                                                class="dropdown-item">
                                                                <i class="ti ti-road-sign"></i>
                                                                <span class="text-danger"> {{ __('Login Disable') }}</span>
                                                            </a>
                                                        @elseif ($user->is_enable_login == 0 && $user->password == null)
                                                            <a href="{{ route('admin.company.login_fn', \Crypt::encrypt($user->id)) }}"
                                                                class="dropdown-item">
                                                                <i class="ti ti-road-sign"></i>
                                                                <span class="text-success"> {{ __('Login Enable') }}</span>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('admin.company.login_fn', \Crypt::encrypt($user->id)) }}"
                                                                class="dropdown-item">
                                                                <i class="ti ti-road-sign"></i>
                                                                <span class="text-success"> {{ __('Login Enable') }}</span>
                                                            </a>
                                                        @endif
                                                    @endcan
                                                    @can('edit company')
                                                        <a href="#!" data-size="md"
                                                            data-url="{{ route('admin.company.edit', $user->id) }}"
                                                            data-ajax-popup="true" class="dropdown-item"
                                                            data-bs-original-title="{{ __('Edit User') }}">
                                                            <i class="ti ti-pencil"></i>
                                                            <span>{{ __('Edit') }}</span>
                                                        </a>
                                                    @endcan

                                                    @can('delete company')
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['admin.company.destroy', $user['id']],
                                                            'id' => 'delete-form-' . $user['id'],
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
                                            @else
                                                <a href="#" class="action-item text-lg"><i class="ti ti-lock"></i></a>
                                            @endif

                                        </div>
                                    </div>

                                </div>

                                <div class="card-body full-card">
                                    <div class="img-fluid rounded-circle card-avatar">
                                        <img src="{{ !empty($user->avatar_url) ? asset('storage/' . $user->avatar_url) : asset(Storage::url('uploads/avatar/avatar.png')) }}"
                                            class="img-fluid rounded border-2 border border-primary mx-auto" width="120px"
                                            height="120px">
                                    </div>
                                    <h4 class=" mt-3 ">{{ $user->name }}</h4>
                                    <small class="">{{ $user->email }}</small>
                                    <p></p>
                                    <div class="text-center" data-bs-toggle="tooltip" title="{{ __('Last Login') }}">
                                        {{ !empty($user->last_login_at) ? $user->last_login_at : '' }}
                                    </div>

                                    <div class="mt-1">
                                        <div class="row justify-content-between align-items-center">

                                            <div class="col-12 text-center pb-2">
                                                <span class="text-dark text-xs">{{ __('Plan Expired : ') }}
                                                    {{ !empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date) : __('Lifetime') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-12 col-sm-12">
                                            <div class="card mb-0">
                                                <div class="card-body p-3">
                                                    <div class="row">
                                                        <div class="col-3">
                                                            <p class="text-muted text-sm mb-0" data-bs-toggle="tooltip"
                                                                title="{{ __('Users') }}"><i
                                                                    class="ti ti-users card-icon-text-space me-1"></i>{{ $user->totalCompanyUser($user->company->user_id) }}
                                                            </p>
                                                        </div>
                                                        <div class="col-3">
                                                            <p class="text-muted text-sm mb-0" data-bs-toggle="tooltip"
                                                                title="{{ __('Owners') }}"><i
                                                                    class="ti ti-users card-icon-text-space me-1"></i>{{ $user->totalOwnerUser($user->company->user_id) }}
                                                            </p>
                                                        </div>
                                                        <div class="col-3">
                                                            <p class="text-muted text-sm mb-0" data-bs-toggle="tooltip"
                                                                title="{{ __('Tenants') }}"><i
                                                                    class="ti ti-users card-icon-text-space me-1"></i>{{ $user->totalTenantUser($user->company->user_id) }}
                                                            </p>
                                                        </div>
                                                        <div class="col-3">
                                                            <p class="text-muted text-sm mb-0" data-bs-toggle="tooltip"
                                                                title="{{ __('Maintainer') }}"><i
                                                                    class="ti ti-users card-icon-text-space me-1"></i>{{ $user->totalMaintainerUser($user->company->user_id) }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-md-12 mb-4">
                            <div class="card text-center p-5 card-2">
                                <h6>No Companies found</h6>
                            </div>
                        </div>
                    @endforelse
                @else
                    <div class="col-md-12 mb-4">
                        <div class="card text-center p-5 card-2">
                            <h6>Permission denied</h6>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script>
        $(document).on('change', '#password_switch', function() {
            if ($(this).is(':checked')) {
                $('.ps_div').removeClass('d-none');
                $('#password').attr("required", true);

            } else {
                $('.ps_div').addClass('d-none');
                $('#password').val(null);
                $('#password').removeAttr("required");
            }
        });
        $(document).on('click', '.login_enable', function() {
            setTimeout(function() {
                $('.modal-body').append($('<input>', {
                    type: 'hidden',
                    val: 'true',
                    name: 'login_enable'
                }));
            }, 2000);
        });
    </script>
@endpush
