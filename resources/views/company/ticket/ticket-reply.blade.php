@extends('layouts.client')
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-xs-6">
            
                <ul class="breadcrumb">
                    <li><a href="{{leadgen_admin('/')}}">Home</a></li>
                    <li><a href="{{leadgen_admin('tickets')}}">Tickets</a></li>
                    <li class="active"></li>
                </ul>                    
           
        </div>
        @can('Ticket Listing')
        <div class="col-xs-6 text-right">
            <a class="btn btn-default" href="{{leadgen_manage('tickets')}}"><i class="fa fa-arrow-left"></i> Back to Tickets</a>
        </div>
        @endcan
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                @if($errors->count())
                    <div class="alert alert-danger">Your submission contains errors! Please review and submit again!</div>
                @endif
                @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
                @endif
                
                <div class="ibox float-e-margins">
                  
                    <div class="ibox-content">
                        <div class="customer-create">
                            <div class="customer-form">
                                        <section>
                                            <div class="row">
                                                <div class="col-sm-8">
                                                   <h1></h1>
                                                   <h1>Subject:{{$ticket->body}}</h1>
                                                   <h2>Body:{{$ticket->subject}}</h2>
                                                </div>
                                            </div> 
                                            </section>
                                             <form class="" action="{{leadgen_manage('tickets/reply')}}"  method="post" enctype="multipart/form-data">
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
                                                     <button type="submit" class="btn btn-success btn-md">Reply Ticket</button>
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