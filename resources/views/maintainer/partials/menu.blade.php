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

                <li class="dash-item {{ Request::route()->getName() == 'maintainer.dashboard' ? ' active' : '' }}">
                    <a href="{{ route('dashboard') }}"
                        class="dash-link {{ Request::route()->getName() == 'maintainer.dashboard' ? ' active' : '' }}">
                        <span class="dash-micon"><i class="ti ti-home"></i></span>
                        <span class="dash-mtext">{{ __('Dashboard') }}</span>
                    </a>
                </li>
                @can('maintenance requests listing')
                    <li
                        class="dash-item {{ Request::routeIs('maintainer.maintenance-works.*') ? ' active' : '' }}">
                        <a class="dash-link" href="{{ route('maintainer.maintenance-works.index') }}">
                            <span class="dash-micon"><i class="ti ti-home"></i></span>
                            <span class="dash-mtext">
                                {{ __('Maintenance Work') }}
                            </span>
                        </a>
                    </li>
                @endcan
                {{-- @can('invoice listing')
                    <li class="dash-item {{ Request::routeIs('maintainer.finance.invoices.*') ? 'active' : '' }}">
                        <a href="{{ route('maintainer.finance.invoices.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-home"></i></span>
                            <span class="dash-mtext">
                                {{ __('Invoices') }}
                            </span>
                        </a>
                    </li>
                @endcan --}}


                <li class="dash-item {{ Request::routeIs('maintainer.settings.*') ? 'active' : '' }}">
                    <a href="{{ route('maintainer.settings.index') }}"
                        class="dash-link {{ Request::routeIs('maintainer.settings.*') ? 'active' : '' }}">
                        <span class="dash-micon"><i class="ti ti-settings"></i></span>
                        <span class="dash-mtext">{{ __('Settings') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
