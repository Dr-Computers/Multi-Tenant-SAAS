<div class="modal-body">
    <div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="folderName" class="form-label">Folder Name</label><x-required></x-required>
            <input type="text" name="folderName" id="folderName" value="{{ old('name', $user->name ?? '') }}"
                class="form-control" placeholder="Folder Name" autocomplete="off" required>
            @error('folderName')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="createFolder"   class="btn btn-primary">Create Folder</button>
    </div>
    </div>
</div>

<script>
    document.getElementById('createFolder').addEventListener('click', function() {
        const name = document.getElementById('folderName').value;
        fetch("{{ route('company.media.folder.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: name,
                    parent_id : `{{ isset($folder) ? $folder->id : '0' }}` || 0,
                })
            }).then(res => res.json())
            .then(data => {
                if (data.success) location.reload();
            });
    });
</script>