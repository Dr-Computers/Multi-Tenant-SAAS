@extends('layouts.app')
@section('page-title')
    {{__('Invoice')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Invoice Report')}}</a>
        </li>
    </ul>
@endsection
<script>
    var base64Image = @json(getBase64Image());
    if (base64Image) {
        console.error('Base64 image is done');
    }
</script>
@section('content')

    <div class="row">
        
        <div class="col-12 mb-4 mt-5">
            <div class="card border-light shadow-sm rounded">
                <div class="card-body d-flex align-items-center" style="background-color: #f8f9fa;">
                    <!-- SVG Icon -->
                    <div class="mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-currency-dollar" viewBox="0 0 16 16">
                            <path d="M8 0a2 2 0 0 1 2 2v1h2a1 1 0 0 1 0 2h-2v1h2a1 1 0 0 1 0 2h-2v1h2a1 1 0 0 1 0 2h-2v1a2 2 0 0 1-4 0v-1H2a1 1 0 0 1 0-2h2v-1H2a1 1 0 0 1 0-2h2V3H2a1 1 0 0 1 0-2h2V2a2 2 0 0 1 2-2z"/>
                        </svg>
                    </div>
        
                    <!-- Text Area -->
                    <div class="flex-grow-1 text-left">
                        <h5 class="card-title font-weight-bold text-primary">
                            {{ __('Total Invoice Amount') }}
                        </h5>
                        <p class="card-text">
                            <strong>{{ priceFormat($totalAmount) }}</strong>
                        </p>
                    </div>
                </div>
            </div>
         </div>  
         {{-- <div class="date-filter">
            <label for="min">From:</label>
            <input type="text" id="min" placeholder="Select Date" />
            <label for="max">To:</label>
            <input type="text" id="max" placeholder="Select Date" />
            <button id="clearDates" class="clear-btn">Clear</button>
            <button id="todayMax" class="today-btn">Today</button>
            <button id="lastMonthMax" class="last-month-btn">Last Month</button>
        </div>  --}}
        <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap; margin-bottom: 20px;">
            
            <form method="GET" action="{{ route('report.invoices.index') }}">
            <span style="font-size: 14px; font-weight: bold;">Building:</span>
            <select id="property" name="property" style="padding: 5px; font-size: 14px; min-width: 150px; max-width: 200px; flex-shrink: 0;">
                <option value="" disabled selected>--Select--</option>
                @foreach($filterProperty as $property)
                <option value="{{$property->id}}" {{ request('property') == $property->id ? 'selected' : '' }}>{{$property->name}}</option>
                @endforeach
            </select>

            {{-- <span style="font-size: 14px; font-weight: bold;">Tenant:</span>
            <select id="tenant" name="tenant" style="padding: 5px; font-size: 14px;  min-width: 150px; max-width: 200px; flex-shrink: 0;">
                <option value="" disabled selected>--Select--</option>
                @foreach($filterTenant as $tenant)
                <option value="{{$tenant->id}}" {{ request('tenant') == $tenant->id ? 'selected' : '' }}>{{$tenant->user->first_name . ' ' . $tenant->user->last_name}}</option>
                @endforeach
            </select> --}}
        
            <label for="start_month" style="font-size: 14px; font-weight: bold;">Month Of:</label>
            <input type="month" id="start_month" name="start_month" value="{{ request('start_month') ? request('start_month') : '' }}" style="padding: 5px; font-size: 14px; min-width: 150px;">
           
            <label for="end_month" style="font-size: 14px; font-weight: bold;">To:</label>
            <input type="month" id="end_month" name="end_month" value="{{ request('end_month') ? request('end_month') : '' }}" style="padding: 5px; font-size: 14px; min-width: 150px;">
        
            <button type="submit" class="btn btn-primary btn-sm" style="margin-right: 10px;">
                {{ __('Filter') }}
            </button>
            </form>
            <a href="{{ route('report.invoices.index') }}" class="btn btn-secondary btn-sm">
                {{ __('Clear') }}
            </a>
        </div>
        
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="display dataTable cell-border datatbl-advance" data-report-name="Invoice Report">
                        <thead>
                        <tr>
                            {{-- <th>{{__('Tenant')}}</th> --}}
                            <th>{{__('End Date')}}</th>
                            <th>{{__('Invoice')}}</th>
                            <th>{{__('Property')}}</th>
                            
                            <th>{{__('Unit')}}</th>
                            <th>{{__('Tenant')}}</th>
                            <th>{{__('Invoice Month')}}</th>
                            <th>{{__('Amount')}}</th>
                            <th>{{__('Tax Amount')}}</th>
                             <th>{{__('Total Amount')}}</th>
                            <th>{{__('Status')}}</th>
                           
                        </tr>
                        </thead>
                        <tbody>
                         
                        @foreach($invoices as $invoice)
                            <tr role="row">
                                {{-- <td>{{ $invoice->units->tenants()->user->name ?? '-' }}</td> --}}
                                <td>{{ \Carbon\Carbon::parse($invoice->end_date)->format('Y-m-d')}} </td>
                                {{-- <td>{{invoicePrefix().$invoice->invoice_id}} </td> --}}
                                <td>
                                    {{ $invoice->invoice_type === 'property_invoice' 
                                    ? (optional($invoice->properties)->invoice_prefix ?: invoicePrefix()) . $invoice->invoice_id
                                    : invoicePrefixOther() 
                                }}
                                </td>
                                <td>{{!empty($invoice->properties)?$invoice->properties->name:'-'}} </td>
                                <td>{{!empty($invoice->units)?$invoice->units->name:'-'}}  </td>
                                <td>{{$invoice->invoice_type === 'other' ?  $invoice->tenant->user->name : '--'  }} </td></td>
                                <td>{{date('F Y',strtotime($invoice->invoice_month))}} </td>
                                @php
                                $amount = $invoice->getInvoiceSubTotalAmount();
                              
                               @endphp
                                  <td>{{priceFormat($invoice->getInvoiceSubAmount())}}</td>
                                  <td>{{priceFormat($invoice->getInvoiceSubTaxAmount())}}</td>
                                <td>{{priceFormat($invoice->getInvoiceSubTotalAmount())}}</td>
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
                              
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                            <tr>

                                {{-- <th colspan="5">Total(OMR)</th>  --}}
                                <th colspan="8"></th>
                                <th colspan="1">{{ priceFormat($totalAmount) }}</th>
                                <th colspan="1"></th>


                            </tr>
                        </tfoot>
                    </table>
                    {{-- <div class="d-flex justify-content-end" style="margin-top: 10px;">
                        {{ $invoices->links() }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

