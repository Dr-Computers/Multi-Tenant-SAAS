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
            <a href="#">{{__('Cheques Report')}}</a>
        </li>
    </ul>
@endsection
<script>
    var base64Image = @json(getBase64Image());
    console.error(base64Image);
    if (base64Image) {
        console.error('Base64 image is done');
    }
</script>
<style>
    .status-cell {
    border: 1px solid #ccc;  /* Light grey border */
    padding: 8px;            /* Add some padding for better spacing */
    text-align: center;      /* Center align the text */
    border-radius: 4px;     /* Optional: rounded corners */
}
</style>
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
                            {{ __('Total Cheque Amount') }}
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
        </div> --}}
        <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap; margin-bottom: 20px;">
            
            {{-- <span style="font-size: 14px; font-weight: bold;">Building:</span> --}}
            <form method="GET" action="{{ route('report.cheques.index') }}"> 
          {{-- <select id="property" name="property" style="padding: 5px; font-size: 14px;">
               <option value="" disabled selected>--Select--</option>
               @foreach($filterProperty as $property)
               <option value="{{$property->id}}" {{ request('property') == $property->id ? 'selected' : '' }}>{{$property->name}}</option>
               @endforeach
           </select> --}}

           <span style="font-size: 14px; font-weight: bold;">Tenant:</span>
           <select id="tenant" name="tenant" style="padding: 5px; font-size: 14px;  min-width: 150px; max-width: 200px; flex-shrink: 0;">
               <option value="" disabled selected>--Select--</option>
               @foreach($filterTenant as $tenant)
               <option value="{{$tenant->id}}" {{ request('tenant') == $tenant->id ? 'selected' : '' }}>{{$tenant->user->first_name . ' ' . $tenant->user->last_name}}</option>
               @endforeach
           </select>
       
           <label for="start_month" style="font-size: 14px; font-weight: bold;">Month Of:</label>
           <input type="month" id="start_month" name="start_month" value="{{ request('start_month') ? request('start_month') : '' }}" style="padding: 5px; font-size: 14px;">
          
           <label for="end_month" style="font-size: 14px; font-weight: bold;">To:</label>
           <input type="month" id="end_month" name="end_month" value="{{ request('end_month') ? request('end_month') : '' }}" style="padding: 5px; font-size: 14px;">
       
           <button type="submit" class="btn btn-primary btn-sm" style="margin-right: 10px;">
               {{ __('Filter') }}
           </button>
           </form>
           <a href="{{ route('report.cheques.index') }}" class="btn btn-secondary btn-sm">
               {{ __('Clear') }}
           </a>
       </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="display dataTable cell-border datatbl-advance" data-report-name="Cheques Report">
                        <thead>
                        <tr>
                            <th>{{ __('Cheque Date') }}</th>
                            <th>{{ __('Tenant') }}</th>
                            <th>{{ __('Cheque Number')}}</th>
                            <th>{{ __('Bank Name') }}</th>
                             <th>{{ __('Amount') }}</th>
                            <th>{{__('Status')}}</th>
                           
                        </tr>
                        </thead>
                        <tbody>
                            @php
                            // Initialize total amount
                            $totalAmount = 0;
                        @endphp
                            @forelse($chequeDetails as $cheque)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($cheque->check_date)->format('Y-m-d') }}</td>
                                <td>{{ $cheque->tenant->user->first_name }}</td>
                                <td>{{ $cheque->check_number }}</td>
                                <td>{{ $cheque->bank_name }}</td>
                                @php
                                $amount = $cheque->amount;
                                $totalAmount += $amount; // Add to total amount
                            @endphp
                                <td>{{ priceFormat($cheque->amount) }}</td>
                                <td class=" status-cell {{ $cheque->status === 'paid' ? 'text-success' : 'text-danger' }}" style="text-transform: uppercase;">
                                    {{ $cheque->status }}
                                </td>
                            </tr>
                        @empty 
                            <tr>
                                <td colspan="5" class="text-center">{{ __('No cheque details found.') }}</td>
                            </tr>
                        @endforelse
                      
                        </tbody>
                        <tfoot>
                            <tr>
                                {{-- <th colspan="4">Total(OMR):</th> --}}
                                <th colspan="4"></th>
                                <th colspan="1" id="amount">{{ priceFormat($totalAmount) }}</th>
                                <th colspan="1"></th>
                            </tr>
                        </tfoot>
                    </table>
                    {{-- <div class="d-flex justify-content-end" style="margin-top: 10px;">
                            {{ $chequeDetails->links() }} <!-- This will display the pagination links -->
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

