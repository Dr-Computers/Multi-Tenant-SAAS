@canany(['create invoice template', 'edit invoice template'])
    {{ Form::open([
        'route' => isset($template) ? ['admin.templates.invoices.update', $template->id] : 'admin.templates.invoices.store',
        'method' => isset($template) ? 'put' : 'post',
        'class' => 'needs-validation',
        'enctype' => 'multipart/form-data',
        'novalidate',
    ]) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="form-group">
                    <img src="{{ isset($template) ? asset('storage/' . $template->image) : asset('storage/uploads/defualt/defualt.png') }}"
                        id="myAvatar" alt="user-image" class="img-thumbnail w-auto" style="height:100px">
                    <div class="choose-files mt-3">
                        <label for="avatar">
                            <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>Choose file here</div>
                            <input type="file" accept="image/png, image/gif, image/jpeg,  image/jpg"
                                class="form-control d-none" name="image" id="avatar" data-filename="avatar-logo">
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('name', isset($template) ? $template->name : null, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => __('Enter  Name'), 'required' => 'required']) }}
                    @error('name')
                        <small class="invalid-name" template="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            </div>
            
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <input type="submit" value="{{ isset($template) ? __('Update') : __('Create') }}" class="btn btn-primary">
    </div>
    {{ Form::close() }}
@endcanany
