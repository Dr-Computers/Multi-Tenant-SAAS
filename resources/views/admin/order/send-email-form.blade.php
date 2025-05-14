<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.order.send-email.process',$order->id) }}" method="POST">
                @csrf
                <div class="form-group my-2">
                    <label class="mb-2" for="">Email Id</label>
                    <input type="email" class="form-control" name="email" value="{{ $company ? $company->email : '' }}">
                </div>
                <div class="form-group text-center">
                    <button class="btn btn-primary" type="submit">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
