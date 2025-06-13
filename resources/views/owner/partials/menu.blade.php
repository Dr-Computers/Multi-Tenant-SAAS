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

                <li class="dash-item {{ Request::route()->getName() == 'owner.dashboard' ? ' active' : '' }}">
                    <a href="{{ route('dashboard') }}"
                        class="dash-link {{ Request::route()->getName() == 'owner.dashboard' ? ' active' : '' }}">
                        <span class="dash-micon"><i class="ti ti-home"></i></span>
                        <span class="dash-mtext">{{ __('Dashboard') }}</span>
                    </a>
                </li>

                @can('properties listing')
                    <li class="dash-item {{ Request::routeIs('owner.realestate.properties.index') ? ' active' : '' }}">
                        <a class="dash-link" href="{{ route('owner.realestate.properties.index') }}">
                            <span class="dash-micon"><i class="ti ti-home"></i></span>
                            <span class="dash-mtext">
                                {{ __('Properties') }}
                            </span>
                        </a>
                    </li>
                    <li class="dash-item {{ Request::routeIs('owner.realestate.properties.lease.index') ? ' active' : '' }}">
                        <a class="dash-link" href="{{ route('owner.realestate.properties.lease.index') }}">
                            <span class="dash-micon"><i class="ti ti-home"></i></span>
                            <span class="dash-mtext">
                                {{ __('Lease Units') }}
                            </span>
                        </a>
                    </li>
                    <li class="dash-item {{ Request::routeIs('owner.realestate.properties.lease.requests') ? ' active' : '' }}">
                        <a class="dash-link" href="{{ route('owner.realestate.properties.lease.requests') }}">
                            <span class="dash-micon"><i class="ti ti-home"></i></span>
                            <span class="dash-mtext">
                                {{ __('Lease Requests') }}
                            </span>
                        </a>
                    </li>
                @endcan


                @can('maintenance requests listing')
                    <li
                        class="dash-item {{ Request::routeIs('owner.realestate.maintenance-requests.*') ? ' active' : '' }}">
                        <a class="dash-link" href="{{ route('owner.realestate.maintenance-requests.index') }}">
                            <span class="dash-micon"><i class="ti ti-home"></i></span>
                            <span class="dash-mtext">
                                {{ __('Maintenance Request') }}
                            </span>
                        </a>
                    </li>
                @endcan

                <li class="dash-item {{ Request::routeIs('owner.finance.invoices.index') ? ' active' : '' }}">
                    <a class="dash-link" href="{{ route('owner.finance.invoices.index') }}">
                        <span class="dash-micon"><i class="ti ti-home"></i></span>
                        <span class="dash-mtext">
                            {{ __('Invoices') }}
                        </span>
                    </a>
                </li>

                <li class="dash-item {{ Request::routeIs('owner.finance.payable.*') ? ' active' : '' }}">
                    <a class="dash-link" href="{{ route('owner.finance.payable.index') }}">
                        <span class="dash-micon"><i class="ti ti-home"></i></span>
                        <span class="dash-mtext">
                            {{ __('Payment Receivable') }}
                        </span>
                    </a>
                </li>

                <li class="dash-item {{ Request::routeIs('owner.settings.*') ? 'active' : '' }}">
                    <a href="{{ route('owner.settings.index') }}"
                        class="dash-link {{ Request::routeIs('owner.settings.*') ? 'active' : '' }}">
                        <span class="dash-micon"><i class="ti ti-settings"></i></span>
                        <span class="dash-mtext">{{ __('Settings') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
