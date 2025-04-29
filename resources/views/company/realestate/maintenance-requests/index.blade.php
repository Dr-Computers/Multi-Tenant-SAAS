@extends('layouts.company')
@section('page-title')
    {{ __('Furnishings') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Furnishings') }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        <button href="#" data-size="lg" data-url="{{ route('company.realestate.maintaince-requests.create') }}"
            data-ajax-popup2="true" data-bs-toggle="tooltip" title="{{ __('Create a new Request ') }}"
            class="btn btn-sm btn-primary me-2">
            <i class="ti ti-plus"></i> Create a new Request
        </button>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-bUsers-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('#') }}</th>
                                    <th>{{ __('Property') }}</th>
                                    <th>{{ __('Unit') }}</th>
                                    <th>{{ __('Issue') }}</th>
                                    <th>{{ __('Maintainer') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($requests ?? [] as $key => $req)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $req->property ? $req->property->name : '---' }}</td>
                                        <td>{{ $req->property && $req->property->unit ? $req->unit->name : '---' }}</td>
                                        <td>{{ $req->issue ? $req->issue->name : '' }}</td>
                                        <td>{{ $req->maintainer ? $req->maintainer->name : '---' }}</td>
                                        <td>
                                            @if ($req->status == '1')
                                                <span class="badge bg-success p-1 px-3 rounded">
                                                    {{ ucfirst('Approved') }}</span>
                                            @elseif($req->status == '2')
                                                <span class="badge bg-danger p-1 px-3 rounded">
                                                    {{ ucfirst('Rejected') }}</span>
                                            @elseif($req->status == '0')
                                                <span class="badge bg-warning p-1 px-3 rounded">
                                                    {{ ucfirst('Pending') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ dateTimeFormat($req->created_at) }}</td>
                                        <td></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <h6>No Requests found..!</h6>
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
@endsection


@push('footer')
    <script>
        $('body').on('chnage', '.propertySelect', function() {
            alert(1)
            // const propertyId = $(this).val();
            // const unitSelect = $('#unitSelect');
            // const url = `{{ route('company.realestate.maintaince-requests.units', ':id') }}`.replace(':id',
            //     propertyId);

            // unitSelect.html('<option value="">Loading...</option>');

            // $.ajax({
            //     url: url,
            //     type: 'GET',
            //     dataType: 'json',
            //     success: function(data) {
            //         let options = '<option value="">-- Select Unit --</option>';
            //         $.each(data, function(index, unit) {
            //             options += `<option value="${unit.id}">${unit.unit_number}</option>`;
            //         });
            //         unitSelect.html(options);
            //     },
            //     error: function() {
            //         unitSelect.html('<option value="">-- Select Unit --</option>');
            //     }
            // });
        });
    </script>
@endpush
