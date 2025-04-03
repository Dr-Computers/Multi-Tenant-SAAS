@php
    use App\Models\Utility;
    $logo = \App\Models\Utility::get_file('uploads/logo/');

    if (\Auth::user()->type == 'super admin') {
        $company_logo = Utility::get_superadmin_logo();
    } else {
        $company_logo = Utility::get_company_logo();
    }

    $mode_setting = \App\Models\Utility::getLayoutsSetting();

    $emailTemplate = App\Models\EmailTemplate::first();
@endphp

{{-- @if ((isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on') || env('SITE_RTL') == 'on') --}}
{{--    <nav class="dash-sidebar light-sidebar transprent-bg"> --}}
{{-- @else --}}
{{--    <nav class="dash-sidebar light-sidebar"> --}}
{{-- @endif --}}
<nav
    class="dash-sidebar light-sidebar {{ isset($mode_setting['cust_theme_bg']) && $mode_setting['cust_theme_bg'] == 'on' ? 'transprent-bg' : '' }}">
    <div class="navbar-wrapper">
        <div class="m-header main-logo">
            <a href="" class="b-brand">
                <img src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') . '?' . time() }}"
                    alt="{{ config('app.name', 'Dr Computer') }}" class="logo logo-lg">
            </a>
        </div>

        <div class="navbar-content">
            <ul class="dash-navbar">
                {{-- -------  Dashboard ---------- --}}
                <li class="dash-item ">

                    <a href="{{ route('dashboard') }}"
                        class="dash-link {{ Request::route()->getName() == 'dashboard' ? ' active' : '' }}">
                        <span class="dash-micon"><i class="ti ti-home"></i></span>
                        <span class="dash-mtext">{{ __('Dashboard') }}</span>
                    </a>
                </li>

                {{-- -------  Customer Invoice ---------- --}}
                @can('manage customer invoice')
                    <li
                        class="dash-item {{ Request::route()->getName() == 'customer.invoice' || Request::route()->getName() == 'customer.invoice.show' ? ' active' : '' }} ">
                        <a href="{{ route('customer.invoice') }}" class="dash-link ">
                            <span class="dash-micon"><i class="ti ti-file-invoice"></i></span>
                            <span class="dash-mtext">{{ __('Invoice') }}</span>
                        </a>
                    </li>
                @endcan

                {{-- -------  Staff ---------- --}}
                @if (\Auth::user()->type == 'super admin' || \Auth::user()->type == 'admin staff')
                    @can('manage user')
                        <li class="dash-item">
                            <a href="{{ route('admin.company.index') }}"
                                class="dash-link {{ Request::route()->getName() == 'admin.company.index' || Request::route()->getName() == 'company.create' || Request::route()->getName() == 'company.edit' ? ' active' : '' }}">
                                <span class="dash-micon"><i class="ti ti-users"></i></span>
                                <span class="dash-mtext">{{ __('Companies') }}</span>
                            </a>
                        </li>
                    @endcan

                    <li
                        class="dash-item dash-hasmenu {{ Request::segment(1) == 'users' || Request::segment(1) == 'roles' || Request::segment(1) == 'permissions' ? ' active dash-trigger' : '' }}">
                        <a href="#!" class="dash-link "><span class="dash-micon"><i
                                    class="ti ti-users"></i></span><span class="dash-mtext">{{ __('Staff') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul
                            class="dash-submenu {{ Request::segment(1) == 'users' || Request::segment(1) == 'roles' || Request::segment(1) == 'permissions' ? 'show' : '' }}">
                            @can('manage user')
                                <li
                                    class="dash-item {{ Request::route()->getName() == 'users.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'users.edit' ? ' active' : '' }}">
                                    <a class="dash-link" href="{{ route('admin.users.index') }}">{{ __('User') }}</a>
                                </li>
                            @endcan
                            @can('manage role')
                                <li
                                    class="dash-item {{ Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit' ? ' active' : '' }}">
                                    <a class="dash-link" href="{{ route('admin.roles.index') }}">{{ __('Role') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
                <li
                    class="dash-item dash-hasmenu {{ Request::segment(1) == 'plans' || Request::segment(1) == 'order' ? ' active dash-trigger' : '' }}">
                    <a href="#!" class="dash-link "><span class="dash-micon"><i
                                class="ti ti-building-bank"></i></span><span
                            class="dash-mtext">{{ __('Subcriptions') }}</span>
                        <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul
                        class="dash-submenu {{ Request::segment(1) == 'bank-account' || Request::segment(1) == 'transfer' ? 'show' : '' }}">
                        {{-- -------  Plan---------- --}}
                        @if (Gate::check('manage plan'))
                            <li
                                class="dash-item {{ Request::segment(1) == 'plans' || Request::segment(1) == 'stripe' ? 'active' : '' }}">
                                <a href="{{ route('admin.plans.index') }}" class="dash-link  ">
                                    <span class="dash-micon"><i class="ti ti-trophy"></i></span>
                                    <span class="dash-mtext">{{ __('Plan') }}</span>
                                </a>
                            </li>
                            <li class="dash-item  {{ Request::segment(0) == 'sections' ? 'active' : '' }}">
                                <a href="{{ route('admin.plans.sections') }}" class="dash-link">
                                    <span class="dash-micon"><i class="ti ti-activity"></i></span>
                                    <span class="dash-mtext">{{ __('Sections') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (Gate::check('manage order'))
                            <li class="dash-item {{ Request::segment(1) == 'order' ? 'active' : '' }}">
                                <a href="{{ route('admin.order.index') }}" class="dash-link ">
                                    <span class="dash-micon"><i class="ti ti-shopping-cart-plus"></i></span>
                                    <span class="dash-mtext">{{ __('Order') }}</span>
                                </a>
                            </li>
                            <li
                                class="dash-item dash-hasmenu {{ Request::segment(1) == 'plans' || Request::segment(1) == 'order' ? ' active dash-trigger' : '' }}">
                                <a href="#!" class="dash-link "><span class="dash-micon"><i
                                            class="ti ti-building-bank"></i></span><span
                                        class="dash-mtext">{{ __('Requests') }}</span>
                                    <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                                </a>
                                <ul
                                    class="dash-submenu {{ Request::segment(1) == 'plan_request' || Request::segment(1) == 'sections' ? 'show' : '' }}">
                                    {{-- -------  Plan---------- --}}
                                    @if (Gate::check('manage plan'))
                                        <li class="dash-item {{ request()->is('plan_request*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.plan_request.index') }}" class="dash-link  ">
                                                <span class="dash-micon"><i class="ti ti-trophy"></i></span>
                                                <span class="dash-mtext">{{ __('Plan') }}</span>
                                            </a>
                                        </li>
                                        <li class="dash-item  {{ request()->is('section_request*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.plans.section_request') }}" class="dash-link">
                                                <span class="dash-micon"><i class="ti ti-activity"></i></span>
                                                <span class="dash-mtext">{{ __('Sections') }}</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>


                        @endif
                    </ul>
                </li>


                {{-- -------  Email Notification ---------- --}}
                <li class="dash-item {{ Request::segment(1) == 'email_template_lang' ? 'active' : '' }}">
                    <a href="{{ route('admin.email_template.index') }}" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-template"></i></span><span
                            class="dash-mtext">{{ __('Email Template') }}</span></a>
                </li>

                {{-- <li class="dash-item {{ Request::segment(1) == 'Notifications' ? 'active' : '' }}">
                    <a href="{{ route('admin.notification-templates.index') }}" class="dash-link"><span
                            class="dash-micon"><i class="ti ti-bell"></i></span><span
                            class="dash-mtext">{{ __('Notification Template') }}</span></a>
                </li> --}}


                {{-- <li class="dash-item {{ Request::segment(1) == 'Notifications' ? 'active' : '' }}">
                    <a href="{{ route('admin.notification-templates.index') }}" class="dash-link"><span
                            class="dash-micon"><i class="ti ti-bell"></i></span><span
                            class="dash-mtext">{{ __('Support Ticket') }}</span></a>
                </li> --}}


                {{-- -------  System Setting ---------- --}}
                @if (Gate::check('manage system settings'))
                    <li
                        class="dash-item {{ Request::route()->getName() == 'admin.settings.index' ? ' active' : '' }}">
                        <a href="{{ route('admin.settings.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-settings"></i></span>
                            <span class="dash-mtext">{{ __('Settings') }}</span>
                        </a>
                    </li>
                @endif


            </ul>
        </div>
    </div>
</nav>
