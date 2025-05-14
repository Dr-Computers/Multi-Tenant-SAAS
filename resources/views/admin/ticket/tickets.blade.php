@extends('layouts.admin')
@section('page-title')
    {{ __('Users') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">{{ __('Support Ticket') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-bUsers-style">
                    @can('ticket listing')
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Ticket Number</th>
                                        <th>Subject</th>
                                        <th>Type</th>
                                        <th>From</th>
                                        <th>Date & Time</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        @if (auth()->user()->type == 'admin')
                                            <th>Assign</th>
                                        @endif
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    @foreach ($tickets ?? [] as $ticket)
                                        <tr @if ($ticket->read_at == null)  @endif>
                                            <td><strong><a
                                                        href="{{ route('company.tickets.view', ['company_id' => $ticket->company_id, 'ticket_no' => $ticket->ticket_no]) }}">TNO{{ $ticket->ticket_no }}</a></strong>
                                            </td>
                                            <td><a
                                                    href="{{ route('company.tickets.view', ['company_id' => $ticket->company_id, 'ticket_no' => $ticket->ticket_no]) }}">{{ $ticket->subject }}</a>
                                            </td>
                                            <td class="text-capitalize">{{ $ticket->type }}</td>
                                            <td>{{ $ticket->company->name }}
                                            </td>
                                            <td>{{ dateTimeFormat($ticket->created_at) }}
                                            </td>

                                            <td>
                                                @if ($ticket->priority == 'urgent')
                                                    <span class="badge bg-danger ">Urgent</span>
                                                @else
                                                    <span class="badge bg-warning">Normal</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($ticket->status == '1')
                                                    <span class="badge bg-success">Open</span>
                                                @else
                                                    <span class="badge bg-primary">Closed</span>
                                                @endif
                                            </td>
                                            @if (auth()->user()->type == 'admin')
                                                <td>
                                                    <select class="form-control asigned_staff"
                                                        data-tno="{{ $ticket->ticket_no }}" name="asigned_staff"
                                                        id="asigned_staff">
                                                        <option value="0" disabled selected>
                                                            Choose Rep</option>
                                                        @foreach ($staffs ?? [] as $rep)
                                                            <option value="{{ $rep->id }}"
                                                                @if ($ticket->staff_id == $rep->id) selected="selected" @endif>
                                                                {{ $rep->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            @endif
                                            <td class="text-center">
                                                @can('reply ticket')
                                                    <div class="action-btn me-2">
                                                        <a class="mx-3 btn btn-sm d-inline-flex align-items-center bg-info"
                                                            href="{{ route('admin.tickets.view', ['company_id' => $ticket->company_id, 'ticket_no' => $ticket->ticket_no]) }}">
                                                            <span> <i class="ti ti-eye text-white"></i></span>
                                                        </a>
                                                    </div>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer')
    <script>
        $(document).ready(function() {
            $(".asigned_staff").change(function(s) {
                var staff_id = $(this).val();
                var tckt_no = $(this).data('tno');
                var url = "{{ route('admin.tickets.assigned_staff') }}";
                if (staff_id != 0) {
                    $.ajax({
                        url: url,
                        type: "GET",
                        data: {
                            'staff_id': staff_id,
                            'tckt_no': tckt_no
                        },
                        success: function(data) {
                            console.log(data)
                        }
                    });
                }
            });
        });
    </script>
@endpush
