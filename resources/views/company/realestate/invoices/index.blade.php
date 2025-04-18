@extends('layouts.company')
@section('page-title')
   {{ __('Invoices') }}
@endsection
@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
   <li class="breadcrumb-item">{{ __('Invoices') }}</li>
@endsection
@section('action-btn')
   <div class="d-flex">
    <a href="{{ route('company.realestate.invoices.create', 0) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
    title="{{ __('Create') }}">
    <i class="ti ti-plus"></i>
</a>
   </div>
@endsection
@section('content')
   <div class="row">
       <div class="col-sm-12">
           <div class="card">
               <div class="card-body table-bUsers-style">
                   <div class="table-responsive">
                       <table class="table datatable">
                           <thead>
                               <tr>
                                   <th>{{__('Invoice')}}</th>
                                   <th>{{__('Invoice Period')}}</th>
                                   <th>{{__('Invoice Period End')}}</th>
      
                                   <th>{{__('Property')}}</th>
                                   <th>{{__('Unit')}}</th>
                                   <th>{{__('Invoice Month')}}</th>
                                   <th>{{__('Date')}}</th>
                                   <th>{{__('Amount')}}</th>
                                   <th>{{_('Due Amount')}}</th>
                                   <th>{{__('Status')}}</th>
                                   <th>{{__('Action')}}</th>


                               </tr>
                           </thead>
                           <tbody>
                               @forelse($invoices as $invoice)
                               <tr role="row">
                                   {{-- <td>{{invoicePrefix().$invoice->invoice_id}} </td>
                                    --}}
                                    <td>
                                       {{ (optional($invoice->properties)->invoice_prefix ?: invoicePrefix()) . $invoice->invoice_id }}
                                   </td>
  
  
                                   <td>{{$invoice->invoice_period ? $invoice->invoice_period.' years':'monthly'}} </td>
                                   <td>{{$invoice->invoice_period_end_date}} </td>
                                   <td>{{!empty($invoice->properties)?$invoice->properties->name:'-'}} </td>
                                   <td>{{!empty($invoice->units)?$invoice->units->name:'-'}}  </td>
                                   <td>{{date('F Y',strtotime($invoice->invoice_month))}} </td>
                                   <td>{{dateFormat($invoice->end_date)}} </td>
                                   <td>{{priceFormat($invoice->getInvoiceSubTotalAmount())}}</td>
                            
                                       <td>{{priceFormat($invoice->getInvoiceDueAmount())}} </td>
                                  
                                   <td>
                                       @if($invoice->status=='open')
                                           <span
                                               class="badge badge-primary">{{\App\Models\Invoice::$status[$invoice->status]}}</span>
                                       @elseif($invoice->status=='paid')
                                           <span
                                               class="badge badge-success">{{\App\Models\Invoice::$status[$invoice->status]}}</span>
                                       @elseif($invoice->status=='partial_paid')
                                           <span
                                               class="badge badge-warning">{{\App\Models\Invoice::$status[$invoice->status]}}</span>
                                       @endif
                                   </td>
                                   @if(Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                                       <td class="text-right">
                                           <div class="cart-action">
                                               {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id]]) !!}
                                               @can('show invoice')
                                                   <a class="text-warning" href="{{ route('invoice.show',$invoice->id) }}"
                                                      data-bs-toggle="tooltip"
                                                      data-bs-original-title="{{__('View')}}"> <i
                                                           data-feather="eye"></i></a>
                                               @endcan
                                               @can('edit invoice')
                                                   <a class="text-success" href="{{ route('invoice.edit',$invoice->id) }}"
                                                      data-bs-toggle="tooltip"
                                                      data-bs-original-title="{{__('Edit')}}"> <i data-feather="edit"></i></a>
                                               @endcan
                                               @can('delete invoice')
                                                   <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                      data-bs-original-title="{{__('Detete')}}" href="#"> <i
                                                           data-feather="trash-2"></i></a>
                                               @endcan
                                               {!! Form::close() !!}
                                           </div>
                                       </td>
                                   @endif
                               </tr>
                       
                               @empty
                                   <tr>
                                       <td colspan="7" class="text-center">
                                           <h6>No Invoices found..!</h6>
                                       </td>
                                   </tr>
                               @endforelse
                           </tbody>
                       </table>
                   </div>
               </div>
           </div>
       </div>
   </div>
@endsection




@push('script-page')
   <script>
       $(document).on('change', '#password_switch', function() {
           if ($(this).is(':checked')) {
               $('.ps_div').removeClass('d-none');
               $('#password').attr("required", true);


           } else {
               $('.ps_div').addClass('d-none');
               $('#password').val(null);
               $('#password').removeAttr("required");
           }
       });
       $(document).on('click', '.login_enable', function() {
           setTimeout(function() {
               $('.modal-body').append($('<input>', {
                   type: 'hidden',
                   val: 'true',
                   name: 'login_enable'
               }));
           }, 2000);
       });
   </script>
@endpush
