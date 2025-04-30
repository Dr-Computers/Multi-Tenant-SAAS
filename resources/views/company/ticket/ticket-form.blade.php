@extends('layouts.client')
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-xs-6">
            
                <ul class="breadcrumb">
                    <li><a href="{{leadgen_manage('/')}}">Home</a></li>
                    <li><a href="{{leadgen_manage('tickets')}}">Tickets</a></li>
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
              
                
                <div class="ibox float-e-margins">
                     <div class="ibox-title">
                        <h5>  Create  Ticket </h5>
                        
                    </div>
                      @if($errors->count())
                    <div class="alert alert-danger">Your submission contains errors! Please review and submit again!</div>
                    @endif
                    @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                    @endif
                    <div class="ibox-content">
                        <div class="customer-create">
                            <div class="customer-form">
                                <form class="" action="{{leadgen_manage('tickets/create')}}"  method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                        <section>
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <!--<label class="control-label" for="subject">Subject</label>-->
                                                        <input type="text" id="subject" class="form-control" name="subject" placeholder="Subject">
                                                        <div class="form-error">{{$errors->first('subject')}}</div>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <!--<label class="control-label" for="subject">Subject</label>-->
                                                        <textarea class="form-control" rows="12" placeholder="Body"  name="body"></textarea>
                                                        <div class="form-error">{{$errors->first('body')}}</div>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                         <label class="control-label" for="role">Campaign</label>
                                                       <select class="form-control" name="compaign_id" id="compaign_id">
													        <option value="">Select Campaign</option>
													        @foreach($compaigns as $compaign)
													        <option value="{{$compaign->id}}" >{{$compaign->name}}</option>
													        @endforeach
													    </select>
														<div class="text-danger">{{$errors->first('compaign_id')}}</div>
                                                    </div>
                                                </div>
                                                
                                        
                                            <div class="col-sm-3">
                                                   <div class="form-group">
                                                        <label class="control-label" for="type">Ticket Type</label>
                                                        <select name="type"  class="form-control" required>
														         <option  value="general message">General Messages</option>
														         <option value="campaign tickets">Campaign Tickets</option>
														         <option value="support">Support</option>
														         <option value="billing">Billing</option>
                                                        </select>
                                                        <div class="form-error">{{$errors->first('type')}}</div>
                                                    </div>
                                            </div> 
                                            </div>
                                             
                                               <div class="row">
                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <label class="control-label" for="priority">Priority :</label> &nbsp;
                                                        <input type="radio" value="normal"  name="priority">&nbsp;Normal(24 hours)&nbsp;
                                                        <input type="radio" value="urgent" name="priority">&nbsp;Urgent(6 hours)&nbsp;
                                                        <div class="form-error">{{$errors->first('priority')}}</div>
                                                    </div>
                                                </div>
                                            </div>    
                                            
                                           
                                                  <div class="col-sm-4">
                                                     <button type="submit" class="btn btn-success btn-md">Create Ticket</button>
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