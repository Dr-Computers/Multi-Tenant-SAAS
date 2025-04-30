<form action="{{ isset($user) ? route('admin.company.update', $user->id) : route('admin.company.store') }}" method="post" enctype="multipart/form-data"
    class="needs-validation" novalidate>
    @csrf
    @if (isset($user))
        @method('PUT')
    @endif
    <div class="modal-body">
        <div class="row">

            {{-- <h6 class="text-md fw-bold text-secondary text-sm">Company Details</h6> --}}
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
                    <label for="name" class="form-label">Business Name</label><x-required></x-required>
                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
                        class="form-control" placeholder="Enter Business Name" autocomplete="off" required>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="bussiness_type" class="form-label">Business Type</label><x-required></x-required>
                    <select name="bussiness_type" class="form-control select selectBusinessType" required>
                        @foreach ($business_types as $type)
                            <option value="{{ $type->id }}"
                                {{ old('bussiness_type', $user->company->bussiness_type ?? '') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('bussiness_type')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="email" class="form-label">Email ID</label><x-required></x-required>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
                        class="form-control" placeholder="Enter Company Email" autocomplete="off" required>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>


            <input type="hidden" name="role" value="company">
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
                    <label for="address" class="form-label">Full Address</label><x-required></x-required>
                    <textarea name="address" class="form-control" rows="2" placeholder="Enter Full Address" required>{{ old('address', $user->company->address ?? '') }}</textarea>
                    @error('address')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="landmark" class="form-label">Landmark</label>
                    <input type="text" name="landmark" value="{{ old('landmark', $user->company->landmark ?? '') }}"
                        class="form-control" placeholder="Enter Company Landmark">
                    @error('landmark')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="postalcode" class="form-label">Postal Code</label><x-required></x-required>
                    <input type="text" name="postalcode"
                        value="{{ old('postalcode', $user->company->postalcode ?? '') }}" class="form-control"
                        placeholder="Enter Postal Code" required>
                    @error('postalcode')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="city" class="form-label">City</label><x-required></x-required>
                    <input type="text" name="city" value="{{ old('city', $user->company->city ?? '') }}"
                        class="form-control" placeholder="Enter City" required>
                    @error('city')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="identify_code" class="form-label">Company Prefix</label><x-required></x-required>
                    <input type="text" name="identify_code"
                        value="{{ old('identify_code', $user->company->identify_code ?? '') }}" class="form-control"
                        placeholder="Enter Prefix" required>
                    @error('identify_code')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            @if (!isset($user))
                <hr>
                {{-- Allowed Sections --}}
                <div class="form-group col-md-12">
                    <label class="form-label mb-3">{{ __('Select plan') }}</label><x-required></x-required>
                    @foreach ($business_types as $category => $business_type)
                        <div class=" mt-2 businessCategory {{ 'BusinessType-' . $business_type->id }}"
                            style="display: none">
                            <div class="card-body">
                                <div class="row">
                                    @forelse ($plans->where('business_type', $business_type->id) as $section)
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check form-switch custom-switch-v1">
                                                <input type="radio" name="plan"
                                                    class="form-check-input input-primary pointer"
                                                    value="{{ $section->id }}" id="section_{{ $section->id }}"
                                                    {{ isset($plan) && $plan->module_section && in_array($section->id, $plan->module_section->pluck('section_id')->toArray()) ? 'checked' : '' }}>
                                                <label class="form-check-label text-sm"
                                                    for="section_{{ $section->id }}">{{ $section->name }}</label>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-md-6 mb-2">
                                            <h6>No Plans available</h6>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif



            @if (!$customFields->isEmpty())
                <div class="col-md-6">
                    <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                        @include('customFields.formBuilder')
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Create</button>
    </div>
</form>


<script>
    $(document).on('change', '.selectBusinessType', function() {
        var selectedBusinessType = $(this).val();
        $('.businessCategory').hide();
        $('.BusinessType-' + selectedBusinessType).show();
    });

    $('.selectBusinessType').trigger('change');
</script>
