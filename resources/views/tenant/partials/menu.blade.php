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

<nav
    class="dash-sidebar light-sidebar {{ isset($mode_setting['cust_theme_bg']) && $mode_setting['cust_theme_bg'] == 'on' ? 'transprent-bg' : '' }}">
    <div class="navbar-wrapper">
        <div class="m-header main-logo">
            <a href="" class="b-brand">
                <img src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') . '?' . time() }}"
                    alt="{{ config('app.name', 'Dr Computers') }}" class="logo logo-lg">
            </a>
        </div>

        <div class="navbar-content">
            <ul class="dash-navbar">
                {{-- -------  Dashboard ---------- --}}

                <li class="dash-item {{ Request::route()->getName() == 'tenant.dashboard' ? ' active' : '' }}">
                    <a href="{{ route('dashboard') }}"
                        class="dash-link {{ Request::route()->getName() == 'tenant.dashboard' ? ' active' : '' }}">
                        <span class="dash-micon"><i class="ti ti-home"></i></span>
                        <span class="dash-mtext">{{ __('Dashboard') }}</span>
                    </a>
                </li>
                @canany(['properties listing'])
                <li
                    class="dash-item dash-hasmenu {{ Request::routeIs('company.realestate.*') ? ' active dash-trigger' : '' }}">
                    <a href="#!" class="dash-link "><span class="dash-micon"><i
                                class="ti ti-propeller"></i></span><span
                            class="dash-mtext">{{ __('Real estate') }}</span>
                        <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="dash-submenu {{ Request::routeIs('company.realestate.*') ? 'show' : '' }}">
                        @can('properties listing')
                            <li class="dash-item {{ Request::routeIs('tenant.realestate.properties.*') ? ' active' : '' }}">
                                <a class="dash-link" href="{{ route('tenant.realestate.properties.index') }}">
                                    <span class="dash-mtext">
                                        {{ __('Properties') }}
                                    </span>
                                </a>
                            </li>
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('tenant.realestate.properties.lease.index') }}">
                                    <span class="dash-mtext">
                                        {{ __('Lease Units') }}
                                    </span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @can('maintenance requests listing')
                    <li
                        class="dash-item {{ Request::routeIs('tenant.realestate.maintenance-requests.*') ? ' active' : '' }}">
                        <a class="dash-link" href="{{ route('tenant.realestate.maintenance-requests.index') }}">
                            <span class="dash-micon"><i class="ti ti-home"></i></span>
                            <span class="dash-mtext">
                                {{ __('Maintenance Request') }}
                            </span>
                        </a>
                    </li>
                @endcan
                {{-- @can('invoice listing')
                    <li class="dash-item {{ Request::routeIs('tenant.finance.invoices.*') ? 'active' : '' }}">
                        <a href="{{ route('tenant.finance.invoices.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-home"></i></span>
                            <span class="dash-mtext">
                                {{ __('Invoices') }}
                            </span>
                        </a>
                    </li>
                @endcan --}}
{{-- 
                @can('reports')
                    <li
                        class="dash-item dash-hasmenu {{ Request::routeIs('tenant.report.*') ? ' active dash-trigger' : '' }}">
                        <a href="#!" class="dash-link "><span class="dash-micon"><i
                                    class="ti ti-file-report"></i></span><span
                                class="dash-mtext">{{ __('Reports') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="dash-submenu {{ Request::routeIs('tenant.report.*') ? 'show' : '' }}">
                            @can('maintenance report')
                                <li class="dash-item {{ Request::routeIs('tenant.report.maintenances.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('tenant.report.maintenances.index') }}">{{ __('Maintenance Report') }}</a>
                                </li>
                            @endcan

                            @can('invoice report')
                                <li class="dash-item {{ Request::routeIs('tenant.report.invoices.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('tenant.report.invoices.index') }}">{{ __('Invoice Report') }}</a>
                                </li>
                            @endcan

                            @can('properties report')
                                <li class="dash-item {{ Request::routeIs('tenant.report.properties.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('tenant.report.properties.index') }}">{{ __('Properties Report') }}</a>
                                </li>
                            @endcan
                            @can('units report')
                                <li class="dash-item {{ Request::routeIs('tenant.report.units.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('tenant.report.units.index') }}">{{ __('Units Report') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan --}}

                <li class="dash-item {{ Request::routeIs('tenant.settings.*') ? 'active' : '' }}">
                    <a href="{{ route('tenant.settings.index') }}"
                        class="dash-link {{ Request::routeIs('tenant.settings.*') ? 'active' : '' }}">
                        <span class="dash-micon"><i class="ti ti-settings"></i></span>
                        <span class="dash-mtext">{{ __('Settings') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
