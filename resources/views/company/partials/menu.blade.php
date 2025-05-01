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
                <li
                    class="dash-item dash-hasmenu {{ Request::segment(1) == 'realestate' || Request::segment(1) == 'realestate' || Request::segment(1) == 'realestate' ? ' active dash-trigger' : '' }}">
                    <a href="#!" class="dash-link "><span class="dash-micon"><i
                                class="ti ti-arrows-double-nw-se"></i></span><span
                            class="dash-mtext">{{ __('HRMS') }}</span>
                        <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul
                        class="dash-submenu {{ Request::segment(1) == 'roles' || Request::segment(1) == 'users' ? 'show' : '' }}">
                        @can('manage user')
                            <li
                                class="dash-item {{ Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit' || Request::route()->getName() == 'roles.show' ? ' active' : '' }}">
                                <a class="dash-link"
                                    href="{{ route('company.hrms.users.index') }}">{{ __('Staff Users') }}</a>
                            </li>
                        @endcan
                        @can('manage role')
                            <li
                                class="dash-item {{ Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit' ? ' active' : '' }}">
                                <a class="dash-link"
                                    href="{{ route('company.hrms.roles.index') }}">{{ __('Role and Permissions') }}</a>
                            </li>
                        @endcan

                    </ul>
                </li>
                <li
                    class="dash-item dash-hasmenu {{ Request::segment(1) == 'realestate' || Request::segment(1) == 'realestate' || Request::segment(1) == 'realestate' ? ' active dash-trigger' : '' }}">
                    <a href="#!" class="dash-link "><span class="dash-micon"><i
                                class="ti ti-propeller"></i></span><span
                            class="dash-mtext">{{ __('Real estate') }}</span>
                        <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul
                        class="dash-submenu {{ Request::segment(1) == 'realestate' || Request::segment(1) == 'owners' || Request::segment(1) == 'tenants' || Request::segment(1) == 'maintainers' || Request::segment(1) == 'properties' ? 'show' : '' }}">
                        @can('manage user')
                            <li
                                class="dash-item {{ Request::route()->getName() == 'owners.index' || Request::route()->getName() == 'owners.create' || Request::route()->getName() == 'owners.edit' || Request::route()->getName() == 'properties.show' ? ' active' : '' }}">
                                <a class="dash-link"
                                    href="{{ route('company.realestate.owners.index') }}">{{ __('Owners') }}</a>
                            </li>
                        @endcan
                        @can('manage role')
                            <li
                                class="dash-item {{ Request::route()->getName() == 'properties.index' || Request::route()->getName() == 'properties.create' || Request::route()->getName() == 'properties.edit' || Request::route()->getName() == 'properties.show' ? ' active' : '' }}">
                                <a class="dash-link"
                                    href="{{ route('company.realestate.properties.index') }}">{{ __('Properties') }}</a>
                            </li>
                        @endcan
                        @can('manage role')
                            <li
                                class="dash-item {{ Request::route()->getName() == 'tenants.index' || Request::route()->getName() == 'tenants.create' || Request::route()->getName() == 'tenants.edit' || Request::route()->getName() == 'tenants.show' ? ' active' : '' }}">
                                <a class="dash-link"
                                    href="{{ route('company.realestate.tenants.index') }}">{{ __('Tenants') }}</a>
                            </li>
                        @endcan
                        @can('manage role')
                            <li
                                class="dash-item {{ Request::route()->getName() == 'maintainers.index' || Request::route()->getName() == 'maintainers.create' || Request::route()->getName() == 'maintainers.edit' || Request::route()->getName() == 'maintainers.show' ? ' active' : '' }}">
                                <a class="dash-link"
                                    href="{{ route('company.realestate.maintainers.index') }}">{{ __('Maintainers') }}</a>
                            </li>
                        @endcan
                        @can('manage role')
                            <li
                                class="dash-item {{ Request::route()->getName() == 'maintenance-requests.index' || Request::route()->getName() == 'maintenance-requests.create' || Request::route()->getName() == 'maintenance-requests.edit' ? ' active' : '' }}">
                                <a class="dash-link" href="{{ route('company.realestate.maintenance-requests.index') }}">{{ __('Maintenance Request') }}</a>
                            </li>
                        @endcan
                        <li
                            class="dash-item dash-hasmenu {{ Request::segment(1) == 'realestate' || Request::segment(1) == 'realestate' || Request::segment(1) == 'realestate' ? ' active dash-trigger' : '' }}">
                            <a href="#!" class="dash-link "><span class="dash-mtext">{{ __('Setup') }}</span>
                                <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                            </a>
                            <ul
                                class="dash-submenu {{ Request::segment(1) == 'realestate' || Request::segment(1) == 'roles' || Request::segment(1) == 'permissions' ? 'show' : '' }}">
                                @can('manage user')
                                    <li
                                        class="dash-item {{ Request::route()->getName() == 'realestate.index' || Request::route()->getName() == 'realestate.create' || Request::route()->getName() == 'realestate.edit' ? ' active' : '' }}">
                                        <a class="dash-link"
                                            href="{{ route('company.realestate.categories.index') }}">{{ __('Categories') }}</a>
                                    </li>
                                @endcan
                                @can('manage role')
                                    <li
                                        class="dash-item {{ Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit' ? ' active' : '' }}">
                                        <a class="dash-link"
                                            href="{{ route('company.realestate.amenities.index') }}">{{ __('Amenities') }}</a>
                                    </li>
                                @endcan
                                @can('manage user')
                                    <li
                                        class="dash-item {{ Request::route()->getName() == 'realestate.index' || Request::route()->getName() == 'realestate.create' || Request::route()->getName() == 'realestate.edit' ? ' active' : '' }}">
                                        <a class="dash-link"
                                            href="{{ route('company.realestate.furnishing.index') }}">{{ __('Furnishings') }}</a>
                                    </li>
                                @endcan
                                @can('manage role')
                                    <li
                                        class="dash-item {{ Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit' ? ' active' : '' }}">
                                        <a class="dash-link"
                                            href="{{ route('company.realestate.landmarks.index') }}">{{ __('Landmarks') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>
                </li>

                <li class="dash-item dash-hasmenu {{ Request::segment(2) == 'finance' ? 'active dash-trigger' : '' }}">
                    <a href="#!" class="dash-link">
                        <span class="dash-micon"><i class="ti ti-coin"></i></span>
                        <span class="dash-mtext">{{ __('Finance') }}</span>
                        <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                    </a>

                    <ul class="dash-submenu {{ Request::segment(2) == 'finance' ? 'show' : '' }}">
                        @can('manage user')
                            <li
                                class="dash-item dash-hasmenu {{ Request::segment(3) == 'realestate' ? 'active dash-trigger' : '' }}">
                                <a href="#!" class="dash-link">
                                    {{ __('Real Estate') }}
                                    <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                                </a>

                                <ul
                                    class="dash-submenu {{ Request::routeIs(
                                        'company.finance.realestate.invoice.choose',
                                        'company.finance.realestate.invoices.*',
                                        'company.finance.realestate.invoice-other.*',
                                    )
                                        ? 'show'
                                        : '' }}">
                                    <li
                                        class="dash-item {{ Request::routeIs(
                                            'company.finance.realestate.invoice.choose',
                                            'company.finance.realestate.invoices.*',
                                            'company.finance.realestate.invoice-other.*',
                                        )
                                            ? 'active'
                                            : '' }}">
                                        <a href="{{ route('company.finance.realestate.invoice.choose') }}"
                                            class="dash-link">{{ __('Invoices') }}</a>
                                    </li>
                                    <li class="dash-item {{ Request::routeIs('company.finance.realestate.payments.*') ? 'active' : '' }}">
                                        <a href="{{ route('company.finance.realestate.payments.choose') }}" class="dash-link">
                                            {{ __('Payments') }}
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li
                                class="dash-item {{ Request::routeIs('company.finance.bank-accounts.*') ? 'active' : '' }}">
                                <a href="{{ route('company.finance.bank-accounts.index') }}"
                                    class="dash-link">{{ __('Bank Accounts') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <li class="dash-item ">
                    <a href="{{ route('company.media.index') }}"
                        class="dash-link {{ Request::route()->getName() == 'media' ? ' active' : '' }}">
                        <span class="dash-micon"><i class="ti ti-cloud-download"></i></span>
                        <span class="dash-mtext">{{ __('Media') }}</span>
                    </a>
                </li>
                <li
                    class="dash-item dash-hasmenu {{ Request::segment(1) == 'realestate' || Request::segment(1) == 'realestate' || Request::segment(1) == 'realestate' ? ' active dash-trigger' : '' }}">
                    <a href="#!" class="dash-link "><span class="dash-micon"><i
                                class="ti ti-arrows-double-nw-se"></i></span><span
                            class="dash-mtext">{{ __('Reports') }}</span>
                        <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul
                        class="dash-submenu {{ Request::segment(1) == 'realestate' || Request::segment(1) == 'roles' || Request::segment(1) == 'permissions' ? 'show' : '' }}">
                        @can('manage user')
                            <li
                                class="dash-item {{ Request::route()->getName() == 'realestate.index' || Request::route()->getName() == 'realestate.create' || Request::route()->getName() == 'realestate.edit' ? ' active' : '' }}">
                                <a class="dash-link" href="#">{{ __('Staff`s') }}</a>
                            </li>
                        @endcan
                        @can('manage role')
                            <li
                                class="dash-item {{ Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit' ? ' active' : '' }}">
                                <a class="dash-link" href="#">{{ __('Role and Permissions') }}</a>
                            </li>
                        @endcan

                    </ul>
                </li>
                <li class="dash-item ">
                    <a href="{{ route('company.tickets.index') }}"
                        class="dash-link {{ Request::route()->getName() == 'tickets' ? ' active' : '' }}">
                        <span class="dash-micon"><i class="ti ti-headset"></i></span>
                        <span class="dash-mtext">{{ __('Support Tickets') }}</span>
                    </a>
                </li>
                <li class="dash-item ">

                    <a href="{{ route('dashboard') }}"
                        class="dash-link {{ Request::route()->getName() == 'dashboard' ? ' active' : '' }}">
                        <span class="dash-micon"><i class="ti ti-settings"></i></span>
                        <span class="dash-mtext">{{ __('Settings') }}</span>
                    </a>
                </li>


            </ul>
        </div>
    </div>
</nav>
