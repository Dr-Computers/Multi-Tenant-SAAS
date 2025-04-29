<div class="my-2 text-end">
    <a href="#" data-size="lg" data-url="{{ route('company.hrms.users.create-documents') }}" data-ajax-popup2="true"
        data-bs-toggle="tooltip" title="{{ __('Upload new document') }}" class="btn btn-sm btn-primary me-2">
        <i class="ti ti-cloud-upload"></i> Upload new one
    </a>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-bdocuments-style">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Document Type') }}</th>
                                <th>{{ __('File Name') }}</th>
                                <th>{{ __('File Format') }}</th>
                                <th class="text-center">{{ __('Size') }}</th>
                                <th>{{ __('Created at') }}</th>
                                <th>{{ __('Action') }}</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($documents ??  [] as $key => $document)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <a href="{{ route('company.hrms.documents.show', $document->id) }}">
                                            <img src="{{ asset('storage/' . $document->avatar_url) }}"
                                                class="h-10 w-auto border mb-1 img-fluid rounded-circle">
                                            <span class="mt-1 text-capitalize small text-dark fw-bold truncate"
                                                title="{{ $document->name }}">{{ $document->name }}</span>
                                        </a>
                                    </td>
                                    <td><a class="text-dark truncate" title="{{ $document->email }}"
                                            href="mailto:{{ $document->email }}"> {{ $document->email }}</a></td>
                                    <td><a class="text-dark truncate" title="{{ $document->mobile }}"
                                            href="tel:{{ $document->mobile }}">{{ $document->mobile }}</a< /td>
                                    <td class="text-center">
                                        <span
                                            class="fw-bold text-primary">{{ $document->getRoleNames()->first() }}</span>
                                    </td>
                                    <td>
                                        @if ($document->is_active == 1)
                                            <span class="badge bg-success   p-1 px-2 rounded">
                                                {{ ucfirst('Enabled') }}</span>
                                        @else
                                            <span class="badge bg-danger p-1 px-2 rounded">
                                                {{ ucfirst('Disabled') }}</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="action-btn me-2">
                                            <button href="#"
                                                class="mx-3 btn btn-sm d-inline-flex align-items-center bg-dark"
                                                data-bs-toggle="tooltip" title="{{ __('Reset Password') }}"
                                                data-url="{{ route('company.hrms.documents.reset.form', $document->id) }}"
                                                data-size="xl" data-ajax-popup="true"
                                                data-original-title="{{ __('Reset Password') }}">
                                                <span> <i class="ti ti-lock text-white"></i></span>
                                            </button>
                                        </div>
                                        <div class="action-btn me-2">
                                            <a class="mx-3 btn btn-sm d-inline-flex align-items-center bg-info"
                                                href="{{ route('company.hrms.documents.show', $document->id) }}">
                                                <span> <i class="ti ti-eye text-white"></i></span>
                                            </a>
                                        </div>
                                        <div class="action-btn me-2">
                                            <button href="#"
                                                class="mx-3 btn btn-sm d-inline-flex align-items-center bg-warning"
                                                data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                data-url="{{ route('company.hrms.documents.edit', $document->id) }}"
                                                data-size="xl" data-ajax-popup="true"
                                                data-original-title="{{ __('Edit') }}">
                                                <span> <i class="ti ti-pencil text-white"></i></span>
                                            </button>
                                        </div>

                                        <div class="action-btn">
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['company.hrms.documents.destroy', $document->id],
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
