@extends('layouts.company')
@section('page-title', $unit->exists ? __('Edit Unit') : __('Create Unit'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a
            href="{{ route('company.realestate.property.units.index', $property->id) }}">{{ __('Units') }}</a></li>
    <li class="breadcrumb-item">{{ $unit->exists ? __('Edit') : __('Create') }}</li>
@endsection

@push('header')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js"></script>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form
                action="{{ $unit->exists ? route('company.realestate.property.units.update', [$property->id, $unit->id]) : route('company.realestate.property.units.store', $property->id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if ($unit->exists)
                    @method('PUT')
                @endif
                <div class="card">
                    <div class="card-body row">
                        <!-- Name -->
                        <div class="form-group col-md-6">
                            <label for="name">{{ __('Name') }}<sup class="text-danger fs-6 d-none">*</sup></label>
                            <input type="text" name="name" autocomplete="off" value="{{ old('name', $unit->name) }}" class="form-control"
                                required>
                        </div>
                        <!-- Registration No -->
                        <div class="form-group col-md-6">
                            <label for="registration_no">{{ __('Registration No') }}</label>
                            <input type="text" name="registration_no"  autocomplete="off" 
                                value="{{ old('registration_no', $unit->registration_no) }}" class="form-control">
                        </div>
                        <div id="room-section" class="mb-3">
                            <div class="row">
                                {{-- === Bedrooms Section === --}}
                                <div class="section col-lg-6 mb-2" x-data="createRoomOptionHandler('bed_room')">
                                    <label class="mt-3">No. of Bedrooms <sup class="text-danger fs-6 d-none">*</sup></label>
                                    <div class="mt-3">
                                        <ul class="flex flex-wrap gap-3">
                                            @for ($i = 1; $i < 5; $i++)
                                                <li class="relative mb-1">
                                                    <input  class="sr-only peer"
                                                        @if ($i == 1) checked @endif type="radio"
                                                        value="{{ $i }}" name="bed_rooms"
                                                        id="bed_room_{{ $i }}">
                                                    <label
                                                        class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                        for="bed_room_{{ $i }}">{{ $i }}</label>
                                                </li>
                                            @endfor

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
                                                        <input type="number"  x-model="newOption"
                                                            placeholder="Enter value"
                                                            class="border border-gray-300 p-2 rounded-md w-20 text-sm focus:ring-blue-500 focus:border-blue-500">
                                                        <button @click="addOption"
                                                            class="px-3 py-1 bg-green-500 text-white text-sm rounded-md hover:bg-green-600">Done</button>
                                                    </div>
                                                </template>

                                                <template x-if="addedOption">
                                                    <div class="flex items-center gap-2 relative mb-1">
                                                        <input  class="sr-only peer" type="radio"
                                                            x-bind:value="addedOption" name="bed_rooms" id="bed_room_other"
                                                            checked>
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
                                    <label class="mt-3">No. of Bathrooms <sup class="text-danger fs-6 d-none">*</sup></label>
                                    <div class="mt-3">
                                        <ul class="flex flex-wrap gap-3">
                                            @for ($i = 1; $i < 5; $i++)
                                                <li class="relative mb-1">
                                                    <input  class="sr-only peer"
                                                        @if ($i == 1) checked @endif type="radio"
                                                        value="{{ $i }}" name="bath_rooms"
                                                        id="bath_rooms_{{ $i }}">
                                                    <label
                                                        class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                        for="bath_rooms_{{ $i }}">{{ $i }}</label>
                                                </li>
                                            @endfor

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
                                                        <input type="number"  x-model="newOption"
                                                            placeholder="Enter value"
                                                            class="border border-gray-300 p-2 rounded-md w-20 text-sm focus:ring-blue-500 focus:border-blue-500">
                                                        <button @click="addOption"
                                                            class="px-3 py-1 bg-green-500 text-white text-sm rounded-md hover:bg-green-600">Done</button>
                                                    </div>
                                                </template>

                                                <template x-if="addedOption">
                                                    <div class="flex items-center gap-2 relative mb-1">
                                                        <input  class="sr-only peer" type="radio"
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
                                                    <input  class="sr-only peer"
                                                        @if ($i == 0) checked @endif type="radio"
                                                        value="{{ $i }}" name="balconies"
                                                        id="balconies_{{ $i }}">
                                                    <label
                                                        class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                        for="balconies_{{ $i }}">{{ $i }}</label>
                                                </li>
                                            @endfor

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
                                                        <input type="number"  x-model="newOption"
                                                            placeholder="Enter value"
                                                            class="border border-gray-300 p-2 rounded-md w-20 text-sm focus:ring-blue-500 focus:border-blue-500">
                                                        <button @click="addOption"
                                                            class="px-3 py-1 bg-green-500 text-white text-sm rounded-md hover:bg-green-600">Done</button>
                                                    </div>
                                                </template>

                                                <template x-if="addedOption">
                                                    <div class="flex items-center gap-2 relative mb-1">
                                                        <input  class="sr-only peer" type="radio"
                                                            x-bind:value="addedOption" name="balconies" id="balconies_other"
                                                            checked>
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
                                                    <input  class="sr-only peer"
                                                        @if ($i == 0) checked @endif type="radio"
                                                        value="{{ $i }}" name="kitchen"
                                                        id="kitchen_{{ $i }}">
                                                    <label
                                                        class="mx-1 px-3 py-1 bg-white border border-gray-300 rounded-5 cursor-pointer hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                                        for="kitchen_{{ $i }}">{{ $i }}</label>
                                                </li>
                                            @endfor

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
                                                        <input type="number"  x-model="newOption"
                                                            placeholder="Enter value"
                                                            class="border border-gray-300 p-2 rounded-md w-20 text-sm focus:ring-blue-500 focus:border-blue-500">
                                                        <button @click="addOption"
                                                            class="px-3 py-1 bg-green-500 text-white text-sm rounded-md hover:bg-green-600">Done</button>
                                                    </div>
                                                </template>

                                                <template x-if="addedOption">
                                                    <div class="flex items-center gap-2 relative mb-1">
                                                        <input  class="sr-only peer" type="radio"
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



                        <!-- Rent Type -->
                        <div class="form-group col-md-6">
                            <label for="rent_type">{{ __('Rent Type') }}</label>
                            <select name="rent_type" class="form-control">
                                <option value="">{{ __('Select Rent Type') }}</option>
                                <option value="monthly"
                                    {{ old('rent_type', $unit->rent_type) == 'monthly' ? 'selected' : '' }}>Monthly
                                </option>
                                <option value="yearly"
                                    {{ old('rent_type', $unit->rent_type) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                        </div>



                        <!-- Deposit Type -->
                        <div class="form-group col-md-6">
                            <label for="deposite_type">{{ __('Deposit Type') }}</label>
                            <select name="deposite_type" class="form-control">
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
                            <input type="number" step="0.01"  autocomplete="off"  name="deposite_amount"
                                value="{{ old('deposite_amount', $unit->deposite_amount) }}" class="form-control">
                        </div>

                        <!-- Late Fee Type -->
                        <div class="form-group col-md-6">
                            <label for="late_fee_type">{{ __('Late Fee Type') }}</label>
                            <select name="late_fee_type" class="form-control">
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
                            <input type="number"  autocomplete="off"  step="0.01" name="late_fee_amount"
                                value="{{ old('late_fee_amount', $unit->late_fee_amount) }}" class="form-control">
                        </div>

                        <!-- Incident Receipt -->
                        <div class="form-group col-md-6">
                            <label for="incident_reicept_amount">{{ __('Incident Receipt Amount') }}</label>
                            <input type="number"  autocomplete="off"  step="0.01" name="incident_reicept_amount"
                                value="{{ old('incident_reicept_amount', $unit->incident_reicept_amount) }}"
                                class="form-control">
                        </div>

                        <!-- Price -->
                        <div class="form-group col-md-6">
                            <label for="price">{{ __('Price') }}</label>
                            <input type="number"  autocomplete="off"  name="price" step="0.01" oninput="convertToWords()"
                                placeholder="Enter price" max="999999999" type="number" id="priceInput"
                                value="{{ old('price', $unit->price) }}" class="form-control">
                            <p class="mt-4 text-dark-700">Price in words: <span id="priceInWords"></span></p>

                        </div>



                        <div class="mb-5 ">
                            <div class="section">
                                <label for="images">{{ __('Unit Images') }}</label>
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
                                                        <input type="radio" name="coverImage" 
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
                                                    <input name="images[]"  type="file"
                                                        accept="image/*" id="fileInput" class="hidden" multiple
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
                                <textarea  name="unique_info" rows="4" autocomplete="off"
                                    class="block w-full mt-2 p-2 border rounded-lg" placeholder="Write your thoughts here..."></textarea>
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
        </div>
    </div>
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
                alert('Price cannot exceed AED 99.9 Crore');
                document.getElementById('priceInput').value = 999999999; // Set to the maximum value
                return;
            }

            // Convert the number to words
            const words = numberToWords(parseInt(num));
            document.getElementById('priceInWords').textContent = 'AED  ' + (words || 'Zero');
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

    </script>
@endpush
