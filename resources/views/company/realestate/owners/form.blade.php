<form action="{{ isset($user) ? route('company.realestate.owners.update', $user->id) : route('company.realestate.owners.store') }}"
    method="post" class="needs-validation" novalidate>
    @csrf
    @if(isset($user)) @method('PUT') @endif
    <div class="modal-body">
        <div class="row">
            <h6 class="text-md fw-bold text-secondary text-sm">Owner Details</h6>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="name" class="form-label">Name</label><x-required></x-required>
                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
                        class="form-control" placeholder="Enter Name" autocomplete="off" required>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="email" class="form-label">Email ID</label><x-required></x-required>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
                        class="form-control" placeholder="Enter Email" autocomplete="off" required>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-md-12 mb-3 mt-4">
                <label for="tenants_approval">Tenants Approval Needed</label>
                <div class="form-check form-switch float-end">
                    <input type="checkbox" {{ isset($user) && $user->owner && $user->owner->is_tenants_approval == '1' ? 'checked' : 'false'  }} name="is_tenants_approval" class="form-check-input" value="on"
                        id="tenants_approval">
                    <label class="form-check-label" for="tenants_approval"></label>
                </div>
            </div>

            @if (!isset($user))
                <div class="col-md-12 mb-3 mt-4">
                    <label for="password_switch">Login is enabled</label>
                    <div class="form-check form-switch float-end">
                        <input type="checkbox" name="password_switch" class="form-check-input" value="on"
                            id="password_switch">
                        <label class="form-check-label" for="password_switch"></label>
                    </div>
                </div>
            @endif

            <div class="col-md-12 ps_div d-none">
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" autocomplete="new-password" name="password" class="form-control"
                        placeholder="Enter Company Password" minlength="6">
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>


            <div class="col-md-12">
                <div class="form-group">
                    <label for="mobile" class="form-label">Mobile No</label><x-required></x-required>
                    <input type="text" name="mobile" value="{{ old('mobile', $user->mobile ?? '') }}"
                        class="form-control" placeholder="Enter Company Mobile" autocomplete="off" required>
                    @error('mobile')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Create</button>
    </div>
</form>
