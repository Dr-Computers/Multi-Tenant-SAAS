@extends('layouts.company')
@section('page-title', $unit->exists ? __('Edit Unit') : __('Create Unit'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>

    <li class="breadcrumb-item"><a href="{{ route('company.realestate.properties.index') }}">{{ __('Properties') }}</a></li>
    <li class="breadcrumb-item"><a
            href="{{ route('company.realestate.property.units.index', ['property_id' => $property->id]) }}">{{ __('Units') }}</a>
    </li>
    <li class="breadcrumb-item">{{ $unit->exists ? __('Edit') : __('Create') }}</li>
@endsection

@push('header')
    <script src="https://cdn.tailwindcss.com"></script> 
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
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js"></script>
@endpush


@section('action-btn')
    <a href="{{ route('company.realestate.property.units.store', $property->id) }}"
        class="btn btn-sm text-light btn-primary-subtle">
        <i class="ti ti-arrow-left"></i> {{ __('Back') }}
    </a>
@endsection

@section('content')
    @canany(['create a unit', 'edit a unit'])

        <form x-data="formHandler()" @submit.prevent="submitForm($event)" id="propertyFrom"
            action="{{ $unit->exists ? route('company.realestate.property.units.update', ['property_id' => $property->id, 'unit' => $unit->id]) : route('company.realestate.property.units.store', $property->id) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if ($unit->exists)
                @method('PUT')
            @endif
            <div class="card shadow-lg p-lg-6 p-2.5 mt-5">
                <div class="card-body row">

                    <div class="pb-1 text-end">
                        <div x-show="showToast" x-transition
                            :class="toastType === 'success' ? 'bg-success text-light' : 'bg-danger text-light'"
                            class="fixed top-5 text-white p-3 rounded shadow-lg transition">
                            <p x-html="toastMessage"></p>

                        </div>
                    </div>
                    <div id="loader" class="loader-overlay hidden">
                        <div class="spinner"></div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <!-- Name -->
                            <div class="form-group col-md-6">
                                <label for="name">{{ __('Name') }}<sup class="text-danger fs-6 d-none">*</sup></label>
                                <input form="propertyFrom" type="text" name="name" autocomplete="off"
                                    value="{{ old('name', $unit->name) }}" class="form-control" required>
                            </div>
                            <!-- Registration No -->
                            <div class="form-group col-md-6">
                                <label for="registration_no">{{ __('Registration No') }}</label>
                                <input form="propertyFrom" type="text" name="registration_no" autocomplete="off"
                                    value="{{ old('registration_no', $unit->registration_no) }}" class="form-control">
                            </div>
                            <div id="room-section" class="mb-3">
                                <div class="row">
                                    {{-- === Bedrooms Section === --}}
                                    <div class="section col-lg-6 mb-2" x-data="createRoomOptionHandler('bed_room')">
                                        <label class="mt-3">No. of Bedrooms <sup
                                                class="text-danger fs-6 d-none">*</sup></label>
                                        <div class="mt-3">
                                            <ul class="flex flex-wrap gap-3">
                                                @for ($i = 1; $i < 5; $i++)
                                                    <li class="relative mb-1">
                                                        <input class="sr-only peer" form="propertyFrom"
                                                            @if ($i == 1 || $i == $unit->bed_rooms) checked @endif type="radio"
                                                            value="{{ $i }}" name="bed_rooms"
                                                            id="bed_room_{{ $i }}">
                                                        <label
                                                            class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                            for="bed_room_{{ $i }}">{{ $i }}</label>
                                                    </li>
                                                @endfor

                                                @if ($unit->bed_rooms > 4)
                                                    <li class="relative mb-1">
                                                        <input form="propertyFrom" class="sr-only peer" checked type="radio"
                                                            value="{{ $unit->bed_rooms }}" name="bed_rooms"
                                                            id="room_{{ $unit->bed_rooms }}">
                                                        <label
                                                            class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                            for="room_{{ $unit->bed_rooms }}">{{ $unit->bed_rooms }}</label>
                                                    </li>
                                                @endif

                                                {{-- Add Other --}}
                                                <li class="relative mb-1">
                                                    <template x-if="!showInput && !addedOption">
                                                        <label @click="showInput = true"
                                                            class="mx-1 px-3 flex items-center py-1 bg-white border border-gray-300 rounded-5 cursor-pointer hover:bg-gray-50">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor" class="bi bi-plus"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                                            </svg>
                                                            Add Other
                                                        </label>
                                                    </template>

                                                    <template x-if="showInput">
                                                        <div class="flex items-center gap-2">
                                                            <input type="number" x-model="newOption" placeholder="Enter value"
                                                                class="border border-gray-300 p-2 rounded-md w-20 text-sm focus:ring-blue-500 focus:border-blue-500">
                                                            <button @click="addOption"
                                                                class="px-3 py-1 bg-green-500 text-white text-sm rounded-md hover:bg-green-600">Done</button>
                                                        </div>
                                                    </template>

                                                    <template x-if="addedOption">
                                                        <div class="flex items-center gap-2 relative mb-1">
                                                            <input class="sr-only peer" type="radio"
                                                                x-bind:value="addedOption" name="bed_rooms"
                                                                id="bed_room_other" checked>
                                                            <label x-text="addedOption"
                                                                class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                                for="bed_room_other"></label>
                                                            <span class="text-blue-500 cursor-pointer" @click="editOption"><i
                                                                    class="fas fa-edit"></i></span>
                                                        </div>
                                                    </template>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    {{-- === Bathrooms Section === --}}
                                    <div class="section col-lg-6 mb-2" x-data="createRoomOptionHandler('bath_rooms')">
                                        <label class="mt-3">No. of Bathrooms <sup
                                                class="text-danger fs-6 d-none">*</sup></label>
                                        <div class="mt-3">
                                            <ul class="flex flex-wrap gap-3">
                                                @for ($i = 1; $i < 5; $i++)
                                                    <li class="relative mb-1">
                                                        <input class="sr-only peer" form="propertyFrom"
                                                            @if ($i == 1 || $i == $unit->bath_rooms) checked @endif type="radio"
                                                            value="{{ $i }}" name="bath_rooms"
                                                            id="bath_rooms_{{ $i }}">
                                                        <label
                                                            class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                            for="bath_rooms_{{ $i }}">{{ $i }}</label>
                                                    </li>
                                                @endfor
                                                @if ($property->bath_rooms > 4)
                                                    <li class="relative mb-1">
                                                        <input form="propertyFrom" class="sr-only peer" checked
                                                            type="radio" value="{{ $property->bath_rooms }}"
                                                            name="bath_rooms" id="bath_rooms_{{ $property->bath_rooms }}">
                                                        <label
                                                            class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                            for="bath_rooms_{{ $property->bath_rooms }}">{{ $property->bath_rooms }}</label>
                                                    </li>
                                                @endif

                                                <li class="relative mb-1">
                                                    <template x-if="!showInput && !addedOption">
                                                        <label @click="showInput = true"
                                                            class="mx-1 px-3 flex items-center py-1 bg-white border border-gray-300 rounded-5 cursor-pointer hover:bg-gray-50">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor" class="bi bi-plus"
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
                                                            <input class="sr-only peer" type="radio"
                                                                x-bind:value="addedOption" name="bath_rooms"
                                                                id="bath_rooms_other" checked>
                                                            <label x-text="addedOption"
                                                                class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                                for="bath_rooms_other"></label>
                                                            <span class="text-blue-500 cursor-pointer" @click="editOption"><i
                                                                    class="fas fa-edit"></i></span>
                                                        </div>
                                                    </template>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    {{-- === Balconies Section === --}}
                                    <div class="section col-lg-6 mb-2" x-data="createRoomOptionHandler('balconies')">
                                        <label class="mt-3">Balconies <sup class="text-danger fs-6 d-none">*</sup></label>
                                        <div class="mt-3">
                                            <ul class="flex flex-wrap gap-3">
                                                @for ($i = 0; $i < 4; $i++)
                                                    <li class="relative mb-1">
                                                        <input class="sr-only peer" form="propertyFrom"
                                                            @if ($i == 0 || $i == $unit->balconies) checked @endif type="radio"
                                                            value="{{ $i }}" name="balconies"
                                                            id="balconies_{{ $i }}">
                                                        <label
                                                            class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                            for="balconies_{{ $i }}">{{ $i }}</label>
                                                    </li>
                                                @endfor
                                                @if ($property->balconies > 4)
                                                    <li class="relative mb-1">
                                                        <input form="propertyFrom" class="sr-only peer" checked
                                                            type="radio" value="{{ $property->balconies }}"
                                                            name="balconies" id="balconies_{{ $property->balconies }}">
                                                        <label
                                                            class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                            for="balconies_{{ $property->balconies }}">{{ $property->balconies }}</label>
                                                    </li>
                                                @endif

                                                <li class="relative mb-1">
                                                    <template x-if="!showInput && !addedOption">
                                                        <label @click="showInput = true"
                                                            class="mx-1 px-3 flex items-center py-1 bg-white border border-gray-300 rounded-5 cursor-pointer hover:bg-gray-50">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor" class="bi bi-plus"
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
                                                            <input class="sr-only peer" type="radio"
                                                                x-bind:value="addedOption" name="balconies"
                                                                id="balconies_other" checked>
                                                            <label x-text="addedOption"
                                                                class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                                for="balconies_other"></label>
                                                            <span class="text-blue-500 cursor-pointer" @click="editOption"><i
                                                                    class="fas fa-edit"></i></span>
                                                        </div>
                                                    </template>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    {{-- === Kitchen Section === --}}
                                    <div class="section col-lg-6 mb-2" x-data="createRoomOptionHandler('kitchen')">
                                        <label class="mt-3">Kitchen <sup class="text-danger fs-6 d-none">*</sup></label>
                                        <div class="mt-3">
                                            <ul class="flex flex-wrap gap-3">
                                                @for ($i = 0; $i < 4; $i++)
                                                    <li class="relative mb-1">
                                                        <input class="sr-only peer" form="propertyFrom"
                                                            @if ($i == 0 || $i == $unit->kitchen) checked @endif type="radio"
                                                            value="{{ $i }}" name="kitchen"
                                                            id="kitchen_{{ $i }}">
                                                        <label
                                                            class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                            for="kitchen_{{ $i }}">{{ $i }}</label>
                                                    </li>
                                                @endfor

                                                @if ($property->kitchen > 4)
                                                    <li class="relative mb-1">
                                                        <input form="propertyFrom" class="sr-only peer" checked
                                                            type="radio" value="{{ $property->kitchen }}" name="kitchen"
                                                            id="kitchen_{{ $property->kitchen }}">
                                                        <label
                                                            class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                            for="kitchen_{{ $property->kitchen }}">{{ $property->kitchen }}</label>
                                                    </li>
                                                @endif
                                                <li class="relative mb-1">
                                                    <template x-if="!showInput && !addedOption">
                                                        <label @click="showInput = true"
                                                            class="mx-1 px-3 flex items-center py-1 bg-white border border-gray-300 rounded-5 cursor-pointer hover:bg-gray-50">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor" class="bi bi-plus"
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
                                                            <input class="sr-only peer" type="radio"
                                                                x-bind:value="addedOption" name="kitchen" id="kitchen_other"
                                                                checked>
                                                            <label x-text="addedOption"
                                                                class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                                for="kitchen_other"></label>
                                                            <span class="text-blue-500 cursor-pointer" @click="editOption"><i
                                                                    class="fas fa-edit"></i></span>
                                                        </div>
                                                    </template>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            @if ($property->mode == 'rent')
                                <!-- Rent Type -->
                                <div class="form-group col-md-6">
                                    <label for="rent_type">{{ __('Rent Type') }}</label>
                                    <select name="rent_type" class="form-control" form="propertyFrom">
                                        <option value="">{{ __('Select Rent Type') }}</option>
                                        <option value="monthly"
                                            {{ old('rent_type', $unit->rent_type) == 'monthly' ? 'selected' : '' }}>Monthly
                                        </option>
                                        <option value="yearly"
                                            {{ old('rent_type', $unit->rent_type) == 'yearly' ? 'selected' : '' }}>Yearly
                                        </option>
                                    </select>
                                </div>

                                <!-- Rent Duration -->
                                <div class="form-group col-md-6">
                                    <label for="rent_type">{{ __('Rent Duration') }}</label>
                                    <input type="number" form="propertyFrom" autocomplete="off"
                                        name="rent_duration" value="{{ old('rent_duration', $unit->rent_duration) }}"
                                        class="form-control">
                                </div>

                                <!-- Deposit Type -->
                                <div class="form-group col-md-6">
                                    <label for="deposite_type">{{ __('Deposit Type') }}</label>
                                    <select name="deposite_type" class="form-control" form="propertyFrom">
                                        <option value="">{{ __('Select Type') }}</option>
                                        <option value="fixed"
                                            {{ old('deposite_type', $unit->deposite_type) == 'fixed' ? 'selected' : '' }}>Fixed
                                        </option>
                                        <option value="percentage"
                                            {{ old('deposite_type', $unit->deposite_type) == 'percentage' ? 'selected' : '' }}>
                                            Percentage</option>
                                    </select>
                                </div>

                                <!-- Deposit Amount -->
                                <div class="form-group col-md-6">
                                    <label for="deposite_amount">{{ __('Deposit Amount') }}</label>
                                    <input type="number" step="0.01" form="propertyFrom" autocomplete="off"
                                        name="deposite_amount" value="{{ old('deposite_amount', $unit->deposite_amount) }}"
                                        class="form-control">
                                </div>

                                <!-- Late Fee Type -->
                                <div class="form-group col-md-6">
                                    <label for="late_fee_type">{{ __('Late Fee Type') }}</label>
                                    <select name="late_fee_type" class="form-control" form="propertyFrom">
                                        <option value="">{{ __('Select Type') }}</option>
                                        <option value="fixed"
                                            {{ old('late_fee_type', $unit->late_fee_type) == 'fixed' ? 'selected' : '' }}>Fixed
                                        </option>
                                        <option value="percentage"
                                            {{ old('late_fee_type', $unit->late_fee_type) == 'percentage' ? 'selected' : '' }}>
                                            Percentage</option>
                                    </select>
                                </div>

                                <!-- Late Fee Amount -->
                                <div class="form-group col-md-6">
                                    <label for="late_fee_amount">{{ __('Late Fee Amount') }}</label>
                                    <input type="number" form="propertyFrom" autocomplete="off" step="0.01"
                                        name="late_fee_amount" value="{{ old('late_fee_amount', $unit->late_fee_amount) }}"
                                        class="form-control">
                                </div>

                                <!-- Incident Receipt -->
                                <div class="form-group col-md-6">
                                    <label for="incident_reicept_amount">{{ __('Incident Receipt Amount') }}</label>
                                    <input type="number" form="propertyFrom" autocomplete="off" step="0.01"
                                        name="incident_reicept_amount"
                                        value="{{ old('incident_reicept_amount', $unit->incident_reicept_amount) }}"
                                        class="form-control">
                                </div>
                            @endif


                            <!-- Price -->
                            <div class="form-group col-md-6">
                                <label for="price">{{ __('Price') }}</label>
                                <input type="number" form="propertyFrom" autocomplete="off" name="price" step="0.01"
                                    oninput="convertToWords()" placeholder="Enter price" max="999999999" type="number"
                                    id="priceInput" value="{{ old('price', $unit->price) }}" class="form-control">
                                <p class="mt-2 text-dark-700">Price in words: <span id="priceInWords"></span></p>

                            </div>

                            <div class="mb-5 ">
                                <div class="section">
                                    <label for="images">{{ __('Unit Images') }}</label>
                                    <div class="mt-3 border-dashed border-2 border-gray-300 rounded-lg p-3  bg-gray-100">

                                        <div x-data="imageUploader()" class="mx-auto bg-white shadow rounded-lg space-y-6">
                                            <!-- Image Preview Grid -->
                                            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                                @foreach ($unit->propertyUnitImages ?? [] as $key => $image)
                                                    @php
                                                        $isImage2 = Str::startsWith($image->mime_type, 'image/');
                                                        $icon2 = match (true) {
                                                            Str::contains($image->mime_type, 'pdf')
                                                                => '/assets/icons/pdf-icon.png',
                                                            Str::contains($image->mime_type, 'msword'),
                                                            Str::contains($image->mime_type, 'wordprocessingml')
                                                                => '/assets/icons/docx-icon.png',
                                                            default => '/assets/icons/file-icon.png',
                                                        };
                                                        $thumbnail2 = $isImage2
                                                            ? asset('storage/' . $image->file_url)
                                                            : asset($icon);
                                                    @endphp
                                                    <div class="flex flex-col relative existing-data-box">
                                                        <div
                                                            class="relative text-center group border rounded-lg overflow-hidden ">
                                                            <img src="{{ $thumbnail2 }}"
                                                                alt="{{ $image->alt ?? $image->name }}"
                                                                class="w-auto object-cover mx-auto mb-2 rounded"
                                                                style="height: 100px;width: 100%;">
                                                            <span title="{{ $image->name }}">{{ $image->name }}</span>
                                                            <input type="hidden" form="propertyFrom"
                                                                value="{{ $image->id }}" name="existingImage[]" />
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
                                                        <div class="mb-2">
                                                            <label
                                                                class="flex items-center space-x-2 text-dark cursor-pointer ">
                                                                <input type="radio" name="exCoverImage" form="propertyFrom"
                                                                    value="{{ $image->id }}"
                                                                    @change="setCoverImage({{ $key + 200 }})"
                                                                    {{ $unit->thumbnail_image == $image->id ? 'checked' : '' }}>
                                                                <span>Make Cover Photo</span>
                                                            </label>
                                                            <span x-show="currentCover === {{ $key + 200 }}"
                                                                class="absolute top-0 left-0 p-2 text-white bg-black opacity-50">Cover</span>
                                                        </div>

                                                    </div>
                                                @endforeach

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
                                                            <input type="radio" name="coverImage" class=""
                                                                form="propertyFrom" :value="image.name"
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
                                                        <img src="/assets/icons/upload-icon.png" class="w-50 mx-auto">
                                                        <input name="images[]" type="file" accept="image/*"
                                                            form="propertyFrom" id="fileInput" class="hidden" multiple
                                                            @change="addImages($event)">
                                                        <p class="text-dark-600">
                                                            click to upload your images here.</p>
                                                        <p class="text-sm text-blue-500 font-medium hidden">Upload up to 30
                                                            images</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label for="images">{{ __('Description Notes') }}</label>
                                    <textarea name="unique_info" form="propertyFrom" rows="4" autocomplete="off"
                                        class="block w-full mt-2 p-2 border rounded-lg" placeholder="Write your thoughts here...">{{ $unit ? $unit->notes : '' }}</textarea>
                                </div>


                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group col-md-12 mt-4">
                        <button type="submit"
                            class="btn btn-primary">{{ $unit->exists ? __('Update') : __('Create') }}</button>
                        <a href="{{ route('company.realestate.property.units.index', $property->id) }}"
                            class="btn btn-secondary">{{ __('Cancel') }}</a>
                    </div>
                </div>
            </div>
        </form>

    @endcanany
@endsection

@push('footer')
    <script>
        function createRoomOptionHandler(fieldName) {
            return {
                showInput: false,
                newOption: null,
                addedOption: null,
                isEditing: false,
                addOption() {
                    if (this.newOption !== null) {
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
        // Function to convert number to words
        function convertToWords() {
            const num = document.getElementById('priceInput').value;

            // Ensure the input is within the maximum allowed value (AED 100 Crore)
            if (num > 999999999) {
                alert('Price cannot exceed  99.9 Crore');
                document.getElementById('priceInput').value = 999999999; // Set to the maximum value
                return;
            }

            // Convert the number to words
            const words = numberToWords(parseInt(num));
            document.getElementById('priceInWords').textContent = '  ' + (words || 'Zero');
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
                        //if (file.size <= 2 * 1024 * 1024 && file.type.startsWith('image/')) {
                        const fileObject = {
                            url: URL.createObjectURL(file), // Blob URL for preview
                            file: file, // The actual file object for uploading
                            name: file.name // Original file name
                        };
                        this.images.push(fileObject);
                        this.files.push(file); // Store the file object for uploading
                        // } else {
                        //     alert('Image not allowed to be more than 2 MB');
                        // }
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
                    const url = formElement.action;


                    try {
                        const response = await fetch(url, {
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
                        window.location = data.redirect ?? "";
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
@endpush
