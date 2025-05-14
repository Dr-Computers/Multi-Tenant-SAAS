@extends('layouts.admin')
@section('page-title')
    {{ __('Settings') }}
@endsection
@php
    use App\Models\Utility;
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $meta_image = \App\Models\Utility::get_file('uploads/metaevent/');
    $lang = \App\Models\Utility::getValByName('default_language');
    $file_type = config('files_types');
    $setting = App\Models\Utility::settings();
    $admin = App\Models\Utility::getAdminPaymentSetting();
    $color = !empty($setting['color']) ? $setting['color'] : 'theme-3';
    $flag = !empty($setting['color_flag']) ? $setting['color_flag'] : '';

    $logo_light = \App\Models\Utility::getValByName('company_logo_light');
    $logo_dark = \App\Models\Utility::getValByName('company_logo_dark');

    $local_storage_validation = $setting['local_storage_validation'];
    $local_storage_validations = explode(',', $local_storage_validation);

    $s3_storage_validation = $setting['s3_storage_validation'];
    $s3_storage_validations = explode(',', $s3_storage_validation);

    $wasabi_storage_validation = $setting['wasabi_storage_validation'];
    $wasabi_storage_validations = explode(',', $wasabi_storage_validation);
    $chatGPT = \App\Models\Utility::settings('enable_chatgpt');
    $enable_chatgpt = !empty($chatGPT);

    $google_recaptcha_version = ['v2-checkbox' => __('v2'), 'v3' => __('v3')];

@endphp

<style>
    .dash-footer {
        margin-left: 0 !important
    }
</style>

@push('script-page')
    <style>
        .invoice-template-card {
            border: 2px solid #d3d3d3;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            position: relative;
        }

        .invoice-template-card.selected {
            border-color: #0d6efd;
            background-color: #e7f1ff;
        }

        /* Hide the radio button itself */
        .invoice-template-card input[type="radio"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        /* If you want to style based on radio checked inside the card */
        .invoice-template-card input[type="radio"]:checked+div {
            /* Target the next sibling div after input */
            border: 2px solid #0d6efd;
            background-color: #e7f1ff;
        }
    </style>

    @if ($color == 'theme-3')
        <style>
            .btn-check:checked+.btn-outline-success,
            .btn-check:active+.btn-outline-success,
            .btn-outline-success:active,
            .btn-outline-success.active,
            .btn-outline-success.dropdown-toggle.show {
                color: #ffffff;
                background-color: #6fd943 !important;
                border-color: #6fd943 !important;

            }

            .btn-outline-success:hover {
                color: #ffffff;
                background-color: #6fd943 !important;
                border-color: #6fd943 !important;
            }

            .btn.btn-outline-success {
                color: #6fd943;
                border-color: #6fd943 !important;
            }
        </style>
    @endif
    @if ($color == 'theme-2')
        <style>
            .btn-check:checked+.btn-outline-success,
            .btn-check:active+.btn-outline-success,
            .btn-outline-success:active,
            .btn-outline-success.active,
            .btn-outline-success.dropdown-toggle.show {
                color: #ffffff;
                background: linear-gradient(141.55deg, rgbisset($setting['cookie_consent']) && $setting['cookie_consent']=='on' ? '' : 'disabled' a(240, 244, 243, 0) 3.46%, #4ebbd3 99.86%)#1f3996 !important;
                border-color: #1F3996 !important;

            }

            .btn-outline-success:hover {
                color: #ffffff;
                background: linear-gradient(141.55deg, rgba(240, 244, 243, 0) 3.46%, #4ebbd3 99.86%)#1f3996 !important;
                border-color: #1F3996 !important;
            }

            .btn.btn-outline-success {
                color: #1F3996;
                border-color: #1F3996 !important;
            }
        </style>
    @endif
    @if ($color == 'theme-4')
        <style>
            .btn-check:checked+.btn-outline-success,
            .btn-check:active+.btn-outline-success,
            .btn-outline-success:active,
            .btn-outline-success.active,
            .btn-outline-success.dropdown-toggle.show {
                color: #ffffff;
                background-color: #584ed2 !important;
                border-color: #584ed2 !important;

            }

            .btn-outline-success:hover {
                color: #ffffff;
                background-color: #584ed2 !important;
                border-color: #584ed2 !important;
            }

            .btn.btn-outline-success {
                color: #584ed2;
                border-color: #584ed2 !important;
            }
        </style>
    @endif
    @if ($color == 'theme-1')
        <style>
            .btn-check:checked+.btn-outline-success,
            .btn-check:active+.btn-outline-success,
            .btn-outline-success:active,
            .btn-outline-success.active,
            .btn-outline-success.dropdown-toggle.show {
                color: #ffffff;
                background: linear-gradient(141.55deg, rgba(81, 69, 157, 0) 3.46%, rgba(255, 58, 110, 0.6) 99.86%), #51459d !important;
                border-color: #51459d !important;

            }

            .btn-outline-success:hover {
                color: #ffffff;
                background: linear-gradient(141.55deg, rgba(81, 69, 157, 0) 3.46%, rgba(255, 58, 110, 0.6) 99.86%), #51459d !important;
                border-color: #51459d !important;
            }

            .btn.btn-outline-success {
                color: #51459d;
                border-color: #51459d !important;
            }
        </style>
    @endif

    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })

        var themescolors = document.querySelectorAll(".themes-color > a");
        for (var h = 0; h < themescolors.length; h++) {
            var c = themescolors[h];
            c.addEventListener("click", function(event) {
                var targetElement = event.target;
                if (targetElement.tagName == "SPAN") {
                    targetElement = targetElement.parentNode;
                }
                var temp = targetElement.getAttribute("data-value");
                removeClassByPrefix(document.querySelector("body"), "theme-");
                document.querySelector("body").classList.add(temp);
            });
        }

        function check_theme(color_val) {
            $('input[value="' + color_val + '"]').prop('checked', true);
            $('a[data-value]').removeClass('active_color');
            $('a[data-value="' + color_val + '"]').addClass('active_color');
        }

        if ($('#cust-darklayout').length > 0) {
            var custthemedark = document.querySelector("#cust-darklayout");
            custthemedark.addEventListener("click", function() {
                if (custthemedark.checked) {
                    document.querySelector("#style").setAttribute("href",
                        "{{ asset('assets/css/style-dark.css') }}");

                    $('.dash-sidebar .main-logo a img').attr('src', '{{ $logo . $logo_light }}');

                } else {
                    document.querySelector("#style").setAttribute("href", "{{ asset('assets/css/style.css') }}");
                    $('.dash-sidebar .main-logo a img').attr('src', '{{ $logo . $logo_dark }}');

                }
            });
        }
        if ($('#cust-theme-bg').length > 0) {
            var custthemebg = document.querySelector("#cust-theme-bg");
            custthemebg.addEventListener("click", function() {
                if (custthemebg.checked) {
                    document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                    document
                        .querySelector(".dash-header:not(.dash-mob-header)")
                        .classList.add("transprent-bg");
                } else {
                    document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                    document
                        .querySelector(".dash-header:not(.dash-mob-header)")
                        .classList.remove("transprent-bg");
                }
            });
        }
    </script>

    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,
        })
        $(".list-group-item").click(function() {
            $('.list-group-item').filter(function() {
                return this.href == id;
            }).parent().removeClass('text-primary');
        });

        function check_theme(color_val) {
            $('#theme_color').prop('checked', false);
            $('input[value="' + color_val + '"]').prop('checked', true);
        }

        $(document).on('change', '[name=storage_setting]', function() {
            if ($(this).val() == 's3') {
                $('.s3-setting').removeClass('d-none');
                $('.wasabi-setting').addClass('d-none');
                $('.local-setting').addClass('d-none');
            } else if ($(this).val() == 'wasabi') {
                $('.s3-setting').addClass('d-none');
                $('.wasabi-setting').removeClass('d-none');
                $('.local-setting').addClass('d-none');
            } else {
                $('.s3-setting').addClass('d-none');
                $('.wasabi-setting').addClass('d-none');
                $('.local-setting').removeClass('d-none');
            }
        });
    </script>

    <script>
        $('.colorPicker').on('click', function(e) {
            $('body').removeClass('custom-color');
            if (/^theme-\d+$/) {
                $('body').removeClassRegex(/^theme-\d+$/);
            }
            $('body').addClass('custom-color');
            $('.themes-color-change').removeClass('active_color');
            $(this).addClass('active_color');
            const input = document.getElementById("color-picker");
            setColor();
            input.addEventListener("input", setColor);

            function setColor() {
                $(':root').css('--color-customColor', input.value);
            }

            $(`input[name='color_flag`).val('true');
        });

        $('.themes-color-change').on('click', function() {

            $(`input[name='color_flag`).val('false');

            var color_val = $(this).data('value');
            $('body').removeClass('custom-color');
            if (/^theme-\d+$/) {
                $('body').removeClassRegex(/^theme-\d+$/);
            }
            $('body').addClass(color_val);
            $('.theme-color').prop('checked', false);
            $('.themes-color-change').removeClass('active_color');
            $('.colorPicker').removeClass('active_color');
            $(this).addClass('active_color');
            $(`input[value=${color_val}]`).prop('checked', true);
        });

        $.fn.removeClassRegex = function(regex) {
            return $(this).removeClass(function(index, classes) {
                return classes.split(/\s+/).filter(function(c) {
                    return regex.test(c);
                }).join(' ');
            });
        };
    </script>

    <script type="text/javascript">
        function enablecookie() {
            const element = $('#enable_cookie').is(':checked');
            $('.cookieDiv').addClass('disabledCookie');
            if (element == true) {
                $('.cookieDiv').removeClass('disabledCookie');
                $("#cookie_logging").attr('checked', true);
            } else {
                $('.cookieDiv').addClass('disabledCookie');
                $("#cookie_logging").attr('checked', false);
            }
        }
    </script>


    <script>
        $(document).on("click", '.send_email', function(e) {

            e.preventDefault();
            var title = $(this).attr('data-title');

            var size = 'md';
            var url = $(this).attr('data-url');
           
            if (typeof url != 'undefined') {
                $("#commonPopModal .modal-title").html(title);
                $("#commonPopModal .modal-dialog").addClass('modal-' + size);
                $("#commonPopModal").modal('show');

                $.post(url, {
                    _token: '{{ csrf_token() }}',
                    mail_driver: $("#mail_driver").val(),
                    mail_host: $("#mail_host").val(),
                    mail_port: $("#mail_port").val(),
                    mail_username: $("#mail_username").val(),
                    mail_password: $("#mail_password").val(),
                    mail_encryption: $("#mail_encryption").val(),
                    mail_from_address: $("#mail_from_address").val(),
                    mail_from_name: $("#mail_from_name").val(),
                }, function(data) {
                    $('#commonPopModal .body').html(data);
                });
            }
        });


        $(document).on('submit', '#test_email', function(e) {
            e.preventDefault();
            $("#email_sending").show();
            var post = $(this).serialize();
            var url = $(this).attr('action');
            $.ajax({
                type: "post",
                url: url,
                data: post,
                cache: false,
                beforeSend: function() {
                    $('#test_email .btn-create').attr('disabled', 'disabled');
                },
                success: function(data) {
                    if (data.is_success) {
                        show_toastr('success', data.message, 'success');
                    } else {
                        show_toastr('error', data.message, 'error');
                    }
                    $("#email_sending").hide();
                    $('#commonModal').modal('hide');
                },
                complete: function() {
                    $('#test_email .btn-create').removeAttr('disabled');
                },
            });
        });
    </script>

@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Settings') }}</li>
@endsection



@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            @can('brand settings')
                                <a href="#useradd-1"
                                    class="list-group-item list-group-item-action border-0">{{ __('Brand Settings') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endcan
                            @can('email settings')
                                <a href="#useradd-2"
                                    class="list-group-item list-group-item-action border-0">{{ __('Email Settings') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endcan
                            @can('payment settings')
                                <a href="#useradd-3"
                                    class="list-group-item list-group-item-action border-0">{{ __('Payment Settings') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endcan
                            @can('set default invoices')
                                <a href="#useradd-4"
                                    class="list-group-item list-group-item-action border-0">{{ __('Invoice Template Settings') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endcan
                            @can('set default letterpad')
                                <a href="#useradd-9"
                                    class="list-group-item list-group-item-action border-0">{{ __('Letter Pad Template Settings') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endcan
                            @can('storage settings')
                                <a href="#useradd-5"
                                    class="list-group-item list-group-item-action border-0">{{ __('Storage Settings') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endcan
                            @can('seo settings')
                                <a href="#useradd-6"
                                    class="list-group-item list-group-item-action border-0">{{ __('SEO Settings') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endcan
                            @can('cookie settings')
                                <a href="#useradd-7"
                                    class="list-group-item list-group-item-action border-0">{{ __('Cookie Settings') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                                <a href="#useradd-8"
                                    class="list-group-item list-group-item-action border-0">{{ __('Cache Settings') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endcan
                            @can('reset permissions')
                                <a href="#useradd-10"
                                    class="list-group-item list-group-item-action border-0">{{ __('Reset Permissions') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    @can('brand settings')
                        <!--Site Setting-->
                        <div id="useradd-1" class="card">

                            <div class="card-header">
                                <h5>{{ __('Brand Settings') }}</h5>
                            </div>
                            {{ Form::model($settings, ['url' => 'settings', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'mb-0']) }}

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-6 col-md-6 dashboard-card">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>{{ __('Logo dark') }}</h5>
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class=" setting-card">
                                                    <div class="logo-content mt-4">
                                                        <a href="{{ $logo . (isset($logo_dark) && !empty($logo_dark) ? $logo_dark : 'logo-dark.png') . '?' . time() }}"
                                                            target="_blank">
                                                            <img id="blah" alt="your image"
                                                                src="{{ $logo . (isset($logo_dark) && !empty($logo_dark) ? $logo_dark : 'logo-dark.png') . '?' . time() }}"
                                                                width="150px" class="big-logo1">
                                                        </a>
                                                    </div>
                                                    <div class="choose-files mt-5">
                                                        <label for="full_logo">
                                                            <div class=" bg-primary company_logo_update"> <i
                                                                    class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                            </div>
                                                            <input type="file" name="logo_dark" id="full_logo"
                                                                class="form-control file" data-filename="full_logo"
                                                                onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">


                                                        </label>
                                                    </div>
                                                    @error('full_logo')
                                                        <div class="row">
                                                            <span class="invalid-logo" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-md-6 dashboard-card">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>{{ __('Logo Light') }}</h5>
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class=" setting-card">
                                                    <div class="logo-content mt-4">
                                                        <a href="{{ $logo . (isset($logo_light) && !empty($logo_light) ? $logo_light : 'logo-light.png') . '?' . time() }}"
                                                            target="_blank">
                                                            <img id="blah1" alt="your image"
                                                                src="{{ $logo . (isset($logo_light) && !empty($logo_light) ? $logo_light : 'logo-light.png') . '?' . time() }}"
                                                                width="150px" class="big-logo1 img_setting">
                                                        </a>
                                                    </div>
                                                    <div class="choose-files mt-5">
                                                        <label for="logo_light">
                                                            <div class=" bg-primary dark_logo_update"> <i
                                                                    class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                            </div>
                                                            <input type="file" name="logo_light" id="logo_light"
                                                                class="form-control file" data-filename="dark_logo_update"
                                                                onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])">


                                                        </label>
                                                    </div>
                                                    @error('logo_light')
                                                        <div class="row">
                                                            <span class="invalid-logo" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-md-6 dashboard-card">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>{{ __('Favicon') }}</h5>
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class=" setting-card">
                                                    <div class="logo-content mt-4">
                                                        <a href="{{ $logo . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '?' . time() }}"
                                                            target="_blank">
                                                            <img id="blah2" alt="your image"
                                                                src="{{ $logo . (isset($company_favicon) && !empty($company_favicon) ? $company_ficon : 'favicon.png') . '?' . time() }}"
                                                                width="60px" height="63px" class=" img_setting">
                                                        </a>
                                                    </div>
                                                    <div class="choose-files mt-5">
                                                        <label for="favicon">
                                                            <div class="bg-primary company_favicon_update"> <i
                                                                    class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                            </div>
                                                            <input type="file" name="favicon" id="favicon"
                                                                class="form-control file"
                                                                data-filename="company_favicon_update"
                                                                onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])">
                                                        </label>
                                                    </div>
                                                    @error('favicon')
                                                        <div class="row">
                                                            <span class="invalid-logo" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('title_text', __('Title Text'), ['class' => 'form-label']) }}
                                                {{ Form::text('title_text', null, ['class' => 'form-control', 'placeholder' => __('Enter Title Text')]) }}
                                                @error('title_text')
                                                    <span class="invalid-title_text" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('footer_text', __('Footer Text'), ['class' => 'form-label']) }}
                                                {{ Form::text('footer_text', Utility::getValByName('footer_text'), ['class' => 'form-control', 'placeholder' => __('Enter Footer Text')]) }}
                                                @error('footer_text')
                                                    <span class="invalid-footer_text" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('default_language', __('Default Language'), ['class' => 'form-label text-dark']) }}
                                                <div class="changeLanguage">
                                                    <select name="default_language" id="default_language"
                                                        class="form-control select">
                                                        @foreach (App\Models\Utility::languages() as $code => $language)
                                                            <option @if ($lang == $code) selected @endif
                                                                value="{{ $code }}">
                                                                {{ Str::upper($language) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('default_language')
                                                    <span class="invalid-default_language" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                        </div>
                                        <div class="col">
                                            <div class="row">


                                                {{-- <div class="col-4 my-auto">
                                                <div class="form-group ">
                                                    <label class="text-dark mb-1 mt-3"
                                                        for="gdpr_cookie">{{ __('GDPR Notification') }}</label>
                                        <div class="">
                                            <input type="checkbox" class="gdpr_fulltime gdpr_type" name="gdpr_cookie" id="gdpr_cookie" data-toggle="switchbutton" {{ isset($settings['gdpr_cookie']) && $settings['gdpr_cookie'] == 'on' ? 'checked="checked"' : '' }} data-onstyle="primary">
                                            <label class="form-check-labe" for="gdpr_cookie"></label>
                                        </div>
                                    </div>
                                </div> --}}
                                                {{-- <div class="col-3 my-auto">
                                                <div class="form-group">
                                                    <label class="text-dark mb-1 mt-3"
                                                        for="email_verification">{{ __('Enable Email Verification') }}</label>
                                                    <div class="">
                                                        <input type="checkbox" name="email_verification"
                                                            id="email_verification" data-toggle="switchbutton"
                                                            {{ $settings['email_verification'] == 'on' ? 'checked="checked"' : '' }}
                                                            data-onstyle="primary">
                                                        <label class="form-check-labe" for="email_verification"></label>
                                                    </div>
                                                </div>
                                            </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="small-title">{{ __('Theme Customizer') }}</h4>
                                    <div class="setting-card setting-logo-box p-3">
                                        <div class="row">
                                            <div class="col-lg-4 col-xl-4 col-md-4">
                                                <h6 class="mt-2">
                                                    <i data-feather="credit-card"
                                                        class="me-2"></i>{{ __('Primary color settings') }}
                                                </h6>

                                                <hr class="my-2" />
                                                <div class="color-wrp">
                                                    <div class="theme-color themes-color">
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-1' ? 'active_color' : '' }}"
                                                            data-value="theme-1"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-1" {{ $color == 'theme-1' ? 'checked' : '' }}>
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-2' ? 'active_color' : '' }}"
                                                            data-value="theme-2"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-2" {{ $color == 'theme-2' ? 'checked' : '' }}>
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-3' ? 'active_color' : '' }}"
                                                            data-value="theme-3"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-3" {{ $color == 'theme-3' ? 'checked' : '' }}>
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-4' ? 'active_color' : '' }}"
                                                            data-value="theme-4"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-4" {{ $color == 'theme-4' ? 'checked' : '' }}>
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-5' ? 'active_color' : '' }}"
                                                            data-value="theme-5"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-5" {{ $color == 'theme-5' ? 'checked' : '' }}>
                                                        <br>
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-6' ? 'active_color' : '' }}"
                                                            data-value="theme-6"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-6" {{ $color == 'theme-6' ? 'checked' : '' }}>
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-7' ? 'active_color' : '' }}"
                                                            data-value="theme-7"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-7" {{ $color == 'theme-7' ? 'checked' : '' }}>
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-8' ? 'active_color' : '' }}"
                                                            data-value="theme-8"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-8" {{ $color == 'theme-8' ? 'checked' : '' }}>
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-9' ? 'active_color' : '' }}"
                                                            data-value="theme-9"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-9" {{ $color == 'theme-9' ? 'checked' : '' }}>
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-10' ? 'active_color' : '' }}"
                                                            data-value="theme-10"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-10" {{ $color == 'theme-10' ? 'checked' : '' }}>
                                                    </div>

                                                    <div class="color-picker-wrp ">
                                                        <input type="color" value="{{ $color ? $color : '' }}"
                                                            class="colorPicker {{ isset($flag) && $flag == 'true' ? 'active_color' : '' }}"
                                                            name="custom_color" id="color-picker">
                                                        <input type='hidden' name="color_flag"
                                                            value={{ isset($flag) && $flag == 'true' ? 'true' : 'false' }}>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="col-lg-4 col-xl-4 col-md-4">
                                            <h6 class="mt-2">
                                                <i data-feather="layout" class="me-2"></i>{{ __('Sidebar settings') }}
                                            </h6>
                                            <hr class="my-2" />
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="cust-theme-bg"
                                                    name="cust_theme_bg"
                                                    {{ !empty($settings['cust_theme_bg']) && $settings['cust_theme_bg'] == 'on' ? 'checked' : '' }} />
                                                <label class="form-check-label f-w-600 pl-1"
                                                    for="cust-theme-bg">{{ __('Transparent layout') }}</label>
                                            </div>
                                        </div> --}}
                                            <div class="col-lg-4 col-xl-4 col-md-4">
                                                <h6 class="mt-2">
                                                    <i data-feather="sun" class="me-2"></i>{{ __('Layout settings') }}
                                                </h6>
                                                <hr class="my-2" />
                                                <div class="form-check form-switch mt-2">
                                                    <input type="checkbox" class="form-check-input" id="cust-darklayout"
                                                        name="cust_darklayout"
                                                        {{ !empty($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on' ? 'checked' : '' }} />
                                                    <label class="form-check-label f-w-600 pl-1"
                                                        for="cust-darklayout">{{ __('Dark Layout') }}</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-xl-4 col-md-4">
                                                <div class="form-group">
                                                    <label class="text-dark mb-2"
                                                        for="SITE_RTL">{{ __('Enable RTL') }}</label>
                                                    <div class="">
                                                        <input type="checkbox" name="SITE_RTL" id="SITE_RTL"
                                                            data-toggle="switchbutton"
                                                            {{ $settings['SITE_RTL'] == 'on' ? 'checked="checked"' : '' }}
                                                            data-onstyle="primary">
                                                        <label class="form-check-labe" for="SITE_RTL"></label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <input class="btn btn-print-invoice btn-primary m-r-10" type="submit"
                                    value="{{ __('Save Changes') }}">
                            </div>
                            {{ Form::close() }}
                        </div>
                    @endcan


                    @can('email settings')
                        <!--Email Setting-->
                        <div id="useradd-2" class="card">
                            <div class="card-header">
                                <h5>{{ __('Email Settings') }}</h5>
                                <small
                                    class="text-muted">{{ __('This SMTP will be used for system-level email sending. Additionally, if a company user does not set their SMTP,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  then this SMTP will be used for sending emails.') }}</small>
                            </div>
                            <div class="card-body">
                                {{ Form::model($settings, ['route' => 'admin.email.settings', 'method' => 'post', 'class' => 'mb-0']) }}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('mail_driver', __('Mail Driver'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_driver', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Driver')]) }}
                                            @error('mail_driver')
                                                <span class="invalid-mail_driver" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('mail_host', __('Mail Host'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_host', null, ['class' => 'form-control ', 'placeholder' => __('Enter Mail Host')]) }}
                                            @error('mail_host')
                                                <span class="invalid-mail_driver" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('mail_port', __('Mail Port'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_port', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Port')]) }}
                                            @error('mail_port')
                                                <span class="invalid-mail_port" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('mail_username', __('Mail Username'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_username', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Username')]) }}
                                            @error('mail_username')
                                                <span class="invalid-mail_username" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('mail_password', __('Mail Password'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_password', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Password')]) }}
                                            @error('mail_password')
                                                <span class="invalid-mail_password" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_encryption', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Encryption')]) }}
                                            @error('mail_encryption')
                                                <span class="invalid-mail_encryption" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('mail_from_address', __('Mail From Address'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_from_address', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail From Address')]) }}
                                            @error('mail_from_address')
                                                <span class="invalid-mail_from_address" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('mail_from_name', __('Mail From Name'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_from_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail From Name')]) }}
                                            @error('mail_from_name')
                                                <span class="invalid-mail_from_name" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-end">
                                <div class="me-2 mb-0">
                                    <a href="#" class="btn btn-primary send_email"
                                        data-title="{{ __('Send Test Mail') }}" data-url="{{ route('admin.test.mail') }}">
                                        {{ __('Send Test Mail') }}
                                    </a>
                                </div>
                                <div class="text-end mb-0">
                                    <input class="btn btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    @endcan

                    @can('payment settings')
                        <!--Payment Setting-->
                        <div class="card" id="useradd-3">
                            <div class="card-header">
                                <h5>{{ __('Payment Settings') }}</h5>
                                <small class="text-secondary font-weight-bold">
                                    {{ __('These details will be used to collect subscription plan payments. Each subscription plan will have a payment button based on the below configuration.') }}
                                </small>
                            </div>
                            {{ Form::open(['route' => 'admin.payment.settings', 'method' => 'post', 'class' => 'mb-0']) }}
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('currency', __('Currency *'), ['class' => 'form-label']) }}
                                            {{ Form::text('currency', $admin['currency'] ?? '', ['class' => 'form-control font-style', 'required', 'placeholder' => __('Enter Currency')]) }}
                                            <small>
                                                {{ __('Note: Add currency code as per three-letter ISO code.') }}<br>
                                                <a href="https://stripe.com/docs/currencies"
                                                    target="_blank">{{ __('you can find out here..') }}</a></small>
                                            <br>
                                            @error('currency')
                                                <span class="invalid-currency" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('currency_symbol', __('Currency Symbol *'), ['class' => 'form-label']) }}
                                            {{ Form::text('currency_symbol', $admin['currency_symbol'] ?? '', ['class' => 'form-control', 'required', 'placeholder' => __('Enter Currency Symbol')]) }}
                                            @error('currency_symbol')
                                                <span class="invalid-currency_symbol" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="faq justify-content-center">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="accordion accordion-flush setting-accordion"
                                                        id="accordionExample">

                                                        {{-- //Manually --}}
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="headingOne">
                                                                <button class="accordion-button collapsed" type="button"
                                                                    data-bs-toggle="collapse" data-bs-target="#collapse14"
                                                                    aria-expanded="false" aria-controls="collapse14">
                                                                    <span class="d-flex align-items-center">

                                                                        {{ __('Manually') }}
                                                                    </span>

                                                                    <div class="d-flex align-items-center">
                                                                        <span class="me-2">{{ __('Enable:') }}</span>
                                                                        <div class="form-check form-switch custom-switch-v1">
                                                                            <input type="hidden" name="is_manually_enabled"
                                                                                value="off">
                                                                            <input type="checkbox" class="form-check-input"
                                                                                name="is_manually_enabled"
                                                                                id="is_manually_enabled"
                                                                                {{ isset($admin_payment_setting['is_manually_enabled']) && $admin_payment_setting['is_manually_enabled'] == 'on' ? 'checked="checked"' : '' }}>

                                                                        </div>
                                                                    </div>
                                                                </button>
                                                            </h2>
                                                            <div id="collapse14" class="accordion-collapse collapse"
                                                                aria-labelledby="headingOne"
                                                                data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <div class="row gy-4">
                                                                        <div class="form-group">
                                                                            <span>{{ __('Requesting manual payment for the planned amount for the subcriptions plan.') }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Bank Transfer -->
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="headingOne">
                                                                <button class="accordion-button collapsed" type="button"
                                                                    data-bs-toggle="collapse" data-bs-target="#collapseBank"
                                                                    aria-expanded="false" aria-controls="collapseBank">
                                                                    <span class="d-flex align-items-center">

                                                                        {{ __('Bank Transfer') }}
                                                                    </span>
                                                                    <div class="d-flex align-items-center">
                                                                        <span class="me-2">{{ __('Enable:') }}</span>
                                                                        <div class="form-check form-switch custom-switch-v1">
                                                                            <input type="hidden" name="is_bank_enabled"
                                                                                value="off">
                                                                            <input type="checkbox" class="form-check-input"
                                                                                name="is_bank_enabled" id="is_bank_enabled"
                                                                                {{ isset($admin_payment_setting['is_bank_enabled']) && $admin_payment_setting['is_bank_enabled'] == 'on' ? 'checked="checked"' : '' }}>

                                                                        </div>
                                                                    </div>
                                                                </button>
                                                            </h2>
                                                            <div id="collapseBank" class="accordion-collapse collapse"
                                                                aria-labelledby="headingOne"
                                                                data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <div class="row gy-4">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label
                                                                                    class="col-form-label">{{ __('Bank Details') }}</label>
                                                                                <textarea class="form-control" rows="5" name="bank_detail">{{ !empty($admin_payment_setting['bank_detail']) ? $admin_payment_setting['bank_detail'] : '' }}</textarea>
                                                                                <small>{{ __('Example : Bank : Bank name </br> Account Number : 0000 0000 </br>') }}</small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Stripe -->
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="headingOne">
                                                                <button class="accordion-button collapsed" type="button"
                                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                                    aria-expanded="false" aria-controls="collapseOne">
                                                                    <span class="d-flex align-items-center">
                                                                        {{ __('Stripe') }}
                                                                    </span>
                                                                    <div class="d-flex align-items-center">
                                                                        <span class="me-2">{{ __('Enable:') }}</span>
                                                                        <div class="form-check form-switch custom-switch-v1">
                                                                            <input type="hidden" name="is_stripe_enabled"
                                                                                value="off">
                                                                            <input type="checkbox" class="form-check-input"
                                                                                name="is_stripe_enabled"
                                                                                id="is_stripe_enabled"
                                                                                {{ isset($admin_payment_setting['is_stripe_enabled']) && $admin_payment_setting['is_stripe_enabled'] == 'on' ? 'checked="checked"' : '' }}>

                                                                        </div>
                                                                    </div>
                                                                </button>
                                                            </h2>
                                                            <div id="collapseOne" class="accordion-collapse collapse"
                                                                aria-labelledby="headingOne"
                                                                data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <div class="row gy-4">
                                                                        <div class="col-lg-6">
                                                                            <div class="input-edits">
                                                                                <div class="form-group">
                                                                                    <label for="stripe_key"
                                                                                        class="col-form-label">{{ __('Stripe Key') }}</label>
                                                                                    <input class="form-control"
                                                                                        placeholder="{{ __('Enter Stripe Key') }}"
                                                                                        name="stripe_key" type="text"
                                                                                        value="{{ !isset($admin_payment_setting['stripe_key']) || is_null($admin_payment_setting['stripe_key']) ? '' : $admin_payment_setting['stripe_key'] }}"
                                                                                        id="stripe_key">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <div class="input-edits">
                                                                                <div class="form-group">
                                                                                    <label for="stripe_secret"
                                                                                        class="col-form-label">{{ __('Stripe Secret') }}</label>
                                                                                    <input class="form-control"
                                                                                        placeholder="Enter Stripe Secret"
                                                                                        name="stripe_secret" type="text"
                                                                                        value="{{ !isset($admin_payment_setting['stripe_secret']) || is_null($admin_payment_setting['stripe_secret']) ? '' : $admin_payment_setting['stripe_secret'] }}"
                                                                                        id="stripe_secret">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Paypal -->
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="headingTwo">
                                                                <button class="accordion-button collapsed" type="button"
                                                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                                    aria-expanded="false" aria-controls="collapseTwo">
                                                                    <span class="d-flex align-items-center">
                                                                        {{ __('Paypal') }}</span>
                                                                    <div class="d-flex align-items-center">
                                                                        <span class="me-2">{{ __('Enable:') }}</span>
                                                                        <div class="form-check form-switch custom-switch-v1">
                                                                            <input type="hidden" name="is_paypal_enabled"
                                                                                value="off">
                                                                            <input type="checkbox" class="form-check-input"
                                                                                name="is_paypal_enabled"
                                                                                id="is_paypal_enabled"
                                                                                {{ isset($admin_payment_setting['is_paypal_enabled']) && $admin_payment_setting['is_paypal_enabled'] == 'on' ? 'checked="checked"' : '' }}>

                                                                        </div>
                                                                    </div>
                                                                </button>
                                                            </h2>
                                                            <div id="collapseTwo" class="accordion-collapse collapse"
                                                                aria-labelledby="headingTwo"
                                                                data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <div class="row gy-4">
                                                                        <div class="col-md-12">
                                                                            <label class="paypal-label col-form-label"
                                                                                for="paypal_mode">{{ __('Paypal Mode') }}</label>
                                                                            <br>
                                                                            <div class="d-flex">
                                                                                <div class="mr-2"
                                                                                    style="margin-right: 15px;">
                                                                                    <div class="border card p-3">
                                                                                        <div class="form-check">
                                                                                            <label
                                                                                                class="form-check-labe text-dark {{ isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == 'sandbox' ? 'active' : '' }}">
                                                                                                <input type="radio"
                                                                                                    name="paypal_mode"
                                                                                                    value="sandbox"
                                                                                                    class="form-check-input"
                                                                                                    {{ isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>

                                                                                                {{ __('Sandbox') }}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="mr-2">
                                                                                    <div class="border card p-3">
                                                                                        <div class="form-check">
                                                                                            <label
                                                                                                class="form-check-labe text-dark">
                                                                                                <input type="radio"
                                                                                                    name="paypal_mode"
                                                                                                    value="live"
                                                                                                    class="form-check-input"
                                                                                                    {{ isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == 'live' ? 'checked="checked"' : '' }}>

                                                                                                {{ __('Live') }}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="paypal_client_id"
                                                                                    class="col-form-label">{{ __('Client ID') }}</label>
                                                                                <input type="text" name="paypal_client_id"
                                                                                    id="paypal_client_id" class="form-control"
                                                                                    value="{{ !isset($admin_payment_setting['paypal_client_id']) || is_null($admin_payment_setting['paypal_client_id']) ? '' : $admin_payment_setting['paypal_client_id'] }}"
                                                                                    placeholder="{{ __('Client ID') }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="paypal_secret_key"
                                                                                    class="col-form-label">{{ __('Secret Key') }}</label>
                                                                                <input type="text" name="paypal_secret_key"
                                                                                    id="paypal_secret_key"
                                                                                    class="form-control"
                                                                                    value="{{ !isset($admin_payment_setting['paypal_secret_key']) || is_null($admin_payment_setting['paypal_secret_key']) ? '' : $admin_payment_setting['paypal_secret_key'] }}"
                                                                                    placeholder="{{ __('Secret Key') }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <!-- Razorpay -->
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="headingFive">
                                                                <button class="accordion-button collapsed" type="button"
                                                                    data-bs-toggle="collapse" data-bs-target="#collapseFive"
                                                                    aria-expanded="false" aria-controls="collapseFive">
                                                                    <span class="d-flex align-items-center">
                                                                        {{ __('Razorpay') }}</span>
                                                                    <div class="d-flex align-items-center">
                                                                        <span class="me-2">{{ __('Enable:') }}</span>
                                                                        <div class="form-check form-switch custom-switch-v1">
                                                                            <input type="hidden" name="is_razorpay_enabled"
                                                                                value="off">
                                                                            <input type="checkbox" class="form-check-input"
                                                                                name="is_razorpay_enabled"
                                                                                id="is_razorpay_enabled"
                                                                                {{ isset($admin_payment_setting['is_razorpay_enabled']) && $admin_payment_setting['is_razorpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>

                                                                        </div>
                                                                    </div>
                                                                </button>
                                                            </h2>
                                                            <div id="collapseFive" class="accordion-collapse collapse"
                                                                aria-labelledby="headingFive"
                                                                data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <div class="row gy-4">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="paypal_client_id"
                                                                                    class="col-form-label">{{ __('Public Key') }}</label>

                                                                                <input type="text"
                                                                                    name="razorpay_public_key"
                                                                                    id="razorpay_public_key"
                                                                                    class="form-control"
                                                                                    value="{{ !isset($admin_payment_setting['razorpay_public_key']) || is_null($admin_payment_setting['razorpay_public_key']) ? '' : $admin_payment_setting['razorpay_public_key'] }}"
                                                                                    placeholder="Public Key">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="paystack_secret_key"
                                                                                    class="col-form-label">{{ __('Secret Key') }}</label>
                                                                                <input type="text"
                                                                                    name="razorpay_secret_key"
                                                                                    id="razorpay_secret_key"
                                                                                    class="form-control"
                                                                                    value="{{ !isset($admin_payment_setting['razorpay_secret_key']) || is_null($admin_payment_setting['razorpay_secret_key']) ? '' : $admin_payment_setting['razorpay_secret_key'] }}"
                                                                                    placeholder="Secret Key">
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Paytm -->
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="headingSix">
                                                                <button class="accordion-button collapsed" type="button"
                                                                    data-bs-toggle="collapse" data-bs-target="#collapseSix"
                                                                    aria-expanded="false" aria-controls="collapseSix">
                                                                    <span
                                                                        class="d-flex align-items-center">{{ __('Paytm') }}</span>
                                                                    <div class="d-flex align-items-center">
                                                                        <span class="me-2">{{ __('Enable:') }}</span>
                                                                        <div class="form-check form-switch custom-switch-v1">
                                                                            <input type="hidden" name="is_paytm_enabled"
                                                                                value="off">
                                                                            <input type="checkbox" class="form-check-input"
                                                                                name="is_paytm_enabled" id="is_paytm_enabled"
                                                                                {{ isset($admin_payment_setting['is_paytm_enabled']) && $admin_payment_setting['is_paytm_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                        </div>
                                                                    </div>
                                                                </button>
                                                            </h2>
                                                            <div id="collapseSix" class="accordion-collapse collapse"
                                                                aria-labelledby="headingSix"
                                                                data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <div class="col-md-12 pb-4">
                                                                        <label class="paypal-label col-form-label"
                                                                            for="paypal_mode">{{ __('Paytm Environment') }}</label>
                                                                        <br>
                                                                        <div class="d-flex">
                                                                            <div class="mr-2" style="margin-right: 15px;">
                                                                                <div class="border card p-3">
                                                                                    <div class="form-check">
                                                                                        <label
                                                                                            class="form-check-labe text-dark">

                                                                                            <input type="radio"
                                                                                                name="paytm_mode"
                                                                                                value="local"
                                                                                                class="form-check-input"
                                                                                                {{ !isset($admin_payment_setting['paytm_mode']) || $admin_payment_setting['paytm_mode'] == '' || $admin_payment_setting['paytm_mode'] == 'local' ? 'checked="checked"' : '' }}>

                                                                                            {{ __('Local') }}
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mr-2">
                                                                                <div class="border card p-3">
                                                                                    <div class="form-check">
                                                                                        <label
                                                                                            class="form-check-labe text-dark">
                                                                                            <input type="radio"
                                                                                                name="paytm_mode"
                                                                                                value="production"
                                                                                                class="form-check-input"
                                                                                                {{ isset($admin_payment_setting['paytm_mode']) && $admin_payment_setting['paytm_mode'] == 'production' ? 'checked="checked"' : '' }}>

                                                                                            {{ __('Production') }}
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row gy-4">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="paytm_public_key"
                                                                                    class="col-form-label">{{ __('Merchant ID') }}</label>
                                                                                <input type="text" name="paytm_merchant_id"
                                                                                    id="paytm_merchant_id"
                                                                                    class="form-control"
                                                                                    value="{{ !isset($admin_payment_setting['paytm_merchant_id']) || is_null($admin_payment_setting['paytm_merchant_id']) ? '' : $admin_payment_setting['paytm_merchant_id'] }}"
                                                                                    placeholder="Merchant ID">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="paytm_secret_key"
                                                                                    class="col-form-label">{{ __('Merchant Key') }}</label>
                                                                                <input type="text"
                                                                                    name="paytm_merchant_key"
                                                                                    id="paytm_merchant_key"
                                                                                    class="form-control"
                                                                                    value="{{ !isset($admin_payment_setting['paytm_merchant_key']) || is_null($admin_payment_setting['paytm_merchant_key']) ? '' : $admin_payment_setting['paytm_merchant_key'] }}"
                                                                                    placeholder="Merchant Key">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="paytm_industry_type"
                                                                                    class="col-form-label">{{ __('Industry Type') }}</label>
                                                                                <input type="text"
                                                                                    name="paytm_industry_type"
                                                                                    id="paytm_industry_type"
                                                                                    class="form-control"
                                                                                    value="{{ !isset($admin_payment_setting['paytm_industry_type']) || is_null($admin_payment_setting['paytm_industry_type']) ? '' : $admin_payment_setting['paytm_industry_type'] }}"
                                                                                    placeholder="Industry Type">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Cashfree -->
                                                        <div class="accordion accordion-flush setting-accordion"
                                                            id="accordionExample">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingFourteen">
                                                                    <button class="accordion-button collapsed" type="button"
                                                                        data-bs-toggle="collapse" data-bs-target="#collapse18"
                                                                        aria-expanded="false" aria-controls="collapse18">
                                                                        <span class="d-flex align-items-center">
                                                                            {{ __('Cashfree') }}
                                                                        </span>
                                                                        <div class="d-flex align-items-center">
                                                                            <span class="me-2">{{ __('Enable:') }}</span>
                                                                            <div
                                                                                class="form-check form-switch custom-switch-v1">
                                                                                <input type="hidden"
                                                                                    name="is_cashfree_enabled" value="off">
                                                                                <input type="checkbox"
                                                                                    class="form-check-input input-primary"
                                                                                    name="is_cashfree_enabled"
                                                                                    id="is_cashfree_enabled"
                                                                                    {{ isset($admin_payment_setting['is_cashfree_enabled']) && $admin_payment_setting['is_cashfree_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                                <label class="form-check-label"
                                                                                    for="is_cashfree_enabled"></label>
                                                                            </div>
                                                                        </div>
                                                                    </button>
                                                                </h2>
                                                                <div id="collapse18" class="accordion-collapse collapse"
                                                                    aria-labelledby="headingFourteen"
                                                                    data-bs-parent="#accordionExample">
                                                                    <div class="accordion-body">
                                                                        <div class="row gy-4">

                                                                            <div class="col-lg-6">
                                                                                <div class="form-group">
                                                                                    {{ Form::label('cashfree_api_key', __('Cashfree Key'), ['class' => 'col-form-label']) }}
                                                                                    {{ Form::text('cashfree_api_key', isset($admin_payment_setting['cashfree_api_key']) ? $admin_payment_setting['cashfree_api_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Cashfree Key')]) }}
                                                                                    @error('cashfree_api_key')
                                                                                        <span class="invalid-cashfree_api_key"
                                                                                            role="alert">
                                                                                            <strong
                                                                                                class="text-danger">{{ $message }}</strong>
                                                                                        </span>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6">
                                                                                <div class="form-group">
                                                                                    {{ Form::label('cashfree_secret_key', __('Cashfree Secret Key'), ['class' => 'col-form-label']) }}
                                                                                    {{ Form::text('cashfree_secret_key', isset($admin_payment_setting['cashfree_secret_key']) ? $admin_payment_setting['cashfree_secret_key'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Cashfree Secret key')]) }}
                                                                                    @error('cashfree_secret_key')
                                                                                        <span class="invalid-cashfree_secret_key"
                                                                                            role="alert">
                                                                                            <strong
                                                                                                class="text-danger">{{ $message }}</strong>
                                                                                        </span>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer text-end">
                                <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit"
                                    value="{{ __('Save Changes') }}">
                            </div>
                            </form>
                        </div>
                    @endcan


                    @can('set default invoices')
                        <!--Invoice Template Setting-->
                        <div id="useradd-4" class="card mb-3">
                            <form action="{{ route('admin.invoice.template.settings.store') }}" method="POST"
                                class="mb-0" accept-charset="UTF-8" x-data="{ selected: '{{ $InvoiceSettings->template ?? '' }}' }">
                                @csrf

                                <div class="card-header row d-flex justify-content-between">
                                    <div class="col-auto">
                                        <h5>{{ __('Choose Invoice Template & Settings') }}</h5>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="row">

                                        @foreach ($invoiceTemplates ?? [] as $InvTemplate)
                                            <div class="col-md-3">
                                                <label
                                                    @click="selected = '{{ $InvoiceSettings ? $InvoiceSettings->template : '' }}'"
                                                    class="template-card invoice-template-card card text-center p-3"
                                                    :class="selected === '{{ $InvTemplate->id }}' ? 'selected' : ''">
                                                    <input type="radio" name="invoice_template"
                                                        value="{{ $InvTemplate->id }}"
                                                        :checked="selected === '{{ $InvTemplate->id }}'">
                                                    <div>
                                                        <div class="my-2">
                                                            <img src="{{ asset('storage/' . $InvTemplate->image) }}"
                                                                class="img-fluid" style="height: 100px;">
                                                        </div>
                                                        <div class="my-2 fw-bold">
                                                            {{ $InvTemplate->name }}
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach

                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ __('Invoice Prefix') }}</label>
                                            <input type="text" name="prefix"
                                                value="{{ $InvoiceSettings->prefix ?? '' }}" class="form-control"
                                                placeholder="INV" autocomplete="off">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ __('Due After (days)') }}</label>
                                            <input type="number" name="due_after"
                                                value="{{ $InvoiceSettings->due_after ?? 7 }}" class="form-control"
                                                min="1">
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Invoice Terms & Conditions') }}</label>
                                            <textarea name="terms" rows="4" class="form-control" placeholder="Enter default terms & conditions...">{{ $InvoiceSettings->terms ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                                </div>
                            </form>
                        </div>
                    @endcan

                    @can('set default letterpad')
                        <!--Letter pad template Setting-->
                        <div id="useradd-9" class="card mb-3">
                            <form action="{{ route('admin.letter-pad.template.settings.store') }}" method="POST"
                                class="mb-0" accept-charset="UTF-8" x-data="{ selected: '{{ $letterPadSettings->template ?? '' }}' }">
                                @csrf
                                <div class="card-header row d-flex justify-content-between">
                                    <div class="col-auto">
                                        <h5>{{ __('Choose Letter Pad Template & Settings') }}</h5>
                                    </div>
                                </div>


                                <div class="card-body">
                                    <div class="row">

                                        @foreach ($letterPadTemplates ?? [] as $letterTemplate)
                                            <div class="col-md-3">
                                                <label
                                                    @click="selected = '{{ $letterPadSettings ? $letterPadSettings->template : '' }}'"
                                                    class="template-card invoice-template-card card text-center p-3"
                                                    :class="selected === '{{ $letterTemplate->id }}' ? 'selected' : ''">
                                                    <input type="radio" name="template" value="{{ $letterTemplate->id }}"
                                                        :checked="selected === '{{ $letterTemplate->id }}'">
                                                    <div>
                                                        <div class="my-2">
                                                            <img src="{{ asset('storage/' . $letterTemplate->image) }}"
                                                                class="img-fluid" style="height: 100px;">
                                                        </div>
                                                        <div class="my-2 fw-bold">
                                                            {{ $letterTemplate->name }}
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach

                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Letter pad Terms & Conditions') }}</label>
                                            <textarea name="terms" rows="4" class="form-control" placeholder="Enter default terms & conditions...">{{ $letterPadSettings->terms ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                                </div>
                            </form>
                        </div>
                    @endcan

                    @can('storage settings')
                        <!--storage Setting-->
                        <div id="useradd-5" class="card mb-3">
                            {{ Form::open(['route' => 'admin.storage.setting.store', 'enctype' => 'multipart/form-data', 'class' => 'mb-0']) }}
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <h5 class="">{{ __('Storage Settings') }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="pe-2">
                                        <input type="radio" class="btn-check" name="storage_setting" id="local-outlined"
                                            autocomplete="off" {{ $setting['storage_setting'] == 'local' ? 'checked' : '' }}
                                            value="local" checked>
                                        <label class="btn btn-outline-primary"
                                            for="local-outlined">{{ __('Local') }}</label>
                                    </div>
                                    <div class="pe-2">
                                        <input type="radio" class="btn-check" name="storage_setting" id="s3-outlined"
                                            autocomplete="off" {{ $setting['storage_setting'] == 's3' ? 'checked' : '' }}
                                            value="s3">
                                        <label class="btn btn-outline-primary" for="s3-outlined">
                                            {{ __('AWS S3') }}</label>
                                    </div>

                                    <div class="pe-2">
                                        <input type="radio" class="btn-check" name="storage_setting" id="wasabi-outlined"
                                            autocomplete="off" {{ $setting['storage_setting'] == 'wasabi' ? 'checked' : '' }}
                                            value="wasabi">
                                        <label class="btn btn-outline-primary"
                                            for="wasabi-outlined">{{ __('Wasabi') }}</label>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div
                                        class="local-setting row {{ $setting['storage_setting'] == 'local' ? ' ' : 'd-none' }}">
                                        {{-- <h4 class="small-title">{{ __('Local Settings') }}</h4> --}}
                                        <div class="form-group col-8 switch-width">
                                            {{ Form::label('local_storage_validation', __('Only Upload Files'), ['class' => ' form-label']) }}
                                            <select name="local_storage_validation[]" class="select2"
                                                id="local_storage_validation" multiple>
                                                @foreach ($file_type as $f)
                                                    <option @if (in_array($f, $local_storage_validations)) selected @endif>
                                                        {{ $f }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="local_storage_max_upload_size">{{ __('Max upload size ( In KB)') }}</label>
                                                <input type="number" name="local_storage_max_upload_size"
                                                    class="form-control"
                                                    value="{{ !isset($setting['local_storage_max_upload_size']) || is_null($setting['local_storage_max_upload_size']) ? '' : $setting['local_storage_max_upload_size'] }}"
                                                    placeholder="{{ __('Max upload size') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="s3-setting row {{ $setting['storage_setting'] == 's3' ? ' ' : 'd-none' }}">

                                        <div class=" row ">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="s3_key">{{ __('S3 Key') }}</label>
                                                    <input type="text" name="s3_key" class="form-control"
                                                        value="{{ !isset($setting['s3_key']) || is_null($setting['s3_key']) ? '' : $setting['s3_key'] }}"
                                                        placeholder="{{ __('S3 Key') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="s3_secret">{{ __('S3 Secret') }}</label>
                                                    <input type="text" name="s3_secret" class="form-control"
                                                        value="{{ !isset($setting['s3_secret']) || is_null($setting['s3_secret']) ? '' : $setting['s3_secret'] }}"
                                                        placeholder="{{ __('S3 Secret') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="s3_region">{{ __('S3 Region') }}</label>
                                                    <input type="text" name="s3_region" class="form-control"
                                                        value="{{ !isset($setting['s3_region']) || is_null($setting['s3_region']) ? '' : $setting['s3_region'] }}"
                                                        placeholder="{{ __('S3 Region') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="s3_bucket">{{ __('S3 Bucket') }}</label>
                                                    <input type="text" name="s3_bucket" class="form-control"
                                                        value="{{ !isset($setting['s3_bucket']) || is_null($setting['s3_bucket']) ? '' : $setting['s3_bucket'] }}"
                                                        placeholder="{{ __('S3 Bucket') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="s3_url">{{ __('S3 URL') }}</label>
                                                    <input type="text" name="s3_url" class="form-control"
                                                        value="{{ !isset($setting['s3_url']) || is_null($setting['s3_url']) ? '' : $setting['s3_url'] }}"
                                                        placeholder="{{ __('S3 URL') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        for="s3_endpoint">{{ __('S3 Endpoint') }}</label>
                                                    <input type="text" name="s3_endpoint" class="form-control"
                                                        value="{{ !isset($setting['s3_endpoint']) || is_null($setting['s3_endpoint']) ? '' : $setting['s3_endpoint'] }}"
                                                        placeholder="{{ __('S3 Endpoint') }}">
                                                </div>
                                            </div>
                                            <div class="form-group col-8 switch-width">
                                                {{ Form::label('s3_storage_validation', __('Only Upload Files'), ['class' => ' form-label']) }}
                                                <select name="s3_storage_validation[]" class="select2"
                                                    id="s3_storage_validation" multiple>
                                                    @foreach ($file_type as $f)
                                                        <option @if (in_array($f, $s3_storage_validations)) selected @endif>
                                                            {{ $f }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        for="s3_max_upload_size">{{ __('Max upload size ( In KB)') }}</label>
                                                    <input type="number" name="s3_max_upload_size" class="form-control"
                                                        value="{{ !isset($setting['s3_max_upload_size']) || is_null($setting['s3_max_upload_size']) ? '' : $setting['s3_max_upload_size'] }}"
                                                        placeholder="{{ __('Max upload size') }}">
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div
                                        class="wasabi-setting row {{ $setting['storage_setting'] == 'wasabi' ? ' ' : 'd-none' }}">
                                        <div class=" row ">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="s3_key">{{ __('Wasabi Key') }}</label>
                                                    <input type="text" name="wasabi_key" class="form-control"
                                                        value="{{ !isset($setting['wasabi_key']) || is_null($setting['wasabi_key']) ? '' : $setting['wasabi_key'] }}"
                                                        placeholder="{{ __('Wasabi Key') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        for="s3_secret">{{ __('Wasabi Secret') }}</label>
                                                    <input type="text" name="wasabi_secret" class="form-control"
                                                        value="{{ !isset($setting['wasabi_secret']) || is_null($setting['wasabi_secret']) ? '' : $setting['wasabi_secret'] }}"
                                                        placeholder="{{ __('Wasabi Secret') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        for="s3_region">{{ __('Wasabi Region') }}</label>
                                                    <input type="text" name="wasabi_region" class="form-control"
                                                        value="{{ !isset($setting['wasabi_region']) || is_null($setting['wasabi_region']) ? '' : $setting['wasabi_region'] }}"
                                                        placeholder="{{ __('Wasabi Region') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        for="wasabi_bucket">{{ __('Wasabi Bucket') }}</label>
                                                    <input type="text" name="wasabi_bucket" class="form-control"
                                                        value="{{ !isset($setting['wasabi_bucket']) || is_null($setting['wasabi_bucket']) ? '' : $setting['wasabi_bucket'] }}"
                                                        placeholder="{{ __('Wasabi Bucket') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        for="wasabi_url">{{ __('Wasabi URL') }}</label>
                                                    <input type="text" name="wasabi_url" class="form-control"
                                                        value="{{ !isset($setting['wasabi_url']) || is_null($setting['wasabi_url']) ? '' : $setting['wasabi_url'] }}"
                                                        placeholder="{{ __('Wasabi URL') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        for="wasabi_root">{{ __('Wasabi Root') }}</label>
                                                    <input type="text" name="wasabi_root" class="form-control"
                                                        value="{{ !isset($setting['wasabi_root']) || is_null($setting['wasabi_root']) ? '' : $setting['wasabi_root'] }}"
                                                        placeholder="{{ __('Wasabi Root') }}">
                                                </div>
                                            </div>
                                            <div class="form-group col-8 switch-width">
                                                {{ Form::label('wasabi_storage_validation', __('Only Upload Files'), ['class' => 'form-label']) }}

                                                <select name="wasabi_storage_validation[]" class="select2"
                                                    id="wasabi_storage_validation" multiple>
                                                    @foreach ($file_type as $f)
                                                        <option @if (in_array($f, $wasabi_storage_validations)) selected @endif>
                                                            {{ $f }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        for="wasabi_root">{{ __('Max upload size ( In KB)') }}</label>
                                                    <input type="number" name="wasabi_max_upload_size" class="form-control"
                                                        value="{{ !isset($setting['wasabi_max_upload_size']) || is_null($setting['wasabi_max_upload_size']) ? '' : $setting['wasabi_max_upload_size'] }}"
                                                        placeholder="{{ __('Max upload size') }}">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit"
                                    value="{{ __('Save Changes') }}">
                            </div>
                            {{ Form::close() }}
                        </div>
                    @endcan

                    @can('seo settings')
                        {{-- SEO Setting --}}
                        <div class="card mb-3" id="useradd-6">
                            {{ Form::open(['url' => route('admin.seo.settings'), 'enctype' => 'multipart/form-data', 'class' => 'mb-0']) }}
                            <div class="card-header">
                                <h5>{{ __('SEO Settings') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if ($enable_chatgpt)
                                        <div>
                                            <a href="javascript:void(0)" data-size="md" data-ajax-popup-over="true"
                                                data-url="{{ route('generate', ['seo settings']) }}"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('Generate') }}"
                                                data-title="{{ __('Generate content with AI') }}"
                                                class="btn btn-primary btn-sm float-end">
                                                <i class="fas fa-robot"></i>
                                                {{ __('Generate with AI') }}
                                            </a>
                                        </div>
                                    @endif
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('meta_keywords', __('Meta Keywords'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('meta_keywords', !empty($settings['meta_keywords']) ? $settings['meta_keywords'] : '', ['class' => 'form-control ', 'placeholder' => 'Meta Keywords']) }}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('meta_description', __('Meta Description'), ['class' => 'form-label']) }}
                                            {{ Form::textarea('meta_description', !empty($settings['meta_description']) ? $settings['meta_description'] : '', ['class' => 'form-control ', 'row' => 3, 'placeholder' => 'Enter Meta Description']) }}
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6">
                                    <div class="form-group ">
                                        {{ Form::label('Meta Image', __('Meta Image'), ['class' => 'col-form-label ']) }}
                                            <div class="card-body pt-0">
                                                <div class="setting-card">
                                                    <div class="logo-content">
                                                        <a href="{{ $meta_image . '/' . (isset($settings['meta_image']) && !empty($settings['meta_image']) ? $settings['meta_image'] : 'meta_image.png') }}" target="_blank"> <img id="meta" src="{{ $meta_image . '/' . (isset($settings['meta_image']) && !empty($settings['meta_image']) ? $settings['meta_image'] : 'meta_image.png') }}" width="400px" class="img_setting"> </a>
                                                    </div>
                                                    <div class="choose-files mt-4">
                                                        <label for="meta_image">
                                                            <div class=" bg-primary logo"> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                            </div>
                                                            <input style="margin-top: -40px;" type="file" class="form-control file" name="meta_image" id="meta_image" data-filename="meta_image" onchange="document.getElementById('meta').src = window.URL.createObjectURL(this.files[0])">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            {{ Form::label('Meta Image', __('Meta Image'), ['class' => 'col-form-label ']) }}
                                        </div>
                                        <div class="setting-card">
                                            <div class="logo-content">
                                                <a href="{{ $meta_image . '/' . (isset($settings['meta_image']) && !empty($settings['meta_image']) ? $settings['meta_image'] : 'meta_image.png') . '?' . time() }}"
                                                    target="_blank"> <img id="meta"
                                                        src="{{ $meta_image . '/' . (isset($settings['meta_image']) && !empty($settings['meta_image']) ? $settings['meta_image'] : 'meta_image.png') . '?' . time() }}"
                                                        width="400px" class="img_setting"> </a>
                                            </div>
                                            <div class="choose-files mt-4">
                                                <label for="meta_image">
                                                    <div class=" bg-primary logo"> <i
                                                            class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                    </div>
                                                    <input style="margin-top: -40px;" type="file"
                                                        class="form-control file" name="meta_image" id="meta_image"
                                                        data-filename="meta_image"
                                                        onchange="document.getElementById('meta').src = window.URL.createObjectURL(this.files[0])">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <button class="btn-submit btn btn-primary m-r-10" type="submit">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                            {{ Form::close() }}
                        </div>
                    @endcan

                    @can('cookie settings')
                        {{-- Cookie Consent --}}
                        <div class="card" id="useradd-7">
                            {{ Form::model($settings, ['route' => 'admin.cookie.setting', 'method' => 'post', 'class' => 'mb-0']) }}
                            <div
                                class="card-header flex-column flex-lg-row  d-flex align-items-lg-center gap-2 justify-content-between">
                                <h5>{{ __('Cookie Settings') }}</h5>
                                <div class="d-flex align-items-center">
                                    {{ Form::label('enable_cookie', __('Enable cookie'), ['class' => 'col-form-label p-0 fw-bold me-3']) }}
                                    <div class="custom-control custom-switch" onclick="enablecookie()">
                                        <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary"
                                            name="enable_cookie" class="form-check-input input-primary "
                                            id="enable_cookie" {{ $settings['enable_cookie'] == 'on' ? ' checked ' : '' }}>
                                        <label class="custom-control-label mb-1" for="enable_cookie"></label>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="card-body cookieDiv {{ $settings['enable_cookie'] == 'off' ? 'disabledCookie ' : '' }}">
                                <div class="row ">
                                    @if ($enable_chatgpt)
                                        <div>
                                            <a href="javascript:void(0)" data-size="md" data-ajax-popup-over="true"
                                                data-url="{{ route('generate', ['cookie']) }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="{{ __('Generate') }}"
                                                data-title="{{ __('Generate content with AI') }}"
                                                class="btn btn-primary btn-sm float-end">
                                                <i class="fas fa-robot"></i>
                                                {{ __('Generate with AI') }}
                                            </a>
                                        </div>
                                    @endif
                                    <div class="col-md-6">
                                        <div class="form-check form-switch custom-switch-v1" id="cookie_log">
                                            <input type="checkbox" name="cookie_logging"
                                                class="form-check-input input-primary cookie_setting" id="cookie_logging"
                                                onclick="enableButton()"
                                                {{ $settings['cookie_logging'] == 'on' ? ' checked ' : '' }}>
                                            <label class="form-check-label"
                                                for="cookie_logging">{{ __('Enable logging') }}</label>
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('cookie_title', __('Cookie Title'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('cookie_title', null, ['class' => 'form-control cookie_setting']) }}
                                        </div>
                                        <div class="form-group ">
                                            {{ Form::label('cookie_description', __('Cookie Description'), ['class' => ' form-label']) }}
                                            {!! Form::textarea('cookie_description', null, ['class' => 'form-control cookie_setting', 'rows' => '3']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch custom-switch-v1 ">
                                            <input type="checkbox" name="necessary_cookies"
                                                class="form-check-input input-primary" id="necessary_cookies" checked
                                                onclick="return false">
                                            <label class="form-check-label"
                                                for="necessary_cookies">{{ __('Strictly necessary cookies') }}</label>
                                        </div>
                                        <div class="form-group ">
                                            {{ Form::label('strictly_cookie_title', __(' Strictly Cookie Title'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('strictly_cookie_title', null, ['class' => 'form-control cookie_setting']) }}
                                        </div>
                                        <div class="form-group ">
                                            {{ Form::label('strictly_cookie_description', __('Strictly Cookie Description'), ['class' => ' form-label']) }}
                                            {!! Form::textarea('strictly_cookie_description', null, [
                                                'class' => 'form-control cookie_setting ',
                                                'rows' => '3',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <h5>{{ __('More Information') }}</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('more_information_description', __('Contact Us Description'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('more_information_description', null, ['class' => 'form-control cookie_setting']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('contactus_url', __('Contact Us URL'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('contactus_url', null, ['class' => 'form-control cookie_setting']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="card-footer d-flex align-items-center gap-2 flex-sm-column flex-lg-row justify-content-between">
                                <div>
                                    @if (isset($settings['cookie_logging']) && $settings['cookie_logging'] == 'on')
                                        <label for="file"
                                            class="form-label">{{ __('Download cookie accepted data') }}</label>
                                        <a href="{{ asset(Storage::url('uploads/sample')) . '/data.csv' }}"
                                            class="btn btn-primary mr-2 ">
                                            <i class="ti ti-download"></i>
                                        </a>
                                    @endif
                                </div>
                                <input type="submit" class="btn-submit btn btn-primary m-r-10"
                                    value="{{ __('Save Changes') }}" class="btn btn-primary">
                            </div>
                            {{ Form::close() }}
                        </div>

                        <!--cache Setting-->
                        <div id="useradd-8" class="card">
                            <div class="card-header">
                                <h5>{{ __('Cache Settings') }}</h5>
                                <small class="text-secondary font-weight-bold">
                                    {{ __('This is a page meant for more advanced users, simply ignore it if you don`t understand what cache is.') }}
                                </small>
                            </div>
                            <div class="card-body">
                                <div class="col-12 form-group">
                                    <label for="Current cache size"
                                        class="col-form-label bold">{{ __('Current cache size') }}</label>
                                    <div class="input-group search-form">
                                        <input type="text" value="{{ Utility::GetCacheSize() }}" class="form-control"
                                            readonly>
                                        <span class="input-group-text bg-transparent">{{ __('MB') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <a href="{{ url('config-cache') }}"
                                    class="btn btn-print-invoice btn-primary m-r-10">{{ __('Clear Cache') }}</a>
                            </div>
                        </div>
                    @endcan
                    @can('reset permissions')
                        <!-- reset permissions   -->
                        <div id="useradd-10" class="card">
                            <div class="card-header">
                                {{ Form::model($settings, ['route' => 'admin.settings.reset-permissions', 'method' => 'post', 'class' => 'mb-0']) }}
                                <h5>{{ __('Reset Role based allowed permission') }}</h5>
                                <small>{{ __('Reset your permissions') }}</small>
                            </div>

                            <div class="card-footer text-end">
                                <button class="btn btn-primary m-r-10"
                                    type="submit">{{ __('Reset Permissions') }}</button>
                            </div>
                            {{ Form::close() }}
                        </div>
                    @endcan
                    <!-- [ sample-page ] end -->
                </div>
            </div>
        @endsection

        @push('script-page')
            <script>
                $(document).on('click', 'input[name="theme_color"]', function() {
                    var eleParent = $(this).attr('data-theme');
                    $('#themefile').val(eleParent);
                    var imgpath = $(this).attr('data-imgpath');
                    $('.' + eleParent + '_img').attr('src', imgpath);
                });

                $(document).ready(function() {
                    setTimeout(function(e) {
                        var checked = $("input[type=radio][name='theme_color']:checked");
                        $('#themefile').val(checked.attr('data-theme'));
                        $('.' + checked.attr('data-theme') + '_img').attr('src', checked.attr('data-imgpath'));
                    }, 300);
                });

                // function check_theme(color_val) {

                //     $('.theme-color').prop('checked', false);
                //     $('input[value="' + color_val + '"]').prop('checked', true);
                //     $('#color_value').val(color_val);
                // }
            </script>

            <script>
                $('.colorPicker').on('click', function(e) {
                    $('body').removeClass('custom-color');
                    if (/^theme-\d+$/) {
                        $('body').removeClassRegex(/^theme-\d+$/);
                    }
                    $('body').addClass('custom-color');
                    $('.themes-color-change').removeClass('active_color');
                    $(this).addClass('active_color');
                    const input = document.getElementById("color-picker");
                    setColor();
                    input.addEventListener("input", setColor);

                    function setColor() {
                        $(':root').css('--color-customColor', input.value);
                    }

                    $(`input[name='color_flag`).val('true');
                });

                $('.themes-color-change').on('click', function() {

                    $(`input[name='color_flag`).val('false');

                    var color_val = $(this).data('value');
                    $('body').removeClass('custom-color');
                    if (/^theme-\d+$/) {
                        $('body').removeClassRegex(/^theme-\d+$/);
                    }
                    $('body').addClass(color_val);
                    $('.theme-color').prop('checked', false);
                    $('.themes-color-change').removeClass('active_color');
                    $('.colorPicker').removeClass('active_color');
                    $(this).addClass('active_color');
                    $(`input[value=${color_val}]`).prop('checked', true);
                });

                $.fn.removeClassRegex = function(regex) {
                    return $(this).removeClass(function(index, classes) {
                        return classes.split(/\s+/).filter(function(c) {
                            return regex.test(c);
                        }).join(' ');
                    });
                };
            </script>
        @endpush
