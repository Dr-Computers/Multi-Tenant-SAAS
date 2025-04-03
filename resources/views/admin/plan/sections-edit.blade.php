@php
    $chatGPT = \App\Models\Utility::settings('enable_chatgpt');
    $enable_chatgpt = !empty($chatGPT);
@endphp
{{ Form::model($section, ['route' => ['admin.plans.section.update', $section->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('category', __('Category'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('category', null, ['class' => 'form-control font-style', 'placeholder' => __('Enter category'), 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('name', null, ['class' => 'form-control font-style', 'placeholder' => __('Enter Plan Name'), 'required' => 'required']) }}
        </div>

        <div class="form-group col-md-12">
            {{ Form::label('price', __('Price'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::number('price', null, ['class' => 'form-control', 'placeholder' => __('Enter Plan Price'), 'required' => 'required', 'step' => '0.01']) }}
        </div>


    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
