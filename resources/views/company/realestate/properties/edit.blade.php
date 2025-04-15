@extends('layouts.company')

@push('header')
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
@endpush

@section('content')
    <div class="container">
        <nav class="flex flex-wrap" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('user.dashboard') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="{{ route('user.properties.index') }}"
                            class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Properties</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Property Edit
                            "{{ $property->name }}"</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div x-data="stepper()" class="container bg-white rounded-lg shadow-lg p-lg-6 p-2.5 mt-5">
        <div class="" x-data="formHandler()">

            <form method="POST" @submit.prevent="submitForm" id="propertyFrom"
                action="{{ route('user.properties.update', $property->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
            </form>
            <!-- Toast Alert -->
            <div x-show="showToast" x-transition :class="toastType === 'success' ? 'bg-green-500' : 'bg-red-500'"
                class="fixed top-5 right-5 text-white p-3 rounded shadow-lg">
                <p x-text="toastMessage"></p>
            </div>
        </div>

        <!-- Responsive Stepper -->
        <div class="flex flex-col lg:flex-row">
            <!-- Stepper Navigation -->
            <div class="lg:w-1/4  lg:border-r border-gray-200 lg:pr-6 mb-6 lg:mb-0 lg:sticky lg:top-0  z-10 bg-white">
                <ul class="flex flex-wrap justify-center justify-content-around lg:flex-col lg:justify-start lg:space-x-0 lg:space-y-8 overflow-auto position-sticky top-0"
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

                    <li class="flex items-center lg:relative  step-items">
                        <!-- Step Circle -->
                        <div
                            class="px-4 py-1 bg-vk-lt step-circle2 border-gray-300 text-gray-500  flex items-center justify-center h-5 w-5 border-2 rounded-full z-10">
                            <span class=" text-dark"><i class="fs-3 fw-bold">✓</i></span>
                        </div>

                        <!-- Step Titles -->
                        <div class="ms-2 lg:ml-0  ">
                            Submit for review
                        </div>
                    </li>
                </ul>
            </div>

            <div id="loader" class="loader-overlay hidden">
                <div class="spinner"></div>
            </div>

            <!-- Step Content -->
            <div class="lg:w-3/4 w-full lg:pl-6">

                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 shadow-sm h-1 mb-1 rounded">
                    <div class="h-full bg-theme border-1 rounded" :style="'width: ' + progressBarWidth + '%'" x-transition>
                    </div>
                </div>
                <div class="space-y-8">
                    <!-- Step 1 -->
                    <div x-show="currentStep === 0" class="mt-2">
                        <!-- Stepper Navigation -->
                        <div id="pageTitleDescription">
                            <h1 class="my-1 fw-bold fs-1">Welcome back {{ $user->name }},</h1>
                            <h4 class="fw-bold text-dark fs-3 mt-2">Sell or Rent your Property</h4>
                            <h6 class=" text-dark fs-5 mt-2">You are posting this property for <span
                                    class="ms-1 bg-warning text-light px-2 py-1 rounded-5 fs-5"> FREE! </span> </h6>
                            <br>
                        </div>
                        <div x-data="propertyForm()" x-init="init()" class="p-2 space-y-2">
                            <!-- Mode Selection -->
                            <div>
                                <h6 class="mb-3 mt-3 font-medium">I'm looking to<sup class="text-danger fs-4">*</sup></h6>
                                <ul class="grid gap-x-5">
                                    <li class="relative">

                                        <input form="propertyFrom" class="sr-only peer" type="radio" id="sell"
                                            name="mode" value="sell" @change="updateMode('sell')"
                                            {{ isset($property) && $property->type == 'sell' ? 'checked' : '' }}>
                                        <label for="sell"
                                            class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent">Sell</label>
                                    </li>
                                    <li class="relative">
                                        <input form="propertyFrom" class="sr-only peer" type="radio" id="rent"
                                            name="mode" value="rent" @change="updateMode('rent')"
                                            {{ isset($property) && $property->type == 'rent' ? 'checked' : '' }}>
                                        <label for="rent"
                                            class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent">Rent/Lease</label>
                                    </li>
                                    <li class="relative">
                                        <input form="propertyFrom" class="sr-only peer" type="radio" id="pg"
                                            name="mode" value="pg" @change="updateMode('pg')"
                                            {{ isset($property) && $property->type == 'pg' ? 'checked' : '' }}>
                                        <label for="pg"
                                            class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent">PG</label>
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

                            <!--  Built & Suit -->
                            <div style="display: none"
                                class="ShowWantedSectionsInCommercialRent HideUnwantedSectionsInPlot HideUnwantedSectionsInPg  mt-2">
                                <h6 class="mb-3 mt-3 font-medium">Commercial real estate agreement</h6>
                                <div class="flex flex-wrap">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" {{ $property->built_suit == 1 ? 'checked' : '' }}
                                            type="checkbox" id="BuiltSuit" name="built_suit" value="1">
                                        <label class="form-check-label" for="BuiltSuit">Built & Suit</label>
                                    </div>

                                </div>
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

                            <!-- Recent Locations - Show only when the form isn't filled -->
                            <div x-show="!formFilled && !locationSelected" class="mx-2 mb-5">
                                <h6 class="mb-2 mt-3 font-medium">Choose from recent locations</h6>
                                <div class="col-lg-6">
                                    <ul class="w-full gap-2 d-flex flex-column">
                                        <template x-for="location in recentLocations" :key="location.id">
                                            <li>
                                                <input type="radio" :id="'location-' + location.id"
                                                    name="recentLocation" class="hidden peer"
                                                    @change="fillForm(location)" />
                                                <label :for="'location-' + location.id"
                                                    class="inline-flex items-center justify-between w-full p-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100">
                                                    <div class="block">
                                                        <div class="w-full text-sm font-semibold" x-text="location.city">
                                                        </div>
                                                        <div class="w-full" x-text="location.locality"></div>
                                                        <div class="w-full" x-text="location.sub_locality"></div>
                                                    </div>
                                                </label>
                                            </li>
                                        </template>
                                        <li class="text-start">
                                            <span class="fw-bold text-gray-500 my-2">Or</span>
                                        </li>
                                        <li>
                                            <label for="city-input"
                                                class="block mb-2 text-sm font-medium text-gray-500">Add
                                                location</label>
                                            <div class="flex">
                                                <input type="text" id="city-input" x-model="form.city"
                                                    autocomplete="off" @input="checkForm"
                                                    class="bg-gray-50 text-dark border border-gray-300 text-sm rounded-s-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                <span role="button"
                                                    class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-e-md">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        fill="#cba641" class="bi bi-crosshair">
                                                        <path
                                                            d="M8.5.5a.5.5 0 0 0-1 0v.518A7 7 0 0 0 1.018 7.5H.5a.5.5 0 0 0 0 1h.518A7 7 0 0 0 7.5 14.982v.518a.5.5 0 0 0 1 0v-.518A7 7 0 0 0 14.982 8.5h.518a.5.5 0 0 0 0-1h-.518A7 7 0 0 0 8.5 1.018zm-6.48 7A6 6 0 0 1 7.5 2.02v.48a.5.5 0 0 0 1 0v-.48a6 6 0 0 1 5.48 5.48h-.48a.5.5 0 0 0 0 1h.48a6 6 0 0 1-5.48 5.48v-.48a.5.5 0 0 0-1 0v.48A6 6 0 0 1 2.02 8.5h.48a.5.5 0 0 0 0-1zM8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Form inputs (City, Locality, etc.) - Show only when form is filled -->
                            {{-- <div x-show="formFilled" class="mx-2 mb-5">
                                <div class="grid md:grid-cols-3 md:gap-6">
                                    <!-- City Input -->
                                    <div class="relative z-0 w-full mb-5 group">
                                        <input form="propertyFrom" name="city" type="text" id="city"
                                            autocomplete="off" x-model="form.city" @input="checkForm"
                                            class="block px-2.5  w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none   dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " />
                                        <label for="city"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">City<sup
                                                class="text-danger fs-4">*</sup></label>
                                    </div>

                                    <!-- Locality Input -->
                                    <div class="relative z-0 w-full mb-5 group">
                                        <input form="propertyFrom" name="locality" type="text" id="locality"
                                            autocomplete="off" x-model="form.locality" @input="checkForm"
                                            class="block px-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none  dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " />
                                        <label for="locality"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Locality</label>
                                    </div>
                                    <!-- Sub Locality Input -->
                                    <div class="relative z-0 w-full mb-5 group">
                                        <input form="propertyFrom" name="sub_locality" type="text" id="sub_locality"
                                            autocomplete="off" x-model="form.sub_locality" @input="checkForm"
                                            class="block px-2.5  w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none   dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " />
                                        <label for="sub_locality"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Sub
                                            Locality</label>
                                    </div>

                                </div>

                                <div class="grid md:grid-cols-2 md:gap-6">

                                    <!-- Apartment Input -->
                                    <div class="relative z-0 w-full mb-5 group">
                                        <input form="propertyFrom" name="apartment" type="text" id="appartment"
                                            autocomplete="off" x-model="form.appartment"
                                            class="block px-2.5  w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none  dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " />
                                        <label for="appartment"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Apartment
                                            (Optional)</label>
                                    </div>

                                    <!-- Landmark Input -->
                                    <div class="relative z-0 w-full mb-5 group">
                                        <input form="propertyFrom" name="landmark" type="text" id="landmark"
                                            autocomplete="off" x-model="form.landmark"
                                            class="block px-2.5  w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none   dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder="" />
                                        <label for="landmark"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Landmark
                                            (Optional)</label>
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 md:gap-6">
                                    <!-- Latitude Input -->
                                    <div class="relative z-0 w-full mb-5 group">
                                        <input form="propertyFrom" name="latitude" type="text" id="clatitudeity"
                                            autocomplete="off" x-model="form.latitude" @input="checkForm"
                                            class="block px-2.5  w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none   dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " />
                                        <label for="latitude"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Latitude<sup
                                                class="text-danger fs-4">*</sup></label>
                                    </div>

                                    <!-- longitude Input -->
                                    <div class="relative z-0 w-full mb-5 group">
                                        <input form="propertyFrom" name="longitude" type="text" id="longitude"
                                            autocomplete="off" x-model="form.longitude" @input="checkForm"
                                            class="block px-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none  dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " />
                                        <label for="longitude"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Longitude</label>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="mx-2 mb-5">
                                <label for="city-input" class="block mb-2 text-sm font-medium text-gray-500">
                                    Enter Location/Address<sup class="text-danger fs-4">*</sup></label>
                                <div class="relative z-0 w-full mb-5 group">
                                    <div class="flex">
                                        <input type="text" id="location"
                                            value="{{ $property ? $property->location : '' }}" name="location_info"
                                            form="propertyFrom" autocomplete="off"
                                            class="bg-gray-50 text-dark border border-gray-300 text-sm rounded-s-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        {{-- <span role="button"
                                                class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-e-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="#cba641" class="bi bi-crosshair">
                                                    <path
                                                        d="M8.5.5a.5.5 0 0 0-1 0v.518A7 7 0 0 0 1.018 7.5H.5a.5.5 0 0 0 0 1h.518A7 7 0 0 0 7.5 14.982v.518a.5.5 0 0 0 1 0v-.518A7 7 0 0 0 14.982 8.5h.518a.5.5 0 0 0 0-1h-.518A7 7 0 0 0 8.5 1.018zm-6.48 7A6 6 0 0 1 7.5 2.02v.48a.5.5 0 0 0 1 0v-.48a6 6 0 0 1 5.48 5.48h-.48a.5.5 0 0 0 0 1h.48a6 6 0 0 1-5.48 5.48v-.48a.5.5 0 0 0-1 0v.48A6 6 0 0 1 2.02 8.5h.48a.5.5 0 0 0 0-1zM8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
                                                </svg>
                                            </span> --}}
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <!-- City Input -->
                                            <div class="relative z-0 w-full mb-5 group">
                                                <input form="propertyFrom" name="city"
                                                    value="{{ $property ? $property->city : '' }}" type="text"
                                                    id="auto_city" autocomplete="off"
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
                                                <input form="propertyFrom" name="locality"
                                                    value="{{ $property ? $property->locality : '' }}" type="text"
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
                                                <input form="propertyFrom" name="sub_locality"
                                                    value="{{ $property ? $property->sub_locality : '' }}" type="text"
                                                    id="auto_subLocality" autocomplete="off"
                                                    class="block px-2.5  w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none   dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                    placeholder=" " />
                                                <label for="auto_subLocality"
                                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                                    Sub Locality (Optional)</label>
                                            </div>
                                        </div>
                                    </div>



                                </div>

                                <div class="grid md:grid-cols-2 md:gap-6 d-none">

                                    <!-- Landmark Input -->
                                    <div class="relative z-0 w-full mb-5 group">
                                        <input form="propertyFrom" name="landmark"
                                            value="{{ $property ? $property->landmark : '' }}" type="text"
                                            id="auto_landmark" autocomplete="off"
                                            class="block px-2.5  w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none   dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " />
                                        <label for="auto_landmark"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Landmark
                                            (Optional)</label>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <!-- Latitude Input -->
                                            <div class="relative z-0 w-full mb-5 group">
                                                <input form="propertyFrom" name="latitude"
                                                    value="{{ $property ? $property->latitude : '' }}" type="text"
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
                                                <input form="propertyFrom" name="longitude"
                                                    value="{{ $property ? $property->longitude : '' }}" type="text"
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
                                <h4 class="fw-bold text-dark fs-3 mt-2">Better your property score, greater your visibility
                                </h4>
                            </div>
                            <div class="">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mx-2 mb-5">
                                            <div class="row">
                                                <div
                                                    class="col-lg-6 HideUnwantedSectionsInPg ShowWantedSectionInSell ShowWantedSectionInRent">
                                                    <!-- Project  -->
                                                    <div class="mb-2">
                                                        <label for="projects" class="mt-3 font-medium mb-2 ">Choose
                                                            Project</label><br>
                                                        <select form="propertyFrom" name="project" id="projects"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                                            <option value="" selected>None of the below</option>
                                                            @foreach ($projects ?? [] as $project_item)
                                                                <option @if ($property->project->id == $project_item->id) selected @endif
                                                                    value="{{ $project_item->id }}">
                                                                    {{ $project_item->name }}</option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <!-- Property Name -->
                                                    <div class="relative z-0 w-full mb-3 group">
                                                        <label for="" class="mt-3 font-medium mb-2 "
                                                            id="property_heighlight_name"> Property
                                                            heighlight name <sup class="text-danger fs-4">*</sup></label>
                                                        <input form="propertyFrom" name="property_name" type="text"
                                                            autocomplete="off" id="name"
                                                            value="{{ isset($property) && $property->name ? $property->name : '' }}"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                                            placeholder=" "
                                                            placeholder="Like Example: 2BHK for remt or 3 BHK for sale ,...." />

                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-lg-6 HideUnwantedSectionsInPlot HideUnwantedSectionsInPg">
                                                <div class="relative z-0 w-full mb-3 group ">
                                                    <input form="propertyFrom" name="unit_info" type="text"
                                                        autocomplete="off" id="unit-info"
                                                        value="{{ isset($property) && $property->unit_info ? $property->unit_info : '' }}"
                                                        class="block px-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                                                    <label for="unit-info"
                                                        class="absolute fs-3 text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                                        Flat/Villa/Building No</label>
                                                </div>
                                            </div>


                                            <div id="room-section"
                                                class="HideUnwantedSectionsInPlot HideUnwantedSectionsInPg HideUnwantedSectionsInCommercial">
                                                <h5 class="my-4 fs-3 text-black font-bold">Room Details</h5>
                                                <div class="my-3 card bg-body p-3">
                                                    <!-- Bedrooms Section -->
                                                    <div class="section" x-data="addOtherBedrooms()">

                                                        <h6 class="mt-3">No. of Bedrooms<sup
                                                                class="text-danger fs-4">*</sup>
                                                        </h6>
                                                        <div class="mt-3">
                                                            <ul class="flex flex-wrap gap-3">
                                                                @for ($i = 1; $i < 5; $i++)
                                                                    <li class="relative mb-1">
                                                                        <input form="propertyFrom" class="sr-only peer"
                                                                            {{-- @if ($i == 1) checked @endif --}}
                                                                            @if ($i == $property->number_bedroom) checked @endif
                                                                            type="radio" value="{{ $i }}"
                                                                            name="room" id="room_{{ $i }}">
                                                                        <label
                                                                            class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                                            for="room_{{ $i }}">{{ $i }}</label>
                                                                    </li>
                                                                @endfor
                                                                @if ($property->number_bedroom > 4)
                                                                    <li class="relative mb-1">
                                                                        <input form="propertyFrom" class="sr-only peer"
                                                                            checked type="radio"
                                                                            value="{{ $property->number_bedroom }}"
                                                                            name="room"
                                                                            id="room_{{ $property->number_bedroom }}">
                                                                        <label
                                                                            class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                                            for="room_{{ $property->number_bedroom }}">{{ $property->number_bedroom }}</label>
                                                                    </li>
                                                                @endif
                                                                <li class="relative mb-1">
                                                                    <template x-if="!showInput && !addedOption">
                                                                        <label @click="showInput = true"
                                                                            class="mx-1 px-3 flex items-center py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="16" height="16"
                                                                                fill="currentColor" class="bi bi-plus"
                                                                                viewBox="0 0 16 16">
                                                                                <path
                                                                                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                                                            </svg>
                                                                            Add Other
                                                                        </label>
                                                                    </template>
                                                                    <template x-if="showInput">
                                                                        <div class="flex items-center gap-2">
                                                                            <input type="number" form="propertyFrom"
                                                                                x-model="newOption"
                                                                                placeholder="Enter value"
                                                                                class="border border-gray-300 p-2 rounded-md w-20 text-sm focus:ring-blue-500 focus:border-blue-500">
                                                                            <button @click="addOption"
                                                                                class="px-3 py-1 bg-green-500 text-white text-sm rounded-md hover:bg-green-600">Done</button>
                                                                        </div>
                                                                    </template>
                                                                    <template x-if="addedOption">
                                                                        <div class="flex items-center gap-2 relative mb-1">
                                                                            <input class="sr-only peer"
                                                                                form="propertyFrom" type="radio"
                                                                                x-bind:value="addedOption"
                                                                                name="room" id="room_other" checked>
                                                                            <label x-text="addedOption"
                                                                                class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                                                for="room_other"></label>
                                                                            <span class="text-blue-500 cursor-pointer"
                                                                                @click="editOption"><i
                                                                                    class="fas fa-edit"></i></span>
                                                                        </div>
                                                                    </template>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>


                                                    <!-- Bathrooms Section -->
                                                    <div class="section" x-data="addOtherBathrooms()">
                                                        <h6 class="mt-3">No. of Bathrooms<sup
                                                                class="text-danger fs-4">*</sup>
                                                        </h6>
                                                        <div class="mt-3">
                                                            <ul class="flex flex-wrap gap-3">
                                                                @for ($i = 1; $i < 5; $i++)
                                                                    <li class="relative mb-1">
                                                                        <input form="propertyFrom" class="sr-only peer"
                                                                            @if ($i == $property->number_bathroom) checked @endif
                                                                            type="radio" value="{{ $i }}"
                                                                            name="bathroom"
                                                                            id="bathroom_{{ $i }}">
                                                                        <label
                                                                            class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                                            for="bathroom_{{ $i }}">{{ $i }}</label>
                                                                    </li>
                                                                @endfor
                                                                @if ($property->number_bedroom > 4)
                                                                    <li class="relative mb-1">
                                                                        <input form="propertyFrom" class="sr-only peer"
                                                                            checked type="radio"
                                                                            value="{{ $property->number_bedroom }}"
                                                                            name="bathroom"
                                                                            id="bathroom_{{ $property->number_bedroom }}">
                                                                        <label
                                                                            class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                                            for="bathroom_{{ $property->number_bedroom }}">{{ $property->number_bedroom }}</label>
                                                                    </li>
                                                                @endif

                                                                <li class="relative mb-1">
                                                                    <template x-if="!showInput && !addedOption">
                                                                        <label @click="showInput = true"
                                                                            class="mx-1 px-3 flex items-center py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="16" height="16"
                                                                                fill="currentColor" class="bi bi-plus"
                                                                                viewBox="0 0 16 16">
                                                                                <path
                                                                                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                                                            </svg>
                                                                            Add Other
                                                                        </label>
                                                                    </template>
                                                                    <template x-if="showInput">
                                                                        <div class="flex items-center gap-2">
                                                                            <input type="number" x-model="newOption"
                                                                                placeholder="Enter value"
                                                                                class="border border-gray-300 p-2 rounded-md w-20 text-sm focus:ring-blue-500 focus:border-blue-500">
                                                                            <button @click="addOption"
                                                                                class="px-3 py-1 bg-green-500 text-white text-sm rounded-md hover:bg-green-600">Done</button>
                                                                        </div>
                                                                    </template>
                                                                    <template x-if="addedOption">
                                                                        <div class="flex items-center gap-2 relative mb-1">
                                                                            <input form="propertyFrom"
                                                                                class="sr-only peer" type="radio"
                                                                                x-bind:value="addedOption"
                                                                                name="bathroom" id="bathroom_other"
                                                                                checked>
                                                                            <label x-text="addedOption"
                                                                                class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                                                for="bathroom_other"></label>
                                                                            <span class="text-blue-500 cursor-pointer"
                                                                                @click="editOption"><i
                                                                                    class="fas fa-edit"></i></span>
                                                                        </div>
                                                                    </template>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                    <!-- Balconies Section -->
                                                    <div class="section" x-data="addOtherBalconies()">
                                                        <h6 class="mt-3">Balconies<sup class="text-danger fs-4">*</sup>
                                                        </h6>
                                                        <div class="mt-3">
                                                            <ul class="flex flex-wrap gap-3">
                                                                @for ($i = 0; $i < 4; $i++)
                                                                    <li class="relative mb-1">
                                                                        <input form="propertyFrom" class="sr-only peer"
                                                                            @if ($i == $property->balconies) checked @endif
                                                                            type="radio" value="{{ $i }}"
                                                                            name="balconie"
                                                                            id="balconie_{{ $i }}">
                                                                        <label
                                                                            class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                                            for="balconie_{{ $i }}">{{ $i }}</label>
                                                                    </li>
                                                                @endfor
                                                                @if ($property->balconies > 3)
                                                                    <li class="relative mb-1">
                                                                        <input form="propertyFrom" class="sr-only peer"
                                                                            checked type="radio"
                                                                            value="{{ $property->balconies }}"
                                                                            name="balconie"
                                                                            id="balconie_{{ $property->balconies }}">
                                                                        <label
                                                                            class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                                            for="balconie_{{ $property->balconies }}">{{ $property->balconies }}</label>
                                                                    </li>
                                                                @endif

                                                                <li class="relative mb-1">
                                                                    <template x-if="!showInput && !addedOption">
                                                                        <label @click="showInput = true"
                                                                            class="mx-1 px-3 flex items-center py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="16" height="16"
                                                                                fill="currentColor" class="bi bi-plus"
                                                                                viewBox="0 0 16 16">
                                                                                <path
                                                                                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                                                            </svg>
                                                                            Add Other
                                                                        </label>
                                                                    </template>
                                                                    <template x-if="showInput">
                                                                        <div class="flex items-center gap-2">
                                                                            <input type="number" x-model="newOption"
                                                                                placeholder="Enter value"
                                                                                class="border border-gray-300 p-2 rounded-md w-20 text-sm focus:ring-blue-500 focus:border-blue-500">
                                                                            <button @click="addOption"
                                                                                class="px-3 py-1 bg-green-500 text-white text-sm rounded-md hover:bg-green-600">Done</button>
                                                                        </div>
                                                                    </template>
                                                                    <template x-if="addedOption">
                                                                        <div class="flex items-center gap-2 relative mb-1">
                                                                            <input form="propertyFrom"
                                                                                class="sr-only peer" type="radio"
                                                                                x-bind:value="addedOption"
                                                                                name="balconie" id="balconie_other"
                                                                                checked>
                                                                            <label x-text="addedOption"
                                                                                class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                                                for="balconie_other"></label>
                                                                            <span class="text-blue-500 cursor-pointer"
                                                                                @click="editOption"><i
                                                                                    class="fas fa-edit"></i></span>
                                                                        </div>
                                                                    </template>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <!-- Other Rooms Section -->
                                                    <div class="section">
                                                        <h6 class="mt-3">Other Rooms (optional)</h6>
                                                        <ul class="flex flex-wrap gap-3 mt-3">

                                                            <li class="relative">
                                                                <input form="propertyFrom" class="sr-only peer"
                                                                    type="checkbox" id="pooja" name="other_rooms[]"
                                                                    {{ is_array(explode(',', $property->other_rooms)) && in_array('pooja-room', explode(',', $property->other_rooms)) ? 'checked' : '' }}
                                                                    value="pooja-room">
                                                                <label for="pooja"
                                                                    class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent">Pooja
                                                                    Room</label>
                                                            </li>
                                                            <li class="relative">
                                                                <input form="propertyFrom" class="sr-only peer"
                                                                    type="checkbox" id="study" name="other_rooms[]"
                                                                    {{ is_array(explode(',', $property->other_rooms)) && in_array('pooja-room', explode(',', $property->other_rooms)) ? 'checked' : '' }}
                                                                    value="study-room">
                                                                <label for="study"
                                                                    class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent">Study
                                                                    Room</label>
                                                            </li>
                                                            <li class="relative">
                                                                <input form="propertyFrom" class="sr-only peer"
                                                                    type="checkbox" id="servent" name="other_rooms[]"
                                                                    {{ is_array(explode(',', $property->other_rooms)) && in_array('pooja-room', explode(',', $property->other_rooms)) ? 'checked' : '' }}
                                                                    value="servent-room">
                                                                <label for="servent"
                                                                    class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent">Servent
                                                                    Room</label>
                                                            </li>
                                                            <li class="relative">
                                                                <input form="propertyFrom" class="sr-only peer"
                                                                    type="checkbox" id="store" name="other_rooms[]"
                                                                    {{ is_array(explode(',', $property->other_rooms)) && in_array('pooja-room', explode(',', $property->other_rooms)) ? 'checked' : '' }}
                                                                    value="store-room">
                                                                <label for="store"
                                                                    class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent">Store
                                                                    Room</label>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="inside-section"
                                                class="HideUnwantedSectionsInPlot HideUnwantedSectionsInPg  ShowWantedSectionsInCommercial">
                                                <h5 class="mt-3 fs-3 text-black font-bold">Inside Details</h5>
                                                <div class="my-3 card bg-body p-3">
                                                    <div class="row">
                                                        <div
                                                            class="col-lg-12 HideUnwantedSectionsInPlot HideUnwantedSectionsInPg">
                                                            <div class="relative z-0 w-full mb-3 group ">
                                                                <input form="propertyFrom" name="pantry" type="text"
                                                                    autocomplete="off" id="pantry"
                                                                    value="{{ $property->pantry }}"
                                                                    class="block px-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                                                                <label for="pantry"
                                                                    class="absolute fs-3 text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                                                    Pantry</label>
                                                            </div>
                                                        </div>
                                                        <div class="mb-2 col-lg-6">
                                                            <div class="mb-2 flex gap-3 mt-3 justify-content-between ">
                                                                <label for="city"
                                                                    class="block mb-2 text-sm font-medium text-gray-500">
                                                                    Washroom
                                                                </label>
                                                                <div x-data="{ count: {{ $property->washroom }} }"
                                                                    class="flex items-center space-x-2">
                                                                    <!-- Minus Button -->
                                                                    <button @click="if (count > 0) count--"
                                                                        class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                        -
                                                                    </button>

                                                                    <!-- Display Counter -->
                                                                    <span class="text-md font-bold" x-text="count"></span>
                                                                    <input type="hidden" form="propertyFrom"
                                                                        :value="count" name="washroom">
                                                                    <!-- Plus Button -->
                                                                    <button @click="count++"
                                                                        class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                        +
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="mb-2 col-lg-6">
                                                            <div class="mb-2 flex gap-3 mt-3 justify-content-between ">
                                                                <label for="city"
                                                                    class="block mb-2 text-sm font-medium text-gray-500">
                                                                    Cabin
                                                                </label>
                                                                <div x-data="{ count: {{ $property->cabin }} }"
                                                                    class="flex items-center space-x-2">
                                                                    <!-- Minus Button -->
                                                                    <button @click="if (count > 0) count--"
                                                                        class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                        -
                                                                    </button>

                                                                    <!-- Display Counter -->
                                                                    <span class="text-md font-bold" x-text="count"></span>
                                                                    <input type="hidden" form="propertyFrom"
                                                                        :value="count" name="cabin">
                                                                    <!-- Plus Button -->
                                                                    <button @click="count++"
                                                                        class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                        +
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="mb-2 col-lg-6">
                                                            <div class="mb-2 flex gap-3 mt-3 justify-content-between ">
                                                                <label for="city"
                                                                    class="block mb-2 text-sm font-medium text-gray-500">
                                                                    Seats
                                                                </label>
                                                                <div x-data="{ count: {{ $property->seats }} }"
                                                                    class="flex items-center space-x-2">
                                                                    <!-- Minus Button -->
                                                                    <button @click="if (count > 0) count--"
                                                                        class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                        -
                                                                    </button>

                                                                    <!-- Display Counter -->
                                                                    <span class="text-md font-bold" x-text="count"></span>
                                                                    <input type="hidden" form="propertyFrom"
                                                                        :value="count" name="seats">
                                                                    <!-- Plus Button -->
                                                                    <button @click="count++"
                                                                        class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                        +
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="mb-2 col-lg-6">
                                                            <div class="mb-2 flex gap-3 mt-3 justify-content-between ">
                                                                <label for="city"
                                                                    class="block mb-2 text-sm font-medium text-gray-500">
                                                                    Units on Floor
                                                                </label>
                                                                <div x-data="{ count: {{ $property->units_on_floor }} }"
                                                                    class="flex items-center space-x-2">
                                                                    <!-- Minus Button -->
                                                                    <button @click="if (count > 0) count--"
                                                                        class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                        -
                                                                    </button>

                                                                    <!-- Display Counter -->
                                                                    <span class="text-md font-bold" x-text="count"></span>
                                                                    <input type="hidden" form="propertyFrom"
                                                                        :value="count" name="units_on_floor">
                                                                    <!-- Plus Button -->
                                                                    <button @click="count++"
                                                                        class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                        +
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        {{-- //////////////////////////////////////////////// --}}

                                                        <div class="mb-2 col-lg-6">
                                                            <div class="mb-2 flex gap-3 mt-3 justify-content-between ">
                                                                <label for="city"
                                                                    class="block mb-2 text-sm font-medium text-gray-500">
                                                                    AC’s
                                                                </label>
                                                                <div x-data="{ count: {{ $property->ac_count }} }"
                                                                    class="flex items-center space-x-2">
                                                                    <!-- Minus Button -->
                                                                    <button @click="if (count > 0) count--"
                                                                        class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                        -
                                                                    </button>

                                                                    <!-- Display Counter -->
                                                                    <span class="text-md font-bold" x-text="count"></span>
                                                                    <input type="hidden" form="propertyFrom"
                                                                        :value="count" name="ac_count">
                                                                    <!-- Plus Button -->
                                                                    <button @click="count++"
                                                                        class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                        +
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="mb-2 col-lg-6">
                                                            <div class="mb-2 flex gap-3 mt-3 justify-content-between ">
                                                                <label for="city"
                                                                    class="block mb-2 text-sm font-medium text-gray-500">
                                                                    Fans
                                                                </label>
                                                                <div x-data="{ count: {{ $property->fans_count }} }"
                                                                    class="flex items-center space-x-2">
                                                                    <!-- Minus Button -->
                                                                    <button @click="if (count > 0) count--"
                                                                        class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                        -
                                                                    </button>

                                                                    <!-- Display Counter -->
                                                                    <span class="text-md font-bold" x-text="count"></span>
                                                                    <input type="hidden" form="propertyFrom"
                                                                        :value="count" name="fans_count">
                                                                    <!-- Plus Button -->
                                                                    <button @click="count++"
                                                                        class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                        +
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="mb-2 col-lg-6">
                                                            <div class="mb-2 flex gap-3 mt-3 justify-content-between ">
                                                                <label for="city"
                                                                    class="block mb-2 text-sm font-medium text-gray-500">
                                                                    Work Stations
                                                                </label>
                                                                <div x-data="{ count: {{ $property->work_stations }} }"
                                                                    class="flex items-center space-x-2">
                                                                    <!-- Minus Button -->
                                                                    <button @click="if (count > 0) count--"
                                                                        class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                        -
                                                                    </button>

                                                                    <!-- Display Counter -->
                                                                    <span class="text-md font-bold" x-text="count"></span>
                                                                    <input type="hidden" form="propertyFrom"
                                                                        :value="count" name="work_stations">
                                                                    <!-- Plus Button -->
                                                                    <button @click="count++"
                                                                        class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                        +
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="mb-2 col-lg-6">
                                                            <div class="mb-2 flex gap-3 mt-3 justify-content-between ">
                                                                <label for="city"
                                                                    class="block mb-2 text-sm font-medium text-gray-500">
                                                                    Chairs
                                                                </label>
                                                                <div x-data="{ count: {{ $property->chairs_count }} }"
                                                                    class="flex items-center space-x-2">
                                                                    <!-- Minus Button -->
                                                                    <button @click="if (count > 0) count--"
                                                                        class="border fw-bold px-2 rounded rounded-5 text-theme">
                                                                        -
                                                                    </button>

                                                                    <!-- Display Counter -->
                                                                    <span class="text-md font-bold" x-text="count"></span>
                                                                    <input type="hidden" form="propertyFrom"
                                                                        :value="count" name="chairs_count">
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
                                            <div id="floor-section"
                                                class="HideUnwantedSectionsInPlot HideUnwantedSectionsInPg ">
                                                <div class="section">
                                                    <h5 class="my-4 fs-3 text-black font-bold">Floor Details</h5>
                                                    <h6 class="mb-3 font-medium">Total no of floors and your floor
                                                        details<sup class="text-danger fs-4">*</sup> </h6>

                                                    <div class="grid grid-cols-1 md:grid-cols-5 lg:grid-cols-2 gap-2">

                                                        <!-- Total Floor -->
                                                        <div class="mb-2">
                                                            <div class="relative">
                                                                <input form="propertyFrom" name="total_floor"
                                                                    type="number"
                                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                                    autocomplete="off" id="total_floor"
                                                                    value="{{ $property->number_floor ?? 0 }}"
                                                                    class="bg-gray-50 text-dark border border-gray-300 text-sm rounded-s-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 peer"
                                                                    placeholder=" " />
                                                                <label for="total_floor"
                                                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Total
                                                                    Floor<sup class="text-danger fs-4">*</sup></label>
                                                            </div>
                                                        </div>

                                                        <!-- Available Floor -->
                                                        <div class="mb-2">
                                                            <div class="relative">
                                                                <input form="propertyFrom" name="available_floor"
                                                                    autocomplete="off" type="number"
                                                                    id="available_floor"
                                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                                    value="{{ $property->available_floor ?? 0 }}"
                                                                    class="bg-gray-50 text-dark border border-gray-300 text-sm rounded-s-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 peer"
                                                                    placeholder=" " />
                                                                <label for="available_floor"
                                                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Available
                                                                    Floor<sup class="text-danger fs-4">*</sup></label>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div id="property-area-section ">
                                                <div class="section">
                                                    <h5 class="my-4 fs-3 text-black font-bold HideUnwantedSectionsInPg">
                                                        Area Details
                                                    </h5>
                                                    <div class="mt-3">
                                                        <div class="ShowWantedSectionsInPlot HideUnwantedSectionsInPg"
                                                            style="display: none">
                                                            <div class="row">
                                                                <!-- Plot Area Input -->
                                                                <div class="mb-2 col-lg-6">
                                                                    <label for="" class="mt-3 font-medium mb-2 ">
                                                                        Plot Area </label>
                                                                    <div class="relative">
                                                                        <input form="propertyFrom" name="plot_area"
                                                                            autocomplete="off" type="text"
                                                                            id="plot_area"
                                                                            value="{{ $property->plot_area }}"
                                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                                            class="bg-gray-50 text-dark border border-gray-300 text-sm rounded-s-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 peer"
                                                                            placeholder=" " />

                                                                        <div
                                                                            class="absolute inset-y-0 end-0 flex items-center pointer-events-none z-20 pe-4">
                                                                            Sq.ft
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-2 col-lg-6">
                                                                    <div class="mb-2 ">
                                                                        <div class="relative">
                                                                            <label for="plot_type"
                                                                                class="mt-3 font-medium mb-2 ">
                                                                                Plot Type </label>
                                                                            <select form="propertyFrom" name="plot_type"
                                                                                id="plot_type"
                                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                                                                <option value="" selected>None of the
                                                                                    below
                                                                                </option>
                                                                                <option class="ResidentialAllowed"
                                                                                    {{ $property->plot_type == 'Independent plots' ? 'selected' : '' }}
                                                                                    value="Independent plots">Independent
                                                                                    plots</option>
                                                                                <option class="ResidentialAllowed"
                                                                                    {{ $property->plot_type == 'Villa,Bungalow,Row houses' ? 'selected' : '' }}
                                                                                    value="Villa,Bungalow,Row houses">
                                                                                    Villa,Bungalow,Row houses</option>
                                                                                <option class="ResidentialAllowed"
                                                                                    {{ $property->plot_type == 'Builder Floor appartments' ? 'selected' : '' }}
                                                                                    value="Builder Floor appartments">
                                                                                    Builder Floor appartments</option>

                                                                                <option class="CommercialAllowed"
                                                                                    {{ $property->plot_type == 'Agricultural/Farm Land' ? 'selected' : '' }}
                                                                                    value="Agricultural/Farm Land">
                                                                                    Agricultural/Farm Land</option>
                                                                                <option class="CommercialAllowed"
                                                                                    {{ $property->plot_type == 'WareHouse plots' ? 'selected' : '' }}
                                                                                    value="Warehouse Plots">WareHouse Plots
                                                                                </option>
                                                                                <option class="CommercialAllowed"
                                                                                    {{ $property->plot_type == 'Industrial Spaces' ? 'selected' : '' }}
                                                                                    value="Industrial Spaces">Industrial
                                                                                    Spaces</option>
                                                                                <option class="CommercialAllowed"
                                                                                    {{ $property->plot_type == 'Retail Plots' ? 'selected' : '' }}
                                                                                    value="Retail Plots">Retail Plots
                                                                                </option>
                                                                                <option class="CommercialAllowed"
                                                                                    {{ $property->plot_type == 'Corporate Plot' ? 'selected' : '' }}
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
                                                                        <div x-data="{ count: `{{ $property->open_sides }}` }"
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
                                                                            value="{{ $property->carpet_area ?? 0 }}"
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
                                                                            value="{{ $property->built_up_area ?? 0 }}"
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
                                                                            value="{{ $property->square ?? 0 }}"
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
                                                    <div class="mt-3">
                                                        <div id="parking" class="mt-3 ">
                                                            <div
                                                                class="HideUnwantedSectionsInPlot HideUnwantedSectionsInPg HideUnwantedSectionsInPg">
                                                                <h5 class="my-4 fs-3 text-black font-bold">Reserved Parking
                                                                </h5>
                                                                <div class="mb-2 col-lg-6">
                                                                    <div
                                                                        class="mb-2 flex gap-3 mt-3 justify-content-between">
                                                                        <label for="city"
                                                                            class="block mb-2 text-sm font-medium text-gray-500">Covered
                                                                            Parking</label>
                                                                        <div x-data="{ count: `{{ $property->covered_parking }}` }"
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
                                                                        class="mb-2 flex gap-3 mt-3 justify-content-between">
                                                                        <label for="city"
                                                                            class="block mb-2 text-sm font-medium text-gray-500">Open
                                                                            Parking</label>
                                                                        <div x-data="{ count: `{{ $property->open_parking }}` }"
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

                                                    <div id="availabiltyStaus" x-data="{ propertyType: '{{ $property->construction_status }}', propertyStatus: '{{ $property->property_age }}', possessionDate: '{{ $property->possession }}' }"
                                                        class="mb-5 mx-2 HideUnwantedSectionsInPlot HideUnwantedSectionsInPg">
                                                        <!-- Availability Status -->
                                                        <div class="col-lg-12 mb-3">
                                                            <h5 class="my-4 fs-3 text-black font-bold">Availability
                                                                Status<sup class="text-danger fs-4">*</sup></h5>
                                                        </div>

                                                        <!-- Radio Buttons for Availability -->
                                                        <div class="flex flex-wrap mb-4">
                                                            <!-- Ready to Move -->
                                                            <div class="flex items-center me-4">
                                                                <input form="propertyFrom" role="button"
                                                                    id="readyto-radio" type="radio"
                                                                    value="ready-to-move" name="available_status"
                                                                    x-model="propertyType"
                                                                    class="w-4 h-4 text-green-600 bg-gray-100 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                <label for="readyto-radio"
                                                                    class="ms-2 text-sm font-medium text-gray dark:text-gray">Ready
                                                                    to move</label>
                                                            </div>

                                                            <!-- Under Construction -->
                                                            <div class="flex items-center me-4">
                                                                <input form="propertyFrom" role="button"
                                                                    id="under-radio" type="radio"
                                                                    value="under-construction" name="available_status"
                                                                    x-model="propertyType"
                                                                    class="w-4 h-4 text-green-600 bg-gray-100 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                <label for="under-radio"
                                                                    class="ms-2 text-sm font-medium text-gray dark:text-gray">Under
                                                                    construction</label>
                                                            </div>
                                                        </div>

                                                        <!-- Conditionally Display Content for 'Ready to Move' -->
                                                        <div x-show="propertyType === 'ready-to-move'" class="mt-3">
                                                            <!-- Age of Property Options -->
                                                            <div class="mt-4">
                                                                <h6 class="font-medium">Age of Property:</h6>
                                                                <div
                                                                    class="flex gap-2 justify-content-between flex-wrap  mt-4">
                                                                    <div class="flex items-center">
                                                                        <input form="propertyFrom" checked type="radio"
                                                                            value="0-1" id="age-0-1"
                                                                            name="property_age" x-model="propertyStatus"
                                                                            class="w-4 h-4 text-green-600 bg-gray-100 focus:ring-green-500 dark:focus:ring-green-600">
                                                                        <label role="button" for="age-0-1"
                                                                            class="ms-2 text-sm font-medium text-gray dark:text-gray">0-1
                                                                            Year</label>
                                                                    </div>
                                                                    <div class="flex items-center">
                                                                        <input form="propertyFrom" type="radio"
                                                                            id="age-1-5" value="1-5"
                                                                            name="property_age" x-model="propertyStatus"
                                                                            class="w-4 h-4 text-green-600 bg-gray-100 focus:ring-green-500 dark:focus:ring-green-600">
                                                                        <label role="button" for="age-1-5"
                                                                            class="ms-2 text-sm font-medium text-gray dark:text-gray">1-5
                                                                            Years</label>
                                                                    </div>
                                                                    <div class="flex items-center">
                                                                        <input form="propertyFrom" type="radio"
                                                                            id="age-5-10" name="property_age"
                                                                            value="5-10" x-model="propertyStatus"
                                                                            class="w-4 h-4 text-green-600 bg-gray-100 focus:ring-green-500 dark:focus:ring-green-600">
                                                                        <label role="button" for="age-5-10"
                                                                            class="ms-2 text-sm font-medium text-gray dark:text-gray">5-10
                                                                            Years</label>
                                                                    </div>
                                                                    <div class="flex items-center">
                                                                        <input form="propertyFrom" value="10+"
                                                                            type="radio" id="age-10-plus"
                                                                            name="property_age" x-model="propertyStatus"
                                                                            class="w-4 h-4 text-green-600 bg-gray-100 focus:ring-green-500 dark:focus:ring-green-600">
                                                                        <label role="button" for="age-10-plus"
                                                                            class="ms-2 text-sm font-medium text-gray dark:text-gray">10+
                                                                            Years</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Conditionally Display Content for 'Under Construction' -->
                                                        <div x-show="propertyType === 'under-construction'"
                                                            class="mt-3">

                                                            <!-- Possession Date Field -->
                                                            <div class="mt-4">
                                                                <h6 class="font-medium">Possession By:</h6>
                                                                <div class="flex items-center gap-3 mt-4 mb-3">
                                                                    <input form="propertyFrom" name="possession"
                                                                        type="month" x-model="possessionDate"
                                                                        min="{{ date('Y-m') }}"
                                                                        max="{{ date('Y-m', strtotime(date('Y-m') . ' + 10 years')) }}"
                                                                        class="w-40 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500">
                                                                </div>
                                                                <span class="text-sm text-gray">Select the month and year
                                                                    when
                                                                    possession will be available.</span>

                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div id="pgRules"
                                                        class="ShowWantedSectionInPg HideUnwantedSectionsInPlot"
                                                        style="display: none">

                                                        <!-- Occupancy -->
                                                        <div class="col-lg-12 mb-3">
                                                            <h5 class="my-4 fs-3 text-black font-bold">Occupancy Type <sup
                                                                    class="text-danger fs-4">*</sup></h5>
                                                            <div class="flex items-center flex-wrap gap-3">
                                                                <div class="flex items-center">
                                                                    <input type="radio" form="propertyFrom"
                                                                        id="single" name="occupancy_type"
                                                                        value="single"
                                                                        {{ $property->occupancy_type == 'single' ? 'checked' : 'checked' }}
                                                                        class="w-3 h-3 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                                    <label for="single"
                                                                        class="ml-2 text-sm font-medium text-gray-900">Single</label>
                                                                </div>
                                                                <div class="flex items-center">
                                                                    <input type="radio" form="propertyFrom"
                                                                        id="double" name="occupancy_type"
                                                                        value="double"
                                                                        {{ $property->occupancy_type == 'double' ? 'checked' : '' }}
                                                                        class="w-3 h-3 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                                    <label for="double"
                                                                        class="ml-2 text-sm font-medium text-gray-900">Double</label>
                                                                </div>
                                                                <div class="flex items-center">
                                                                    <input type="radio" form="propertyFrom"
                                                                        id="triple" name="occupancy_type"
                                                                        value="triple"
                                                                        {{ $property->occupancy_type == 'triple' ? 'checked' : '' }}
                                                                        class="w-3 h-3 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                                    <label for="triple"
                                                                        class="ml-2 text-sm font-medium text-gray-900">3+
                                                                        more</label>
                                                                </div>
                                                                <div class="flex items-center">
                                                                    <input type="radio" form="propertyFrom"
                                                                        id="capsule" name="occupancy_type"
                                                                        value="capsule"
                                                                        {{ $property->occupancy_type == 'capsule' ? 'checked' : '' }}
                                                                        class="w-3 h-3 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                                    <label for="capsule"
                                                                        class="ml-2 text-sm font-medium text-gray-900">Capsule</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Available For -->
                                                        <div class="col-lg-12 mb-3">
                                                            <h5 class="my-4 fs-3 text-black font-bold">Available For <sup
                                                                    class="text-danger fs-4">*</sup></h5>
                                                            <div class="flex items-center flex-wrap gap-4">
                                                                <div class="flex items-center">
                                                                    <input type="radio" form="propertyFrom"
                                                                        id="male" name="available_for"
                                                                        value="male"
                                                                        {{ $property->available_for == 'male' ? 'checked' : 'checked' }}
                                                                        class="w-3 h-3 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                                    <label for="male"
                                                                        class="ml-2 text-sm font-medium text-gray-900">Boys</label>
                                                                </div>
                                                                <div class="flex items-center">
                                                                    <input type="radio" form="propertyFrom"
                                                                        id="female" name="available_for"
                                                                        value="female"
                                                                        {{ $property->available_for == 'female' ? 'checked' : '' }}
                                                                        class="w-3 h-3 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                                    <label for="female"
                                                                        class="ml-2 text-sm font-medium text-gray-900">Girls</label>
                                                                </div>
                                                                <div class="flex items-center">
                                                                    <input type="radio" form="propertyFrom"
                                                                        id="any" name="available_for"
                                                                        value="any"
                                                                        {{ $property->available_for == 'any' ? 'checked' : '' }}
                                                                        class="w-3 h-3 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                                    <label for="any"
                                                                        class="ml-2 text-sm font-medium text-gray-900">
                                                                        Boys & Girls</label>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <!-- Rules  -->
                                                        <div class="col-lg-12 mb-3 ">
                                                            <h5 class="my-4 fs-3 text-black font-bold">
                                                                Rules & Informations<sup class="text-danger fs-4">*</sup>
                                                            </h5>
                                                        </div>
                                                        <div class="relative col-lg-12 mt-3 card p-3">
                                                            <div class="row">

                                                                @foreach ($pg_rules as $Rkey => $ruleItem)
                                                                    @php
                                                                        $ruleValue = $property->pg_rules
                                                                            ->where('rule_id', $ruleItem->id)
                                                                            ->pluck('value')
                                                                            ->first();

                                                                    @endphp
                                                                    @if ($ruleItem->type == 'text')
                                                                        <div class="relative z-0  mb-3 group col-lg-6">
                                                                            <!-- Text Input -->
                                                                            <input form="propertyFrom" type="hidden"
                                                                                name="rule[{{ $ruleItem->id }}][id]"
                                                                                value="{{ $ruleItem->id }}">
                                                                            <input form="propertyFrom"
                                                                                name="rule[{{ $ruleItem->id }}][value]"
                                                                                type="text" autocomplete="off"
                                                                                id="rule_{{ $ruleItem->id }}"
                                                                                value="{{ $ruleValue ?? '' }}"
                                                                                class="block px-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 pt-3 pb-2 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                                placeholder=" " />
                                                                            <label for="rule_{{ $ruleItem->id }}"
                                                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                                                                {{ $ruleItem->name }}
                                                                            </label>
                                                                        </div>
                                                                    @elseif ($ruleItem->type == 'radio')
                                                                        <div class="relative z-0  mb-3 group col-lg-6">
                                                                            <!-- Radio Buttons for Yes/No -->
                                                                            <h5 class="mb-2 mt-3 font-medium">
                                                                                {{ $ruleItem->name }}</h5>
                                                                            <div class="flex items-center space-x-4">
                                                                                <!-- Yes Option -->
                                                                                <div class="flex items-center"
                                                                                    role="button">
                                                                                    <input form="propertyFrom"
                                                                                        type="hidden"
                                                                                        name="rule[{{ $ruleItem->id }}][id]"
                                                                                        value="{{ $ruleItem->id }}">
                                                                                    <input form="propertyFrom"
                                                                                        id="rule_{{ $Rkey }}_yes"
                                                                                        name="rule[{{ $ruleItem->id }}][value]"
                                                                                        type="radio" value="Yes"
                                                                                        {{ $ruleValue == 'Yes' ? 'checked' : '' }}
                                                                                        class="w-3 h-3 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2" />
                                                                                    <label
                                                                                        for="rule_{{ $Rkey }}_yes"
                                                                                        class="ml-2 text-sm font-medium text-gray-900">
                                                                                        Yes
                                                                                    </label>
                                                                                </div>
                                                                                <!-- No Option -->
                                                                                <div class="flex items-center"
                                                                                    role="button">
                                                                                    <input form="propertyFrom"
                                                                                        id="rule_{{ $Rkey }}_no"
                                                                                        name="rule[{{ $ruleItem->id }}][value]"
                                                                                        type="radio" value="No"
                                                                                        {{ $ruleValue == 'No' ? 'checked' : '' }}
                                                                                        class="w-3 h-3 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2" />
                                                                                    <label
                                                                                        for="rule_{{ $Rkey }}_no"
                                                                                        class="ml-2 text-sm font-medium text-gray-900">
                                                                                        No
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                    @endif
                                                                @endforeach
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class=" col-lg-12">
                                        <div class="mx-2 mb-5">
                                            @php
                                                // $customFieldList = $property->customFields
                                                //     ->map(function ($custom) {
                                                //         return [
                                                //             'selected' => $custom->name,
                                                //             'value' => $custom->value ?? '',
                                                //         ];
                                                //     })
                                                //     ->toArray();
                                            @endphp



                                            <div id="MoreaboutDetails"
                                                class="HideUnwantedSectionsInPlot HideUnwantedSectionsInPg">
                                                <h5 class="my-4 fs-3 text-black font-bold">More about Details</h5>
                                                <div class="mt-3 card p-3 bg-body">
                                                    <div class="col-lg-12 mt-2">
                                                        <div class="row">
                                                            @foreach ($customFields ?? [] as $key => $option_item)
                                                                @php
                                                                    $customFields = $property->customFields
                                                                        ->where('name', $option_item->name)
                                                                        ->first();
                                                                @endphp
                                                                <div
                                                                    class="col-md-6 more-details {{ $option_item->has_rent ? 'ShowWantedSectionInRent' : 'HideUnwantedSectionsInRent' }} {{ $option_item->has_sell ? 'ShowWantedSectionInSell' : 'HideUnwantedSectionsInSell' }} ">
                                                                    <div class="relative z-0  mb-3 group  ">
                                                                        <input form="propertyFrom"
                                                                            name="custom_fields[{{ $key }}][name]"
                                                                            type="hidden"
                                                                            value="{{ $option_item->name }}" />
                                                                        <input form="propertyFrom"
                                                                            name="custom_fields[{{ $key }}][value]"
                                                                            type="text" autocomplete="off"
                                                                            value="{{ $customFields ? $customFields->value : '' }}"
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

                                                    {{-- <div x-data="{
                                                        customFields: [],
                                                        addField() {
                                                            this.customFields.push({ selected: '', value: '' });
                                                        },
                                                        removeField(index) {
                                                            if (this.customFields.length > 1) {
                                                                this.customFields.splice(index, 1);
                                                            } else {
                                                                alert('At least one field must remain.');
                                                            }
                                                        }
                                                    }">
                                                        @foreach ($customFieldList as $keyCus => $customField)
                                                            <div class="flex items-center gap-2 mb-3"
                                                                id="100{{ $keyCus }}">
                                                                <!-- Select Box -->
                                                                <div class="w-1/2">
                                                                    <select form="propertyFrom"
                                                                        name="custom_fields[100{{ $keyCus }}][name]"
                                                                        class="w-full p-2 border rounded-md">
                                                                        <option value="">Select Option
                                                                        </option>
                                                                        @foreach ($customFields ?? [] as $option_item)
                                                                            <option
                                                                                {{ $customField['selected'] == $option_item->name ? 'selected' : '' }}
                                                                                value="{{ $option_item->name }}">
                                                                                {{ $option_item->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <!-- Input Box -->
                                                                <div class="w-1/2">
                                                                    <input type="text" form="propertyFrom"
                                                                        autocomplete="off"
                                                                        value="{{ $customField['value'] }}"
                                                                        name="custom_fields[100{{ $keyCus }}][value]"
                                                                        class="w-full p-2 border rounded-md"
                                                                        placeholder="Enter value">
                                                                </div>

                                                                <!-- Remove Button -->
                                                                <div class="position-absolute right-2">
                                                                    <button
                                                                        @click="removeFieldParentDiv(100{{ $keyCus }})"
                                                                        class="text-red-500 hover:text-red-700">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="20" height="20"
                                                                            fill="currentColor" class="bi bi-x-circle"
                                                                            viewBox="0 0 16 16">
                                                                            <path
                                                                                d="M11.742 4.742a1 1 0 1 0-1.414-1.414L8 6.586 5.672 4.258a1 1 0 1 0-1.414 1.414L6.586 8l-2.328 2.328a1 1 0 1 0 1.414 1.414L8 9.414l2.328 2.328a1 1 0 1 0 1.414-1.414L9.414 8l2.328-2.328z" />
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                        <!-- Dynamic Custom Fields -->
                                                        <template x-for="(field, index) in customFields"
                                                            :key="index">
                                                            <div class="flex items-center gap-2 mb-3">
                                                                <!-- Select Box -->
                                                                <div class="w-1/2">
                                                                    <select form="propertyFrom"
                                                                        :name="'custom_fields[' + index + '][name]'"
                                                                        x-model="field.selected"
                                                                        class="w-full p-2 border rounded-md">
                                                                        <option value="">Select Option
                                                                        </option>
                                                                        @foreach ($customFields ?? [] as $option_item)
                                                                            <option value="{{ $option_item->name }}">
                                                                                {{ $option_item->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <!-- Input Box -->
                                                                <div class="w-1/2">
                                                                    <input type="text" form="propertyFrom"
                                                                        autocomplete="off"
                                                                        :name="'custom_fields[' + index + '][value]'"
                                                                        x-model="field.value"
                                                                        class="w-full p-2 border rounded-md"
                                                                        placeholder="Enter value">
                                                                </div>

                                                                <!-- Remove Button -->
                                                                <div class="position-absolute right-2">
                                                                    <button @click="removeField(index)"
                                                                        class="text-red-500 hover:text-red-700">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="20" height="20"
                                                                            fill="currentColor" class="bi bi-x-circle"
                                                                            viewBox="0 0 16 16">
                                                                            <path
                                                                                d="M11.742 4.742a1 1 0 1 0-1.414-1.414L8 6.586 5.672 4.258a1 1 0 1 0-1.414 1.414L6.586 8l-2.328 2.328a1 1 0 1 0 1.414 1.414L8 9.414l2.328 2.328a1 1 0 1 0 1.414-1.414L9.414 8l2.328-2.328z" />
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </template>

                                                        <div class="mt-4 text-end">
                                                            <!-- Add More Button -->
                                                            <button @click="addField"
                                                                class="bg-gray-500 px-2 py-1 rounded-2xl text-dark text-sm">
                                                                <i class="fa fa-plus me-2"></i> Add More
                                                            </button>
                                                        </div>
                                                    </div> --}}
                                                </div>
                                            </div>

                                            <div x-data="{ furnishingStatus: '{{ $property->furnishing_status }}' }" class="mb-5 mx-2">
                                                <div class="section">
                                                    <div id="furnishing" class="HideUnwantedSectionsInPlot">
                                                        <h5 class="my-4 fs-3 text-black font-bold">Furnishing Details</h5>

                                                        <div class="mt-3 ">

                                                            <!-- Radio Buttons for Furnishing Status -->
                                                            <div class="flex flex-wrap">
                                                                <div class="flex items-center me-4 mb-2">
                                                                    <input form="propertyFrom" type="radio"   onclick="selectAllFurnishingItems('unfurnished')"
                                                                        value="unfurnished" name="furnishing_status"
                                                                        x-model="furnishingStatus"
                                                                        id="unfurnished-radio"
                                                                        class="w-4 h-4 text-green-600 bg-gray-100 focus:ring-green-500 dark:focus:ring-green-600">
                                                                    <label for="unfurnished-radio"
                                                                        class="ms-2 text-sm font-medium text-gray dark:text-gray">Unfurnished</label>
                                                                </div>
                                                                <div class="flex items-center me-4 mb-2">
                                                                    <input form="propertyFrom" type="radio"  onclick="selectAllFurnishingItems('semi-furnished')"
                                                                        value="semi-furnished" name="furnishing_status"
                                                                        x-model="furnishingStatus"
                                                                        id="semi-furnished-radio"
                                                                        class="w-4 h-4 text-green-600 bg-gray-100 focus:ring-green-500 dark:focus:ring-green-600">
                                                                    <label for="semi-furnished-radio"
                                                                        class="ms-2 text-sm font-medium text-gray dark:text-gray">Semi-Furnished</label>
                                                                </div>
                                                                <div class="flex items-center me-4 mb-2">
                                                                    <input form="propertyFrom" type="radio" onclick="selectAllFurnishingItems('furnished')"
                                                                        value="furnished" name="furnishing_status"
                                                                        x-model="furnishingStatus" id="furnished-radio"
                                                                        class="w-4 h-4 text-green-600 bg-gray-100 focus:ring-green-500 dark:focus:ring-green-600">
                                                                    <label for="furnished-radio"
                                                                        class="ms-2 text-sm font-medium text-gray dark:text-gray">Furnished</label>
                                                                </div>

                                                            </div>

                                                            <!-- Conditionally Display Content for Furnishing Status -->
                                                            <div x-show="furnishingStatus === 'furnished' || furnishingStatus === 'semi-furnished'"
                                                                class="mt-2 border-top bg-body card p-3">
                                                                <div class="row mt-3 ">
                                                                    @foreach ($furnishing ?? [] as $key_0 => $furnish_items)
                                                                        <div class="col-lg-4">
                                                                            <div class="flex items-center mb-4">
                                                                                <input name="furnishing[]"
                                                                                    type="checkbox"
                                                                                    @if (in_array($furnish_items->id, $property->furnishing->pluck('id')->toArray())) checked @endif
                                                                                    value="{{ $furnish_items->id }}"
                                                                                    id="furnish_checkbox_{{ $key_0 }}"
                                                                                    form="propertyFrom"
                                                                                    class="w-3 h-3 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                                                                <label
                                                                                    for="furnish_checkbox_{{ $key_0 }}"
                                                                                    class="ms-2 text-sm text-capitalize font-medium text-dark d-flex gap-2">
                                                                                    <img src="{{ $furnish_items->image_url }}"
                                                                                        class="w-4 h-4">
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
                                                    <h5 class="my-4 fs-3 text-black font-bold">Amenities</h5>
                                                    <div class="mt-3 card bg-body p-3">

                                                        <div class="row mt-3">
                                                            @foreach ($features ?? [] as $feature_item)
                                                                <div class="col-lg-4">
                                                                    <div class="flex items-center  mb-4">
                                                                        <input name="amenities[]" form="propertyFrom"
                                                                            type="checkbox"
                                                                            value="{{ $feature_item->id }}"
                                                                            @if (in_array($feature_item->id, $property->features->pluck('id')->toArray())) checked @endif
                                                                            id="amenity_{{ $feature_item->id }}"
                                                                            class="w-3 h-3 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                                                        <label for="amenity_{{ $feature_item->id }}"
                                                                            class="ms-2 text-sm font-medium text-dark d-flex gap-2">
                                                                            <img src="{{ $feature_item->image_url }}"
                                                                                class="w-4 h-4">
                                                                            {{ $feature_item->name }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    @php
                                                        $facilitiesList = $property->facilities
                                                            ->map(function ($facility) {
                                                                return [
                                                                    'id' => $facility->id,
                                                                    'distance' => $facility->pivot->distance ?? '',
                                                                ];
                                                            })
                                                            ->toArray();

                                                    @endphp


                                                    <h5 class="my-4 fs-3 text-black font-bold">Nearest Landmarks</h5>
                                                    <div class="mt-3 card bg-body p-3" x-data="{
                                                        facilities: [],
                                                        addFacility() {
                                                            this.facilities.push({ id: '', distance: '' });
                                                        },
                                                        removeFacility(index) {
                                                            {{-- if (this.facilities.length > 1) { --}}
                                                                this.facilities.splice(index, 1);
                                                            {{-- } --}}
                                                        }
                                                    }">
                                                        @foreach ($facilitiesList as $keyFac => $exFacility)
                                                            <div class="col-lg-12 items-center  mb-4"
                                                                id="1000{{ $keyFac }}">
                                                                <div class="row">
                                                                    <!-- Facility Select Box -->
                                                                    <div class="col-lg-6 mb-2">
                                                                        <select form="propertyFrom"
                                                                            name="facilities[100{{ $keyFac }}][id]"
                                                                            class="w-full p-2 bg-gray-100 border border-gray-300 rounded-md">
                                                                            <option value="">Select Landmark
                                                                            </option>
                                                                            @foreach ($facilities ?? [] as $facility_item)
                                                                                <option
                                                                                    {{ $exFacility['id'] == $facility_item->id ? 'selected' : '' }}
                                                                                    value="{{ $facility_item->id }}">
                                                                                    {{ $facility_item->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <!-- Custom Facility Input Box -->
                                                                    <div class="col-lg-6 mb-2">
                                                                        <input type="text"
                                                                            name="facilities[100{{ $keyFac }}][distance]"
                                                                            value="{{ $exFacility['distance'] }}"
                                                                            autocomplete="off" form="propertyFrom"
                                                                            class="w-full p-2 mt-1 border border-gray-300 rounded-md"
                                                                            placeholder="Enter custom facility distance">
                                                                    </div>

                                                                </div>
                                                                <!-- Remove Icon Button -->
                                                                <div class="position-absolute right-2">
                                                                    <button
                                                                        @click="removeFieldParentDiv(1000{{ $keyFac }})"
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
                                                        @endforeach


                                                        <!-- Dynamic Facility List -->
                                                        <template x-for="(facility, index) in facilities"
                                                            :key="index">
                                                            <div class="col-lg-12 items-center mb-4">
                                                                <div class="row">
                                                                    <!-- Facility Select Box -->
                                                                    <div class="col-lg-6 mb-2">
                                                                        <select form="propertyFrom"
                                                                            :name="'facilities[' + index + '][id]'"
                                                                            x-model="facility.id"
                                                                            class="w-full p-2 bg-gray-100 border border-gray-300 rounded-md">
                                                                            <option value="">Select Landmark
                                                                            </option>
                                                                            @foreach ($facilities ?? [] as $facility_item)
                                                                                <option
                                                                                    value="{{ $facility_item->id }}">
                                                                                    {{ $facility_item->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <!-- Custom Facility Input Box -->
                                                                    <div class="col-lg-6 mb-2">
                                                                        <input type="text"
                                                                            :name="'facilities[' + index + '][distance]'"
                                                                            autocomplete="off" form="propertyFrom"
                                                                            x-model="facility.distance"
                                                                            class="w-full p-2 mt-1 border border-gray-300 rounded-md"
                                                                            placeholder="Enter custom facility distance">
                                                                    </div>

                                                                </div>
                                                                <!-- Remove Icon Button -->
                                                                <div class="position-absolute right-2">
                                                                    <button @click="removeFacility(index)"
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

                                                        <!-- Add Another Facility Button -->
                                                        <div class="mt-4 text-end">
                                                            <button @click="addFacility"
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

                    <!-- Step 4 -->

                    <div x-show="currentStep === 3" class="space-y-4 mt-2">
                        <div class="mb-5 mx-2">
                            <div class="section">
                                <h5 class="my-4 fs-3 text-black font-bold">Add photos of your property</h5>
                                <h6 class="mt-3 font-medium">
                                    A picture is worth a thousand words. 87% of buyers look at photos before buying</h6>
                                <div class="mt-3 border-dashed border-2 border-gray-300 rounded-lg p-3  bg-gray-100">
                                    <div x-data="imageUploader()" class="mx-auto bg-white shadow rounded-lg space-y-6">
                                        <!-- Image Preview Grid -->
                                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                            @if (is_array($property->images))
                                                @foreach ($property->images ?? [] as $key => $image)
                                                    <div class="flex flex-col relative existing-data-box">
                                                        <div class="relative group border rounded-lg overflow-hidden ">
                                                            <img src="{{ asset('images/' . $image) }}"
                                                                class="thumbnail" style="height: 100px;width: 100%;"
                                                                alt="Uploaded Image">
                                                            <input type="hidden" form="propertyFrom"
                                                                value="{{ $image }}" name="existingImage[]" />
                                                            <button type="button" onclick="removeExistingRow(this)"
                                                                class="absolute bg-white p-1 right-0 top-0 rounded-full">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                                    fill="red" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <label
                                                            class="flex items-center space-x-2 text-dark cursor-pointer">
                                                            <input type="radio" name="coverImage"
                                                                form="propertyFrom" value="{{ $image }}"
                                                                @change="setCoverImage({{ $key + 200 }})"
                                                                {{ $property->cover_image === $image ? 'checked' : '' }}>
                                                            <span>Make Cover Photo</span>
                                                        </label>
                                                        <span x-show="currentCover === {{ $key + 200 }}"
                                                            class="absolute top-0 left-0 p-2 text-white bg-black opacity-50">Cover</span>
                                                    </div>
                                                @endforeach
                                            @endif

                                            <!-- Existing Images -->
                                            <template x-for="(image, index) in images" :key="index">
                                                <div class="flex flex-col relative">
                                                    <div class="relative group border rounded-lg overflow-hidden">
                                                        <!-- Image -->
                                                        <img :src="image.url" style="height: 100px;width: 100%;"
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
                            <div x-data="{ isModalOpen: false }" class="section mt-5">
                                <div
                                    class="block w-full md:w-2/3 lg:w-1/2 mx-auto p-6 mt-3 border-dashed border-2 border-gray-300 rounded-lg bg-gray-100 shadow hover:bg-gray-50">
                                    <h5 class="font-bold text-lg text-gray-700 text-center">Add YouTube Videos of Your
                                        Property</h5>
                                    <h6 class="mt-3 font-medium text-sm text-gray-600 text-center">
                                        A video is worth a thousand pictures. Properties with videos get higher page views.
                                    </h6>

                                    <div class="relative z-0 w-full mb-3 mt-5 group">
                                        <input name="youtube_video" form="propertyFrom"
                                            value="{{ isset($property) && $property->youtube_video ? $property->youtube_video : '' }}"
                                            type="text" autocomplete="off" id="youtube_video"
                                            class="block px-3 w-full text-sm text-gray-900 bg-transparent border rounded-lg py-3 appearance-none focus:outline-none focus:ring focus:ring-blue-200 peer"
                                            placeholder="Paste YouTube link of your video" />
                                        <label for="youtube_video"
                                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 bg-white px-2 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-1/2  peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4">
                                            Paste YouTube link of your video
                                        </label>
                                    </div>
                                    <div class="text-end mt-4">
                                        <button @click="isModalOpen = true" class="text-blue-600 underline"
                                            role="button">
                                            How to?
                                        </button>
                                    </div>
                                </div>

                                <!-- Modal -->
                                <div x-show="isModalOpen" tabindex="-1"
                                    class="fixed top-0 left-0 right-0 z-50 w-full h-full flex justify-center items-center bg-gray-800 bg-opacity-50"
                                    x-cloak>
                                    <div class="relative w-full max-w-md">
                                        <!-- Modal content -->
                                        <div class="bg-white rounded-lg shadow dark:bg-gray-700">

                                            <!-- Modal body -->
                                            <div class="p-4 md:p-5">
                                                <div class="d-flex justify-between">
                                                    <div>
                                                        <h3 class="text-xl font-medium text-dark-900 dark:text-white">How
                                                            to Add YouTube Video</h3>
                                                        <h6 class="text-sm text-gray-600">A step-by-step guide</h6>
                                                    </div>
                                                    <button @click="isModalOpen = false"
                                                        class="text-gray-400 hover:bg-gray-200 rounded-lg text-sm  dark:hover:bg-gray-600">
                                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round"
                                                                stroke-linejoin="round" stroke-width="2"
                                                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                <img src="{{ asset('storage/general/howTo_youtube.png') }}"
                                                    class="w-full rounded-lg mt-5" alt="How To Add YouTube Video">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="section mt-5 d-none" x-data="videoUploader()">
                                <h5 class="font-medium">Add one or more videos of your property</h5>
                                <h6 class="mt-3 font-medium">A video is worth a thousand pictures. Properties with videos
                                    get higher page views</h6>
                                <div class="mt-3 border-dashed border-2 border-gray-300 rounded-lg p-3  bg-gray-100">
                                    <!-- Drag and Drop Area for Video -->
                                    <div class="relative text-center cursor-pointer">
                                        <div class="flex flex-col">
                                            <div class="relative group  rounded-lg p-2 overflow-hidden"
                                                @click="triggerFileInputVideo()"
                                                x-bind:class="{ 'border-blue-500': isDragging }">
                                                <input form="propertyFrom" id="fileInputVideo" name="videos[]"
                                                    type="file" accept="video/*" class="hidden" multiple
                                                    @change="addVideos($event)">
                                                <p class="text-gray-600">Drag & Drop your videos here or click to upload.
                                                </p>
                                                <p class="text-sm text-blue-500 font-medium ">Upload multiple videos</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Error Message Display -->

                                    <!-- Video Preview Grid -->
                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-4">
                                        <template x-for="(video, index) in videos" :key="index">
                                            <div class="flex flex-col relative">
                                                <div class="relative group border rounded-lg overflow-hidden">
                                                    <!-- Video Preview -->
                                                    <video :src="video.url" controls
                                                        class="w-30 h-30 object-cover">
                                                        Your browser does not support the video tag.
                                                    </video>

                                                    <!-- Overlay with Remove Option -->
                                                    <div
                                                        class="absolute flex flex-col inset-0 group-hover:opacity-100 space-y-2 transition">
                                                        <!-- Remove Video -->
                                                        <button @click="removeVideo(index)"
                                                            class="absolute bg-white p-1 right-0 rounded-full top-0">
                                                            <!-- Remove Icon -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                                viewBox="0 0 20 20" fill="red">
                                                                <path fill-rule="evenodd"
                                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                        <div class="flex flex-wrap gap-4">

                                            <!-- Loop Through Videos -->
                                            @foreach ($property->video ?? [] as $key => $video)
                                                <div
                                                    class="relative group border rounded-lg overflow-hidden existing-data-box">
                                                    <!-- Video Preview -->
                                                    <video src="{{ asset('images/' . $video) }}" controls
                                                        class="w-30 h-30 object-cover">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                    <input type="hidden" form="propertyFrom"
                                                        value="{{ $video }}" name="existingVideo[]" />
                                                    <!-- Remove Button -->
                                                    <button type="button" onclick="removeExistingRow(this)"
                                                        class="absolute bg-white p-1 right-0 top-0 rounded-full">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="red" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="mt-5">
                                <h6 class="my-4 fs-3 text-black font-bold">Ownership<sup
                                        class="text-danger fs-4">*</sup></h6>
                                <ul class="flex flex-wrap gap-5 mt-3">
                                    <li class="relative">
                                        <input form="propertyFrom" class="sr-only peer"
                                            @if ($property->ownership == 'freehold') checked @endif type="radio"
                                            value="freehold" name="ownership" id="freehold">
                                        <label for="freehold"
                                            class="mx-1 px-3 py-1 bg-white border rounded-lg cursor-pointer peer-checked:ring-2 peer-checked:ring-green-500">
                                            Freehold
                                        </label>
                                    </li>
                                    <li class="relative">
                                        <input form="propertyFrom" @if ($property->ownership == 'co-operative_society') checked @endif
                                            class="sr-only peer" type="radio" value="co-operative_society"
                                            name="ownership" id="co_operative_society">
                                        <label for="co_operative_society"
                                            class="mx-1 px-3 py-1 bg-white border rounded-lg cursor-pointer peer-checked:ring-2 peer-checked:ring-green-500">
                                            Co-operative Society
                                        </label>
                                    </li>
                                    <li class="relative">
                                        <input form="propertyFrom" @if ($property->ownership == 'power_of_attorney') checked @endif
                                            class="sr-only peer" type="radio" value="power_of_attorney"
                                            name="ownership" id="power_of_attorney">
                                        <label for="power_of_attorney"
                                            class="mx-1 px-3 py-1 bg-white border rounded-lg cursor-pointer peer-checked:ring-2 peer-checked:ring-green-500">
                                            Power of Attorney
                                        </label>
                                    </li>
                                </ul>
                            </div>

                            <div class="mt-5">
                                <h6 class="my-4 fs-3 text-black font-bold">Price Details</h6>
                                <div class="bg-white shadow rounded-lg">
                                    <h5 class="font-medium mb-3">Enter Price<sup class="text-danger fs-4">*</sup></h5>
                                    <input form="propertyFrom" type="number" min="0" id="priceInput"
                                        value="{{ $property->price }}" onchange="convertToWords()"
                                        x-mask:dynamic="$money($input)" class="border p-2 rounded w-1/2"
                                        autocomplete="off" placeholder="Enter price" oninput="convertToWords()"
                                        name="price" />

                                    <p class="mt-4 text-gray-700">Price in words: <span id="priceInWords"></span></p>
                                </div>
                                <div class="mt-3 flex gap-3 flex-wrap">
                                    <label class="flex items-center">
                                        <input name="all_include" form="propertyFrom"
                                            @if ($property->all_include == 1) checked @endif type="checkbox"
                                            class="mr-2">
                                        <span>All inclusive price</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input name="tax_include" form="propertyFrom"
                                            @if ($property->tax_include == 1) checked @endif type="checkbox"
                                            class="mr-2">
                                        <span>Tax and Govt. charges excluded</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input name="negotiable" form="propertyFrom"
                                            @if ($property->negotiable == 1) checked @endif type="checkbox"
                                            class="mr-2">
                                        <span>Price Negotiable</span>
                                    </label>
                                </div>
                            </div>

                            <div class="mt-5">
                                <h6 class="my-4 fs-3 text-black font-bold">What makes your property unique?</h6>
                                <textarea form="propertyFrom" name="unique_info" rows="4" autocomplete="off"
                                    class="block w-full mt-2 p-2 border rounded-lg" placeholder="Write your thoughts here...">{{ $property->content }}</textarea>
                            </div>

                            <div class="mt-5">
                                <div class="mt-3 font-bold text-black fs-3">
                                    <label class="font-bold text-black dark:text-white mb-2"> Keywords <span
                                            class="small text-[#637381]">(if you have multiple words use ' , '
                                            comma)</span> </label>
                                    <textarea form="propertyFrom" rows="4" autocomplete="off" class="block w-full mt-2 p-2 border rounded-lg" cols="3"
                                        placeholder="bangalore central,bengaluru central,..." name="keywords">{{ isset($property) ? $property->keywords : '' }}</textarea>
                                </div>
                            </div>

                            {{-- <div class="mt-5">
                                <h6 class="font-medium">Mark as moderation status <sup class="text-danger fs-4">*</sup></h6>
                                <ul class="flex gap-5 mt-3">
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
                                            Under review 
                                        </label>
                                    </li>
                                </ul>
                            </div> --}}

                            <div class="mt-5">
                                <h6 class="font-medium">Mark as property status <sup class="text-danger fs-4">*</sup>
                                </h6>
                                <ul class="flex gap-5 mt-3">

                                    <li class="relative">
                                        <input form="propertyFrom" class="sr-only peer"
                                            {{ $property->status == 'not_available' ? 'checked' : '' }} type="radio"
                                            value="not_available" name="property_status" id="not_available">
                                        <label for="not_available"
                                            class="mx-1 px-3 py-1 bg-white border rounded-lg cursor-pointer peer-checked:ring-2 peer-checked:ring-green-500">
                                            Not available
                                        </label>
                                    </li>


                                    @if ($property->type == 'sell')
                                        <li
                                            class="relative {{ $property->moderation_status == 'sold' || $property->moderation_status == 'pending' ? 'd-none' : '' }}">
                                            <input form="propertyFrom" class="sr-only peer" checked type="radio"
                                                {{ $property->status == 'selling' ? 'checked' : '' }} value="selling"
                                                name="property_status" id="selling">
                                            <label for="selling"
                                                class="mx-1 px-3 py-1 bg-white border rounded-lg cursor-pointer peer-checked:ring-2 peer-checked:ring-green-500">
                                                Selling
                                            </label>
                                        </li>

                                        <li
                                            class="relative {{ $property->moderation_status == 'pending' ? 'd-none' : '' }}">
                                            <input form="propertyFrom" class="sr-only peer" type="radio"
                                                {{ $property->status == 'sold' ? 'checked' : '' }} value="sold"
                                                name="property_status" id="sold">
                                            <label for="sold"
                                                class="mx-1 px-3 py-1 bg-white border rounded-lg cursor-pointer peer-checked:ring-2 peer-checked:ring-green-500">
                                                Sold
                                            </label>
                                        </li>
                                    @elseif($property->type == 'rent' || $property->type == 'pg')
                                        <li
                                            class="relative {{ $property->moderation_status == 'rented' || $property->moderation_status == 'pending' ? 'd-none' : '' }}">
                                            <input form="propertyFrom" class="sr-only peer" type="radio"
                                                {{ $property->status == 'renting' ? 'checked' : '' }} value="renting"
                                                name="property_status" id="renting">
                                            <label for="renting"
                                                class="mx-1 px-3 py-1 bg-white border rounded-lg cursor-pointer peer-checked:ring-2 peer-checked:ring-green-500">
                                                Renting
                                            </label>
                                        </li>

                                        <li
                                            class="relative {{ $property->moderation_status == 'pending' ? 'd-none' : '' }}">
                                            <input form="propertyFrom" class="sr-only peer" type="radio"
                                                {{ $property->status == 'rented' ? 'checked' : '' }} value="rented"
                                                name="property_status" id="rented">
                                            <label for="rented"
                                                class="mx-1 px-3 py-1 bg-white border rounded-lg cursor-pointer peer-checked:ring-2 peer-checked:ring-green-500">
                                                Rented
                                            </label>
                                        </li>
                                    @endif

                                    @if (
                                        $property->moderation_status == 'not_available' ||
                                            $property->moderation_status == 'draft' ||
                                            $property->moderation_status == 'pending')
                                        <li class="relative">
                                            <input form="propertyFrom" class="sr-only peer" type="radio"
                                                {{ $property->moderation_status == 'pending' ? 'checked' : '' }}
                                                value="pending" name="property_status" id="pending">
                                            <label for="pending"
                                                class="mx-1 px-3 py-1 bg-white border rounded-lg cursor-pointer peer-checked:ring-2 peer-checked:ring-green-500">
                                                {{ $property->moderation_status == 'pending' ? 'Under Review' : 'Submit for review' }}
                                            </label>
                                        </li>
                                    @endif
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
            function removeFieldParentDiv(id) {
                const element = document.getElementById(id);
                if (element) {
                    element.remove();
                } else {
                    console.error(`Element with ID ${id} not found.`);
                }
            }


            function stepper() {
                return {
                    currentStep: 0,
                    steps: [{
                            title: "Basic Details",
                        },
                        {
                            title: "Location & Property Intro",
                        },
                        {
                            title: "Property Profile",
                        },
                        {
                            title: "Images,Videos & Pricing",
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
                        sell: @json($has_sell ?? []),
                        rent: @json($has_rent ?? []),
                        pg: @json($has_pg ?? []),
                    },
                    types: [], // Available types based on mode
                    categories: [], // Available categories based on mode and type
                    currentMode: `{{ isset($property) && $property->type ? $property->type : 'sell' }}`,
                    currentType: `{{ isset($property) && $property->mode ? $property->mode : 'Residential' }}`,
                    currentCategory: `{{ $property->category->id ?? '' }}`, // Ensure a valid ID is provided or an empty string


                    // Initialize on page load
                    init() {
                        this.updateMode(this.currentMode); // Initialize with default mode
                    },
                    // Update mode and filter types & categories
                    updateMode(mode) {
                        this.currentMode = mode;
                        this.types = [];
                        this.categories = [];
                        this.currentType =
                            `{{ isset($property) && $property->mode == 'Commercial' ? 'Commercial' : 'Residential' }}`;

                        // Set types for Sell and Rent modes
                        // if (mode === 'sell' || mode === 'rent') {
                        //     this.types = ['Residential', 'Commercial'];
                        // } else if (mode === 'pg') {
                        //     this.types = ['Residential'];
                        //     ShowWantedSectionInPg();
                        // }

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


                        } else if (mode === 'pg') {
                            this.types = ['Residential'];
                            ShowWantedSectionInPg();
                            this.toggleSections('more-details', 'none');
                            this.toggleSections('ShowWantedSectionInPg', 'block');
                        }


                        // Set categories based on the default type
                        this.updateCategories();
                    },

                    // Update categories based on the type
                    updateCategories() {

                        const modeData = this.modes[this.currentMode];
                        this.categories = modeData.filter(category =>
                            (this.currentType === 'Residential' && category.has_residential) ||
                            (this.currentType === 'Commercial' && category.has_commercial)
                        );

                        // Set the first category as selected by default
                        // if (this.categories.length > 0) {
                        //     this.currentCategory = this.categories[0].id;
                        //     this.selectedCategory(this.categories[0].name);
                        // }

                        // Ensure currentCategory is valid
                        if (this.categories.length > 0) {
                            const matchingCategory = this.categories.find(category => category.id == this.currentCategory);
                            if (!matchingCategory) {
                                this.currentCategory = this.categories[0].id; // Set to the first category if no match

                            }
                            const selectedCategory = this.categories.find(category => category.id == this.currentCategory);
                            if (selectedCategory) {
                                this.selectedCategory(selectedCategory.name);
                            }
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
                        } else if (this.currentMode === 'pg') {
                            ShowWantedSectionInPg();
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
                for (let el2 of document.querySelectorAll('.ShowWantedSectionInPg')) el2.style.display = 'none';
            }

            function ShowWantedSectionInPg() {
                for (let el of document.querySelectorAll('.ShowWantedSectionInPg')) el.style.display = 'block';
                for (let el2 of document.querySelectorAll('.ShowWantedSectionsInPlot')) el2.style.display = 'none';
                for (let el3 of document.querySelectorAll('.HideUnwantedSectionsInPg')) el3.style.display = 'none';
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
                            longitude: '',
                            location_info: ''
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
                        city: `{{ isset($property) && $property->city ? $property->city : '' }}`,
                        location_info: `{{ isset($property) && $property->location ? $property->location : '' }}`,
                        locality: `{{ isset($property) && $property->locality ? $property->locality : '' }}`,
                        sub_locality: `{{ isset($property) && $property->sub_locality ? $property->sub_locality : '' }}`,
                        appartment: `{{ isset($property) && $property->appartment ? $property->appartment : '' }}`,
                        landmark: `{{ isset($property) && $property->landmark ? $property->landmark : '' }}`,
                        latitude: `{{ isset($property) && $property->latitude ? $property->latitude : '' }}`,
                        longitude: `{{ isset($property) && $property->longitude ? $property->longitude : '' }}`,
                    },
                    locationSelected: true,
                    formFilled: true,
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

                // Ensure the input is within the maximum allowed value (₹100 Crore)
                if (num > 999999999) {
                    alert('Price cannot exceed ₹99.9 Crore');
                    document.getElementById('priceInput').value = 999999999; // Set to the maximum value
                    return;
                }

                // Convert the number to words
                const words = numberToWords(parseInt(num));
                document.getElementById('priceInWords').textContent = '₹ ' + (words || 'Zero');
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
                        // Reset validation errors before submitting

                        this.toggleLoader(true);

                        this.validationErrors = [];
                        this.errorMessage = '';
                        this.responseMessage = '';

                        // Reference the form element
                        const formElement = document.getElementById('propertyFrom');
                        const formData = new FormData(formElement);

                        // Serialize form data (key=value&key=value format)
                        // const serializedData = Array.from(formData.entries())
                        //     .map(([key, value]) => `${encodeURIComponent(key)}=${encodeURIComponent(value)}`)
                        //     .join('&');

                        // console.log(serializedData); // Debug: Check serialized data

                        try {
                            const response = await fetch(
                                `{{ route('user.properties.update', $property->id) }}`, {
                                    method: 'POST',
                                    headers: {
                                        // 'Content-Type': 'application/x-www-form-urlencoded', // Important for serialized data
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include Laravel CSRF token
                                    },
                                    body: formData // Use serialized data as request body
                                });

                            // Handle the validation error (422)
                            if (!response.ok) {
                                const errorData = await response.json();
                                this.validationErrors = errorData.errors ||
                            []; // Store the error messages in validationErrors

                                // Show each error message one by one in a toast with a slight delay
                                this.showValidationErrorsOneByOne();

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


                    // Function to display validation errors one by one in a toast
                    showValidationErrorsOneByOne() {
                        this.validationErrors.forEach((error, index) => {
                            setTimeout(() => {
                                this.showToastMessage(error, 'error');
                            }, index * 2000); // 2 seconds delay between each error
                        });
                    },

                    showToastMessage(message, type) {
                        this.toastMessage = message;
                        this.toastType = type;
                        this.showToast = true;
                        setTimeout(() => {
                            this.showToast = false; // Hide toast after 3 seconds
                        }, 3000);
                    }
                };
            }


            document.addEventListener('DOMContentLoaded', function() {
                window.removeExistingRow = function(button) {
                    // Locate the parent .existing-data-box and remove it
                    const row = button.closest('.existing-data-box');
                    if (row) {
                        row.remove(); // Remove the div
                    } else {
                        console.error("Could not find the parent element to remove.");
                    }
                };
            });
        </script>


        <script
            src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&callback=initAutocomplete&libraries=places,geometry&v=weekly"
            defer loading=async></script>
        <script>
            function initAutocomplete() {
                const input = document.getElementById("location");


                // Coordinates for Bangalore center
                const center = {
                    lat: 12.9716,
                    lng: 77.5946
                };

                // Expanded bounding box to cover the full area of Bangalore
                const defaultBounds = {
                    north: 13.3000, // Expanded to cover the northernmost part of the city
                    south: 12.7000, // Expanded to cover the southernmost part
                    east: 77.7500, // Extended to the easternmost part
                    west: 77.3500 // Extended to the westernmost part
                };

                const options = {
                    bounds: defaultBounds, // Restrict to Bangalore's bounding box
                    strictBounds: true, // Enforce the bounding box strictly
                    types: ['establishment'],
                    componentRestrictions: {
                        country: 'IN', // Restrict to India
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
