@extends('layouts.owner')
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




@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">{{ __('Dashboard') }}</a></li>
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

                            <a href="#useradd-7"
                                class="list-group-item list-group-item-action border-0">{{ __('Reset Permissions') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                        </div>
                    </div>
                </div>


                <div class="col-xl-9">



                    <!-- Reset Role based allowed permission-->
                    <div id="useradd-7" class="card">

                        <div class="card-header">
                            {{ Form::model($settings, ['route' => 'owner.settings.reset-permissions', 'method' => 'post', 'class' => 'mb-0']) }}
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
