@extends('layouts.company')
@section('page-title')
    {{ __('Properties') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Media Manager') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-bUsers-style">
                    <div class="mb-4">
                        @if (!empty($breadcrumbs) && count($breadcrumbs) > 0)
                            <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                            &nbsp;/&nbsp;
                            <a href="{{ route('company.media.index') }}">{{ __('Media') }}</a>
                            @foreach ($breadcrumbs as $crumb)
                                &nbsp;/&nbsp;
                                <a href="{{ route('company.media.folder.sub', $crumb['id']) }}"
                                    class="text-blue-600 hover:underline">
                                    {{ $crumb['name'] }}
                                </a>
                            @endforeach
                        @endif

                        <div class="flex gap-2 justify-content-end">
                            <div class="btn-group card-option">
                                <button type="button"
                                    class="btn dropdown-toggle  btn-sm btn-primary text-white px-4 py-2 rounded me-2"
                                    data-bs-toggle="dropdown">
                                    <i class="ti ti-cloud-upload"></i> Upload
                                </button>
                                <div class="dropdown-menu dropdown-menu-center">

                                    <button href="#" class="dropdown-item" data-size="lg"
                                        data-url="{{ route('company.media.files.select', isset($folder) ? $folder->id : 0) }}"
                                        data-ajax-popup2="true" data-bs-toggle="tooltip"
                                        title="{{ __('Upload from local') }}">
                                        <span> <i class="ti ti-lock text-dark"></i>
                                            {{ __('Upload from local') }}</span>
                                    </button>
                                    <button href="#" class="dropdown-item" data-bs-toggle="tooltip"
                                        title="{{ __('Reset Password') }}" data-url="#" data-size="xl"
                                        data-ajax-popup="true" data-original-title="{{ __('Reset Password') }}">
                                        <span> <i class="ti ti-lock text-dark"></i>
                                            {{ __('Upload from URL') }}</span>
                                    </button>
                                </div>
                            </div>
                            <button href="#" data-size="md"
                                data-url="{{ route('company.media.folder.create', ['folder_id' => isset($folder) ? $folder->id : 0]) }}"
                                data-ajax-popup2="true" data-bs-toggle="tooltip" title="{{ __('Create Folder') }}"
                                class="btn btn-sm btn-primary text-white px-4 py-2 rounded me-2">
                                <i class="ti ti-plus"></i> Create Folder
                            </button>
                            <button href="#" class="btn btn-sm btn-danger text-white px-4 py-2 rounded me-2">
                                <i class="ti ti-trash"></i> Trash
                            </button>
                        </div>
                    </div>

                    <div id="folderList" class="row">
                        @if ($folders->count() > 0 || $files->count() > 0)
                            @foreach ($folders ?? [] as $folder)
                                <div class="col-6 col-md-3 col-lg-2 mb-2">
                                    <div class="p-4 border rounded bg-white shadow position-relative">
                                        <div class="dropdown position-absolute top-0 end-0 m-2">
                                            <button class="text-dark" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item"
                                                        href="{{ route('company.media.folder.sub', $folder->id) }}"
                                                        title="{{ __('Rename Folder') }}">Show</a>
                                                </li>
                                                <li><button href="#" class="dropdown-item" data-size="md"
                                                        data-url="{{ route('company.media.folder.rename', ['folder_id' => isset($folder) ? $folder->id : 0]) }}"
                                                        data-ajax-popup2="true" data-bs-toggle="tooltip"
                                                        title="{{ __('Rename Folder') }}">Rename</button>
                                                </li>
                                                <li><button href="#" class="dropdown-item"
                                                        onclick="deleteFolder({{ $folder->id }})">Delete</button></li>
                                            </ul>
                                        </div>
                                        <a href="{{ route('company.media.folder.sub', $folder->id) }}">
                                            <img src="{{ asset('assets/icons/folder.png') }}" class="w-25 mx-auto"
                                                alt="folders">
                                            <p class="font-bold text-center mt-2 text-truncate">{{ $folder->name }}</p>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                            @foreach ($files ?? [] as $file)
                                @php
                                    $isImage = Str::startsWith($file->mime_type, 'image/');
                                    $icon = match (true) {
                                        Str::contains($file->mime_type, 'pdf') => '/assets/icons/pdf-icon.png',
                                        Str::contains($file->mime_type, 'msword'),
                                        Str::contains($file->mime_type, 'wordprocessingml')
                                            => '/assets/icons/docx-icon.png',
                                        default => '/assets/icons/file-icon.png',
                                    };
                                    $thumbnail = $isImage ? asset('storage/' . $file->file_url) : asset($icon);
                                @endphp
                                <div class="col-6 col-md-3 col-lg-2 mb-2">
                                    <div class="p-2 border rounded bg-white shadow position-relative">
                                        <div class="dropdown position-absolute top-0 end-0 m-2">
                                            <button class="text-dark" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">

                                                <li><a href="{{ asset('storage/' . $file->file_url) }}" target="_blank"
                                                        class="dropdown-item">Show</a></li>
                                                <li><button href="#" class="dropdown-item"
                                                        onclick="deleteFile({{ $file->id }})">Delete</button></li>
                                            </ul>
                                        </div>
                                        <a href="{{ asset('storage/' . $file->file_url) }}" target="_blank">
                                            <img src="{{ $thumbnail }}" alt="{{ $file->alt ?? $file->name }}"
                                                class="w-auto h-10 object-cover mx-auto mb-2 rounded">

                                            <div class="text-sm font-medium text-center text-truncate">{{ $file->name }}
                                            </div>
                                            <div class="text-xs text-gray-500 text-center text-truncate mt-1">Size:
                                                {{ number_format($file->size / 1024, 2) }} KB
                                            </div>
                                        </a>
                                        {{--  <div class="text-xs text-gray-500">Alt: {{ $file->alt ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">Type: {{ $file->mime_type }}</div>
                                   
                                    <div class="text-xs text-gray-500">Uploaded: {{ $file->created_at->diffForHumans() }}
                                    </div> --}}
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-lg-12 text-center">
                                <h3 class="py-4">No Data Found..!</h3>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fileInput = document.getElementById("fileUpload");

            // Trigger file picker
            document.querySelector('[onclick*="fileUpload"]').addEventListener("click", function(e) {
                e.preventDefault();
                fileInput.click();
            });

            // Handle file selection
            fileInput.addEventListener("change", function() {
                if (!this.files.length) return;

                const file = this.files[0];
                const formData = new FormData();
                formData.append("file", file);

                // Dynamically determine the selected folder_id
                const selectedFolderId = window.currentFolderId || 1; // fallback to 1
                formData.append("folder_id", selectedFolderId);

                fetch("{{ route('company.media.file.upload') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        body: formData,
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert("File uploaded!");
                            location.reload(); // reload to show the new file
                        } else {
                            alert("Upload failed!");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Something went wrong while uploading the file.");
                    });
            });
        });


        /////////////////////////////////////////////////////////////////////////

        function deleteFolder(folderId) {
            if (confirm("Are you sure you want to delete this folder?")) {
                fetch(`/company/media/folder/delete/${folderId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(res => res.json()).then(data => {
                    if (data.success) location.reload();
                });
            }
        }

        function deleteFile(fileId) {
            if (confirm("Are you sure you want to delete this file?")) {
                fetch(`/company/media/files/delete/${fileId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(res => res.json()).then(data => {
                    if (data.success) location.reload();
                });
            }
        }
    </script>
@endpush
