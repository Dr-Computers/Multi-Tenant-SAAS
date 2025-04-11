<form method="POST" action="{{ route('company.hrms.users.reset.update', $user->id) }}">
    @csrf
    <div class="modal-body">
        <h5 class="mb-3 fw-bold text-primary">Reset Password for {{ $user->name }}</h5>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="password" class="form-control" required minlength="6">
        </div>
        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control" required minlength="6">
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Reset</button>
    </div>
</form>
