@extends('layouts.client')
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-xs-6">
          <ul class="breadcrumb">
              <li><a href="{{leadgen_manage('/')}}">Home</a></li>
              <li class="active">Tickets</li>
          </ul>                    
    </div>
     @can('Ticket Creating')
    <div class="text-right">
        <a href="{{leadgen_manage('tickets/create')}}" class="btn btn-success"><i class="fa fa-plus"></i>Add Tickets</a>
    </div>
    @endcan
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                @if(session()->has('message'))
                    {!! session('message') !!}
                @endif
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Support Tickets</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="customer-index">
                            <div class="gridlist ">
                                <div id="p0" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000">
                                    <div id="w0-pjax" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000">
                                        <div id="w0" class="grid-view is-bs3 hide-resize" data-krajee-grid="kvGridInit_b84b0ca9" data-krajee-ps="ps_w0_container">
                                            <div class="panel panel-info">
                                                @if(count($tickets))
                                                <div id="w0-container" class="table-responsive kv-grid-container">
                                                    <table class="tableFilter table table-hover table-bordered  table-condensed table-style">
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
                                                        <tbody class="ajaxFilterResult">
                                                                @foreach($tickets as $ticket)
                                                                    <tr>
                                                                       <td>TNO{{$ticket->ticket_no}}</td>
                                                                       <td>{{$ticket->type}}</td>
                                                                       <td>{{$ticket->subject}}</td>
                                                                       <td>{{date('d M y, h:i a',strtotime($ticket->created_at))}}</td>
                                                                       <td> @if($ticket->priority=='urgent')
                                                                                <span class="text text-danger">Urgent</span>
                                                                            @else
                                                                                <span class="text text-success">Normal</span>
                                                                            @endif</td>
                                                                       <td>
                                                                            @if($ticket->status=='1')
                                                                                <span class="label label-success">Opened</span>
                                                                            @else
                                                                                <span class="label label-danger">Closed</span>
                                                                            @endif
                                                                        </td>
                                                                         <td class="text-right">
                                                                       @can('Ticket Viewing')
                                                                          <a href="{{leadgen_manage('tickets/view/'.$ticket->client_id.'/'.$ticket->ticket_no)}}" class="btn btn-success btn-circle" data-toggle="tooltip" data-original-title="View Ticket Details"><i class="fa fa-search" aria-hidden="true"></i></a>
                                                                       @endcan
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                 @else

                                                  <div class="empty_result">
        
                                                      No records found. Please create one
        
                                                  </div>
        
                                                    <div class="nothing">
        
                                                      <h2>No Tickets found</h2>
                                                      @can('Ticket Creating')
                                                      <p><a class="btn btn-success" href="{{leadgen_manage('tickets/create')}}">Create the first Ticket</a></p>
                                                      @endcan
                                                    </div>
        
                                                @endif
                                            </div>
                                        </div>
                                        <div class="kv-loader-overlay">
                                            <div class="kv-loader"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
@endsection
@section('styles')
<link rel="stylesheet" type="text/css" href="/cp/assets/css/sweetalert.css">
@endsection