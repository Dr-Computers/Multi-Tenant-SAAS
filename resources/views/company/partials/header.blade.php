@php
    use App\Models\Utility;
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
                <li class="dropdown dash-h-item drp-company">
                    <a class="btn btn-primary btn-sm me-3" href="{{ route('company.plan.upgrade') }}"><i
                            class="ti ti-shopping-cart-plus"></i>
                        {{ __('Upgrade Plan') }}
                    </a>
                </li>
                <li class="dropdown dash-h-item drp-company">
                    <a class="btn btn-success btn-sm me-3" href="{{ route('company.addon.features') }}"><i
                            class="ti ti-apps"></i>
                        {{ __('Addon Features') }}
                    </a>
                </li>

                @impersonating($guard = null)
                    <li class="dropdown dash-h-item drp-company">
                        <a class="btn btn-danger btn-sm me-3" href="{{ route('admin.exit.company') }}"><i
                                class="ti ti-ban"></i>
                            {{ __('Exit Company Login') }}
                        </a>
                    </li>
                @endImpersonating
                <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-world nocolor"></i>
                        <span class="drp-text hide-mob">{{ ucFirst($languages->fullName ?? 'English') }}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                    </a>

                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">

                        {{-- @if (\Auth::guard('customer')->check())
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
                        @else --}}
                        @foreach (App\Models\Utility::languages() as $code => $lang)
                            <a href="{{ route('change.language', $code) }}"
                                class="dropdown-item {{ $currantLang == $code ? 'text-primary' : '' }}">
                                <span>{{ ucFirst($lang) }}</span>
                            </a>
                        @endforeach
                        {{-- @endif --}}
                    </div>
                </li>
                <li class="dropdown dash-h-item drp-company">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="theme-avtar">
                            <img src="{{ !empty(\Auth::user()->avatar_url) ? \App\Models\Utility::get_file(\Auth::user()->avatar_url) : asset(Storage::url('uploads/avatar/avatar.png')) }}"
                                class="img-fluid rounded-circle">
                        </span>
                        <span class="hide-mob ms-2">{{ __('Hi, ') }}{{ \Auth::user()->name }}!</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown">

                        <a href="{{ route('company.profile') }}" class="dropdown-item">
                            <i class="ti ti-user"></i> <span>{{ __('My Profile') }}</span>
                        </a>

                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('frm-logout').submit();"
                            class="dropdown-item">
                            <i class="ti ti-power"></i>
                            <span>{{ __('Logout') }}</span>
                        </a>
                        <form id="frm-logout" action="{{ route('logout') }}" method="POST" class="d-none">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
