@extends('layouts.company')
@section('page-title')
    {{ __('Settings') }}
@endsection
@php
    use App\Models\Utility;
    $logo = Utility::get_file('uploads/logo/');
    $logo_light = Utility::getValByName('company_logo_light');
    $logo_dark = Utility::getValByName('company_logo_dark');
    $company_favicon = Utility::getValByName('company_favicon');

    $EmailTemplates = App\Models\EmailTemplate::all();
    $setting = App\Models\Utility::settings();
    $lang = Utility::getValByName('company_default_language');
    $color = !empty($setting['color']) ? $setting['color'] : 'theme-3';
    $flag = !empty($setting['color_flag']) ? $setting['color_flag'] : 'false';

    $companyDetails = App\Models\Utility::companySettings();
    $logo_1 = $companyDetails->logo_1;
    $logo_2 = $companyDetails->logo_2;
    $company_seal = $companyDetails->seal;
    $company_signature = $companyDetails->signature;
    $company_name = $companyDetails->bussiness_name ?? '';
    $company_address = $companyDetails->address ?? '';
    $company_city = $companyDetails->city ?? '';
    $company_zipcode = $companyDetails->postalcode ?? '';
    $company_country = $companyDetails->country ?? '';
    $registration_number = $companyDetails->reg_no ?? '';
    $tax_number = $companyDetails->vat != null ? true : false;
    $vat_number = $companyDetails->vat ?? '';

@endphp



<style>
    .dash-footer {
        margin-left: 0 !important;
    }

    /* .card-footer {
        padding: 2 !important;
    } */
</style>

{{-- <link rel="stylesheet" href="{{ asset('assets/css/footer-style.css') }}"> --}}

@push('script-page')
    <script type="text/javascript">
        $(".email-template-checkbox").click(function() {

            var chbox = $(this);
            $.ajax({
                url: chbox.attr('data-url'),
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    status: chbox.val()
                },
                type: 'post',
                success: function(response) {
                    if (response.is_success) {
                        -
                        // show_toastr('success', '{{ __('Link Copy on Clipboard') }}');
                        show_toastr('success', response.success, 'success');
                        if (chbox.val() == 1) {
                            $('#' + chbox.attr('id')).val(0);
                        } else {
                            $('#' + chbox.attr('id')).val(1);
                        }
                    } else {
                        show_toastr('error', response.error, 'error');
                    }
                },
                error: function(response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        show_toastr('error', response.error, 'error');
                    } else {
                        show_toastr('error', response, 'error');
                    }
                }
            })
        });
    </script>
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
                    document.querySelector("#style").setAttribute("href",
                        "{{ asset('assets/css/style.css') }}");
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
        $(document).on("change", "select[name='invoice_template'], input[name='invoice_color']", function() {
            var template = $("select[name='invoice_template']").val();
            var color = $("input[name='invoice_color']:checked").val();
            $('#invoice_frame').attr('src', '{{ url('/invoices/preview') }}/' + template + '/' + color);
        });

        $(document).on("change", "select[name='proposal_template'], input[name='proposal_color']", function() {
            var template = $("select[name='proposal_template']").val();
            var color = $("input[name='proposal_color']:checked").val();
            $('#proposal_frame').attr('src', '{{ url('/proposal/preview') }}/' + template + '/' + color);
        });

        $(document).on("change", "select[name='bill_template'], input[name='bill_color']", function() {
            var template = $("select[name='bill_template']").val();
            var color = $("input[name='bill_color']:checked").val();
            $('#bill_frame').attr('src', '{{ url('/bill/preview') }}/' + template + '/' + color);
        });

        $(document).on("change", "select[name='retainer_template'], input[name='retainer_color']", function() {
            var template = $("select[name='retainer_template']").val();
            var color = $("input[name='retainer_color']:checked").val();
            $('#retainer_frame').attr('src', '{{ url('/retainer/preview') }}/' + template + '/' + color);
        });
    </script>

    <script>
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

    <script type="text/javascript">
        $(document).ready(function() {
            var checkBox = document.getElementById('tax_number');
            // Check if the element is selected/checked
            if (checkBox.checked) {
                $('#tax_checkbox_id').removeClass('d-none');
            } else {
                $('#tax_checkbox_id').addClass('d-none');
            }
            $(document).on('change', '#tax_number', function() {

                if ($(this).is(':checked') == true) {
                    $('#tax_checkbox_id').removeClass('d-none');
                } else {
                    $('#tax_checkbox_id').addClass('d-none');
                }
            });
        });
    </script>
    <script>
        $(document).on("click", '.send_email', function(e) {

            e.preventDefault();
            var title = $(this).attr('data-title');

            var size = 'md';
            var url = $(this).attr('data-url');
            if (typeof url != 'undefined') {
                $("#commonModal .modal-title").html(title);
                $("#commonModal .modal-dialog").addClass('modal-' + size);
                $("#commonModal").modal('show');

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
                    $('#commonModal .body').html(data);
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
    <li class="breadcrumb-item"><a href="{{ route('company.dashboard') }}">{{ __('Dashboard') }}</a></li>
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
                            <a href="#useradd-1"
                                class="list-group-item list-group-item-action border-0">{{ __('Brand Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-2"
                                class="list-group-item list-group-item-action border-0">{{ __('System Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-3"
                                class="list-group-item list-group-item-action border-0">{{ __('Company Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-4"
                                class="list-group-item list-group-item-action border-0">{{ __('Invoice Template Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-5"
                                class="list-group-item list-group-item-action border-0">{{ __('Letter Pad Template Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-6"
                                class="list-group-item list-group-item-action border-0">{{ __('Estimate Templete Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-7"
                                class="list-group-item list-group-item-action border-0">{{ __('Reset Permissions') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                        </div>
                    </div>
                </div>


                <div class="col-xl-9">

                    <!--Business Setting-->
                    <div id="useradd-1" class="card">

                        {{ Form::model($settings, ['route' => 'company.business.setting', 'class' => 'mb-0', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="card-header">
                            <h5>{{ __('Brand Settings') }}</h5>
                            <small class="text-muted">{{ __('Edit your brand details') }}</small>
                        </div>

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
                                                            width="" class="big-logo">
                                                    </a>
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="company_logo">
                                                        <div class=" bg-primary company_logo_update "> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="company_logo_dark" id="company_logo"
                                                            class="form-control file" data-filename="company_logo_update"
                                                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">


                                                    </label>
                                                </div>
                                                @error('company_logo')
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
                                                            width="150px" class="big-logo img_setting">
                                                    </a>
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="company_logo_light">
                                                        <div class=" bg-primary dark_logo_update "> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="company_logo_light"
                                                            id="company_logo_light" class="form-control file"
                                                            data-filename="dark_logo_update"
                                                            onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])">


                                                    </label>
                                                </div>
                                                @error('company_logo_light')
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
                                                            src="{{ $logo . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '?' . time() }}"
                                                            width="60px" height="63px" class=" img_setting">
                                                    </a>

                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="company_favicon">
                                                        <div class="bg-primary company_favicon_update "> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="company_favicon" id="company_favicon"
                                                            class="form-control file"
                                                            data-filename="company_favicon_update"
                                                            onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])">

                                                    </label>
                                                </div>
                                                @error('logo')
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
                                                <div class="color-picker-wrp">
                                                    <input type="color" value="{{ $color ? $color : '' }}"
                                                        class="colorPicker {{ isset($settings['color_flag']) && $settings['color_flag'] == 'true' ? 'active_color' : '' }} image-input"
                                                        name="custom_color" data-bs-toggle="tooltip"
                                                        data-bs-placement="right"
                                                        title="{{ __('Select Your Own Brand Color') }}"
                                                        id="color-picker">
                                                    <input type="hidden" name="custom-color" id="colorCode">
                                                    <input type='hidden' name="color_flag"
                                                        value={{ isset($settings['color_flag']) && $settings['color_flag'] == 'true' ? 'true' : 'false' }}>
                                                </div>
                                            </div>
                                        </div>

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

                    <!--System Setting-->
                    <div id="useradd-2" class="card">
                        <div class="card-header">
                            <h5>{{ __('System Settings') }}</h5>
                            <small class="text-muted">{{ __('Edit your system details') }}</small>
                        </div>

                        {{ Form::model($settings, ['route' => 'company.system.settings', 'class' => 'mb-0', 'method' => 'post']) }}
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ Form::label('site_currency', __('Currency *'), ['class' => 'form-label']) }}
                                    {{ Form::text('site_currency', null, ['class' => 'form-control font-style']) }}
                                    @error('site_currency')
                                        <span class="invalid-site_currency" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('site_currency_symbol', __('Currency Symbol *'), ['class' => 'form-label']) }}
                                    {{ Form::text('site_currency_symbol', null, ['class' => 'form-control']) }}
                                    @error('site_currency_symbol')
                                        <span class="invalid-site_currency_symbol" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"
                                            for="example3cols3Input">{{ __('Currency Symbol Position') }}</label>
                                        <div class="row px-3">
                                            <div class="form-check col-md-6">
                                                <input class="form-check-input" type="radio"
                                                    name="site_currency_symbol_position" value="pre"
                                                    @if (@$settings['site_currency_symbol_position'] == 'pre') checked @endif id="flexCheckDefault"
                                                    checked>
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    {{ __('Pre') }}
                                                </label>
                                            </div>
                                            <div class="form-check col-md-6">
                                                <input class="form-check-input" type="radio"
                                                    name="site_currency_symbol_position" value="post"
                                                    @if (@$settings['site_currency_symbol_position'] == 'post') checked @endif id="flexCheckChecked">
                                                <label class="form-check-label" for="flexCheckChecked">
                                                    {{ __('Post') }}
                                                </label>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="site_date_format" class="form-label">{{ __('Date Format') }}</label>
                                    <select type="text" name="site_date_format" class="form-control selectric"
                                        id="site_date_format">
                                        <option value="M j, Y"
                                            @if (@$settings['site_date_format'] == 'M j, Y') selected="selected" @endif>Jan 1,2015</option>
                                        <option value="d-m-Y"
                                            @if (@$settings['site_date_format'] == 'd-m-Y') selected="selected" @endif>dd-mm-yyyy</option>
                                        <option value="m-d-Y"
                                            @if (@$settings['site_date_format'] == 'm-d-Y') selected="selected" @endif>mm-dd-yyyy</option>
                                        <option value="Y-m-d"
                                            @if (@$settings['site_date_format'] == 'Y-m-d') selected="selected" @endif>yyyy-mm-dd</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="site_time_format" class="form-label">{{ __('Time Format') }}</label>
                                    <select type="text" name="site_time_format" class="form-control selectric"
                                        id="site_time_format">
                                        <option value="g:i A"
                                            @if (@$settings['site_time_format'] == 'g:i A') selected="selected" @endif>10:30 PM</option>

                                        <option value="H:i"
                                            @if (@$settings['site_time_format'] == 'H:i') selected="selected" @endif>22:30</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">

                            <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit"
                                value="{{ __('Save Changes') }}">

                        </div>
                        {{ Form::close() }}
                    </div>

                    <!--Company Setting-->
                    <div id="useradd-3" class="card">
                        <div class="card-header">
                            <h5>{{ __('Company Settings') }}</h5>
                            <small class="text-muted">{{ __('Edit your company details') }}</small>
                        </div>
                        {{ Form::model($settings, ['route' => 'company.company-settings', 'method' => 'post', 'class' => 'mb-0', 'enctype' => 'multipart/form-data']) }}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-md-6 dashboard-card">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Logo 1') }}</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class=" setting-card">
                                                <div class="logo-content mt-4">
                                                    <a href="{{ $logo . (isset($logo_1) && !empty($logo_1) ? $logo_1 : 'logo-dark.png') . '?' . time() }}"
                                                        target="_blank">
                                                        <img id="logo1" alt="your image"
                                                            src="{{ $logo . (isset($logo_1) && !empty($logo_1) ? $logo_1 : 'logo-dark.png') . '?' . time() }}"
                                                            width="" class="big-logo">
                                                    </a>
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="logo_1">
                                                        <div class=" bg-primary logo_1_update "> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="logo_1" id="logo_1"
                                                            accept=".png" class="form-control file"
                                                            data-filename="logo_1_update"
                                                            onchange="document.getElementById('logo1').src = window.URL.createObjectURL(this.files[0])">
                                                    </label>
                                                </div>
                                                @error('logo_1')
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
                                <div class="col-lg-6 col-sm-6 col-md-6 dashboard-card">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Logo 2') }}</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class=" setting-card">
                                                <div class="logo-content mt-4">
                                                    <a href="{{ $logo . (isset($logo_2) && !empty($logo_2) ? $logo_2 : 'logo-light.png') . '?' . time() }}"
                                                        target="_blank">
                                                        <img id="logo2" alt="your image"
                                                            src="{{ $logo . (isset($logo_2) && !empty($logo_2) ? $logo_2 : 'logo-light.png') . '?' . time() }}"
                                                            width="150px" class="big-logo img_setting">
                                                    </a>
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="logo_2">
                                                        <div class=" bg-primary dark_logo_update "> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="logo_2" id="logo_2"
                                                            accept=".png" class="form-control file"
                                                            data-filename="dark_logo_update"
                                                            onchange="document.getElementById('logo2').src = window.URL.createObjectURL(this.files[0])">
                                                    </label>
                                                </div>
                                                @error('logo_2')
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
                                <div class="col-lg-6 col-sm-6 col-md-6 dashboard-card">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Seal') }}</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class=" setting-card">
                                                <div class="logo-content mt-4">


                                                    <a href="{{ $logo . (isset($company_seal) && !empty($company_seal) ? $company_seal : 'favicon.png') . '?' . time() }}"
                                                        target="_blank">
                                                        <img id="signature" alt="your image"
                                                            src="{{ $logo . (isset($company_seal) && !empty($company_seal) ? $company_seal : 'favicon.png') . '?' . time() }}"
                                                            width="60px" height="63px" class=" img_setting">
                                                    </a>


                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="company_seal">
                                                        <div class="bg-primary company_seal_update "> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="company_seal" id="company_seal"
                                                            accept=".png" class="form-control file"
                                                            data-filename="company_seal_update"
                                                            onchange="document.getElementById('seal').src = window.URL.createObjectURL(this.files[0])">

                                                    </label>
                                                </div>
                                                @error('company_seal')
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
                                <div class="col-lg-6 col-sm-6 col-md-6 dashboard-card">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Signature') }}</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class=" setting-card">
                                                <div class="logo-content mt-4">
                                                    <a href="{{ $logo . (isset($company_signature) && !empty($company_signature) ? $company_signature : 'favicon.png') . '?' . time() }}"
                                                        target="_blank">
                                                        <img id="signature" alt="your image"
                                                            src="{{ $logo . (isset($company_signature) && !empty($company_signature) ? $company_signature : 'favicon.png') . '?' . time() }}"
                                                            width="60px" height="63px" class=" img_setting">
                                                    </a>

                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="company_signature">
                                                        <div class="bg-primary company_signature_update "> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="company_signature" accept=".png"
                                                            id="company_signature" class="form-control file"
                                                            data-filename="company_signature_update"
                                                            onchange="document.getElementById('signature').src = window.URL.createObjectURL(this.files[0])">

                                                    </label>
                                                </div>
                                                @error('company_signature')
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
                                <div class="form-group col-md-6">
                                    {{ Form::label('company_name *', __('Company Name *'), ['class' => 'form-label']) }}
                                    {{ Form::text('company_name', $company_name, ['class' => 'form-control font-style']) }}
                                    @error('company_name')
                                        <span class="invalid-company_name" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('company_address', __('Address'), ['class' => 'form-label']) }}
                                    {{ Form::text('company_address', $company_address, ['class' => 'form-control font-style']) }}
                                    @error('company_address')
                                        <span class="invalid-company_address" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('company_city', __('City'), ['class' => 'form-label']) }}
                                    {{ Form::text('company_city', $company_city, ['class' => 'form-control font-style']) }}
                                    @error('company_city')
                                        <span class="invalid-company_city" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                {{-- <div class="form-group col-md-6">
                                    {{ Form::label('company_state', __('State'), ['class' => 'form-label']) }}
                                    {{ Form::text('company_state', null, ['class' => 'form-control font-style']) }}
                                    @error('company_state')
                                        <span class="invalid-company_state" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div> --}}
                                <div class="form-group col-md-6">
                                    {{ Form::label('company_zipcode', __('Zip/Post Code'), ['class' => 'form-label']) }}
                                    {{ Form::text('company_zipcode', $company_zipcode, ['class' => 'form-control']) }}
                                    @error('company_zipcode')
                                        <span class="invalid-company_zipcode" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group  col-md-6">
                                    {{ Form::label('company_country', __('Country'), ['class' => 'form-label']) }}
                                    {{ Form::text('company_country', $company_country, ['class' => 'form-control font-style']) }}
                                    @error('company_country')
                                        <span class="invalid-company_country" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                {{-- <div class="form-group col-md-6">
                                    {{ Form::label('company_telephone', __('Telephone'), ['class' => 'form-label']) }}
                                    {{ Form::text('company_telephone', null, ['class' => 'form-control']) }}
                                    @error('company_telephone')
                                        <span class="invalid-company_telephone" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div> --}}
                                <div class="form-group col-md-6">
                                    {{ Form::label('registration_number', __('Company Registration Number *'), ['class' => 'form-label']) }}
                                    {{ Form::text('registration_number', $registration_number, ['class' => 'form-control']) }}
                                    @error('registration_number')
                                        <span class="invalid-registration_number" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            {{ Form::label('tax_number', __('Tax Number'), ['class' => 'form-chech-label']) }}
                                            <div class="form-check form-switch custom-switch-v1 float-end">
                                                <input type="checkbox" class="form-check-input" name="tax_number"
                                                    id="tax_number" {{ $tax_number == 'on' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="vat_gst_number_switch"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-6" id="tax_checkbox_id">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check form-check-inline form-group mb-3">
                                                    <input type="radio" id="customRadio8" name="tax_type"
                                                        value="VAT" class="form-check-input" checked
                                                        {{ $settings['tax_type'] == 'VAT' ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="customRadio8">{{ __('VAT Number') }}</label>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-6">
                                                <div class="form-check form-check-inline form-group mb-3">
                                                    <input type="radio" id="customRadio7" name="tax_type"
                                                        value="GST" class="form-check-input"
                                                        {{ $settings['tax_type'] == 'GST' ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="customRadio7">{{ __('GST Number') }}</label>
                                                </div>
                                            </div> --}}
                                        </div>
                                        {{ Form::text('vat_number', $vat_number, ['class' => 'form-control', 'placeholder' => __('Enter VAT / GST Number')]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" id="addSig"
                                value="{{ __('Save Changes') }}">
                        </div>
                        {{ Form::close() }}
                    </div>


                    <!--Choose Invoice Template & Settings-->
                    <div id="useradd-4" class="card">
                        <form action="{{ route('company.invoice.template.settings.store') }}" method="POST"
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

                    <!--Choose Letter Pad Template & Settings-->
                    <div id="useradd-5" class="card">
                        <form action="{{ route('company.letter-pad.template.settings.store') }}" method="POST"
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

                    <!--Choose Estimate Template & Settings-->
                    <div id="useradd-6" class="card">
                        <form action="{{ route('company.estimate.template.settings.store') }}" method="POST"
                            class="mb-0" accept-charset="UTF-8" x-data="{ selected: '{{ $estimateSettings->template ?? '' }}' }">
                            @csrf
                            <div class="card-header row d-flex justify-content-between">
                                <div class="col-auto">
                                    <h5>{{ __('Choose Estimate Template & Settings') }}</h5>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">


                                    @foreach ($estimateTemplates ?? [] as $estimateTemplate)
                                        <div class="col-md-3">
                                            <label
                                                @click="selected = '{{ $estimateSettings ? $estimateSettings->template : '' }}'"
                                                class="template-card invoice-template-card card text-center p-3"
                                                :class="selected === '{{ $estimateTemplate->id }}' ? 'selected' : ''">
                                                <input type="radio" name="estimateTemplate"
                                                    value="{{ $estimateTemplate->id }}"
                                                    :checked="selected === '{{ $estimateTemplate->id }}'">
                                                <div>
                                                    <div class="my-2">
                                                        <img src="{{ asset('storage/' . $estimateTemplate->image) }}"
                                                            class="img-fluid" style="height: 100px;">
                                                    </div>
                                                    <div class="my-2 fw-bold">
                                                        {{ $estimateTemplate->name }}
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">{{ __('Estimate Terms & Conditions') }}</label>
                                        <textarea name="terms" rows="4" class="form-control" placeholder="Enter default terms & conditions...">{{ $estimateSettings->terms ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                            </div>
                        </form>
                    </div>
                    <!-- Reset Role based allowed permission-->
                    <div id="useradd-7" class="card">

                        <div class="card-header">
                            {{ Form::model($settings, ['route' => 'company.settings.reset-permissions', 'method' => 'post', 'class' => 'mb-0']) }}
                            <h5>{{ __('Reset Role based allowed permission') }}</h5>
                            <small>{{ __('Reset your permissions') }}</small>
                        </div>

                        <div class="card-footer text-end">
                            <button class="btn btn-primary m-r-10" type="submit">{{ __('Reset Permissions') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>



            </div>
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>
@endsection


@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush
@push('script-page')
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.summernote-simple').summernote({
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                    ['list', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'unlink']],
                ],
                height: 200,
            });
        });
    </script>

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

        function check_theme(color_val) {

            $('.theme-color').prop('checked', false);
            $('input[value="' + color_val + '"]').prop('checked', true);
            $('#color_value').val(color_val);
        }
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
