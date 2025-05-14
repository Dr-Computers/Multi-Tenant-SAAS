@can('create permission')
    <div class="modal-body">
        <div class="row py-4">
            <form action="{{ route('admin.permissions.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="permission_file" class="form-control" required>
                <button type="submit" class="btn btn-primary my-3 text-center">Upload</button>
            </form>
        </div>
    </div>
@endcan