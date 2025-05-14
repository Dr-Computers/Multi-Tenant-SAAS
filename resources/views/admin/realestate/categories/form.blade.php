@canany(['create category', 'edit category'])
    {{ Form::open([
        'route' => isset($category)
            ? ['admin.realestate.categories.update', $category->id]
            : 'admin.realestate.categories.store',
        'method' => isset($category) ? 'put' : 'post',
        'class' => 'needs-validation',
        'novalidate',
    ]) }}
    <div class="modal-body">

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('name', isset($category) ? $category->name : null, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => __('Enter  Name'), 'required' => 'required']) }}
                    @error('name')
                        <small class="invalid-name" category="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <input type="submit" value="{{ isset($category) ? __('Update') : __('Create') }}" class="btn btn-primary">
    </div>
    {{ Form::close() }}
@endcanany
