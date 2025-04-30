@extends('layouts.client')
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-xs-6">
          <ul class="breadcrumb">
              <li><a href="{{leadgen_manage('/')}}">Home</a></li>
              <li class="active">Tickets</li>
          </ul>                    
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                @if(session()->has('message'))
                    {!! session('message') !!}
                @endif
                <div class="ibox float-e-margins">
                    <!--<div class="ibox-title">-->
                    <!--    <h5>Manage Tickets</h5>-->
                    <!--</div>-->
                    <div class="ibox-content">
                        <div class="customer-index">
                            <div class="gridlist ">
                                <div id="p0" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000">
                                    <div id="w0-pjax" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000">
                                        <div id="w0" class="grid-view is-bs3 hide-resize" data-krajee-grid="kvGridInit_b84b0ca9" data-krajee-ps="ps_w0_container">
                                            <div class="panel panel-info">
                                                @can('Ticket Listing')
                                                <div class="text-right">
                                                    <a href="{{leadgen_manage('tickets')}}" class="btn btn-success">Back to Tickets</a>
                                                </div>
                                                @endcan
                                                <div id="w0-container" class="table-responsive kv-grid-container">
                                                    <table class="tableFilter table table-hover table-bordered  table-condensed table-style">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="ajaxFilterResult" >
                                                                @foreach($tickets as $ticket)
                                                                    <tr>
                                                                       <td>
                                                                       <div class="card" >
                                                                           <!--<img style="width:50px;height:30px;" src="https://images.pexels.com/photos/736230/pexels-photo-736230.jpeg?cs=srgb&dl=pexels-jonas-kakaroto-736230.jpg&fm=jpg" class="card-img-top" alt="...">-->
                                                                           <div class="card-body" style="color:black;">
                                                                             <h3>{{$ticket->users->email}} < {{$ticket->users->email}} ></h3>
                                                                             <h5 class="card-title">{{$ticket->subject}}</h5>
                                                                             <p class="card-text">{{$ticket->body}}</p>
                                                                            
                                                                           </div>
                                                                        </div>
                                                                       </td>
                                                                    </tr>
                                                                @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                 @if($ticket->status == '1')
                                                 @can('Ticket Replaying')
                                                <div class="panel-footer text-center"> 
                                                   <a href="{{leadgen_manage('tickets/reply/'.$ticket->id)}}" class="btn btn-info pull-right">Reply</a>
                                                </div>
                                                @endcan
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