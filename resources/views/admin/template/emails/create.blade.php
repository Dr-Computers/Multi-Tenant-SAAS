@can('create email template')
    {{ Form::open(['url' => 'admin.email_template', 'method' => 'post']) }}
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('name', __('Name')) }}
            {{ Form::text('name', null, ['class' => 'form-control font-style', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-12 text-right">
            {{--        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button> --}}
            {{ Form::submit(__('Create'), ['class' => 'btn btn-primary']) }}
        </div>
    </div>
    {{ Form::close() }}
@endcan
