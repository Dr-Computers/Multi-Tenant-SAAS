@can('manage landmark request')
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.realestate.landmarks.request-accept', $categoryRequest->id) }}"
                        method="POST" class="">
                        @csrf

                        <h5 >Requested Company Name : </h5> 
                        <h6 class="mb-3 mt-2 fw-bold">{{ $categoryRequest->company->name }}</h6>

                        <h5 >Requested Name : </h5> 
                        <h6 class="mb-3 mt-2 fw-bold">{{ $categoryRequest->request_for }}</h6>

                        <div class="form-group">
                            <label class="mb-1 mt-3">Notes : </label><br>
                            @if ($categoryRequest->status == 0)
                                <textarea class="col-lg-12 form-control" name="" rows="3">{{ $categoryRequest->notes }}</textarea>
                            @else
                                <p class="fw-bold">
                                    {{ $categoryRequest->notes }}
                                </p>
                                <label class="mb-1 mt-3">Status : </label>
                                @if ($categoryRequest->status == 1)
                                    <span class="badge bg-success p-1 px-2 rounded">
                                        {{ ucfirst('Accepted') }}</span>
                                @else
                                    <span class="badge bg-danger p-1 px-2 rounded">
                                        {{ ucfirst('Rejected') }}</span>
                                @endif
                            @endif
                        </div>
                        <div class="form-group mt-4">
                            @if ($categoryRequest->status == 0)
                                <button type="submit"
                                    formaction="{{ route('admin.realestate.landmarks.request-reject', $categoryRequest->id) }}"
                                    class="btn btn-sm  btn-danger">Reject</button>

                                <button type="submit" class="btn btn-sm ms-4 btn-success">Accept</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan