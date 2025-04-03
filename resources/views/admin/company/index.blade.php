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
        <a href="#" data-size="md" data-url="{{ route('admin.company.create') }}" data-ajax-popup="true"
            data-bs-toggle="tooltip" title="{{ __('Create New Company') }}" class="btn btn-sm btn-primary me-2">
            <i class="ti ti-plus"></i>
        </a>
        @if (\Auth::user()->type == 'company')
            <a href="{{ route('userlogs.index') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                title="{{ __('User Log') }}">
                <i class="ti ti-user-check"></i>
            </a>
        @endif
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xxl-12">
            <div class="row">
                @forelse ($users as $user)
                    <div class="col-md-4 mb-4">
                        <div class="card text-center card-2">
                            <div class="card-header border-0 pb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <div class="badge bg-primary p-2 px-3 ">
                                            {{ !empty($user->currentPlan) ? $user->currentPlan->name : '' }}
                                        </div>
                                    </h6>
                                </div>
                                @if (Gate::check('edit user') || Gate::check('delete user'))
                                    <div class="card-header-right">
                                        <div class="btn-group card-option">
                                            @if ($user->is_active == 1 && $user->is_disable == 1)
                                                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>

                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="#" data-url="{{ route('plan.upgrade', $user->id) }}"
                                                        data-size="lg" data-ajax-popup="true" class="dropdown-item"
                                                        data-title="{{ __('Upgrade Plan') }}">
                                                        <i class="ti ti-coin"></i>
                                                        {{ __('Upgrade Plan') }}</a>
                                                    <a href="#"
                                                        data-url="{{ route('admin.company.info', $user->id) }}"
                                                        data-size="lg" data-ajax-popup="true" class="dropdown-item"
                                                        data-title="{{ __('Company Info') }}">
                                                        <i class="ti ti-info-circle"></i>
                                                        {{ __('Company Info') }}</a>

                                                    <a href="#" class="dropdown-item">
                                                        <i class="ti ti-plus"></i>
                                                        {{ __('Addon Features') }}</a>

                                                    <a href="{{ route('login.with.company', $user->id) }}"
                                                        class="dropdown-item"
                                                        data-bs-original-title="{{ __('Login As Company') }}">
                                                        <i class="ti ti-replace"></i>
                                                        <span> {{ __('Login As Company') }}</span>
                                                    </a>


                                                    <a href="#!"
                                                        data-url="{{ route('admin.company.reset', \Crypt::encrypt($user->id)) }}"
                                                        data-ajax-popup="true" data-size="md" class="dropdown-item"
                                                        data-bs-original-title="{{ __('Reset Password') }}">
                                                        <i class="ti ti-adjustments"></i>
                                                        <span> {{ __('Reset Password') }}</span>
                                                    </a>

                                                    @if ($user->is_enable_login == 1)
                                                        <a href="{{ route('admin.company.login', \Crypt::encrypt($user->id)) }}"
                                                            class="dropdown-item">
                                                            <i class="ti ti-road-sign"></i>
                                                            <span class="text-danger"> {{ __('Login Disable') }}</span>
                                                        </a>
                                                    @elseif ($user->is_enable_login == 0 && $user->password == null)
                                                        <a href="#"
                                                            data-url="{{ route('admin.company.reset', \Crypt::encrypt($user->id)) }}"
                                                            data-ajax-popup="true" data-size="md"
                                                            class="dropdown-item login_enable"
                                                            data-title="{{ __('New Password') }}" class="dropdown-item">
                                                            <i class="ti ti-road-sign"></i>
                                                            <span class="text-success"> {{ __('Login Enable') }}</span>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admin.company.login', \Crypt::encrypt($user->id)) }}"
                                                            class="dropdown-item">
                                                            <i class="ti ti-road-sign"></i>
                                                            <span class="text-success"> {{ __('Login Enable') }}</span>
                                                        </a>
                                                    @endif
                                                    @can('edit user')
                                                        <a href="#!" data-size="md"
                                                            data-url="{{ route('admin.company.edit', $user->id) }}"
                                                            data-ajax-popup="true" class="dropdown-item"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil"></i>
                                                            <span>{{ __('Edit') }}</span>
                                                        </a>
                                                    @endcan

                                                    @can('delete user')
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
                                @endif
                            </div>

                            <div class="card-body full-card">
                                <div class="img-fluid rounded-circle card-avatar">
                                    <img src="{{ !empty($user->avatar) ? asset(Storage::url('uploads/avatar/' . $user->avatar)) : asset(Storage::url('uploads/avatar/avatar.png')) }}"
                                        class="img-fluid rounded border-2 border border-primary" width="120px"
                                        height="120px">
                                </div>
                                <h4 class=" mt-3 ">{{ $user->name }}</h4>
                                <small class="">{{ $user->email }}</small>
                                <p></p>
                                <div class="text-center" data-bs-toggle="tooltip" title="{{ __('Last Login') }}">
                                    {{ !empty($user->last_login_at) ? $user->last_login_at : '' }}
                                </div>
                                @if (\Auth::user()->type == 'super admin')
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
                                                        <div class="col-4">
                                                            <p class="text-muted text-sm mb-0" data-bs-toggle="tooltip"
                                                                title="{{ __('Users') }}"><i
                                                                    class="ti ti-users card-icon-text-space"></i>{{ $user->totalCompanyUser($user->id) }}
                                                            </p>
                                                        </div>
                                                        <div class="col-4">
                                                            <p class="text-muted text-sm mb-0" data-bs-toggle="tooltip"
                                                                title="{{ __('Customers') }}"><i
                                                                    class="ti ti-users card-icon-text-space"></i>{{ $user->totalCompanyCustomer($user->id) }}
                                                            </p>
                                                        </div>
                                                        <div class="col-4">
                                                            <p class="text-muted text-sm mb-0" data-bs-toggle="tooltip"
                                                                title="{{ __('Vendors') }}"><i
                                                                    class="ti ti-users card-icon-text-space"></i>{{ $user->totalCompanyVender($user->id) }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
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
