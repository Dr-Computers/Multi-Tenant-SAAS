@extends('layouts.admin')
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-xs-6">
          <ul class="breadcrumb">
              <li><a href="{{leadgen_admin('/')}}">Home</a></li>
              <li class="active">Tickets</li>
          </ul>                    
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
               
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Support Tickets</h5>
                    </div>
                    <div class="ibox-content">
                         @if(session()->has('message'))
                            {!! session('message') !!}
                        @endif
                        <div class="customer-index">
                            <div class="gridlist ">
                                <div id="p0" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000">
                                    <div id="w0-pjax" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000">
                                        <div id="w0" class="grid-view is-bs3 hide-resize" data-krajee-grid="kvGridInit_b84b0ca9" data-krajee-ps="ps_w0_container">
                                            
                                                <!--<div class="text-right">-->
                                                <!--    <a href="{{leadgen_admin('tickets/create')}}" class="btn btn-success">Add Tickets</a>-->
                                                <!--</div>-->
                                                <div class="table-filter">
                                                  <div class="row">
                                                    <div class="col-sm-3 text-left">
                                                            <div class="summary">Showing records <b>{{$tickets->firstItem()}}-{{$tickets->lastItem()}}</b> of total <b>{{$tickets->total()}}</b> item{{$tickets->total()>0 ? 's':''}}</div>
                                                    </div>
                                                    <div class="col-sm-9 text-right">
                                                        <form action="{{url()->current()}}" method="GET">
                                                            <table class="filter-form" align="right">
                                                                <tr>
                                                                   <th>Filter By</th>
                                                                  <!--<td><input type="text" name="search" placeholder="Enter search term"  /></td>//-->
                                                                    <td>
                                                                        <input type="text" class="form-control"  id="myInput" name="search" value="{!! request()->search !!}" placeholder="Enter No/Subject">
                                                                    </td>
                                                                    <td>
                                                                        <select name="client" id="client" class="form-control">
                                                                          <option value="">Select Client</option>
                                                                           @foreach($clients as $client)
                                                                                <option value="{{$client->user_id}}"  @if(request()->client == $client->user_id) selected="selected" @endif>
                                                                                    {{$client->company_name}}
                                                                                </option>
                                                                            @endforeach
                                                                         
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="type" id="type" class="form-control">
                                                                            <option value="">Select Type</option>
                                                                            <option value="general message" @if(request()->type == 'general message') selected="selected" @endif >General message</option>
                                                                            <option value="support" @if(request()->type == 'support') selected="selected" @endif >Support</option>
                                                                            <option value="billing" @if(request()->type == 'billing') selected="selected" @endif >Billing</option>
                                                                            <option value="campaign tickets" @if(request()->type == 'campaign tickets') selected="selected" @endif >Campaign Tickets</option>
                                                                    
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="priority" id="priority" class="form-control">
                                                                           <option value="">Priority</option>
                                                                           <option value="urgent" @if(request()->priority == 'urgent') selected="selected" @endif >Urgent</option>
                                                                           <option value="normal" @if(request()->priority == 'normal') selected="selected" @endif >Normal</option>
                                                                         
                                                                          <!--<option value="active" >Active</option>-->
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="acc_mnger" id="acc_mnger" class="form-control">
                                                                          <option value=""> Select Account Manager</option>
                                                                           @foreach($client_acc as $rep)
                                                                                <option value="{{$rep->id}}"  @if(request()->acc_mnger == $rep->id) selected="selected" @endif>
                                                                                    {{$rep->name}}
                                                                                </option>
                                                                            @endforeach
                                                                         
                                                                        </select>
                                                                    </td>
                                                                    @if(auth()->user()->type =='admin')
                                                                    <td>
                                                                        <select name="rep" id="rep" class="form-control">
                                                                          <option value="">Select Representative</option>
                                                                           @foreach($client_rep as $rep)
                                                                                <option value="{{$rep->id}}"  @if(request()->rep == $rep->id) selected="selected" @endif>
                                                                                    {{$rep->name}}
                                                                                </option>
                                                                            @endforeach
                                                                         
                                                                        </select>
                                                                    </td>
                                                                    @endif
                                                                    <td><button type="submit" class="btn btn-success">Go</button></td>
                                                                </tr>
                                                            </table>
                                                        </form>
                                                    </div>
                                                  </div>
                                                </div>

                                                <div id="w0-container" class="table-responsive kv-grid-container">
                                                    <table class="tableFilter table table-hover table-bordered  table-condensed table-style">
                                                        <thead>
                                                            <tr>
                                                                <th>Ticket Number</th>
                                                                <th>Subject</th>
                                                                <th>Type</th>
                                                                <th>From</th>
                                                                <th>Date & Time</th>
                                                                <th>Priority</th>
                                                                <th>Status</th>
                                                                @if(auth()->user()->type =='admin')
                                                                <th>Assign</th>
                                                                @endif
                                                                <th class="text-right">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="">
                                                                @foreach($tickets as $ticket)
                                                                    <tr @if($ticket->read_at == null)  @endif >
                                                                        <td><strong><a href="{{leadgen_admin('tickets/view/'.$ticket->client_id.'/'.$ticket->ticket_no)}}">TNO{{$ticket->ticket_no}}</a></strong></td>
                                                                        <td><a href="{{leadgen_admin('tickets/view/'.$ticket->client_id.'/'.$ticket->ticket_no)}}">{{$ticket->subject}}</a></td>
                                                                        <td>{{$ticket->type}}</td>
                                                                        <td>{{$ticket->users->client->company_name }}<br>
                                                                        {{$ticket->users->name }}
                                                                        </td>
                                                                        <td>{{date('d M Y h:i:a',strtotime($ticket->created_at))}}</td>
                                                                       
                                                                         <td> @if($ticket->priority=='urgent')
                                                                                <span class="label label-danger">urgent</span>
                                                                            @else
                                                                                <span class="label label-warning">normal</span>
                                                                            @endif</td>
                                                                        <td>
                                                                            @if($ticket->status=='1')
                                                                                <span class="label label-success">Open</span>
                                                                            @else
                                                                                <span class="label label-primary">Closed</span>
                                                                            @endif
                                                                        </td>
                                                                        @if(auth()->user()->type =='admin')
                                                                        <td>
                                                                            <select class="form-control asigned_staff" data-tno="{{$ticket->ticket_no}}" name="asigned_staff" id="asigned_staff"> 
                                                                                <option value="0" disabled selected>Choose Rep</option>
                                                                                @foreach($client_rep as $rep)
                                                                                   <option value="{{$rep->id}}"  @if($ticket->staff_id == $rep->id) selected="selected" @endif>
                                                                                    {{$rep->name}}
                                                                                    </option> 
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                        @endif
                                                                        <td class="text-right">
                                                                          @can('Ticket View')
                                                                          <a href="{{leadgen_admin('tickets/view/'.$ticket->client_id.'/'.$ticket->ticket_no)}}" class="btn btn-success btn-circle"><i class="fa fa-search" aria-hidden="true"></i></a>
                                                                          @endcan
                                                                          @if($ticket->status == '1')
                                                                          <a href="{{leadgen_admin('tickets/view/'.$ticket->client_id.'/'.$ticket->ticket_no)}}" class="btn btn-danger btn-circle"><i class="fa fa-times" aria-hidden="true"></i></a>
                                                                          @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class=" text-center"> 
                                                    {!! $tickets->links() !!}
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
<script>
    $(document).ready(function(){
        $(".asigned_staff").change(function(s){
            var staff_id = $(this).val();
            var tckt_no  = $(this).data('tno');
            var url      = "{{leadgen_admin('ticket/asigned-staff')}}";
            if(staff_id != 0){
                $.ajax({
                    url: url,
                    type: "GET",
                    data: {'staff_id' : staff_id, 'tckt_no' : tckt_no},
                    success: function(data){
                        console.log(data)
                    }
                });
            }
        });
    });
    
</script>



@endsection
@section('styles')
<link rel="stylesheet" type="text/css" href="/cp/assets/css/sweetalert.css">
@endsection