{{ Form::open([
    'route' => isset($role) ? ['admin.roles.update', $role->id] : 'admin.roles.store',
    'method' => isset($role) ? 'put' : 'post',
    'class' => 'needs-validation',
    'novalidate'
]) }}
<div class="modal-body">

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('name', isset($role) ? $role->name : null, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => __('Enter Role Name'), 'required' => 'required']) }}
                @error('name')
                    <small class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </small>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                @if (!empty($permissions))
                    <h6 class="my-3">{{ __('Assign Permission to Roles') }}</h6>
                    <div class="mb-2">
                        <input type="checkbox" id="checkall" class="form-check-input">
                        <label for="checkall" class="form-check-label"><strong>{{ __('Check All') }}</strong></label>
                    </div>
                    @foreach ($permissions->groupBy('section') ?? [] as $section => $permission)
                        <div class="row mb-2">
                            <div class="col-lg-12 mb-2">
                                <h6 class="fw-bold">{{ $section }}</h6>
                            </div>
                            <div class="row">
                                @foreach ($permission ?? [] as $permission_section)
                                    <div class="col-md-12 mb-2 custom-control custom-checkbox">
                                        {{ Form::checkbox(
                                            'permissions[]', 
                                            $permission_section->id, 
                                            isset($role) ? $role->permissions->contains($permission_section->id) : false, 
                                            ['class' => 'form-check-input isscheck isscheck_' . str_replace(' ', '', $permission_section->name), 'id' => 'permission' . $permission_section->id]
                                        ) }}
                                        {{ Form::label('permission' . $permission_section->id, $permission_section->name, ['class' => 'form-check-label text-capitalize']) }}<br>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ isset($role) ? __('Update') : __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        $("#checkall").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    });
</script>
