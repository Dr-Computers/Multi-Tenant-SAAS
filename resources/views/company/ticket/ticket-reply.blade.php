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
    <div class="d-flex">
        <button href="#" data-size="lg" data-url="{{ route('company.tickets.create') }}" data-ajax-popup="true"
            data-bs-toggle="tooltip" title="{{ __('Create New Ticket') }}" class="btn btn-sm btn-primary me-2">
            <i class="ti ti-plus"></i> Create New Ticket
        </button>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
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
                                             <form class="" action="{{route('company.tickets.reply',$ticket->id)}}"  method="post" enctype="multipart/form-data">
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
                                                <input type="hidden" value="{{$ticket->company_id}}" name="companyid">
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