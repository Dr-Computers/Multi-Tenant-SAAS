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

                @can('company listing')
                    <li class="dash-item">
                        <a href="{{ route('admin.company.index') }}"
                            class="dash-link {{ Request::route()->getName() == 'admin.company.index' || Request::route()->getName() == 'company.create' || Request::route()->getName() == 'company.edit' ? ' active' : '' }}">
                            <span class="dash-micon"><i class="ti ti-users"></i></span>
                            <span class="dash-mtext">{{ __('Companies') }}</span>
                        </a>
                    </li>
                @endcan
                @canany(['staff user listing', 'permission listing', 'role listing'])
                    <li
                        class="dash-item dash-hasmenu {{ Request::segment(1) == 'users' || Request::segment(1) == 'roles' || Request::segment(1) == 'permissions' ? ' active dash-trigger' : '' }}">
                        <a href="#!" class="dash-link "><span class="dash-micon"><i
                                    class="ti ti-users"></i></span><span class="dash-mtext">{{ __('Staff') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul
                            class="dash-submenu {{ Request::segment(1) == 'users' || Request::segment(1) == 'roles' || Request::segment(1) == 'permissions' ? 'show' : '' }}">
                            @can('staff user listing')
                                <li
                                    class="dash-item {{ Request::route()->getName() == 'users.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'users.edit' ? ' active' : '' }}">
                                    <a class="dash-link" href="{{ route('admin.users.index') }}">{{ __('User') }}</a>
                                </li>
                            @endcan
                            @can('role listing')
                                <li
                                    class="dash-item {{ Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit' ? ' active' : '' }}">
                                    <a class="dash-link" href="{{ route('admin.roles.index') }}">{{ __('Role') }}</a>
                                </li>
                            @endcan
                            @can('permission listing')
                                <li
                                    class="dash-item {{ Request::route()->getName() == 'permissions.index' || Request::route()->getName() == 'permissions.create' || Request::route()->getName() == 'permissions.edit' ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('admin.permissions.index') }}">{{ __('Permissions') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @canany(['category listing', 'amenty listing', 'furnishing listing','landmark listing'])
                    <li
                        class="dash-item dash-hasmenu {{ Request::segment(1) == 'users' || Request::segment(1) == 'roles' || Request::segment(1) == 'permissions' ? ' active dash-trigger' : '' }}">
                        <a href="#!" class="dash-link "><span class="dash-micon"><i
                                    class="ti ti-users"></i></span><span class="dash-mtext">{{ __('Realestate') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul
                            class="dash-submenu {{ Request::segment(1) == 'users' || Request::segment(1) == 'roles' || Request::segment(1) == 'permissions' ? 'show' : '' }}">
                            @can('category listing')
                                <li
                                    class="dash-item {{ Request::route()->getName() == 'users.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'users.edit' ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('admin.realestate.categories.index') }}">{{ __('Categories') }}</a>
                                </li>
                            @endcan
                            @can('amenty listing')
                                <li
                                    class="dash-item {{ Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit' ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('admin.realestate.amenities.index') }}">{{ __('Amenities') }}</a>
                                </li>
                            @endcan
                            @can('furnishing listing')
                                <li
                                    class="dash-item {{ Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit' ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('admin.realestate.furnishings.index') }}">{{ __('Furnishings') }}</a>
                                </li>
                            @endcan
                            @can('landmark listing')
                                <li
                                    class="dash-item {{ Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit' ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('admin.realestate.landmarks.index') }}">{{ __('Landmarks') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @canany(['plan listing', 'section listing', 'role listing'])
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
                            @can('plan listing')
                                <li
                                    class="dash-item {{ Request::segment(1) == 'plans' || Request::segment(1) == 'stripe' ? 'active' : '' }}">
                                    <a href="{{ route('admin.plans.index') }}" class="dash-link  ">
                                        <span class="dash-micon"><i class="ti ti-trophy"></i></span>
                                        <span class="dash-mtext">{{ __('Plan') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('section listing')
                                <li class="dash-item  {{ Request::segment(0) == 'sections' ? 'active' : '' }}">
                                    <a href="{{ route('admin.plans.sections') }}" class="dash-link">
                                        <span class="dash-micon"><i class="ti ti-activity"></i></span>
                                        <span class="dash-mtext">{{ __('Sections') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('plan listing')
                                <li class="dash-item {{ Request::segment(1) == 'order' ? 'active' : '' }}">
                                    <a href="{{ route('admin.order.index') }}" class="dash-link ">
                                        <span class="dash-micon"><i class="ti ti-shopping-cart-plus"></i></span>
                                        <span class="dash-mtext">{{ __('Order') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @canany(['manage requested plans', 'manage requested sections'])
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
                                        @can('manage requested plans')
                                            <li class="dash-item {{ request()->is('plan_request*') ? 'active' : '' }}">
                                                <a href="{{ route('admin.plan_request.index') }}" class="dash-link  ">
                                                    <span class="dash-micon"><i class="ti ti-trophy"></i></span>
                                                    <span class="dash-mtext">{{ __('Plan') }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('manage requested sections')
                                            <li class="dash-item  {{ request()->is('section_request*') ? 'active' : '' }}">
                                                <a href="{{ route('admin.plans.section_request') }}" class="dash-link">
                                                    <span class="dash-micon"><i class="ti ti-activity"></i></span>
                                                    <span class="dash-mtext">{{ __('Sections') }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcan
                @canany([
                    'email template listing',
                    'estimate template listing',
                    'invoice template listing',
                    'letter pad
                    template listing',
                    ])
                    <li
                        class="dash-item dash-hasmenu {{ Request::segment(1) == 'users' || Request::segment(1) == 'roles' || Request::segment(1) == 'permissions' ? ' active dash-trigger' : '' }}">
                        <a href="#!" class="dash-link "><span class="dash-micon"><i
                                    class="ti ti-template"></i></span><span
                                class="dash-mtext">{{ __('Templates') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul
                            class="dash-submenu {{ Request::segment(1) == 'users' || Request::segment(1) == 'roles' || Request::segment(1) == 'permissions' ? 'show' : '' }}">
                            @can('email template listing')
                                <li
                                    class="dash-item {{ Request::route()->getName() == 'users.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'users.edit' ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('admin.email_template.index') }}">{{ __('Email Templates') }}</a>
                                </li>
                            @endcan
                            @can('invoice template listing')
                                <li
                                    class="dash-item {{ Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit' ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('admin.templates.invoices.index') }}">{{ __('Invoice Templates') }}</a>
                                </li>
                            @endcan
                            @can('estimate template listing')
                                <li
                                    class="dash-item {{ Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit' ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('admin.templates.estimates.index') }}">{{ __('Estimate Templates') }}</a>
                                </li>
                            @endcan
                            @can('letter pad template listing')
                                <li
                                    class="dash-item {{ Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit' ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('admin.templates.letter-pads.index') }}">{{ __('Letter Pad Templates') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                {{-- -------  Email Notification ---------- --}}


                {{-- <li class="dash-item {{ Request::segment(1) == 'Notifications' ? 'active' : '' }}">
                    <a href="{{ route('admin.notification-templates.index') }}" class="dash-link"><span
                            class="dash-micon"><i class="ti ti-bell"></i></span><span
                            class="dash-mtext">{{ __('Notification Template') }}</span></a>
                </li> --}}
                @can('ticket listing')
                    <li class="dash-item ">
                        <a href="{{ route('admin.tickets.index') }}"
                            class="dash-link {{ Request::route()->getName() == 'tickets' ? ' active' : '' }}">
                            <span class="dash-micon"><i class="ti ti-headset"></i></span>
                            <span class="dash-mtext">{{ __('Support Tickets') }}</span>
                        </a>
                    </li>
                @endcan

                {{-- -------  System Setting ---------- --}}
                @canany([
                    'brand settings',
                    'email settings',
                    'payment settings',
                    'set default invoices',
                    'set default
                    letterpad',
                    'storage settings',
                    'seo settings',
                    'cookie settings',
                    'reset permissions',
                    ])

                    <li class="dash-item {{ Request::route()->getName() == 'admin.settings.index' ? ' active' : '' }}">
                        <a href="{{ route('admin.settings.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-settings"></i></span>
                            <span class="dash-mtext">{{ __('Settings') }}</span>
                        </a>
                    </li>
                @endcanany


            </ul>
        </div>
    </div>
</nav>
