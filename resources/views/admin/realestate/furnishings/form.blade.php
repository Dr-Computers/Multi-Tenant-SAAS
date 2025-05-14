@canany(['create furnishing', 'edit furnishing'])
    {{ Form::open([
        'route' => isset($furnishing)
            ? ['admin.realestate.furnishings.update', $furnishing->id]
            : 'admin.realestate.furnishings.store',
        'method' => isset($furnishing) ? 'put' : 'post',
        'class' => 'needs-validation',
        'novalidate',
    ]) }}
    <div class="modal-body">

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('name', isset($furnishing) ? $furnishing->name : null, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => __('Enter  Name'), 'required' => 'required']) }}
                    @error('name')
                        <small class="invalid-name" furnishing="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <input type="submit" value="{{ isset($furnishing) ? __('Update') : __('Create') }}" class="btn btn-primary">
    </div>
    {{ Form::close() }}
@endcanany
