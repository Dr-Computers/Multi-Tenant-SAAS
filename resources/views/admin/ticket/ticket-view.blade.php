

@extends('layouts.admin')
@section('page-title')
    {{ __('Users') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">{{ __('Support Ticket') }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-primary me-2">
            <i class="ti ti-arrow-left"></i> Back to Tickets
        </a>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-bUsers-style">
                    <div class="pb-1" x-data="formHandler()">
                        <div id="loader" class="loader-overlay hidden">
                            <div class="spinner"></div>
                        </div>
                        <div x-show="showToast" x-transition
                            :class="toastType === 'success' ? 'bg-success text-light' : 'bg-danger text-light'"
                            class="fixed top-5 right-5 text-white p-3 rounded shadow-lg transition">
                            <p x-html="toastMessage"></p>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">

                                <div class="">
                                    <div class="mt-3">
                                        <div class="row mb-3">
                                            <div class="col-md-10">
                                                @php $ticketFirst = $tickets ?  $tickets->first() : NULL;  @endphp
                                                <span class="mb-3 fw-bold">Subject :
                                                    {{ $ticketFirst != null ? $ticketFirst->subject : '--' }}
                                                </span><br>
                                                <span class="mb-3 fw-bold">Created at :
                                                    {{ $ticketFirst != null ? dateTimeFormat($ticketFirst->created_at) : '--' }}
                                                </span><br>
                                                <span class="mb-3 fw-bold">Type :
                                                    <span class="badge bg-info text-capitalize">
                                                        {{ $ticketFirst != null ? $ticketFirst->type : '--' }}</span>
                                                </span>
                                            </div>
                                            @if ($ticketFirst && $ticketFirst->status == 1)
                                                <div class="col-md-2 text-end">
                                                    <form
                                                        action="{{ route('admin.tickets.closed_ticket', $ticketFirst->ticket_no) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" name="submit" class="btn btn-danger">Close
                                                            Ticket</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @foreach ($tickets as $ticket)
                                        <div class="row mb-2">
                                            @if ($ticket->to != 'admin')
                                                <div class="col-lg-1"></div>
                                            @endif
                                            <div class="col-lg-11">
                                                <div class="card-body {{ $ticket->to == 'admin' ? 'bg-gray-100' : 'bg-gray-200' }} rounded-3 mb-3"
                                                    style="color:black;">
                                                    <p class="card-text">{{ $ticket->body }}</p>

                                                    <div class="row">
                                                        <div class="col-lg-12 mt-2">
                                                            <div class="row ">
                                                                @foreach ($ticket->attachments ?? [] as $attachment)
                                                                    <div class="col-lg-1 border p-2">
                                                                        <img class="w-100"
                                                                            src="{{ asset('storage/' . ($attachment && $attachment->media ? $attachment->media->fileUrl : '')) }}">
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($ticket->to == 'admin')
                                                <div class="col-lg-1"></div>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if ($ticket && $ticket->status == 1)
                                        <div class="card-footer">
                                            <form class=""
                                                action="{{ route('company.tickets.reply', $ticket->ticket_no) }}"
                                                method="post" @submit.prevent="submitForm" enctype="multipart/form-data"
                                                id="ticketFrom">
                                                <div class="form-group my-2">

                                                    <label class="mb-2">Content</label>
                                                    <textarea class="form-control" rows="4" placeholder="Reply" required name="body"></textarea>
                                                </div>
                                                <div class="section">
                                                    <h6 class="mt-3 font-bold text-black ">Add Attachment</h6>
                                                    <div
                                                        class="mt-3 border-dashed border-2 border-gray-300 rounded-lg p-3  bg-gray-100">
                                                        <div x-data="documentUploader()"
                                                            class="mx-auto bg-white shadow rounded-lg space-y-6">
                                                            <!-- Document Preview Grid -->
                                                            <div class="grid grid-cols-2 md:grid-cols-6 gap-4">

                                                                <!-- Existing Documents -->
                                                                <template x-for="(document, index) in documents"
                                                                    :key="index">
                                                                    <div class="flex flex-col relative">
                                                                        <div
                                                                            class="relative group border rounded-lg overflow-hidden">
                                                                            <!-- Document -->
                                                                            <img :src="document.url"
                                                                                style="height: 100px;"
                                                                                alt="Uploaded Document"
                                                                                class="w-30 h-30 object-cover">
                                                                            <!-- Overlay with Cover Option -->
                                                                            <div
                                                                                class="absolute flex flex-col inset-0 group-hover:opacity-100 space-y-2 transition">
                                                                                <!-- Remove Document -->
                                                                                <button @click="removeDocument(index)"
                                                                                    class="absolute bg-white p-1 right-0 rounded-full top-0">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        class="h-4 w-4" viewBox="0 0 20 20"
                                                                                        fill="red">
                                                                                        <path fill-rule="evenodd"
                                                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                                            clip-rule="evenodd" />
                                                                                    </svg>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </template>
                                                                <!-- Upload New Documents -->
                                                                <div class="flex flex-col col-auto text-center p-2 ">
                                                                    <div class="relative group border rounded-lg p-2 overflow-hidden"
                                                                        @click="triggerFileInput()"
                                                                        x-bind:class="{ 'border-blue-500': isDragging }">
                                                                        <img src="/assets/icons/upload-icon.png"
                                                                            class="w-50 mx-auto">
                                                                        <input name="documents[]" type="file"
                                                                            accept=".pdf,.docx,image/*" id="fileDocInput"
                                                                            class="hidden" multiple
                                                                            @change="addDocuments($event)">
                                                                        <p class="text-gray-600">
                                                                            click to upload here.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 text-end mt-4">
                                                    <button type="submit" name="submit"
                                                        class="btn btn-primary">Reply</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
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
                const formElement = document.getElementById('ticketFrom');
                const formData = new FormData(formElement);

                try {
                    const response = await fetch(`{{ route('admin.tickets.reply', $ticket->ticket_no) }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                    window.location = '';
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
