@php
    $chatGPT = \App\Models\Utility::settings('enable_chatgpt');
    $enable_chatgpt = !empty($chatGPT);
@endphp
@can('edit section')
    {{ Form::model($section, ['route' => ['admin.plans.section.update', $section->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                {{ Form::label('category', __('Category'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('category', null, ['class' => 'form-control font-style text-capitalize', 'readonly', 'placeholder' => __('Enter category'), 'required' => 'required']) }}
            </div>
            <div class="form-group col-md-12">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('name', null, ['class' => 'form-control font-style text-capitalize', 'readonly', 'placeholder' => __('Enter Plan Name'), 'required' => 'required']) }}
            </div>

            <div class="form-group col-md-6">
                {{ Form::label('price', __('Price'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('price', null, ['class' => 'form-control', 'placeholder' => __('Enter Plan Price'), 'required' => 'required', 'step' => '0.01']) }}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('duration', __('Duration'), ['class' => 'form-label']) }}<x-required></x-required>
                <br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" value="monthly" checked name="duration"
                        id="flexRadioDefault1">
                    <label class="form-check-label" for="flexRadioDefault1">
                        Monthly
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" value="yearly" name="duration" id="flexRadioDefault2">
                    <label class="form-check-label" for="flexRadioDefault2">
                        Yearly
                    </label>
                </div>
            </div>
            <div class="form-group col-md-12">
                @if (!empty($permissions))
                    <h6 class="my-3">{{ __('Assign Permission to the feature') }}</h6>
                    <div class="mb-2">
                        <input type="checkbox" id="checkall" class="form-check-input">
                        <label for="checkall" class="form-check-label"><strong>{{ __('Check All') }}</strong></label>
                    </div>
                    <div class="row mb-2">
                        @foreach ($permissions->groupBy('section') ?? [] as $category => $permission)
                            <div class="col-md-4 mb-3">
                                <div class="col-lg-12 mb-2">
                                    <h6 class="fw-bold">{{ $category }}</h6>
                                </div>
                                <div class="row">
                                    @foreach ($permission ?? [] as $permission_section)
                                        <div class="col-md-12 mb-2 custom-control custom-checkbox">
                                            {{ Form::checkbox(
                                                'permissions[]',
                                                $permission_section->id,
                                                isset($section) ? $section->permissions->contains($permission_section->id) : false,
                                                [
                                                    'class' => 'form-check-input isscheck isscheck_' . str_replace(' ', '', $permission_section->name),
                                                    'id' => 'permission' . $permission_section->id,
                                                ],
                                            ) }}
                                            {{ Form::label('permission' . $permission_section->id, $permission_section->name, ['class' => 'form-check-label text-capitalize']) }}<br>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
    </div>
    {{ Form::close() }}
@endcan

<script>
    $(document).ready(function() {
        $("#checkall").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    });
</script>
