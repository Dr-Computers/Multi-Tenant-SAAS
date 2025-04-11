@php
    use \App\Models\Utility;
    $users = \Auth::user();
    $profile = asset(Storage::url('uploads/avatar/'));
    $currantLang = $users->currentLanguage();
    $languages = \App\Models\Language::where('code', $currantLang)->first();
    $mode_setting = \App\Models\Utility::getLayoutsSetting();
@endphp

<header
    class="dash-header  {{ isset($mode_setting['cust_theme_bg']) && $mode_setting['cust_theme_bg'] == 'on' ? 'transprent-bg' : '' }}">
    <div class="header-wrapper">
        <div class="me-auto dash-mob-drp">
            <ul class="list-unstyled">
                <li class="dash-h-item mob-hamburger">
                    <a href="#!" class="dash-head-link" id="mobile-collapse">
                        <div class="hamburger hamburger--arrowturn">
                            <div class="hamburger-box">
                                <div class="hamburger-inner">
                                </div>
                            </div>
                        </div>
                    </a>
                </li>

            
            </ul>

        </div>
        <div class="ms-auto">
            <ul class="list-unstyled">
               
                <li class="dropdown dash-h-item drp-language">
                    {{-- <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-world nocolor"></i>
                        <span class="drp-text hide-mob">{{ ucFirst($languages->fullName ?? 'English') }}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                    </a> --}}

                    {{-- <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">

                        @if (\Auth::guard('customer')->check())
                            @foreach (App\Models\Utility::languages() as $code => $lang)
                                <a href="{{ route('customer.change.language', $code) }}"
                                    class="dropdown-item {{ $currantLang == $code ? 'text-primary' : '' }}">
                                    <span>{{ ucFirst($lang) }}</span>
                                </a>
                            @endforeach
                        @elseif(\Auth::guard('vender')->check())

                            @foreach (App\Models\Utility::languages() as $code => $lang)
                                <a href="{{ route('vender.change.language', $code) }}"
                                    class="dropdown-item {{ $currantLang == $code ? 'text-primary' : '' }}">
                                    <span>{{ ucFirst($lang) }}</span>
                                </a>
                            @endforeach
                        @else
                            @foreach (App\Models\Utility::languages() as $code => $lang)
                                <a href="{{ route('change.language', $code) }}"
                                    class="dropdown-item {{ $currantLang == $code ? 'text-primary' : '' }}">
                                    <span>{{ ucFirst($lang) }}</span>
                                </a>
                            @endforeach
                        @endif

                        @if (\Auth::user()->type == 'super admin')
                            <div class="dropdown-divider m-0"></div>
                            <a href="#" data-url="{{ route('create.language') }}"
                                class="dropdown-item text-primary" data-bs-toggle="tooltip"
                                title="{{ __('Create') }}" data-ajax-popup="true"
                                data-title="{{ __('Create New Language') }}">{{ __('Create Language') }}
                            </a>
                        @endif

                        @if (\Auth::user()->type == 'super admin')
                            <div class="dropdown-divider m-0"></div>
                            <a class="dropdown-item text-primary"
                                href="{{ route('manage.language', [$currantLang]) }}">{{ __('Manage Language') }}</a>
                        @endif

                    </div> --}}
                </li>
                <li class="dropdown dash-h-item drp-company">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="theme-avtar">
                            @if (\Auth::guard('customer')->check())
                                <img src="{{  (isset(\Auth::user()->avatar) && !empty(\Auth::user()->avatar) ? \App\Models\Utility::get_file('uploads/avatar/'.\Auth::user()->avatar) : 'logo-dark.png') }}"
                                    class="img-fluid rounded-circle">
                            @else
                                <img src="{{ !empty(\Auth::user()->avatar) ? \App\Models\Utility::get_file(\Auth::user()->avatar) : asset(Storage::url('uploads/avatar/avatar.png')) }}"
                                    class="img-fluid rounded-circle">
                            @endif
                        </span>
                        <span class="hide-mob ms-2">{{ __('Hi, ') }}{{ \Auth::user()->name }}!</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown">

                        @if (\Auth::guard('customer')->check())
                            <a href="{{ route('customer.profile') }}" class="dropdown-item">
                                <i class="ti ti-user"></i> <span>{{ __('My Profile') }}</span>
                            </a>
                        @elseif(\Auth::guard('vender')->check())
                            <a href="{{ route('vender.profile') }}" class="dropdown-item">
                                <i class="ti ti-user"></i> <span>{{ __('My Profile') }}</span>
                            </a>
                        @else
                            <a href="{{ route('profile') }}" class="dropdown-item">
                                <i class="ti ti-user"></i> <span>{{ __('My Profile') }}</span>
                            </a>
                        @endif

                        @if (\Auth::guard('customer')->check())
                            <a href="{{ route('customer.logout') }}"
                                onclick="event.preventDefault(); document.getElementById('frm-logout').submit();"
                                class="dropdown-item">
                                <i class="ti ti-power"></i>
                                <span>{{ __('Logout') }}</span>
                            </a>
                            <form id="frm-logout" action="{{ route('customer.logout') }}" method="POST"
                                class="d-none">
                                {{ csrf_field() }}
                            </form>
                        @elseif(\Auth::guard('vender')->check())
                            <a href="{{ route('vender.logout') }}"
                                onclick="event.preventDefault(); document.getElementById('frm-logout').submit();"
                                class="dropdown-item">
                                <i class="ti ti-power"></i>
                                <span>{{ __('Logout') }}</span>
                            </a>
                            <form id="frm-logout" action="{{ route('vender.logout') }}" method="POST" class="d-none">
                                {{ csrf_field() }}
                            </form>
                        @else
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('frm-logout').submit();"
                                class="dropdown-item">
                                <i class="ti ti-power"></i>
                                <span>{{ __('Logout') }}</span>
                            </a>
                            <form id="frm-logout" action="{{ route('logout') }}" method="POST" class="d-none">
                                {{ csrf_field() }}
                            </form>
                        @endif


                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
