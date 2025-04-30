@extends('layouts.admin')
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-xs-6">
            
                <ul class="breadcrumb">
                    <li><a href="{{leadgen_admin('/')}}">Home</a></li>
                    <li><a href="{{leadgen_admin('tickets')}}">Tickets</a></li>
                    <li class="active"></li>
                </ul>                    
           
        </div>
        @can('Ticket List')
        <div class="col-xs-6 text-right">
            <a class="btn btn-default" href="{{leadgen_admin('tickets')}}"><i class="fa fa-arrow-left"></i> Back to Tickets</a>
        </div>
        @endcan
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                
                
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        @if($errors->count())
                            <div class="alert alert-danger">Your submission contains errors! Please review and submit again!</div>
                        @endif
                        @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                         @endif
                        <div class="customer-create">
                            <div class="customer-form">
                                        <section>
                                            <div class="row">
                                                <div class="col-sm-8">
                                                   <big><strong>Replay To Ticket No : {{$ticket->ticket_no}}</strong> - Sub: {{$ticket->subject}}</big><br><br>
                                                   <strong class="m-l">{{$ticket->body}}</strong><br><br>
                                                </div>
                                            </div> 
                                            </section>
                                             <form class="" action="{{leadgen_admin('tickets/reply')}}"  method="post" enctype="multipart/form-data">
                                             {{ csrf_field() }}
                                            <section>
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <!--<label class="control-label" for="subject">Subject</label>-->
                                                        <textarea class="form-control" rows="12" placeholder="Reply" required name="body"></textarea>
                                                        <div class="form-error">{{$errors->first('body')}}</div>
                                                    </div>
                                                </div>
                                                <input type="hidden" value="{{$ticket->ticket_no}}" name="ticketno">
                                                <input type="hidden" value="{{$ticket->id}}">
                                                <input type="hidden" value="{{$ticket->client_id}}" name="clientid">
                                            </div> 
                                            
                                         
                                            
                                            
                                            <div class="row">
                                                <div class="col-sm-4">
                                                     <input type="submit" name="submit" class="btn btn-info" value="Reply">
                                                     <input type="submit" name="submit" class="btn btn-danger" value="Reply & Close">
                                                </div>
                                            </div>
                                        </section>
                                      </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection