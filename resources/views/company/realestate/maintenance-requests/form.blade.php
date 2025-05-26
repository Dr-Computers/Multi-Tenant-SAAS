<div class="pb-1" x-data="formHandler()">
    <div id="loader" class="loader-overlay hidden">
        <div class="spinner"></div>
    </div>
    <div x-show="showToast" x-transition
        :class="toastType === 'success' ? 'bg-success text-light' : 'bg-danger text-light'"
        class="fixed top-5 right-5 text-white p-3 rounded shadow-lg transition">

        <p x-html="toastMessage"></p>

    </div>
    <form @submit.prevent="submitForm" enctype="multipart/form-data" id="requestFrom"
        action="{{ isset($maintenance) ? route('company.realestate.maintenance-requests.update', $maintenance->id) : route('company.realestate.maintenance-requests.store') }}"
        method="POST">
        @csrf
        @if (isset($maintenance))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row" x-data="propertyUnitSelector()">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">Property <x-required /></label>
                                    <select name="property" class="form-control" x-model="selectedProperty"
                                        @change="fetchUnits" required>
                                        <option value="">-- Select Property --</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}"
                                                {{ old('property', $maintenance->property_id ?? '') == $property->id ? 'selected' : '' }}>
                                                {{ $property->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('property')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">Unit <x-required /></label>
                                    <select name="unit" class="form-control" id="unitSelect" x-model="selectedUnit"
                                        required>
                                        <option value="">-- Select Unit --</option>
                                        @if (isset($maintenance))
                                            @foreach ($units ?? [] as $unit)
                                                <option value="{{ $unit->id }}"
                                                    {{ $unit->id == $maintenance->unit_id ? 'selected' : '' }}>
                                                    {{ $unit->name }}</option>
                                            @endforeach
                                        @endif
                                        <template x-for="unit in units" :key="unit.id">
                                            <option :value="unit.id" x-text="unit.name"></option>
                                        </template>
                                    </select>
                                    @error('unit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                {{-- Maintainer --}}
                                <div class="form-group">
                                    <label class="form-label">Maintainer <x-required /></label>
                                    <select name="maintainer" class="form-control" required>
                                        <option value="">-- Select Maintainer --</option>
                                        @foreach ($maintainers as $maintainer)
                                            <option value="{{ $maintainer->id }}"
                                                {{ old('maintainer', $maintenance->maintainer_id ?? '') == $maintainer->id ? 'selected' : '' }}>
                                                {{ $maintainer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('maintainer')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                {{-- Issue Type --}}
                                <div class="form-group">
                                    <label class="form-label">Issue Type <x-required /></label>
                                    <select name="issue" class="form-control" required>
                                        <option value="">-- Select Issue Type --</option>
                                        @foreach ($issues as $issue)
                                            <option value="{{ $issue->id }}"
                                                {{ old('issue', $maintenance->issue_type ?? '') == $issue->id ? 'selected' : '' }}>
                                                {{ $issue->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('issue')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                {{-- Request date --}}
                                <div class="form-group">
                                    <label class="form-label">Request date <x-required /></label>
                                    <input type="date" class="form-control"
                                        value="{{ old('request_date', isset($maintenance) ? date('Y-m-d', strtotime($maintenance->request_date)) : '') }}"
                                        name="request_date">
                                    @error('request_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="section">
                                <h6 class="mt-3 font-bold text-black ">Add Attachment</h6>
                                <div class="mt-3 border-dashed border-2 border-gray-300 rounded-lg p-3  bg-gray-100">
                                    <div x-data="documentUploader()" class="mx-auto bg-white shadow rounded-lg space-y-6">
                                        <!-- Document Preview Grid -->
                                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                            {{-- @dump($maintenance->maintenanceRequestAttachments) --}}
                                            @if (isset($maintenance))

                                                @foreach ($maintenance->maintenanceRequestAttachments ?? [] as $key => $image)
                                                    <div class="flex flex-col relative existing-data-box">
                                                        <div class="relative group border rounded-lg overflow-hidden ">
                                                            <img src="{{ asset('storage/' . $image->file_url) }}"
                                                                class="thumbnail" style="height: 100px;width: 100%;"
                                                                alt="Uploaded Image">
                                                            <input type="hidden" value="{{ $image->id }}"
                                                                name="existingImage[]" />
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
                                                    </div>
                                                @endforeach
                                            @endif
                                            <!-- Existing Documents -->
                                            <template x-for="(document, index) in documents" :key="index">
                                                <div class="flex flex-col relative">
                                                    <div class="relative group border rounded-lg overflow-hidden">
                                                        <!-- Document -->
                                                        <img :src="document.url" style="height: 100px;"
                                                            alt="Uploaded Document" class="w-30 h-30 object-cover">
                                                        <!-- Overlay with Cover Option -->
                                                        <div
                                                            class="absolute flex flex-col inset-0 group-hover:opacity-100 space-y-2 transition">
                                                            <!-- Remove Document -->
                                                            <span @click="removeDocument(index)"
                                                                class="absolute bg-white p-1 right-0 rounded-full top-0">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                                    viewBox="0 0 20 20" fill="red">
                                                                    <path fill-rule="evenodd"
                                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>

                                            <!-- Upload New Documents -->
                                            <div class="flex flex-col col-auto text-center">
                                                <div class="relative group border rounded-lg p-2 overflow-hidden"
                                                    @click="triggerFileInput()"
                                                    x-bind:class="{ 'border-blue-500': isDragging }">
                                                    <img src="/assets/icons/upload-icon.png" class="w-50 mx-auto">
                                                    <input name="documents[]" type="file"
                                                        accept=".pdf,.docx,image/*" id="fileDocInput" class="hidden"
                                                        multiple @change="addDocuments($event)">
                                                    <p class="text-gray-600">
                                                        click to upload here.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-3">
                                {{-- Notes --}}
                                <div class="form-group">
                                    <label class="form-label">Notes <x-required /></label>
                                    <textarea name="notes" class="form-control" rows="4" required>{{ old('notes', $maintenance->notes ?? '') }}</textarea>
                                    @error('notes')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12">
                                {{-- Status --}}
                                <div class="form-group">
                                    <label class="form-label">Status <x-required /></label><br>
                                    @php
                                        $status = old('status', $maintenance->status ?? 'pending');
                                    @endphp
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status"
                                            value="pending" {{ $status == 'pending' ? 'checked' : '' }}>
                                        <label class="form-check-label">Pending</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status"
                                            value="inprogress" {{ $status == 'inprogress' ? 'checked' : '' }}>
                                        <label class="form-check-label">In Progress</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status"
                                            value="completed" {{ $status == 'completed' ? 'checked' : '' }}>
                                        <label class="form-check-label">Completed</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status"
                                            value="rejected" {{ $status == 'rejected' ? 'checked' : '' }}>
                                        <label class="form-check-label">Rejected</label>
                                    </div>

                                    @error('status')
                                        <br><small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary">
                                    {{ isset($maintenance) ? 'Update' : 'Create' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    function documentUploader() {
        return {
            isDragging: false,
            documents: [],
            currentCover: null,
            files: [],

            triggerFileInput() {
                document.getElementById('fileDocInput').click();
            },

            addDocuments(event) {
                const files = event.target.files || event.dataTransfer.files;
                Array.from(files).forEach(file => {
                    const isAllowedImage = file.type.startsWith('image/');
                    const isAllowedPdf = file.type === 'application/pdf';
                    const isAllowedDocx = file.type ===
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document';

                    if ((isAllowedImage || isAllowedPdf || isAllowedDocx)) {
                        let previewUrl = isAllowedImage ?
                            URL.createObjectURL(file) :
                            isAllowedPdf ?
                            '/assets/icons/pdf-icon.png' :
                            '/assets/icons/docx-icon.png';

                        const fileObject = {
                            url: previewUrl,
                            file: file,
                            name: file.name,
                            type: file.type
                        };
                        this.documents.push(fileObject);
                        this.files.push(file);
                    } else {
                        alert('Only image, PDF, and DOCX files under 2 MB are allowed.');
                    }
                });
            },

            removeDocument(index) {
                if (this.currentCover === index) {
                    this.currentCover = null;
                }
                this.documents.splice(index, 1);
                this.files.splice(index, 1);
            },
        }
    }
</script>
<script>
    function propertyUnitSelector() {
        return {
            selectedProperty: '{{ old('property', $maintenance->property_id ?? '') }}',
            selectedUnit: '{{ old('unit', $maintenance->unit_id ?? '') }}',
            units: [],
            fetchUnits() {
                if (!this.selectedProperty) {
                    this.units = [];
                    return;
                }

                const url = `{{ route('company.realestate.maintenance-requests.units', ':id') }}`.replace(':id', this
                    .selectedProperty);

                this.units = [{
                    id: '',
                    unit_number: 'Loading...'
                }];

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        this.units = data;
                    })
                    .catch(() => {
                        this.units = [];
                    });
            }
        }
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
                const formElement = document.getElementById('requestFrom');
                const formData = new FormData(formElement);
                const url =`{{ isset($maintenance) ? route('company.realestate.maintenance-requests.update', $maintenance->id) : route('company.realestate.maintenance-requests.store') }}`;
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
