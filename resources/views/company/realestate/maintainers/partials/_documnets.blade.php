<div class="my-2 text-end">
    <button href="#" data-size="lg" data-url="{{ route('company.realestate.maintainers.create-documents', $maintainer->id) }}"
        data-ajax-popup2="true" data-bs-toggle="tooltip" title="{{ __('Upload new document') }}"
        class="btn btn-sm btn-primary me-2">
        <i class="ti ti-cloud-upload"></i> Upload new one
    </button>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-bdocuments-style">
                <div class="table-responsive">
                    <table class="table ">
                        <thead>
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Document Type') }}</th>
                                <th>{{ __('File Name') }}</th>
                                <th>{{ __('File Format') }}</th>
                                <th class="text-center">{{ __('Size') }}</th>
                                <th class="text-center">{{ __('Created at') }}</th>
                                <th>{{ __('Action') }}</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($maintainer->documents ??  [] as $key => $document)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <a href="{{ asset('storage/'.$document->file->file_url) }}" target="_blank">
                                            <span class="fw-bold text-dark">{{ $document->document_type }}</span>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ asset('storage/'.$document->file->file_url) }}" target="_blank" <span
                                            class="text-dark truncate" title="{{ $document->file->name }}">
                                            {{ $document->file->name }}</span>
                                        </a>
                                    </td>
                                    <td><span class="text-dark truncate"
                                            title="{{ $document->file->mime_type }}">{{ $document->file->mime_type }}
                                        </span> </td>
                                    <td><span class="text-dark truncate"
                                            title="{{ $document->file->size }}">{{ $document->file->size }}
                                            KB</span> </td>
                                    <td class="text-center">
                                        <span
                                            class="fw-bold text-primary">{{ dateTimeFormat($document->created_at) }}</span>
                                    </td>


                                    <td>

                                        <div class="action-btn me-2">
                                            <a class="mx-3 btn btn-sm d-inline-flex align-items-center bg-info"
                                                href="{{ asset('storage/'.$document->file->file_url) }}" target="_blank">
                                                <span> <i class="ti ti-eye text-white"></i></span>
                                            </a>
                                        </div>
                                        <div class="action-btn">
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['company.realestate.maintainers.documents.destroy', $document->id],
                                                'id' => 'delete-form-' . $document->id,
                                            ]) !!}
                                            <a href="#"
                                                class="mx-4 btn btn-sm  align-items-center bs-pass-para bg-danger"
                                                data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                                <i class="ti ti-trash text-white text-white "></i></a>

                                            {!! Form::close() !!}
                                        </div>


                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <h6>No documents found..!</h6>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
