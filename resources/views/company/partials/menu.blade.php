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
                @canany(['staff user listing', 'role listing'])

                    <li
                        class="dash-item dash-hasmenu {{ Request::routeIs('company.hrms.*') ? ' active dash-trigger' : '' }}">
                        <a href="#!" class="dash-link "><span class="dash-micon"><i
                                    class="ti ti-arrows-double-nw-se"></i></span><span
                                class="dash-mtext">{{ __('HRMS') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="dash-submenu {{ Request::routeIs('company.hrms.*') == 'users' ? 'show' : '' }}">
                            @can('staff user listing')
                                <li class="dash-item {{ Request::routeIs('company.hrms.users.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.hrms.users.index') }}">{{ __('Staff Users') }}</a>
                                </li>
                            @endcan
                            @can('role listing')
                                <li class="dash-item {{ Request::routeIs('company.hrms.roles.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.hrms.roles.index') }}">{{ __('Role and Permissions') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @canany(['owner user listing', 'properties listing', 'tenant user listing', 'maintainer user listing',
                    'maintenance requests listing'])
                    <li
                        class="dash-item dash-hasmenu {{ Request::routeIs('company.realestate.*') ? ' active dash-trigger' : '' }}">
                        <a href="#!" class="dash-link "><span class="dash-micon"><i
                                    class="ti ti-propeller"></i></span><span
                                class="dash-mtext">{{ __('Real estate') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="dash-submenu {{ Request::routeIs('company.realestate.*') ? 'show' : '' }}">
                            @can('owner user listing')
                                <li class="dash-item {{ Request::routeIs('company.realestate.owners.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.realestate.owners.index') }}">{{ __('Owners') }}</a>
                                </li>
                            @endcan
                            @can('properties listing')
                                <li
                                    class="dash-item {{ Request::routeIs('company.realestate.properties.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.realestate.properties.index') }}">{{ __('Properties') }}</a>
                                </li>
                                <li
                                    class="dash-item {{ Request::routeIs('company.realestate.properties.lease.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.realestate.properties.lease.index') }}">{{ __('Lease Units') }}</a>
                                </li>
                            @endcan
                            @can('tenant user listing')
                                <li class="dash-item {{ Request::routeIs('company.realestate.tenants.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.realestate.tenants.index') }}">{{ __('Tenants') }}</a>
                                </li>
                            @endcan
                            @can('maintainer user listing')
                                <li
                                    class="dash-item {{ Request::routeIs('company.realestate.maintainers.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.realestate.maintainers.index') }}">{{ __('Maintainers') }}</a>
                                </li>
                            @endcan
                            @can('maintenance requests listing')
                                <li
                                    class="dash-item {{ Request::routeIs('company.realestate.maintenance-requests.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.realestate.maintenance-requests.index') }}">{{ __('Maintenance Request') }}</a>
                                </li>
                            @endcan
                            @canany('category listing', 'amenity listing', 'furnishing listing', 'landmark listing')
                                <li
                                    class="dash-item dash-hasmenu {{ Request::routeIs('company.realestate.categories.*') || Request::routeIs('company.realestate.amenities.*') || Request::routeIs('company.realestate.furnishing.*') || Request::routeIs('company.realestate.landmarks.*') ? ' active dash-trigger' : '' }}">
                                    <a href="#!" class="dash-link "><span class="dash-mtext">{{ __('Setup') }}</span>
                                        <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                                    </a>
                                    <ul
                                        class="dash-submenu {{ Request::routeIs('company.realestate.categories.*') || Request::routeIs('company.realestate.amenities.*') || Request::routeIs('company.realestate.furnishing.*') || Request::routeIs('company.realestate.landmarks.*') ? 'show' : '' }}">
                                        @can('category listing')
                                            <li
                                                class="dash-item {{ Request::routeIs('company.realestate.categories.*') ? ' active' : '' }}">
                                                <a class="dash-link"
                                                    href="{{ route('company.realestate.categories.index') }}">{{ __('Categories') }}</a>
                                            </li>
                                        @endcan
                                        @can('amenity listing')
                                            <li
                                                class="dash-item {{ Request::routeIs('company.realestate.amenities.*') ? ' active' : '' }}">
                                                <a class="dash-link"
                                                    href="{{ route('company.realestate.amenities.index') }}">{{ __('Amenities') }}</a>
                                            </li>
                                        @endcan
                                        @can('furnishing listing')
                                            <li
                                                class="dash-item {{ Request::routeIs('company.realestate.furnishing.*') ? ' active' : '' }}">
                                                <a class="dash-link"
                                                    href="{{ route('company.realestate.furnishing.index') }}">{{ __('Furnishings') }}</a>
                                            </li>
                                        @endcan
                                        @can('landmark listing')
                                            <li
                                                class="dash-item {{ Request::routeIs('company.realestate.landmarks.*') ? ' active' : '' }}">
                                                <a class="dash-link"
                                                    href="{{ route('company.realestate.landmarks.index') }}">{{ __('Landmarks') }}</a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany
                @canany(['invoice listing', 'bank account lising', 'expense listing'])
                    <li
                        class="dash-item dash-hasmenu {{ Request::routeIs('company.finance.*') ? 'active dash-trigger' : '' }}">
                        <a href="#!" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-coin"></i></span>
                            <span class="dash-mtext">{{ __('Finance') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>

                        <ul
                            class="dash-submenu {{ Request::routeIs('company.finance.realestate.invoice.*') == 'finance' ? 'show' : '' }}">

                            <li
                                class="dash-item dash-hasmenu {{ Request::routeIs('company.finance.realestate.invoice.realestate.*') ? 'active dash-trigger' : '' }}">
                                <a href="#!" class="dash-link">
                                    {{ __('Real Estate') }}
                                    <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                                </a>

                                <ul
                                    class="dash-submenu {{ Request::routeIs('company.finance.realestate.*') ? 'show' : '' }}">
                                    @can('invoice listing')
                                        <li
                                            class="dash-item {{ Request::routeIs('company.finance.realestate.invoices.*') ? 'active' : '' }}">
                                            <a href="{{ route('company.finance.realestate.invoice.choose') }}"
                                                class="dash-link">{{ __('Invoices') }}</a>
                                        </li>
                                    @endcan
                                    @can('invoice receivable listing')
                                        <li
                                            class="dash-item {{ Request::routeIs('company.finance.realestate.invoice.payments.*', 'company.finance.realestate.other.payments.*') ? 'active' : '' }}">
                                            <a href="{{ route('company.finance.realestate.payments.choose') }}"
                                                class="dash-link">
                                                {{ __('Payments Receivable') }}
                                            </a>
                                        </li>
                                    @endcan
                                    @can('invoice payable listing')
                                        <li
                                            class="dash-item {{ Request::routeIs('company.finance.realestate.payments.payables.*') ? 'active' : '' }}">
                                            <a href="{{ route('company.finance.realestate.payments.payables.index') }}"
                                                class="dash-link">
                                                {{ __('Payments Payables') }}
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                            @can('bank account lising')
                                <li
                                    class="dash-item {{ Request::routeIs('company.finance.bank-accounts.*') ? 'active' : '' }}">
                                    <a href="{{ route('company.finance.bank-accounts.index') }}"
                                        class="dash-link">{{ __('Bank Accounts') }}</a>
                                </li>
                            @endcan
                            @can('expense listing')
                                <li class="dash-item {{ Request::routeIs('company.finance.expense.*') ? 'active' : '' }}">
                                    <a href="{{ route('company.finance.expense.index') }}"
                                        class="dash-link">{{ __('Expenses') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @can('asset listing')
                    <li class="dash-item {{ Request::routeIs('company.assets.*') ? 'active' : '' }}">
                        <a href="{{ route('company.assets.index') }}"
                            class="dash-link {{ Request::route()->getName() == 'assets' ? ' active' : '' }}">
                            <span class="dash-micon"><i class="ti ti-file"></i></span>
                            <span class="dash-mtext">{{ __('Assets') }}</span>
                        </a>
                    </li>
                @endcan
                @can('liabilities listing')
                    <li class="dash-item {{ Request::routeIs('company.liabilities.*') ? 'active' : '' }}">
                        <a href="{{ route('company.liabilities.index') }}"
                            class="dash-link {{ Request::route()->getName() == 'liabilities' ? ' active' : '' }}">
                            <span class="dash-micon"><i class="ti ti-file"></i></span>
                            <span class="dash-mtext">{{ __('Libility') }}</span>
                        </a>
                    </li>
                @endcan
                @can('upload a file')
                    <li class="dash-item {{ Request::routeIs('company.media.*') ? 'active' : '' }}">
                        <a href="{{ route('company.media.index') }}"
                            class="dash-link {{ Request::route()->getName() == 'media' ? ' active' : '' }}">
                            <span class="dash-micon"><i class="ti ti-cloud-download"></i></span>
                            <span class="dash-mtext">{{ __('Media') }}</span>
                        </a>
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
                                <li
                                    class="dash-item {{ Request::routeIs('company.report.maintenances.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.report.maintenances.index') }}">{{ __('Maintenance Report') }}</a>
                                </li>
                            @endcan
                            @can('maintainer report')
                                <li class="dash-item {{ Request::routeIs('company.report.maintainers.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.report.maintainers.index') }}">{{ __('Maintainer Report') }}</a>
                                </li>
                            @endcan
                            @can('invoice report')
                                <li class="dash-item {{ Request::routeIs('company.report.invoices.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.report.invoices.index') }}">{{ __('Invoice Report') }}</a>
                                </li>
                            @endcan
                            @can('expense report')
                                <li class="dash-item {{ Request::routeIs('company.report.expenses.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.report.expenses.index') }}">{{ __('Expense Report') }}</a>
                                </li>
                            @endcan
                            @can('cheques report')
                                <li class="dash-item {{ Request::routeIs('company.report.cheques.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.report.cheques.index') }}">{{ __('Cheques Report') }}</a>
                                </li>
                            @endcan
                            @can('tenants report')
                                <li class="dash-item {{ Request::routeIs('company.report.tenants.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.report.tenants.index') }}">{{ __('Tenants Report') }}</a>
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
                            @can('rent collection summary report')
                                <li
                                    class="dash-item {{ Request::routeIs('company.report.rent_collection.*') ? ' active' : '' }}">
                                    <a class="dash-link" href="{{ route('company.report.rent_collection.index') }}">
                                        {{ __('Rent Collection Summary Report') }}</a>
                                </li>
                            @endcan

                            @can('payments report')
                                <li class="dash-item {{ Request::routeIs('company.report.payments.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.report.payments.index') }}">{{ __('Payments Report') }}</a>
                                </li>
                            @endcan
                            @can('deposit payments report')
                                <li
                                    class="dash-item {{ Request::routeIs('company.report.deposit.payments.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.report.deposit.payments.index') }}">{{ __('Deposit Payments Report') }}</a>
                                </li>
                            @endcan
                            @can('bank transactions report')
                                <li
                                    class="dash-item {{ Request::routeIs('company.report.bank_transactions.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.report.bank_transactions.index') }}">{{ __('Bank Transactions Report') }}</a>
                                </li>
                            @endcan
                            @can('profit and loss report')
                                <li
                                    class="dash-item {{ Request::routeIs('company.report.profit_loss.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.report.profit_loss.index') }}">{{ __('Profit and Loss Report') }}</a>
                                </li>
                            @endcan
                            @can('balance sheet report')
                                <li
                                    class="dash-item {{ Request::routeIs('company.report.balance_sheet.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.report.balance_sheet.index') }}">{{ __('Balance Sheet') }}</a>
                                </li>
                            @endcan
                            @can('lease expiry report')
                                <li
                                    class="dash-item {{ Request::routeIs('company.report.lease-expiry.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.report.lease-expiry.index') }}">{{ __('Lease Expiry') }}</a>
                                </li>
                            @endcan
                            @can('fire and safety expiry report')
                                <li
                                    class="dash-item {{ Request::routeIs('company.report.fireandsafety-expiry.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.report.fireandsafety-expiry.index') }}">{{ __('Fire And Safety Expiry') }}</a>
                                </li>
                            @endcan
                            @can('insurance expiry report')
                                <li
                                    class="dash-item {{ Request::routeIs('company.report.insurance-expiry.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.report.insurance-expiry.index') }}">{{ __('Insurance Expiry') }}</a>
                                </li>
                            @endcan
                            @can('building wise outstanding report')
                                <li
                                    class="dash-item {{ Request::routeIs('company.report.invoice-outstanding.*') ? ' active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('company.report.invoice-outstanding.index') }}">{{ __('Building wise outstanding report') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('ticket listing')
                    <li class="dash-item {{ Request::routeIs('company.tickets.*') ? 'active' : '' }}">
                        <a href="{{ route('company.tickets.index') }}"
                            class="dash-link {{ Request::routeIs('company.tickets.*') ? 'active' : '' }}">
                            <span class="dash-micon"><i class="ti ti-headset"></i></span>
                            <span class="dash-mtext">{{ __('Support Tickets') }}</span>
                        </a>
                    </li>
                @endcan
                  @canany(['plan listing', 'section listing', 'role listing'])
                    <li
                        class="dash-item dash-hasmenu {{ Request::routeIs('company.subcriptions.*')  ? ' active dash-trigger' : '' }}">
                        <a href="#!" class="dash-link "><span class="dash-micon"><i
                                    class="ti ti-building-bank"></i></span><span
                                class="dash-mtext">{{ __('Subcriptions') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul
                            class="dash-submenu {{ Request::routeIs('company.subcriptions.*') ? 'show' : '' }}">
                            {{-- -------  Plan---------- --}}
                            @can('plan listing')
                                <li
                                    class="dash-item {{ Request::routeIs('company.subcriptions.plans.index') ? 'active' : '' }}">
                                    <a href="{{ route('company.subcriptions.plans.index') }}" class="dash-link  ">
                                        <span class="dash-micon"><i class="ti ti-trophy"></i></span>
                                        <span class="dash-mtext">{{ __('Plans') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('section listing')
                                <li class="dash-item  {{ Request::routeIs('company.plans.sections') ? 'active' : '' }}">
                                    <a href="{{ route('company.subcriptions.plans.sections') }}" class="dash-link">
                                        <span class="dash-micon"><i class="ti ti-activity"></i></span>
                                        <span class="dash-mtext">{{ __('Features') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('plan listing')
                                <li class="dash-item {{ Request::routeIs('company.subcriptions.orders.*') ? 'active' : '' }}">
                                    <a href="{{ route('company.subcriptions.orders.index') }}" class="dash-link ">
                                        <span class="dash-micon"><i class="ti ti-shopping-cart-plus"></i></span>
                                        <span class="dash-mtext">{{ __('Orders') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @canany(['manage requested plans', 'manage requested sections'])
                                <li
                                    class="dash-item dash-hasmenu {{ Request::routeIs('company.subcriptions.plan_request.*') || Request::routeIs('company.subcriptions.section_request.*') ? ' active dash-trigger' : '' }}">
                                    <a href="#!" class="dash-link "><span class="dash-micon"><i
                                                class="ti ti-building-bank"></i></span><span
                                            class="dash-mtext">{{ __('Requested') }}</span>
                                        <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                                    </a>
                                    <ul
                                        class="dash-submenu {{ Request::segment(1) == 'plan_request' || Request::segment(1) == 'sections' ? 'show' : '' }}">
                                        {{-- -------  Plan---------- --}}
                                        @can('manage requested plans')
                                            <li class="dash-item {{ Request::routeIs('company.subcriptions.plan_request.*') ? 'active' : '' }}">
                                                <a href="{{ route('company.subcriptions.plan_request.index') }}" class="dash-link  ">
                                                    <span class="dash-micon"><i class="ti ti-trophy"></i></span>
                                                    <span class="dash-mtext">{{ __('Plan') }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('manage requested sections')
                                            <li class="dash-item  {{ Request::routeIs('company.subcriptions.section_request.*') ? 'active' : '' }}">
                                                <a href="{{ route('company.subcriptions.section_request.index') }}" class="dash-link">
                                                    <span class="dash-micon"><i class="ti ti-activity"></i></span>
                                                    <span class="dash-mtext">{{ __('Features') }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
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
