@can('manage furnishing request')
    {{ Form::open([
        'route' => 'company.realestate.furnishing.store',
        'method' => 'post',
        'class' => 'needs-validation',
        'novalidate',
    ]) }}
    <div class="modal-body">

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('name', __('Furnishing Name'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('name', isset($furnishing) ? $furnishing->name : null, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => __('Enter Furnishing Name'), 'required' => 'required']) }}
                    @error('name')
                        <small class="invalid-name" furnishing ="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
                <div class="form-group">
                    {{ Form::label('notes', __('Notes'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::textarea('notes', isset($furnishing) ? $furnishing->notes : null, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => __('Notes')]) }}
                    @error('notes')
                        <small class="invalid-name" furnishing ="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>

            </div>
        </div>

    </div>

    <div class="modal-footer">
        <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
    </div>
    {{ Form::close() }}
@endcan
