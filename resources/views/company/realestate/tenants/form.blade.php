<form
    action="{{ isset($user) ? route('company.realestate.tenants.update', $user->id) : route('company.realestate.tenants.store') }}"
    method="post" class="needs-validation" novalidate>
    @csrf
    @if (isset($user))
        @method('PUT')
    @endif
    <div class="modal-body">
        <div class="row">
            <h6 class="text-md fw-bold text-secondary text-sm">Personal Details</h6>

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

            <div class="col-md-12">
                <div class="form-group">
                    <label for="trn_no" class="form-label">TRN Number</label><x-required></x-required>
                    <input type="text" name="trn_no" value="{{ old('trn_no', $user->name ?? '') }}"
                        class="form-control" placeholder="Enter TRN Number" autocomplete="off" required>
                    @error('trn_no')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
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

            <div class="col-md-12">
                <div class="form-group">
                    <label for="address" class="form-label">Address</label><x-required></x-required>
                    <textarea type="text" name="address"
                        class="form-control" placeholder="Enter Address" autocomplete="off" required >{{ old('address', $user->personal->address ?? '') }}</textarea>
                    @error('address')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="city" class="form-label">City</label><x-required></x-required>
                    <input type="text" name="city" value="{{ old('city', $user->personal->city ?? '') }}"
                        class="form-control" placeholder="Enter City" autocomplete="off" required>
                    @error('city')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="state" class="form-label">State</label><x-required></x-required>
                    <input type="text" name="state" value="{{ old('state', $user->personal->state ?? '') }}"
                        class="form-control" placeholder="Enter State" autocomplete="off" required>
                    @error('state')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="postal_code" class="form-label">Postal code/Zip code</label><x-required></x-required>
                    <input type="text" name="postal_code" value="{{ old('postal_code', $user->personal->postal_code ?? '') }}"
                        class="form-control" placeholder="Enter Postal code/Zip code" autocomplete="off" required>
                    @error('postal_code')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="country" class="form-label">Country</label><x-required></x-required>
                    <input type="text" name="country" value="{{ old('country', $user->personal->country ?? '') }}"
                        class="form-control" placeholder="Enter Country" autocomplete="off" required>
                    @error('country')
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
