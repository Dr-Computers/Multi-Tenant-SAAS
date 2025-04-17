@extends('layouts.company')
@push('header')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js"></script>

    <style>
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 0.3s ease-in-out;
        }

        .loader-overlay.hidden {
            display: none;
        }

        .spinner {
            border: 8px solid rgba(255, 255, 255, 0.3);
            border-top: 8px solid white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <style>
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 0.3s ease-in-out;
        }

        .loader-overlay.hidden {
            display: none;
        }

        .spinner {
            border: 8px solid rgba(255, 255, 255, 0.3);
            border-top: 8px solid white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .theme-toggle {
            display: none !important;
        }


        input.form-control,
        select.form-control,
        .select2.select2-container {
            width: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: 40px !important;
            border: var(--bb-border-width) var(--bb-border-style) var(--bb-border-color) !important;
        }

        .select2-container--default .select2-selection--single {
            border-radius: 6px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 35px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear {
            height: 35px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }
    </style>

    <link media="all" type="text/css" rel="stylesheet" href="/assets/css/core.css">
@endpush
@section('page-title')
    {{ __('Properties') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Properties') }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        <a href="{{ route('company.realestate.properties.create') }}" title="{{ __('Create New Property') }}"
            class="btn btn-sm btn-primary me-2">
            <i class="ti ti-plus"></i>
        </a>
    </div>
@endsection
@section('content')
    <div x-data="stepper()" class="container bg-white rounded-lg shadow-lg p-lg-6 p-2.5 mt-5">
        <div class="pb-1" x-data="formHandler()">

            <form method="POST" @submit.prevent="submitForm" id="propertyFrom"
                action="{{ route('company.realestate.properties.store') }}" enctype="multipart/form-data">
                @csrf
            </form>


            <div x-show="showToast" x-transition
                :class="toastType === 'success' ? 'bg-success text-light' : 'bg-danger text-light'"
                class="fixed top-5 right-5 text-white p-3 rounded shadow-lg transition">

                <p x-html="toastMessage"></p>

            </div>
        </div>
        <div id="loader" class="loader-overlay hidden">
            <div class="spinner"></div>
        </div>
        <!-- Responsive Stepper -->
        <div class="flex flex-col lg:flex-col">
            <!-- Stepper Navigation -->
            {{-- lg:w-1/4  lg:border-r border-gray-200 lg:pr-6 mb-6 lg:mb-0 lg:sticky lg:top-0  z-10 bg-white --}}
            <div class="w-full   border-gray-200 mb-0 sticky top-0  z-10 bg-white">
                <ul class="flex flex-wrap justify-center justify-content-around lg:flex-row lg:justify-start lg:space-x-0 lg:space-y-8 overflow-auto position-sticky top-0"
                    style="z-index: 99999999" {{-- class="flex justify-center lg:flex-col lg:justify-start lg:space-x-0 lg:space-y-8 overflow-auto position-sticky space-x-4 top-0 z-1000" --}}>
                    <template x-for="(step, index) in steps" :key="index">
                        <li role="button" class="flex items-center lg:relative mt-2 step-items" @click="jumpToStep(index)">
                            <!-- Step Circle -->
                            <div class="px-4 py-1 step-circle flex items-center justify-center h-5 w-5 border-2 rounded-full z-10"
                                :class="{
                                    'active border-white-500 bg-theme text-white': index <= currentStep,
                                    'border-gray-300 bg-vk-lt text-gray-500': index > currentStep
                                }">
                                <!-- Add click event to jump to specific step -->
                                <span x-text="index + 1"></span>
                            </div>

                            <!-- Step Titles -->
                            <div class="ms-2 lg:ml-0 lg:mt-4">
                                <p class="text-sm font-medium mb-3"
                                    :class="{ 'text-blue-500': index === currentStep, 'text-gray-600': index !== currentStep }"
                                    x-text="step.title"></p>
                            </div>
                        </li>
                    </template>

                    <li class="d-none   ">
                        <div class="flex items-center lg:relative  step-items">
                            <!-- Step Circle -->
                            <div
                                class="px-4 py-1 bg-vk-lt step-circle2 border-gray-300 text-gray-500  flex items-center justify-center h-5 w-5 border-2 rounded-full z-10">
                                <span class=" text-dark"><i class="fs-3 fw-bold">✓</i></span>
                            </div>

                            <!-- Step Titles -->
                            <div class="ms-2 lg:ml-0  ">
                                Submit for review
                            </div>
                        </div>

                    </li>
                </ul>
            </div>

            <!-- Step Content -->
            {{-- lg:pl-6 --}}
            <div class=" w-full ">

                <!-- Progress Bar -->
                {{-- <div class="w-full bg-gray-200 shadow-sm h-1 mb-1 rounded">
                    <div class="h-full bg-theme border-1 rounded" :style="'width: ' + progressBarWidth + '%'" x-transition>
                    </div>
                </div> --}}
                <div class="space-y-8">
                    <!-- Step 1 -->
                    <div x-show="currentStep === 0" class="mt-2">
                        <!-- Stepper Navigation -->

                        <div x-data="propertyForm()" x-init="init()" class="p-2 space-y-2">
                            <!-- Mode Selection -->
                            <div>
                                <h6 class="mb-3 mt-3 font-medium">I'm looking to<sup class="text-danger fs-4">*</sup></h6>
                                <ul class="grid gap-x-5">
                                    <li class="relative">
                                        <input form="propertyFrom" class="sr-only peer" type="radio" id="sell"
                                            name="mode" value="sell" @change="updateMode('sell')" checked>
                                        <label for="sell"
                                            class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent">Sell</label>
                                    </li>
                                    <li class="relative">
                                        <input form="propertyFrom" class="sr-only peer" type="radio" id="rent"
                                            name="mode" value="rent" @change="updateMode('rent')">
                                        <label for="rent"
                                            class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent">Rent/Lease</label>
                                    </li>

                                </ul>
                            </div>

                            <!-- Type Selection -->
                            <div class="">
                                <h6 class="mb-3 mt-3 font-medium">What kind of property do you have?<sup
                                        class="text-danger fs-4">*</sup></h6>
                                <div class="flex flex-wrap ">
                                    <template x-for="type in types" :key="type">
                                        <div class="flex items-center me-4">
                                            <input form="propertyFrom" type="radio" :id="type.toLowerCase() + '-radio'"
                                                :value="type" name="type" x-model="currentType"
                                                @change="updateCategories()"
                                                class="w-4 h-4 text-green-600 bg-gray-100 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label :for="type.toLowerCase() + '-radio'"
                                                class="ms-2 text-sm font-medium text-gray dark:text-gray"
                                                x-text="type"></label>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Categories -->
                            <div>
                                <h6 class="mb-3 mt-3 font-medium">Choose a Category<sup class="text-danger fs-4">*</sup>
                                </h6>
                                <ul class="flex flex-wrap">
                                    <template x-for="category in categories" :key="category.id">
                                        <li class="relative mb-3">
                                            <input form="propertyFrom" class="sr-only peer" type="radio"
                                                @change="selectedCategory(category.name)" :id="'category_' + category.id"
                                                :value="category.id" name="category" x-model="currentCategory">
                                            <label :for="'category_' + category.id"
                                                class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                x-text="category.name"></label>
                                        </li>
                                    </template>
                                </ul>
                            </div>

       

                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div x-show="currentStep === 1" class="space-y-4 mt-2">

                        <div x-data="locationForm()" x-init="init2()" class="mb-5">

                            <!-- Location form header -->
                            <div class="col-lg-12 mb-3 mx-2">
                                <h1 class="my-1 fw-bold fs-1 mt-2">Where is your property located?</h1>
                                <h4 class="fw-bold text-dark fs-3 mt-2">An accurate location helps you connect with the
                                    right buyers
                                </h4>
                            </div>

                            <div x-data="{ isModalOpen2: false }" class="section mt-5">
                                <div class="mx-1 mb-5">
                                    <label for="city-input" class="block mb-2 text-sm font-medium text-gray-500">
                                        Enter Location/Address<sup class="text-danger fs-4">*</sup></label>
                                    <div class="relative z-0 w-full mb-5 group">
                                        <div class="flex">
                                            <input type="text" id="location" form="propertyFrom"
                                                name="location_info" autocomplete="off"
                                                class="bg-gray-50 text-dark border border-gray-300 text-sm rounded-s-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">

                                        </div>

                                    </div>



                                </div>

                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <!-- City Input -->
                                            <div class="relative z-0 w-full mb-5 group">
                                                <input form="propertyFrom" name="city" type="text" id="auto_city"
                                                    autocomplete="off"
                                                    class="block px-2.5  w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none   dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                    placeholder=" " />
                                                <label for="auto_city"
                                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                                    City<sup class="text-danger fs-4">*</sup></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">

                                            <!-- Locality Input -->
                                            <div class="relative z-0 w-full mb-5 group">
                                                <input form="propertyFrom" name="locality" type="text"
                                                    id="auto_locality" autocomplete="off"
                                                    class="block px-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none  dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                    placeholder=" " />
                                                <label for="auto_locality"
                                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                                    Locality<sup class="text-danger fs-4">*</sup>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <!-- Sub Locality Input -->
                                            <div class="relative z-0 w-full mb-5 group">
                                                <input form="propertyFrom" name="sub_locality" type="text"
                                                    id="auto_subLocality" autocomplete="off"
                                                    class="block px-2.5  w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none   dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                    placeholder=" " />
                                                <label for="auto_subLocality"
                                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                                    Sub Locality (Optional)</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-none">
                                            <!-- Landmark Input -->
                                            <div class="relative z-0 w-full mb-5 group">
                                                <input form="propertyFrom" name="landmark" type="text"
                                                    id="auto_landmark" autocomplete="off"
                                                    class="block px-2.5  w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none   dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                    placeholder=" " />
                                                <label for="auto_landmark"
                                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Landmark
                                                    (Optional)</label>
                                            </div>
                                        </div>

                                    </div>


                                </div>


                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <!-- Latitude Input -->
                                            <div class="relative z-0 w-full mb-5 group">
                                                <input form="propertyFrom" name="latitude" type="text"
                                                    id="auto_latitude" autocomplete="off"
                                                    class="block px-2.5  w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none   dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                    placeholder=" " />
                                                <label for="auto_latitude"
                                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Latitude
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <!-- longitude Input -->
                                            <div class="relative z-0 w-full mb-5 group">
                                                <input form="propertyFrom" name="longitude" type="text"
                                                    id="auto_longitude" autocomplete="off"
                                                    class="block px-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none  dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                    placeholder=" " />
                                                <label for="auto_longitude"
                                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Longitude</label>
                                            </div>
                                        </div>
                                    </div>




                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div x-show="currentStep === 2" class="space-y-4 mt-2">
                        <div class="col-lg-12">
                            <div class="col-lg-12 mb-3 mx-2">
                                <h1 class="my-1 fw-bold fs-1 mt-2">
                                    Tell us about your property
                                </h1>

                            </div>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mx-2 mb-5">
                                            <div class="row">

                                                <div class="col-lg-6">
                                                    <!-- Property Name -->
                                                    <div class="relative z-0 w-full mb-3 group">

                                                        <input form="propertyFrom" name="property_name" type="text"
                                                            autocomplete="off" id="name"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                                            placeholder=" "
                                                            placeholder="Like Example: 2BHK for remt or 3 BHK for sale ,...." />
                                                        <label for="unit-info" id="property_heighlight_name"
                                                            class="absolute fs-3 text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                                            Property heighlight name</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 HideUnwantedSectionsInPlot HideUnwantedSectionsInPg">
                                                    <div class="relative z-0 w-full mb-3 group ">
                                                        <input form="propertyFrom" name="unit_info" type="text"
                                                            autocomplete="off" id="unit-info"
                                                            class="block px-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                                                        <label for="unit-info"
                                                            class="absolute fs-3 text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                                            Flat/Villa/Building No</label>
                                                    </div>
                                                </div>

                                            </div>
                                       
                                            <div id="floor-section"
                                                class="HideUnwantedSectionsInPlot HideUnwantedSectionsInPg ">
                                                <div class="section">
                                                    <h5 class="mt-3 mb-3 fs-3 text-black font-bold">Floor Details</h5>
                                                    <h6 class="mb-3 font-medium">Total no of floors and your floor
                                                        details </h6>

                                                    <div class="grid grid-cols-1 md:grid-cols-5 lg:grid-cols-2 gap-2">

                                                        <!-- Total Floor -->
                                                        <div class="mb-2">
                                                            <div class="relative flex">
                                                                <input form="propertyFrom" name="total_floor"
                                                                    type="number" autocomplete="off" id="total_floor"
                                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                                    class="bg-gray-50 text-dark border border-gray-300 text-sm rounded-s-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 peer"
                                                                    placeholder=" " />
                                                                <label for="total_floor"
                                                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Total
                                                                    Floor</label>

                                                            </div>
                                                        </div>

                                                       

                                                    </div>
                                                </div>
                                            </div>
                                            <div id="property-area-section ">
                                                <div class="section">
                                                    <h5 class="mt-3 fs-3 text-black fw-bold HideUnwantedSectionsInPg">
                                                        Area Details
                                                    </h5>
                                                    <div class="mt-3">
                                                        <div class="ShowWantedSectionsInPlot HideUnwantedSectionsInPg"
                                                            style="display: none">
                                                            <div class="row">
                                                                <!-- Plot Area Input -->
                                                                <div class="mb-2 col-lg-6">
                                                                    <div class="mb-2 ">
                                                                        <label for="projects"
                                                                            class="mt-3 font-medium mb-2 ">
                                                                            Plot Area </label>
                                                                        <div class="relative">


                                                                            <input form="propertyFrom" name="plot_area"
                                                                                autocomplete="off" type="text"
                                                                                id="plot_area"
                                                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                                                class="bg-gray-50 text-dark border border-gray-300 text-sm rounded-s-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 peer"
                                                                                placeholder=" " />

                                                                            <div
                                                                                class="absolute inset-y-0 end-0 flex items-center pointer-events-none z-20 pe-4">
                                                                                Sq.ft
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-2 col-lg-6">
                                                                    <div class="mb-2 ">
                                                                        <div class="relative">
                                                                            <label for="projects"
                                                                                class="mt-3 font-medium mb-2 ">
                                                                                Plot Type </label>
                                                                            <select form="propertyFrom" name="plot_type"
                                                                                id="projects"
                                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                                                                <option value="" selected>None of the
                                                                                    below
                                                                                </option>
                                                                                <option class="ResidentialAllowed"
                                                                                    value="Independent plots">Independent
                                                                                    plots</option>
                                                                                <option class="ResidentialAllowed"
                                                                                    value="Villa,Bungalow,Row houses">
                                                                                    Villa,Bungalow,Row houses</option>
                                                                                <option class="ResidentialAllowed"
                                                                                    value="Builder Floor appartments">
                                                                                    Builder Floor appartments</option>

                                                                                <option class="CommercialAllowed"
                                                                                    value="Agricultural/Farm Land">
                                                                                    Agricultural/Farm Land</option>
                                                                                <option class="CommercialAllowed"
                                                                                    value="Warehouse Plots">Warehouse Plots
                                                                                </option>
                                                                                <option class="CommercialAllowed"
                                                                                    value="Industrial Spaces">Industrial
                                                                                    Spaces</option>
                                                                                <option class="CommercialAllowed"
                                                                                    value="Retail Plots">Retail Plots
                                                                                </option>
                                                                                <option class="CommercialAllowed"
                                                                                    value="Corporate Plot">Corporate Plot
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-2 col-lg-6 mt-3">
                                                                    <h5 class="mt-3 fs-3 text-black font-bold">Boundary
                                                                        Wall
                                                                    </h5>
                                                                    <!-- Open Sides  -->
                                                                    <div
                                                                        class="mb-2 flex gap-3 mt-3 justify-content-between  ">
                                                                        <label for="city"
                                                                            class="block mb-2 text-sm font-medium text-gray-500">
                                                                            No of Open Sides</label>
                                                                        <div x-data="{ count: 1 }"
                                                                            class="flex items-center space-x-2">
                                                                            <!-- Minus Button -->
                                                                            <button @click="if (count > 1) count--"
                                                                                class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                                -
                                                                            </button>

                                                                            <!-- Display Counter -->
                                                                            <span class="text-md font-bold"
                                                                                x-text="count"></span>
                                                                            <input type="hidden" form="propertyFrom"
                                                                                :value="count" name="open_sides">

                                                                            <!-- Plus Button -->
                                                                            <button @click="count++"
                                                                                class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                                +
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div
                                                            class="col-lg-12 HideUnwantedSectionsInPlot HideUnwantedSectionsInPg">
                                                            <div class="row">
                                                                <!-- Carpet Area Input -->
                                                                <div class="mb-2 col-lg-6">
                                                                    <div class="relative">
                                                                        <input form="propertyFrom" name="carpet_area"
                                                                            autocomplete="off" type="text"
                                                                            id="carpet_area"
                                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                                            class="bg-gray-50 text-dark border border-gray-300 text-sm rounded-s-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 peer"
                                                                            placeholder=" " />
                                                                        <label for="carpet_area"
                                                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                                                            Carpet
                                                                            Area<sup
                                                                                class="text-danger fs-4">*</sup></label>
                                                                        <div
                                                                            class="absolute inset-y-0 end-0 flex items-center pointer-events-none z-20 pe-4">
                                                                            Sq.ft
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Built-up Area Input -->
                                                                <div class="mb-2 d-none col-lg-6">
                                                                    <div class="relative">
                                                                        <input form="propertyFrom" name="built_up_area"
                                                                            autocomplete="off" type="text"
                                                                            id="built_up_area"
                                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                                            class="bg-gray-50 text-dark border border-gray-300 text-sm rounded-s-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 peer"
                                                                            placeholder=" " />
                                                                        <label for="built_up_area"
                                                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                                                            Built-up Area</label>
                                                                        <div
                                                                            class="absolute inset-y-0 end-0 flex items-center pointer-events-none z-20 pe-4">
                                                                            Sq.ft
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Super Built-up Area Input -->
                                                                <div class="mb-2 col-lg-6">
                                                                    <div class="relative">
                                                                        <input form="propertyFrom"
                                                                            name="super_built_up_area" autocomplete="off"
                                                                            type="text" id="super_built_up_area"
                                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                                            class="bg-gray-50 text-dark border border-gray-300 text-sm rounded-s-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 peer"
                                                                            placeholder=" " />
                                                                        <label for="super_built_up_area"
                                                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Super
                                                                            Built-up Area</label>
                                                                        <div
                                                                            class="absolute inset-y-0 end-0 flex items-center pointer-events-none z-20 pe-4">
                                                                            Sq.ft
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>
                                                    <div id="parking" class="mt-3 ">

                                                        <div
                                                            class="col-lg-12 HideUnwantedSectionsInPlot HideUnwantedSectionsInPg">
                                                            <h5 class="mt-3 fs-3 text-black font-bold">Reserved Parking
                                                            </h5>
                                                            <div class="row1">
                                                                <div class="mb-2 col-lg-6">

                                                                    <div
                                                                        class="mb-2 flex gap-3 mt-3 justify-content-between ">
                                                                        <label for="city"
                                                                            class="block mb-2 text-sm font-medium text-gray-500">Covered
                                                                            Parking</label>
                                                                        <div x-data="{ count: 0 }"
                                                                            class="flex items-center space-x-2">
                                                                            <!-- Minus Button -->
                                                                            <button @click="if (count > 0) count--"
                                                                                class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                                -
                                                                            </button>

                                                                            <!-- Display Counter -->
                                                                            <span class="text-md font-bold"
                                                                                x-text="count"></span>
                                                                            <input type="hidden" form="propertyFrom"
                                                                                :value="count"
                                                                                name="covered_parking">
                                                                            <!-- Plus Button -->
                                                                            <button @click="count++"
                                                                                class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                                +
                                                                            </button>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <div class="mb-2 col-lg-6">
                                                                    <!-- Parking Area -->
                                                                    <div
                                                                        class="mb-2 flex gap-3 mt-3 justify-content-between  ">
                                                                        <label for="city"
                                                                            class="block mb-2 text-sm font-medium text-gray-500">Open
                                                                            Parking</label>
                                                                        <div x-data="{ count: 0 }"
                                                                            class="flex items-center space-x-2">
                                                                            <!-- Minus Button -->
                                                                            <button @click="if (count > 0) count--"
                                                                                class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                                -
                                                                            </button>

                                                                            <!-- Display Counter -->
                                                                            <span class="text-md font-bold"
                                                                                x-text="count"></span>
                                                                            <input type="hidden" form="propertyFrom"
                                                                                :value="count"
                                                                                name="open_parking">

                                                                            <!-- Plus Button -->
                                                                            <button @click="count++"
                                                                                class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                                +
                                                                            </button>
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
                                    <div class=" col-lg-12">
                                        <div class="">
                                            <div class="mb-5 mx-2">
                                                <div class="section">

                                                    {{-- <div id="MoreaboutDetails"
                                                        class="HideUnwantedSectionsInPlot HideUnwantedSectionsInPg">
                                                        <h5 class="mt-3 font-bold text-black fs-3">More about details
                                                            <small>(optional)</small>
                                                        </h5>
                                                        <div class="my-4 card p-3 bg-body">
                                                            <div class="col-lg-12 mt-2">
                                                                <div class="row">
                                                                    @foreach ($customFields ?? [] as $key => $option_item)
                                                                        <div
                                                                            class="col-md-6 more-details {{ $option_item->is_rent ? 'ShowWantedSectionInRent' : 'HideUnwantedSectionsInRent' }} {{ $option_item->is_sell ? 'ShowWantedSectionInSell' : 'HideUnwantedSectionsInSell' }} ">
                                                                            <div class="relative z-0  mb-3 group  ">
                                                                                <input form="propertyFrom"
                                                                                    name="custom_fields[{{ $key }}][name]"
                                                                                    type="hidden"
                                                                                    value="{{ $option_item->name }}" />
                                                                                <input form="propertyFrom"
                                                                                    name="custom_fields[{{ $key }}][value]"
                                                                                    type="text" autocomplete="off"
                                                                                    id="more-info-{{ $key }}"
                                                                                    class="block px-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                                                                                <label for="more-info-{{ $key }}"
                                                                                    class="absolute fs-3 t dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-body dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                                                                    {{ $option_item->name }}</label>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>

                                                    
                                                    </div> --}}
                                                    <div x-data="{ furnishingStatus: 'unfurnished' }" id="furnishing"
                                                        class="HideUnwantedSectionsInPlot">
                                                        <h5 class="mt-3 font-bold text-black fs-3">Furnishing Details</h5>
                                                        <div class="mt-3 card p-3">
                                                            <!-- Radio Buttons for Furnishing Status -->
                                                            <div class="flex flex-wrap">
                                                                <div class="flex items-center me-4 mb-2">
                                                                    <input form="propertyFrom" type="radio"
                                                                        onclick="selectAllFurnishingItems('unfurnished')"
                                                                        value="unfurnished" name="furnishing_status"
                                                                        x-model="furnishingStatus" id="unfurnished-radio"
                                                                        class="w-3 h-3 text-green-600 bg-gray-100 focus:ring-green-500 dark:focus:ring-green-600">
                                                                    <label for="unfurnished-radio"
                                                                        class="ms-2 fs-5 font-medium text-gray dark:text-gray">Unfurnished</label>
                                                                </div>

                                                                <div class="flex items-center me-4 mb-2">
                                                                    <input form="propertyFrom" type="radio"
                                                                        value="semi-furnished" name="furnishing_status"
                                                                        onclick="selectAllFurnishingItems('semi-furnished')"
                                                                        x-model="furnishingStatus"
                                                                        id="semi-furnished-radio"
                                                                        class="w-3 h-3 text-green-600 bg-gray-100 focus:ring-green-500 dark:focus:ring-green-600">
                                                                    <label for="semi-furnished-radio"
                                                                        class="ms-2 fs-5 font-medium text-gray dark:text-gray">Semi-Furnished</label>
                                                                </div>
                                                                <div class="flex items-center me-4 mb-2">
                                                                    <input form="propertyFrom" type="radio"
                                                                        value="furnished" name="furnishing_status"
                                                                        onclick="selectAllFurnishingItems('furnished')"
                                                                        x-model="furnishingStatus" id="furnished-radio"
                                                                        class="w-3 h-3 text-green-600 bg-gray-100 focus:ring-green-500 dark:focus:ring-green-600">
                                                                    <label for="furnished-radio"
                                                                        class="ms-2 fs-5 font-medium text-gray dark:text-gray">Furnished</label>
                                                                </div>

                                                            </div>

                                                            <!-- Conditionally Display Content for Furnishing Status -->
                                                            {{-- furnishingStatus === 'furnished' || --}}
                                                            <div x-show="furnishingStatus === 'furnished' || furnishingStatus === 'semi-furnished'"
                                                                class="mt-2 border-top bg-body card p-3">
                                                                <div class="row mt-3 ">
                                                                    @foreach ($furnishings ?? [] as $key_0 => $furnish_items)
                                                                        <div class="col-lg-4">
                                                                            <div class="flex items-center flex-wrap mb-4">
                                                                                <input name="furnishing[]" type="checkbox"
                                                                                    value="{{ $furnish_items->id }}"
                                                                                    id="furnish_checkbox_{{ $key_0 }}"
                                                                                    form="propertyFrom"
                                                                                    class="w-3 h-3 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                                                                <label
                                                                                    for="furnish_checkbox_{{ $key_0 }}"
                                                                                    class="ms-2 f5 text-capitalize font-medium text-dark d-flex gap-2">
                                                                                    {{-- <img src="{{ $furnish_items->image_url }}"
                                                                                        class="w-4 h-4"> --}}
                                                                                    {{ $furnish_items->name }}
                                                                                </label>
                                                                            </div>

                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>

                                                            <div x-show="furnishingStatus === 'unfurnished'"
                                                                class="mt-3">
                                                                <!-- Add specific content for unfurnished if needed -->
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="amenities">
                                                        <h5 class="mt-3 font-bold text-black fs-3">Amenities</h5>
                                                        <div class="mt-3 card p-3 bg-body">
                                                            <div class="row mt-3 ">
                                                                @foreach ($amenities ?? [] as $amenity_item)
                                                                    <div class="col-lg-4">
                                                                        <div class="flex items-center mb-4">
                                                                            <input name="amenities[]" form="propertyFrom"
                                                                                type="checkbox"
                                                                                value="{{ $amenity_item->id }}"
                                                                                id="amenity_{{ $amenity_item->id }}"
                                                                                class="w-3 h-3 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                                                            <label for="amenity_{{ $amenity_item->id }}"
                                                                                class="ms-2 text-sm font-medium text-dark d-flex gap-2">
                                                                                {{-- <img src="{{ $amenity_item->image_url }}"
                                                                                    class="w-4 h-4"> --}}
                                                                                {{ $amenity_item->name }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>

                                                    </div>


                                                    <div id="NearestLandmarks">
                                                        <h5 class="mt-3 font-bold text-black fs-3">Nearest Landmarks</h5>
                                                        <div class="mt-3 bg-body card p-3 " x-data="{
                                                            landmarks: [{ id: '', distance: '' }],
                                                            addlandmark() {
                                                                this.landmarks.push({ id: '', distance: '' });
                                                            },
                                                            removelandmark(index) {
                                                                {{-- if (this.landmarks.length > 1) { --}}
                                                                this.landmarks.splice(index, 1);
                                                                {{-- } --}}
                                                            }
                                                        }">
                                                            <!-- Dynamic landmark List -->
                                                            <template x-for="(landmark, index) in landmarks"
                                                                :key="index">
                                                                <div class="col-lg-12 items-center mb-4 position-relative">
                                                                    <div class="row">
                                                                        <!-- landmark Select Box -->
                                                                        <div class="col-lg-6 mb-2">
                                                                            <select form="propertyFrom"
                                                                                :name="'landmarks[' + index + '][id]'"
                                                                                x-model="landmark.id"
                                                                                class="w-full p-2 bg-gray-100 border border-gray-300 rounded-md">
                                                                                <option value="">Select Landmark
                                                                                </option>
                                                                                @foreach ($landmarks ?? [] as $landmark_item)
                                                                                    <option
                                                                                        value="{{ $landmark_item->id }}">
                                                                                        {{ $landmark_item->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <!-- Custom landmark Input Box -->
                                                                        <div class="col-lg-6 mb-2">
                                                                            <input type="text"
                                                                                :name="'landmarks[' + index + '][distance]'"
                                                                                autocomplete="off" form="propertyFrom"
                                                                                x-model="landmark.distance"
                                                                                class="w-full p-2 mt-1 border border-gray-300 rounded-md"
                                                                                placeholder="Enter custom landmark distance">
                                                                        </div>
                                                                    </div>


                                                                    <!-- Remove Icon Button -->
                                                                    <div class="position-absolute right-2">
                                                                        <button @click="removelandmark(index)"
                                                                            class="text-red-600 font-medium">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="16" height="16"
                                                                                fill="currentColor" class="bi bi-x-circle"
                                                                                viewBox="0 0 16 16">
                                                                                <path
                                                                                    d="M11.742 4.742a1 1 0 1 0-1.414-1.414L8 6.586 5.672 4.258a1 1 0 1 0-1.414 1.414L6.586 8l-2.328 2.328a1 1 0 1 0 1.414 1.414L8 9.414l2.328 2.328a1 1 0 1 0 1.414-1.414L9.414 8l2.328-2.328z" />
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </template>

                                                            <!-- Add Another landmark Button -->
                                                            <div class="mt-4 text-end">
                                                                <button @click="addlandmark"
                                                                    class="bg-gray-500 px-2 py-1 rounded-2xl text-dark text-sm">
                                                                    <i class="fa fa-plus me-2"></i>Add Another
                                                                </button>
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

                    <!-- Step 4 -->

                    <div x-show="currentStep === 3" class="space-y-4 mt-2">
                        <div class="mb-5 mx-2">
                           
                          
                            <div class="section">
                                <h5 class="mt-3 font-bold text-black fs-3">Add Documents of your property</h5>
                                

                                <div class="mt-3 border-dashed border-2 border-gray-300 rounded-lg p-3  bg-gray-100">
                                    <div x-data="imageUploader()" class="mx-auto bg-white shadow rounded-lg space-y-6">
                                        <!-- Image Preview Grid -->
                                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                            <!-- Existing Images -->
                                            <template x-for="(image, index) in images" :key="index">
                                                <div class="flex flex-col relative">
                                                    <div class="relative group border rounded-lg overflow-hidden">
                                                        <!-- Image -->
                                                        <img :src="image.url" style="height: 100px;"
                                                            alt="Uploaded Image" class="w-30 h-30 object-cover">

                                                        <!-- Overlay with Cover Option -->
                                                        <div
                                                            class="absolute flex flex-col inset-0 group-hover:opacity-100 space-y-2 transition">
                                                            <!-- Remove Image -->
                                                            <button @click="removeImage(index)"
                                                                class="absolute bg-white p-1 right-0 rounded-full top-0">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                                    viewBox="0 0 20 20" fill="red">
                                                                    <path fill-rule="evenodd"
                                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <label class="flex items-center space-x-2 text-dark cursor-pointer ">
                                                        <input type="radio" name="coverImage" form="propertyFrom"
                                                            class="" :value="image.name"
                                                            @change="setCoverImage(index)"
                                                            :checked="currentCover === index" />
                                                        <span>Make Cover Photo</span>
                                                    </label>
                                                    <span x-show="currentCover === index"
                                                        class="absolute top-0 left-0 p-2 text-white bg-black opacity-50">Cover</span>
                                                </div>
                                            </template>

                                            <!-- Upload New Images -->
                                            <div class="flex flex-col col-auto text-center">
                                                <div class="relative group border rounded-lg p-2 overflow-hidden"
                                                    @click="triggerFileInput()"
                                                    x-bind:class="{ 'border-blue-500': isDragging }">
                                                    <input name="images[]" form="propertyFrom" type="file"
                                                        accept="image/*" id="fileInput" class="hidden" multiple
                                                        @change="addImages($event)">
                                                    <p class="text-gray-600">
                                                        click to upload your images here.</p>
                                                    <p class="text-sm text-blue-500 font-medium hidden">Upload up to 30
                                                        images</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="section">
                                <h5 class="mt-3 font-bold text-black fs-3">Add photos of your property</h5>
                                

                                <div class="mt-3 border-dashed border-2 border-gray-300 rounded-lg p-3  bg-gray-100">
                                    <div x-data="imageUploader()" class="mx-auto bg-white shadow rounded-lg space-y-6">
                                        <!-- Image Preview Grid -->
                                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                            <!-- Existing Images -->
                                            <template x-for="(image, index) in images" :key="index">
                                                <div class="flex flex-col relative">
                                                    <div class="relative group border rounded-lg overflow-hidden">
                                                        <!-- Image -->
                                                        <img :src="image.url" style="height: 100px;"
                                                            alt="Uploaded Image" class="w-30 h-30 object-cover">

                                                        <!-- Overlay with Cover Option -->
                                                        <div
                                                            class="absolute flex flex-col inset-0 group-hover:opacity-100 space-y-2 transition">
                                                            <!-- Remove Image -->
                                                            <button @click="removeImage(index)"
                                                                class="absolute bg-white p-1 right-0 rounded-full top-0">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                                    viewBox="0 0 20 20" fill="red">
                                                                    <path fill-rule="evenodd"
                                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <label class="flex items-center space-x-2 text-dark cursor-pointer ">
                                                        <input type="radio" name="coverImage" form="propertyFrom"
                                                            class="" :value="image.name"
                                                            @change="setCoverImage(index)"
                                                            :checked="currentCover === index" />
                                                        <span>Make Cover Photo</span>
                                                    </label>
                                                    <span x-show="currentCover === index"
                                                        class="absolute top-0 left-0 p-2 text-white bg-black opacity-50">Cover</span>
                                                </div>
                                            </template>

                                            <!-- Upload New Images -->
                                            <div class="flex flex-col col-auto text-center">
                                                <div class="relative group border rounded-lg p-2 overflow-hidden"
                                                    @click="triggerFileInput()"
                                                    x-bind:class="{ 'border-blue-500': isDragging }">
                                                    <input name="images[]" form="propertyFrom" type="file"
                                                        accept="image/*" id="fileInput" class="hidden" multiple
                                                        @change="addImages($event)">
                                                    <p class="text-gray-600">
                                                        click to upload your images here.</p>
                                                    <p class="text-sm text-blue-500 font-medium hidden">Upload up to 30
                                                        images</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="mt-5">
                                <h6 class="mt-3 font-bold text-black fs-3">Ownership<sup
                                        class="text-danger fs-4">*</sup></h6>
                                <ul class="flex gap-5 mt-3 flex-wrap">
                                    <li class="relative">
                                        <input form="propertyFrom" class="sr-only peer" checked type="radio"
                                            value="freehold" name="ownership" id="freehold">
                                        <label for="freehold"
                                            class="mx-1 px-3 py-1 bg-white border rounded-lg cursor-pointer peer-checked:ring-2 peer-checked:ring-green-500">
                                            Freehold
                                        </label>
                                    </li>
                                    <li class="relative">
                                        <input form="propertyFrom" class="sr-only peer" type="radio"
                                            value="co-operative_society" name="ownership" id="co_operative_society">
                                        <label for="co_operative_society"
                                            class="mx-1 px-3 py-1 bg-white border rounded-lg cursor-pointer peer-checked:ring-2 peer-checked:ring-green-500">
                                            Co-operative Society
                                        </label>
                                    </li>
                                    <li class="relative">
                                        <input form="propertyFrom" class="sr-only peer" type="radio"
                                            value="power_of_attorney" name="ownership" id="power_of_attorney">
                                        <label for="power_of_attorney"
                                            class="mx-1 px-3 py-1 bg-white border rounded-lg cursor-pointer peer-checked:ring-2 peer-checked:ring-green-500">
                                            Power of Attorney
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            `fire_safty_start_date`, 
                            `fire_safty_end_date`, 
                            `insurance_start_date`, 
                            `insurance_end_date`,


                            <div class="mt-5">
                                <h6 class="mt-3 font-bold text-black fs-3">What makes your property unique?</h6>
                                <textarea form="propertyFrom" name="unique_info" rows="4" autocomplete="off"
                                    class="block w-full mt-2 p-2 border rounded-lg" placeholder="Write your thoughts here..."></textarea>
                            </div>

                            <div class="mt-5">
                                <div class="mt-3 font-bold text-black fs-3">
                                    <label class="font-bold text-black dark:text-white mb-2"> Keywords <span
                                            class="small text-[#637381]">(if you have multiple words use ' , '
                                            comma)</span> </label>
                                    <textarea form="propertyFrom" rows="4" autocomplete="off" class="block w-full mt-2 p-2 border rounded-lg"
                                        cols="3" placeholder="bangalore central,bengaluru central,..." name="keywords">{{ isset($property) ? $property->keywords : '' }}</textarea>
                                </div>
                            </div>


                            <div class="mt-5">
                                <h6 class="mt-3 font-bold text-black fs-3">Mark as moderation status <sup
                                        class="text-danger fs-4">*</sup>
                                </h6>
                                <ul class="flex gap-5 mt-3 flex-wrap">
                                    <li class="relative">
                                        <input form="propertyFrom" class="sr-only peer" checked type="radio"
                                            value="draft" name="moderation_status" id="draft">
                                        <label for="draft"
                                            class="mx-1 px-3 py-1 bg-white border rounded-lg cursor-pointer peer-checked:ring-2 peer-checked:ring-green-500">
                                            Draft
                                        </label>
                                    </li>
                                    <li class="relative">
                                        <input form="propertyFrom" class="sr-only peer" type="radio"
                                            value="pending" name="moderation_status" id="pending">
                                        <label for="pending"
                                            class="mx-1 px-3 py-1 bg-white border rounded-lg cursor-pointer peer-checked:ring-2 peer-checked:ring-green-500">
                                            Submit for review
                                        </label>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-end mt-2">
                    <button type="button" @click="prevStep"
                        class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400"
                        x-show="currentStep > 0">
                        Back
                    </button>
                    <button type="button" @click="nextStep"
                        class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600" x-show="currentStep < 3">
                        Next
                    </button>
                    <button type="submit" form="propertyFrom"
                        class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600"
                        x-show="currentStep === 3">
                        Submit
                    </button>
                </div>
            </div>

        </div>
    @endsection

    @push('footer')
        <script>
            function stepper() {
                return {
                    currentStep: 0,
                    steps: [{
                            title: "Basic Details",
                        },
                        {
                            title: "Location Details",
                        },
                        {
                            title: "More details",
                        },
                        {
                            title: "Media",
                        },
                    ],
                    nextStep() {
                        if (this.currentStep < 3) {
                            this.currentStep++;
                        }
                        if (this.currentStep === 1) {
                            document.getElementById("pageTitleDescription").style.display = 'none';
                        }
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        })
                    },
                    prevStep() {
                        if (this.currentStep > 0) {
                            this.currentStep--;
                        }
                        if (this.currentStep === 0) {
                            document.getElementById("pageTitleDescription").style.display = 'block';
                        }
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        })
                    },
                    jumpToStep(index) {
                        this.currentStep = index;
                        if (this.currentStep === 1) {
                            document.getElementById("pageTitleDescription").style.display = 'none';
                        } else {
                            document.getElementById("pageTitleDescription").style.display = 'block';
                        }
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        })
                    },
                    get progressBarWidth() {
                        return (this.currentStep / 2) * 100; // 2 is the total number of steps - 1
                    },

                }
            }
        </script>

        {{-- bedroom number other --}}
        <script>
            function addOtherBedrooms() {
                return {
                    showInput: false,
                    newOption: null,
                    addedOption: null,
                    isEditing: false,
                    addOption() {
                        if (this.newOption) {
                            this.addedOption = this.newOption;
                            this.newOption = null;
                            this.showInput = false;
                        }
                    },
                    editOption() {
                        this.showInput = true;
                        this.newOption = this.addedOption;
                        this.addedOption = null;
                    },
                    saveEdit() {
                        this.isEditing = false;
                    }
                };
            }

            function addOtherBathrooms() {
                return {
                    showInput: false,
                    newOption: null,
                    addedOption: null,
                    isEditing: false,
                    addOption() {
                        if (this.newOption) {
                            this.addedOption = this.newOption;
                            this.newOption = null;
                            this.showInput = false;
                        }
                    },
                    editOption() {
                        this.showInput = true;
                        this.newOption = this.addedOption;
                        this.addedOption = null;
                    },
                    saveEdit() {
                        this.isEditing = false;
                    }
                };
            }

            function addOtherBalconies() {
                return {
                    showInput: false,
                    newOption: null,
                    addedOption: null,
                    isEditing: false,
                    addOption() {
                        if (this.newOption) {
                            this.addedOption = this.newOption;
                            this.newOption = null;
                            this.showInput = false;
                        }
                    },
                    editOption() {
                        this.showInput = true;
                        this.newOption = this.addedOption;
                        this.addedOption = null;
                    },
                    saveEdit() {
                        this.isEditing = false;
                    }
                };
            }
        </script>

        <script>
            function imageUploader() {
                return {
                    isDragging: false,
                    images: [],
                    currentCover: null,
                    files: [],

                    // Trigger the hidden file input when the user clicks the drop area
                    triggerFileInput() {
                        document.getElementById('fileInput').click();
                    },

                    // Add images when they are selected from the file input or dropped
                    addImages(event) {
                        const files = event.target.files || event.dataTransfer.files;
                        Array.from(files).forEach(file => {
                            if (file.size <= 2 * 1024 * 1024 && file.type.startsWith('image/')) {
                                const fileObject = {
                                    url: URL.createObjectURL(file), // Blob URL for preview
                                    file: file, // The actual file object for uploading
                                    name: file.name // Original file name
                                };
                                this.images.push(fileObject);
                                this.files.push(file); // Store the file object for uploading
                            } else {
                                alert('Image not allowed to be more than 2 MB');
                            }
                        });
                    },

                    // Remove an image from the list and reset the cover if needed
                    removeImage(index) {
                        // Check if the removed image was the cover photo
                        if (this.currentCover === index) {
                            this.currentCover = null; // Reset the cover image if it was removed
                        }
                        this.images.splice(index, 1);
                        this.files.splice(index, 1); // Remove the file object as well
                    },

                    // Set the selected image as the cover photo and store the original filename
                    setCoverImage(index) {
                        this.currentCover = index;
                        // Access the original file name here
                        const coverImageName = this.images[index].name;
                        console.log('Cover Image Name:', coverImageName); // Use this value to send to your server
                    },

                    // Handle the drop event for drag-and-drop
                    handleDrop(event) {
                        this.isDragging = false;
                        this.addImages(event);
                    },

                    // Visual feedback for drag-and-drop area
                    toggleDragging(state) {
                        this.isDragging = state;
                    },
                };
            }


            function videoUploader() {
                return {
                    videos: [], // Store videos for preview
                    files: [], // Store actual file objects
                    isDragging: false, // Track the drag-and-drop state
                    errorMessage: '', // Store error message for invalid files

                    // Trigger the hidden file input when the user clicks the drop area
                    triggerFileInputVideo() {
                        document.getElementById('fileInputVideo').click();
                    },

                    // Add videos when they are selected from the file input or dropped
                    addVideos(event) {
                        const files = event.target.files || event.dataTransfer.files;
                        Array.from(files).forEach(file => {
                            // Check if the file is a valid video type and under 50MB
                            if (file.size <= 50 * 1024 * 1024 && file.type.startsWith('video/')) {
                                const videoObject = {
                                    url: URL.createObjectURL(file), // Blob URL for preview
                                    file: file, // The actual file object for uploading
                                    name: file.name // Original file name
                                };
                                this.videos.push(videoObject);
                                this.files.push(file); // Store the file object for uploading
                            } else {
                                // Show error message if the file is invalid
                                this.errorMessage = 'Invalid file type or file size exceeds 50MB.';
                                setTimeout(() => this.errorMessage = '', 3000); // Clear error after 3 seconds
                            }
                        });
                    },

                    // Remove a video from the list
                    removeVideo(index) {
                        this.videos.splice(index, 1);
                        this.files.splice(index, 1); // Remove the file object as well
                    },

                    // Handle the drop event for drag-and-drop
                    handleDrop(event) {
                        this.isDragging = false;
                        this.addVideos(event);
                    },

                    // Visual feedback for drag-and-drop area
                    toggleDragging(state) {
                        this.isDragging = state;
                    }
                };
            }
        </script>


        {{-- property form --}}
        <script>
            function propertyForm() {
                return {
                    // Backend data
                    modes: {
                        sell: @json($is_sell ?? []),
                        rent: @json($is_rent ?? [])
                    },
                    types: [], // Available types based on mode
                    categories: [], // Available categories based on mode and type
                    currentMode: 'sell', // Default mode
                    currentType: 'Residential', // Default type
                    currentCategory: null, // Default category

                    // Initialize on page load
                    init() {
                        this.updateMode(this.currentMode); // Initialize with default mode
                    },

                    // Update mode and filter types & categories
                    updateMode(mode) {
                        this.currentMode = mode;
                        this.types = [];
                        this.categories = [];
                        this.currentType = 'Residential';

                        // Set types for Sell and Rent modes
                        if (mode === 'sell' || mode === 'rent') {
                            // Commercial
                            this.types = ['Residential', 'Commercial'];
                            this.toggleSections('more-details', 'none');
                            if (mode === 'sell') {
                                this.toggleSections('ShowWantedSectionInSell', 'block');
                            } else if (mode === 'rent') {
                                this.toggleSections('ShowWantedSectionInRent', 'block');
                            }

                        }

                        // Set categories based on the default type
                        this.updateCategories();
                    },

                    // Update categories based on the type
                    updateCategories() {
                        const modeData = this.modes[this.currentMode];
                        this.categories = modeData.filter(category =>
                            (this.currentType === 'Residential' && category.is_residential) ||
                            (this.currentType === 'Commercial' && category.is_commercial)
                        );

                        // Set the first category as selected by default
                        if (this.categories.length > 0) {
                            this.currentCategory = this.categories[0].id;
                            this.selectedCategory(this.categories[0].name);
                        } else {
                            this.currentCategory = null;
                        }


                    },
                    // Utility function to toggle visibility of sections
                    toggleSections(className, displayStyle) {
                        console.log(className, displayStyle)
                        const elements = document.querySelectorAll(`.${className}`);
                        for (const el of elements) {
                            el.style.display = displayStyle;
                        }
                    },


                    // Handle category selection
                    selectedCategory(categoryName) {

                        // Perform actions based on category
                        if (categoryName === 'Plot and Land') {

                            if (this.currentType === 'Residential') {
                                this.toggleSections('ResidentialAllowed', 'block');
                                this.toggleSections('CommercialAllowed', 'none');
                            } else {
                                this.toggleSections('ResidentialAllowed', 'none');
                                this.toggleSections('CommercialAllowed', 'block');
                            }

                            HideUnwantedSectionsInPlot();
                        } else {
                            ShowHiddenSections();

                            if (this.currentType === 'Residential' && categoryName != 'Plot and Land') {
                                this.toggleSections('ShowWantedSectionsInCommercial', 'none');
                                this.toggleSections('HideUnwantedSectionsInCommercial', 'block');


                            } else if (this.currentType === 'Commercial' && categoryName != 'Plot and Land') {

                                this.toggleSections('HideUnwantedSectionsInCommercial', 'none');
                                this.toggleSections('ShowWantedSectionsInCommercial', 'block');

                            }

                            if (this.currentType === 'Commercial' && this.currentMode === 'rent') {
                                this.toggleSections('ShowWantedSectionsInCommercialRent', 'block');
                            } else {
                                this.toggleSections('ShowWantedSectionsInCommercialRent', 'none');
                            }

                        }

                        if (this.currentType === 'Commercial' && categoryName != 'Plot and Land') {

                            document.getElementById('property_heighlight_name').textContent = 'Building Name';
                        } else {

                            document.getElementById('property_heighlight_name').textContent = 'Property Heighlight Name';
                        }
                    },
                };
            }

            // Utility functions to show/hide sections
            function HideUnwantedSectionsInPlot() {
                for (let el of document.querySelectorAll('.HideUnwantedSectionsInPlot')) el.style.display = 'none';
                for (let el2 of document.querySelectorAll('.ShowWantedSectionsInPlot')) el2.style.display = 'block';
            }

            function ShowHiddenSections() {

                for (let el of document.querySelectorAll('.HideUnwantedSectionsInPlot')) el.style.display = 'block';
                for (let el2 of document.querySelectorAll('.ShowWantedSectionsInPlot')) el2.style.display = 'none';
            }
        </script>

        {{-- location form --}}
        <script>
            localStorage.clear();

            function locationForm() {
                return {
                    recentLocations: [{
                            id: 1,
                            city: 'New York',
                            locality: 'Manhattan',
                            sub_locality: 'Brooklyn',
                            appartment: '5A',
                            landmark: 'Near Central Park',
                            latitude: '',
                            longitude: ''
                        },
                        {
                            id: 2,
                            city: 'Los Angeles',
                            locality: 'Downtown',
                            sub_locality: 'Hollywood',
                            appartment: '10B',
                            landmark: 'Near Hollywood Sign',
                            latitude: '',
                            longitude: ''
                        },
                    ],
                    form: {
                        city: '',
                        location_info: '',
                        locality: '',
                        sub_locality: '',
                        appartment: '',
                        landmark: '',
                        latitude: '',
                        longitude: '',
                    },
                    locationSelected: false,
                    formFilled: false,
                    fillForm(location) {
                        this.form = {
                            ...location
                        };
                        this.locationSelected = true;
                        this.formFilled = true;
                        // Store selected location in localStorage
                        // localStorage.setItem('selectedLocation', JSON.stringify(this.form));
                    },
                    checkForm() {
                        this.formFilled = this.form.city && this.form.locality && this.form.sub_locality;
                    },
                    // Retrieve location from localStorage if exists
                    init2() {

                        // const storedLocation = localStorage.getItem('selectedLocation');
                        // if (storedLocation) {
                        //     this.form = JSON.parse(storedLocation);
                        //     this.formFilled = true;
                        //     this.locationSelected = true;
                        // }
                    }
                };
            }
        </script>

        {{-- convert price to words --}}

        <script>
            // Function to convert number to words
            function convertToWords() {
                const num = document.getElementById('priceInput').value;

                // Ensure the input is within the maximum allowed value (AED 100 Crore)
                if (num > 999999999) {
                    alert('Price cannot exceed AED 99.9 Crore');
                    document.getElementById('priceInput').value = 999999999; // Set to the maximum value
                    return;
                }

                // Convert the number to words
                const words = numberToWords(parseInt(num));
                document.getElementById('priceInWords').textContent = 'AED ' + (words || 'Zero');
            }

            function numberToWords(num) {
                if (isNaN(num) || num === 0) return 'Zero';

                const a = [
                    '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
                    'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
                    'Seventeen', 'Eighteen', 'Nineteen'
                ];
                const b = [
                    '', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'
                ];
                const c = ['Crore', 'Lakh', 'Thousand', 'Hundred', ''];

                // Define how to split numbers in the Indian system
                const divisors = [10000000, 100000, 1000, 100, 1];
                let words = [];

                for (let i = 0; i < divisors.length; i++) {
                    const divisor = divisors[i];
                    const quotient = Math.floor(num / divisor);
                    if (quotient > 0) {
                        if (i === 3 && quotient < 10 && words.length > 0) {
                            // Special handling for numbers below 10 in the "Hundred" place
                            words.push('and');
                        }
                        if (quotient < 20) {
                            words.push(a[quotient]);
                        } else {
                            words.push(b[Math.floor(quotient / 10)] + (quotient % 10 > 0 ? ' ' + a[quotient % 10] : ''));
                        }
                        if (c[i]) words.push(c[i]); // Add the place (Crore, Lakh, etc.)
                        num %= divisor; // Update the remainder
                    }
                }

                return words.join(' ').trim();
            }
        </script>

        <script>
            function formHandler() {
                return {
                    formData: {}, // Object to hold form data
                    responseMessage: '', // Success message
                    errorMessage: '', // Error message
                    validationErrors: [], // Array to store validation errors
                    showToast: false, // Controls visibility of the toast
                    toastMessage: '', // Message for the toast
                    toastType: '', // Type of toast (success/error)

                    async submitForm() {
                        // Show loader
                        this.toggleLoader(true);

                        // Reset validation errors before submitting
                        this.validationErrors = [];
                        this.errorMessage = '';
                        this.responseMessage = '';

                        // Reference the form element
                        const formElement = document.getElementById('propertyFrom');
                        const formData = new FormData(formElement);

                        try {
                            const response = await fetch(`{{ route('company.realestate.properties.store') }}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include Laravel CSRF token
                                },
                                body: formData // Use FormData as request body
                            });

                            // Handle validation errors (422)
                            if (!response.ok) {
                                const errorData = await response.json();
                                this.validationErrors = errorData.errors || [];
                                this.showToastMessage('Validation failed.', 'error');
                                return; // Stop further execution if validation fails
                            }

                            // Handle successful response
                            const data = await response.json();
                            this.responseMessage = data.message || 'Form submitted successfully!';
                            this.validationErrors = []; // Clear validation errors
                            this.showToastMessage(this.responseMessage, 'success');
                            window.location = data.redirect;
                        } catch (error) {
                            // Catch unexpected errors (e.g., network issues)
                            this.errorMessage = error.message || 'An error occurred during form submission';
                            this.responseMessage = ''; // Clear success messages
                            this.showToastMessage(this.errorMessage, 'error');
                        } finally {
                            // Hide loader
                            this.toggleLoader(false);
                        }
                    },
                    toggleLoader(show) {
                        const loader = document.getElementById('loader');
                        if (show) {
                            loader.classList.remove('hidden');
                        } else {
                            loader.classList.add('hidden');
                        }
                    },

                    showToastMessage(message, type) {
                        this.toastType = type;

                        if (type === 'error' && this.validationErrors.length > 0) {
                            // Construct an unordered list of errors
                            this.toastMessage = `
                                <strong>${message}</strong>
                                <ul>
                                    ${this.validationErrors.map(error => `<li>${error}</li>`).join('')}
                                </ul>
                            `;
                        } else {
                            this.toastMessage = message + ':(';
                        }

                        this.showToast = true;
                        setTimeout(() => {
                            this.showToast = false; // Hide toast after 3 seconds
                        }, 3000);
                    }
                };
            }
        </script>

        <script
            src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&callback=initAutocomplete&libraries=places,geometry&v=weekly"
            defer loading=async></script>
        <script>
            function initAutocomplete() {
                const input = document.getElementById("location");

                // const options = {
                //     strictBounds: false,
                //     types: ['address'],
                //     componentRestrictions: {
                //         country: 'IN', // Restrict to India
                //     }
                // };

                // Coordinates for Bangalore center
                const center = {
                    lat: 12.9716,
                    lng: 77.5946
                };

                // Expanded bounding box to cover the full area of Bangalore
                // const defaultBounds = {
                //     north: 13.3000, // Expanded to cover the northernmost part of the city
                //     south: 12.7000, // Expanded to cover the southernmost part
                //     east: 77.7500, // Extended to the easternmost part
                //     west: 77.3500 // Extended to the westernmost part
                // };

                const options = {
                    // bounds: defaultBounds, // Restrict to Bangalore's bounding box
                    strictBounds: true, // Enforce the bounding box strictly
                    types: ['establishment'],
                    componentRestrictions: {
                        country: 'AE', // Restrict to India
                    }
                };

                const autocomplete = new google.maps.places.Autocomplete(input, options);

                autocomplete.addListener("place_changed", () => {

                    const place = autocomplete.getPlace();

                    if (!place.geometry || !place.geometry.location) {

                        window.alert("No details available for input: '" + place.name + "'");
                        return;
                    }
                    // Get city, postal code, and country from the place details
                    // Variables to store details
                    let city = '';
                    let locality = '';
                    let subLocality = '';
                    let landmark = '';
                    let latitude = '';
                    let longitude = '';

                    // Extract latitude and longitude
                    latitude = place.geometry.location.lat();
                    longitude = place.geometry.location.lng();

                    // Loop through address components
                    place.address_components.forEach(component => {
                        const componentType = component.types[0];

                        // Fetch city
                        if (componentType === 'locality') {
                            city = component.long_name;
                        }

                        // Fetch locality (level 2 or 3)
                        if (componentType === 'sublocality_level_1') {
                            locality = component.long_name;
                        }

                        // Fetch sub-locality (level 2 or deeper)
                        if (componentType === 'sublocality_level_2' || componentType === 'sublocality') {
                            subLocality = component.long_name;
                        }

                        // Fetch landmark (route or point of interest)
                        if (componentType === 'route') {
                            landmark = component.long_name;
                        }
                    });

                    // Log the extracted values
                    console.log({
                        city,
                        locality,
                        subLocality,
                        landmark,
                        latitude,
                        longitude
                    });

                    // Set values in respective fields if needed
                    document.getElementById('auto_city').value = city;
                    document.getElementById('auto_locality').value = locality;
                    document.getElementById('auto_subLocality').value = subLocality;
                    document.getElementById('auto_landmark').value = landmark;
                    document.getElementById('auto_latitude').value = latitude;
                    document.getElementById('auto_longitude').value = longitude;
                });
            }

            // Initialize Google Maps Autocomplete
            document.addEventListener("DOMContentLoaded", () => {
                if (typeof google !== "undefined") {
                    initAutocomplete();
                }
            });
        </script>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            jQuery(document).ready(function($) {
                $('#projects').select2({
                    placeholder: "Select a project",
                    allowClear: true,
                    width: 'resolve'
                });
            });
        </script>

        <script>
            function selectAllFurnishingItems(value = 'unfurnished') {
                if (value === 'furnished') {
                    // Check all checkboxes
                    $('input[name="furnishing[]"]').prop('checked', true);
                } else {
                    // Uncheck all checkboxes
                    $('input[name="furnishing[]"]').prop('checked', false);
                }
            }
        </script>
    @endpush
