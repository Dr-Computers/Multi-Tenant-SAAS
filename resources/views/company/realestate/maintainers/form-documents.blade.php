<div class="modal-body">
    <div class="row" x-data="documentUploader()">
        <div class="col-md-12">
            <div class="form-group">
                <label for="doc_typ" class="form-label">Document Type</label><x-required></x-required>
                <input type="text" name="document_type" value="{{ old('document_type', $user->document_type ?? '') }}" class="form-control"
                    placeholder="Enter Document Type" id="document_type" autocomplete="off" required>
                @error('document_type')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-md-12">

            <div class="mt-3 border-dashed border-2 border-gray-300 rounded-lg p-3  bg-gray-100">
                <div class="mx-auto p-2 bg-white shadow rounded-lg space-y-6">
                    <!-- Document Preview Grid -->
                    <div class="grid grid-cols-4 md:grid-cols-5 gap-4">
                        <!-- Existing Documents -->
                        <template x-for="(document, index) in documents" :key="index">
                            <div class="flex flex-col relative">
                                <div class="relative group border rounded-lg overflow-hidden">
                                    <!-- Document -->
                                    <img :src="document.url" style="height: 100px;" alt="Uploaded Document"
                                        class="w-30 h-30 object-cover">
                                    <!-- Overlay with Cover Option -->
                                    <div
                                        class="absolute flex flex-col inset-0 group-hover:opacity-100 space-y-2 transition">
                                        <!-- Remove Document -->
                                        <button @click="removeDocument(index)"
                                            class="absolute bg-white p-1 right-0 rounded-full top-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
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
                        <div class="flex flex-col col-auto text-center">
                            <div class="relative group border rounded-lg p-2 overflow-hidden"
                                @click="triggerFileInput()" x-bind:class="{ 'border-blue-500': isDragging }">
                                <img src="/assets/icons/upload-icon.png" class="w-50 mx-auto">
                                <input name="documents[]" form="propertyFrom" type="file"
                                    accept=".pdf,.docx,image/*,.webp,.csv,.excel" id="fileDocInput" class="hidden"
                                    multiple @change="addDocuments($event)">
                                <p class="text-gray-600">
                                    click to upload your files here.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            <button type="button" @click="uploadAllDocuments()" class="btn btn-primary">Upload files</button>

        </div>
    </div>
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
// file.size <= 2 * 1024 * 1024 && 
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
                        alert('Only image, PDF, and DOCX files');
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

            handleDrop(event) {
                event.preventDefault();
                this.isDragging = false;
                this.addDocuments(event);
            },

            toggleDragging(state) {
                this.isDragging = state;
            },

            uploadAllDocuments() {
                if (this.files.length === 0) {
                    alert("Please select at least one document to upload.");
                    return;
                }

                const formData = new FormData();
                var doc_typ = document.getElementById('document_type').value;
                this.files.forEach(file => {
                    formData.append('documents[]', file);
                });

                formData.append('user_id', `{{ isset($user) ? $user->id : '0' }}` || 0);
                formData.append('document_type',doc_typ)

                fetch("{{ route('company.realestate.maintainers.upload-documents',isset($user) ? $user->id : '0') }}", {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert("All files uploaded successfully!");
                            this.documents = [];
                            this.files = [];
                            location.reload();
                        } else {
                            alert("Upload failed. Please try again.");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("An error occurred while uploading files.");
                    });
            }
        };
    }
</script>
