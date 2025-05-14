@canany(['create staff user', 'edit staff user'])
    <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="post"
        class="needs-validation" novalidate enctype="multipart/form-data">
        @csrf
        @if (isset($user))
            @method('PUT')
        @endif
        <div class="modal-body">
            <div class="row">
                {{-- <h6 class="text-md fw-bold text-secondary text-sm">User Details</h6> --}}
                <div class="col-lg-12 mb-3">
                    <div class="form-group">
                        <img src="{{ isset($user) ? asset('storage/' . $user->avatar_url) : asset('storage/uploads/avatar/avatar.png') }}"
                            id="myAvatar" alt="user-image" class="img-thumbnail w-auto" style="height:100px">
                        <div class="choose-files mt-3">
                            <label for="avatar">
                                <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>Choose file here</div>
                                <input type="file" accept="image/png, image/gif, image/jpeg,  image/jpg"
                                    class="form-control d-none" name="profile" id="avatar" data-filename="avatar-logo">
                            </label>
                        </div>
                    </div>
                </div>
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
                        <label for="email" class="form-label">Role</label><x-required></x-required>
                        <select name="role" class="form-control select" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ old('role', $user->role->id ?? '') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
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

            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
    </form>
@endcanany
