{{ Form::open([
    'route' => isset($landmark) ? ['admin.realestate.landmarks.update', $landmark->id] : 'admin.realestate.landmarks.store',
    'method' => isset($landmark) ? 'put' : 'post',
    'class' => 'needs-validation',
    'novalidate'
]) }}
<div class="modal-body">

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('name', isset($landmark) ? $landmark->name : null, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => __('Enter  Name'), 'required' => 'required']) }}
                @error('name')
                    <small class="invalid-name" landmark="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </small>
                @enderror
            </div>
        </div>
    </div>

</div>

<div class="modal-footer">
    <input type="submit" value="{{ isset($landmark) ? __('Update') : __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}

