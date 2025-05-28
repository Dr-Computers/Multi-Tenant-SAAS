@extends('layouts.company')
@section('page-title')
    {{ __('Users') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">{{ __('Support Ticket') }}</li>
@endsection
@section('action-btn')
    @can('create ticket')
        <div class="d-flex">
            <button href="#" data-size="lg" data-url="{{ route('company.tickets.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="{{ __('Create New Ticket') }}" class="btn btn-sm btn-primary me-2">
                <i class="ti ti-plus"></i> Create New Ticket
            </button>
        </div>
    @endcan
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
                                        <th>Ticket Type</th>
                                        <th>Subject</th>
                                        <th>Created Time</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tickets ?? [] as $ticket)
                                        <tr>
                                            <td>TNO{{ $ticket->ticket_no }}</td>
                                            <td>{{ $ticket->type }}</td>
                                            <td>{{ $ticket->subject }}</td>
                                            <td>{{ date('d M y, h:i a', strtotime($ticket->created_at)) }}
                                            </td>
                                            <td>
                                                @if ($ticket->priority == 'urgent')
                                                    <span class="text text-danger">Urgent</span>
                                                @else
                                                    <span class="text text-success">Normal</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($ticket->status == 1)
                                                    <span class="label label-success">Opened</span>
                                                @else
                                                    <span class="label label-danger">Closed</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="action-btn me-2">
                                                    <a class="mx-3 btn btn-sm d-inline-flex align-items-center bg-info"
                                                        href="{{ route('company.tickets.view', ['company_id' => $ticket->company_id, 'ticket_no' => $ticket->ticket_no]) }}">
                                                        <span> <i class="ti ti-eye text-white"></i></span>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <h6>No users found..!</h6>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
