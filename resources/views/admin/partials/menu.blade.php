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
                <li class="dash-item  {{ Request::route()->getName() == 'admin.dashboard'  ? ' active' : '' }}">
                    <a href="{{ route('dashboard') }}"
                        class="dash-link {{ Request::route()->getName() == 'admin.dashboard' ? ' active' : '' }}">
                        <span class="dash-micon"><i class="ti ti-home"></i></span>
                        <span class="dash-mtext">{{ __('Dashboard') }}</span>
                    </a>
                </li>

                @can('company listing')
                    <li class="dash-item {{ Request::routeIs('admin.company.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.company.index') }}"
                            class="dash-link {{ Request::routeIs('admin.company.*') ? 'active' : '' }}">
                            <span class="dash-micon"><i class="ti ti-users"></i></span>
                            <span class="dash-mtext">{{ __('Companies') }}</span>
                        </a>
                    </li>
                @endcan
                @canany(['staff user listing', 'permission listing', 'role listing'])
                    <li
                        class="dash-item dash-hasmenu {{ Request::routeIs('admin.hrms.*') ? ' active dash-trigger' : '' }}">
                        <a href="#!" class="dash-link "><span class="dash-micon"><i
                                    class="ti ti-users"></i></span><span class="dash-mtext">{{ __('Staff') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul
                            class="dash-submenu {{ Request::segment(1) == 'users' || Request::segment(1) == 'roles' || Request::segment(1) == 'permissions' ? 'show' : '' }}">
                            @can('staff user listing')
                                <li
                                    class="dash-item {{ Request::routeIs('admin.hrms.users.*') ? ' active' : '' }}">
                                    <a class="dash-link" href="{{ route('admin.users.index') }}">{{ __('User') }}</a>
                                </li>
                            @endcan
                            @can('role listing')
                                <li
                                    class="dash-item {{ Request::routeIs('admin.hrms.roles.*') ? ' active' : '' }}">
                                    <a class="dash-link" href="{{ route('admin.roles.index') }}">{{ __('Role') }}</a>
                                </li>
                            @endcan
                            @can('permission listing')
                                <li
                                    class="dash-item {{ Request::routeIs('admin.hrms.permissions.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('admin.permissions.index') }}">{{ __('Permissions') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @canany(['category listing', 'amenity listing', 'furnishing listing', 'landmark listing'])
                    <li
                        class="dash-item dash-hasmenu {{ Request::routeIs('admin.realestate.*') ? ' active dash-trigger' : '' }}">
                        <a href="#!" class="dash-link "><span class="dash-micon"><i
                                    class="ti ti-users"></i></span><span class="dash-mtext">{{ __('Realestate') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul
                            class="dash-submenu {{ Request::segment(1) == 'users' || Request::segment(1) == 'roles' || Request::segment(1) == 'permissions' ? 'show' : '' }}">
                            @can('category listing')
                                <li
                                    class="dash-item {{ Request::routeIs('admin.realestate.categories.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('admin.realestate.categories.index') }}">{{ __('Categories') }}</a>
                                </li>
                            @endcan
                            @can('amenity listing')
                                <li
                                    class="dash-item {{ Request::routeIs('admin.realestate.amenities.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('admin.realestate.amenities.index') }}">{{ __('Amenities') }}</a>
                                </li>
                            @endcan
                            @can('furnishing listing')
                                <li
                                    class="dash-item {{ Request::routeIs('admin.amenities.furnishings.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('admin.realestate.furnishings.index') }}">{{ __('Furnishings') }}</a>
                                </li>
                            @endcan
                            @can('landmark listing')
                                <li
                                    class="dash-item {{ Request::routeIs('admin.realestate.landmarks.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('admin.realestate.landmarks.index') }}">{{ __('Landmarks') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @canany(['plan listing', 'section listing', 'role listing'])
                    <li
                        class="dash-item dash-hasmenu {{ Request::routeIs('admin.plans.*') || Request::routeIs('admin.order.*')   ? ' active dash-trigger' : '' }}">
                        <a href="#!" class="dash-link "><span class="dash-micon"><i
                                    class="ti ti-building-bank"></i></span><span
                                class="dash-mtext">{{ __('Subcriptions') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul
                            class="dash-submenu {{ Request::routeIs('admin.plans.*') || Request::routeIs('admin.order.*') ? 'show' : '' }}">
                            {{-- -------  Plan---------- --}}
                            @can('plan listing')
                                <li
                                    class="dash-item {{ Request::routeIs('admin.plans.index') ? 'active' : '' }}">
                                    <a href="{{ route('admin.plans.index') }}" class="dash-link  ">
                                        <span class="dash-micon"><i class="ti ti-trophy"></i></span>
                                        <span class="dash-mtext">{{ __('Plan') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('section listing')
                                <li class="dash-item  {{ Request::routeIs('admin.plans.sections') ? 'active' : '' }}">
                                    <a href="{{ route('admin.plans.sections') }}" class="dash-link">
                                        <span class="dash-micon"><i class="ti ti-activity"></i></span>
                                        <span class="dash-mtext">{{ __('Sections') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('plan listing')
                                <li class="dash-item {{ Request::routeIs('admin.order.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.order.index') }}" class="dash-link ">
                                        <span class="dash-micon"><i class="ti ti-shopping-cart-plus"></i></span>
                                        <span class="dash-mtext">{{ __('Order') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @canany(['manage requested plans', 'manage requested sections'])
                                <li
                                    class="dash-item dash-hasmenu {{ Request::routeIs('admin.plans.plan_request.*') || Request::routeIs('admin.plans.section_request.*') ? ' active dash-trigger' : '' }}">
                                    <a href="#!" class="dash-link "><span class="dash-micon"><i
                                                class="ti ti-building-bank"></i></span><span
                                            class="dash-mtext">{{ __('Requests') }}</span>
                                        <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                                    </a>
                                    <ul
                                        class="dash-submenu {{ Request::segment(1) == 'plan_request' || Request::segment(1) == 'sections' ? 'show' : '' }}">
                                        {{-- -------  Plan---------- --}}
                                        @can('manage requested plans')
                                            <li class="dash-item {{ Request::routeIs('admin.plans.plan_request.*') ? 'active' : '' }}">
                                                <a href="{{ route('admin.plans.plan_request.index') }}" class="dash-link  ">
                                                    <span class="dash-micon"><i class="ti ti-trophy"></i></span>
                                                    <span class="dash-mtext">{{ __('Plan') }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('manage requested sections')
                                            <li class="dash-item  {{ Request::routeIs('admin.plans.section_request.*') ? 'active' : '' }}">
                                                <a href="{{ route('admin.plans.section_request.index') }}" class="dash-link">
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
                        class="dash-item dash-hasmenu {{ Request::routeIs('admin.templates.*')   ? ' active dash-trigger' : '' }}">
                        <a href="#!" class="dash-link "><span class="dash-micon"><i
                                    class="ti ti-template"></i></span><span
                                class="dash-mtext">{{ __('Templates') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul
                            class="dash-submenu {{ Request::routeIs('admin.templates.*')   ? 'show' : '' }}">
                            @can('email template listing')
                                {{-- <li
                                    class="dash-item {{ Request::route()->getName() == 'users.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'users.edit' ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('admin.email_template.index') }}">{{ __('Email Templates') }}</a>
                                </li> --}}
                            @endcan
                            @can('invoice template listing')
                                <li
                                    class="dash-item {{ Request::routeIs('admin.templates.invoices.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('admin.templates.invoices.index') }}">{{ __('Invoice Templates') }}</a>
                                </li>
                            @endcan
                            @can('estimate template listing')
                                <li
                                    class="dash-item {{ Request::routeIs('admin.templates.estimates.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('admin.templates.estimates.index') }}">{{ __('Estimate Templates') }}</a>
                                </li>
                            @endcan
                            @can('letter pad template listing')
                                <li
                                    class="dash-item {{ Request::routeIs('admin.templates.letter-pads.*') ? ' active' : '' }}">
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
                    <li class="dash-item {{ Request::routeIs('admin.tickets.*') ? ' active' : '' }} ">
                        <a href="{{ route('admin.tickets.index') }}"
                            class="dash-link {{ Request::routeIs('admin.tickets.*') ? ' active' : '' }}">
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

                    <li class="dash-item {{ Request::routeIs('admin.settings') ? ' active' : '' }}">
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
