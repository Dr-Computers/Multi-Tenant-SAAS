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
                                <form class="" action="{{leadgen_admin('tickets/create')}}"  method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                        <section>
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <!--<label class="control-label" for="subject">Subject</label>-->
                                                        <input type="text" id="subject" class="form-control" name="subject" placeholder="Subject" required>
                                                        <div class="form-error">{{$errors->first('subject')}}</div>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <!--<label class="control-label" for="subject">Subject</label>-->
                                                        <textarea class="form-control" rows="12" placeholder="Body" required name="body"></textarea>
                                                        <div class="form-error">{{$errors->first('subject')}}</div>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                       <select class="form-control" name="compaign_id" id="compaign_id" required>
													        <option value="">Select Compaign</option>
													        @foreach($compaigns as $compaign)
													        <option value="{{$compaign->id}}" >{{$compaign->name}}</option>
													        @endforeach
													    </select>
														<span class="text-danger">{{$errors->first('compaign_id')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                     <button type="submit" class="btn btn-success btn-md">Create Ticket</button>
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