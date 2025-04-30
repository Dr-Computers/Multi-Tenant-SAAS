@extends('layouts.admin')
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-xs-6">
          <ul class="breadcrumb">
              <li><a href="{{leadgen_admin('/')}}">Home</a></li>
              <li class="active">Tickets</li>
          </ul>                    
    </div>
    <div class="col-xs-6 text-right">
        @can('Ticket List')
        <div class="text-right">
            <a class="btn btn-default" href="{{leadgen_admin('tickets')}}"><i class="fa fa-arrow-left"></i> Back to Tickets</a>
        </div>
        @endcan
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                
                <div class="ibox float-e-margins">
                    <!--<div class="ibox-title">-->
                    <!--    <h5>Manage Tickets</h5>-->
                    <!--</div>-->
                    <div class="ibox-content">
                        @if(session()->has('message'))
                            {!! session('message') !!}
                        @endif
                        <div class="customer-index">
                            <div class="gridlist ">
                                <div id="p0" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000">
                                    <div id="w0-pjax" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000">
                                        <div id="w0" class="grid-view is-bs3 hide-resize" data-krajee-grid="kvGridInit_b84b0ca9" data-krajee-ps="ps_w0_container">
                                            <div class="panel panel-info">
                                                
                                                <div id="w0-container" class="table-responsive kv-grid-container">
                                                    
                                                    <table class="tableFilter table table-hover table-bordered  table-condensed table-style">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    <div class=" text-left">
                                                                        <div class="row"> 
                                                                            <div class="col-lg-8">                                                        
                                                                                    <strong class="m-l"><big>TNO{{$tickets->pluck('ticket_no')->first()}}</big> </strong> 
                                                                                    <strong class="m-l"> - &nbsp;&nbsp; <big>{{$tickets->pluck('subject')->first()}}</big></strong>
                                                                                    <a class="m-l" href="{{url('admin/clients/'.$tickets->pluck('client_id')->last().'/view')}}"><strong>< {{ucwords($client_detials->where('user_id',$tickets->pluck('client_id')->last())->pluck('company_name')->first())}} ></strong></a>

                                                                            </div>
                                                                           
                                                                        </div>
                                                                       
                                                                    </div>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="ajaxFilterResult">
                                                                @foreach($tickets as $ticket)
                                                                    <tr>
                                                                       <td>
                                                                       <div class="card @if($ticket->to == 'client') {{'bg-muted'}} @endif">
                                                                            <p class="text-dark"  style="color:black;">{{$ticket->users->name}} < {{$ticket->users->email}} > &nbsp; {{date_for($ticket->created_at)}} &nbsp; {{ date("H-i a", strtotime($ticket->created_at))}} </p>
                                                                           <div class="card-body" style="color:black;">
                                                                             <!--<h5 class="card-title">{{$ticket->subject}}</h5>-->
                                                                             <p class="card-text">{{$ticket->body}}</p>
                                                                              
                                                                           </div>
                                                                        </div>
                                                                       </td>
                                                                    </tr>
                                                                @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div style="padding-top: 10px;padding-bottom: 30px;">
                                                      @if($ticket->status == '1')
                                                    <div class="panel-footer text-center"> 
                                                    @can('Ticket Replay')
                                                       <a href="{{leadgen_admin('tickets/reply/'.$ticket->id)}}" class="btn btn-info pull-right ">Reply</a>
                                                     @endcan 
                                                       <a href="{{leadgen_admin('tickets/closed/'.$ticket->ticket_no)}}" class="btn btn-danger pull-right m-r-md">Close Ticket</a>
                                                    </div>
                                                    @endif
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