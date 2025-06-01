@can('create plan')
    <form action="{{ isset($plan) ? route('admin.plans.update', $plan->id) : route('admin.plans.store') }}" method="POST"
        enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf
        @if (isset($plan))
            @method('PUT')
        @endif

        <div class="modal-body">
            <div class="row">
                {{-- Plan Name --}}
                <div class="form-group col-md-12">
                    <label for="name" class="form-label">{{ __('Name') }}</label><x-required></x-required>
                    <input type="text" name="name" class="form-control font-style" autocomplete="off"
                        value="{{ isset($plan) ? $plan->name : old('name') }}" placeholder="{{ __('Enter Plan Name') }}"
                        required>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Business Type --}}
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="business_type"
                            class="form-label">{{ __('Business Type') }}</label><x-required></x-required>
                        <select name="business_type" class="form-control select" required>
                            @foreach ($business_types as $type)
                                <option value="{{ $type->id }}"
                                    {{ (isset($plan) && $plan->business_type == $type->id) || old('business_type') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('business_type')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- Price --}}
                <div class="form-group col-md-6">
                    <label for="price" class="form-label">{{ __('Price') }}</label><x-required></x-required>
                    <input type="number" name="price" class="form-control" autocomplete="off"
                        value="{{ isset($plan) ? $plan->price : old('price') }}" placeholder="{{ __('Enter Price') }}"
                        required step="0.01">
                    @error('price')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Duration --}}
                <div class="form-group col-md-6">
                    <label for="duration" class="form-label">{{ __('Duration') }}</label><x-required></x-required>
                    <select name="duration" class="form-control select" required>
                        @foreach ($arrDuration as $key => $value)
                            <option value="{{ $key }}"
                                {{ (isset($plan) && $plan->duration == $key) || old('duration') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('duration')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Max Users --}}
                <div class="form-group col-md-6">
                    <label for="max_users" class="form-label">{{ __('Max Staff Users') }}</label><x-required></x-required>
                    <input type="number" name="max_users" class="form-control" autocomplete="off"
                        value="{{ isset($plan) ? $plan->max_users : old('max_users') }}" required>
                    <span class="small">{{ __('Note: "-1" for Unlimited') }}</span>
                    @error('max_users')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Max Customers --}}
                <div class="form-group col-md-6">
                    <label for="max_customers" class="form-label">{{ __('Max Tenants') }}</label><x-required></x-required>
                    <input type="number" name="max_customers" class="form-control" autocomplete="off"
                        value="{{ isset($plan) ? $plan->max_tenants : old('max_customers') }}" required>
                    <span class="small">{{ __('Note: "-1" for Unlimited') }}</span>
                    @error('max_customers')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Max Customers --}}
                <div class="form-group col-md-6">
                    <label for="max_venders" class="form-label">{{ __('Max Owners') }}</label><x-required></x-required>
                    <input type="number" name="max_owners" class="form-control" autocomplete="off"
                        value="{{ isset($plan) ? $plan->max_owners : old('max_venders') }}" required>
                    <span class="small">{{ __('Note: "-1" for Unlimited') }}</span>
                    @error('max_venders')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>


                {{-- Storage Limit --}}
                <div class="form-group col-md-6">
                    <label for="storage_limit"
                        class="form-label">{{ __('Storage Limit') }}</label><x-required></x-required>
                    <div class="input-group search-form">
                        <input type="number" name="storage_limit" class="form-control" autocomplete="off"
                            value="{{ isset($plan) ? $plan->storage_limit : old('storage_limit') }}" required>
                        <span class="input-group-text bg-transparent">{{ __('MB') }}</span>
                    </div>
                    @error('storage_limit')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="form-group col-md-12">
                    <label for="description" class="form-label">{{ __('Description') }}</label>
                    <textarea name="description" class="form-control" rows="3">{{ isset($plan) ? $plan->description : old('description') }}</textarea>
                    @error('description')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Allowed Sections --}}
                <div class="form-group col-md-12">
                    <label class="form-label mb-3">{{ __('Select allowed sections') }}</label>
                    @foreach ($sections->groupBy('category') as $category => $groupedSections)
                        <div class=" mt-2">
                            <div class="card-header">
                                <h6 class="mb-3 text-sm text-dark fw-bold">{{ $category }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    @foreach ($groupedSections as $section)
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check form-switch custom-switch-v1">
                                                <input type="checkbox" name="sections[]"
                                                    class="form-check-input input-primary pointer"
                                                    value="{{ $section->id }}" id="section_{{ $section->id }}"
                                                    {{ isset($plan) && $plan->module_section && in_array($section->id, $plan->module_section->pluck('section_id')->toArray()) ? 'checked' : '' }}>
                                                <label class="form-check-label text-sm text-capitalize"
                                                    for="section_{{ $section->id }}">{{ $section->name }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Trial Section --}}
                <div class="row mb-4">
                    <div class="col-md-12 mt-3 plan_price_div">
                        <label for="trial" class="form-label">{{ __('Trial Enable (On/Off)') }}</label>
                        <div class="form-check form-switch custom-switch-v1 float-end">
                            <input type="checkbox" name="trial" class="form-check-input input-primary pointer"
                                value="1" id="trial" {{ isset($plan) && $plan->trial ? 'checked' : '' }}>
                            <label class="form-check-label" for="trial"></label>
                        </div>
                    </div>

                    {{-- Trial Days --}}
                    <div class="col-md-12 {{ isset($plan) && $plan->trial ? '' : 'd-none' }} plan_div">
                        <div class="form-group">
                            <label for="trial_days" class="form-label">{{ __('Trial Days') }}</label>
                            <input type="number" name="trial_days" class="form-control"
                                value="{{ isset($plan) ? $plan->trial_days : old('trial_days') }}"
                                placeholder="{{ __('Enter Trial days') }}" step="1" min="1">
                            @error('trial_days')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="btn btn-primary">{{ isset($plan) ? __('Update') : __('Create') }}</button>
        </div>
    </form>
@endcan
