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

                <li class="dash-item {{ Request::route()->getName() == 'company.dashboard' ? ' active' : '' }}">
                    <a href="{{ route('dashboard') }}"
                        class="dash-link {{ Request::route()->getName() == 'company.dashboard' ? ' active' : '' }}">
                        <span class="dash-micon"><i class="ti ti-home"></i></span>
                        <span class="dash-mtext">{{ __('Dashboard') }}</span>
                    </a>
                </li>
                @can('properties listing')
                    <li class="dash-item {{ Request::routeIs('company.realestate.properties.*') ? ' active' : '' }}">
                        <a class="dash-link"
                            href="{{ route('company.realestate.properties.index') }}">{{ __('Properties') }}</a>
                    </li>
                    <li class="dash-item {{ Request::routeIs('company.realestate.properties.lease.*') ? ' active' : '' }}">
                        <a class="dash-link"
                            href="{{ route('company.realestate.properties.lease.index') }}">{{ __('Lease Units') }}</a>
                    </li>
                @endcan

                @can('maintenance requests listing')
                    <li
                        class="dash-item {{ Request::routeIs('company.realestate.maintenance-requests.*') ? ' active' : '' }}">
                        <a class="dash-link"
                            href="{{ route('company.realestate.maintenance-requests.index') }}">{{ __('Maintenance Request') }}</a>
                    </li>
                @endcan
                @can('invoice listing')
                    <li class="dash-item {{ Request::routeIs('company.finance.realestate.invoices.*') ? 'active' : '' }}">
                        <a href="{{ route('company.finance.realestate.invoice.choose') }}"
                            class="dash-link">{{ __('Invoices') }}</a>
                    </li>
                @endcan

                @can('reports')
                    <li
                        class="dash-item dash-hasmenu {{ Request::routeIs('company.report.*') ? ' active dash-trigger' : '' }}">
                        <a href="#!" class="dash-link "><span class="dash-micon"><i
                                    class="ti ti-file-report"></i></span><span
                                class="dash-mtext">{{ __('Reports') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="dash-submenu {{ Request::routeIs('company.report.*') ? 'show' : '' }}">
                            @can('maintenance report')
                                <li class="dash-item {{ Request::routeIs('company.report.maintenances.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.report.maintenances.index') }}">{{ __('Maintenance Report') }}</a>
                                </li>
                            @endcan
                         
                            @can('invoice report')
                                <li class="dash-item {{ Request::routeIs('company.report.invoices.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.report.invoices.index') }}">{{ __('Invoice Report') }}</a>
                                </li>
                            @endcan
                          
                            @can('properties report')
                                <li class="dash-item {{ Request::routeIs('company.report.properties.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.report.properties.index') }}">{{ __('Properties Report') }}</a>
                                </li>
                            @endcan
                            @can('units report')
                                <li class="dash-item {{ Request::routeIs('company.report.units.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.report.units.index') }}">{{ __('Units Report') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
              
                <li class="dash-item {{ Request::routeIs('company.settings.*') ? 'active' : '' }}">
                    <a href="{{ route('company.settings.index') }}"
                        class="dash-link {{ Request::routeIs('company.settings.*') ? 'active' : '' }}">
                        <span class="dash-micon"><i class="ti ti-settings"></i></span>
                        <span class="dash-mtext">{{ __('Settings') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
