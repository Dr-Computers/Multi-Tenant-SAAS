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
            <a href="#">{{__('Transactions Report')}}</a>
        </li>
    </ul>
@endsection

@push('script-page')

<script>
   

</script>
@endpush
<style>
    /* .card {
    transition: transform 0.2s;
}

.card:hover {
    transform: scale(1.05);
    cursor: pointer;
} */

.card-title {
    font-size: 1.25rem; /* Larger font size for the title */
}

.card-text {
    font-size: 1rem; /* Slightly larger font for text */
}
.card-title {
  font: bold;
    margin-bottom: 10px; /* Spacing below title */
}

.card-text {
    font-size: 1.1rem; /* Adjust text size for card */
}

.text-muted {
    color: #6c757d; /* Muted text color */
}



    </style>
<script>
    var base64Image = @json(getBase64Image());
    console.error(base64Image);
    if (base64Image) {
        console.error('Base64 image is done');
    }
</script>

@section('content')

    <div class="row">
        <div class="col-12 text-center mb-4">
            <h2 class="font-weight-bold" style="font-size: 1.5rem; color: #0cc0df;">
                <i class="fas fa-wallet"></i> {{ __('Bank Accounts Overview') }} <i class="fas fa-wallet"></i>
            </h2>
            <p class="text-muted" style="font-size: 0.9rem;">{{ __('A comprehensive view of all your bank accounts and their balances.') }}</p>
        </div>
      
        @foreach($bankAccounts as $account)
            <div class="col-md-4 mb-4 mt-5" style="margin-top: 20px !important">
                <div class="card border-light shadow-sm rounded">
                    <div class="card-body text-center" style="background-color: #f8f9fa;">
                        <h5 class="card-title font-weight-bold text-primary">{{ $account->account_name }}</h5>
                        <p class="card-text">
                            <strong>{{ __('Opening Balance:') }}</strong> 
                            <span class="text-success">{{ priceFormat($account->balance) }}</span><br>
                            <strong>{{ __('Closing Balance:') }}</strong> 
                            <span class="text-danger">{{ priceFormat($account->closing_balance) }}</span>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
        <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap; margin-bottom: 20px;">
            
            <form method="GET" action="{{ route('report.bank_transactions.index') }}">
        
            <label for="start_month" style="font-size: 14px; font-weight: bold;">Month Of:</label>
            <input type="month" id="start_month" name="start_month" value="{{ request('start_month') ? request('start_month') : '' }}" style="padding: 5px; font-size: 14px; min-width: 150px;">
           
            <label for="end_month" style="font-size: 14px; font-weight: bold;">To:</label>
            <input type="month" id="end_month" name="end_month" value="{{ request('end_month') ? request('end_month') : '' }}" style="padding: 5px; font-size: 14px; min-width: 150px;">
        
            <button type="submit" class="btn btn-primary btn-sm" style="margin-right: 10px;">
                {{ __('Filter') }}
            </button>
            </form>
            <a href="{{ route('report.bank_transactions.index') }}" class="btn btn-secondary btn-sm">
                {{ __('Clear') }}
            </a>
        </div>
{{--         
        <div class="date-filter">
            <label for="min">From:</label>
            <input type="text" id="min" placeholder="Select Date" />
            <label for="max">To:</label>
            <input type="text" id="max" placeholder="Select Date" />
            <button id="todayMax" class="today-btn">Today</button> 
            <button id="lastMonthMax" class="last-month-btn">Last Month</button> <!-- Last Month Button -->
        </div> --}}
        {{-- <div class="date-filter">
            <label for="min">From:</label>
            <input type="text" id="min" placeholder="Select Date" />
            <label for="max">To:</label>
            <input type="text" id="max" placeholder="Select Date" />
            <button id="todayMax" class="today-btn">Today</button>
            <button id="lastMonthMax" class="last-month-btn">Last Month</button>
            <button id="clearDates" class="clear-btn">Clear</button> 
        </div> --}}
        {{-- <div class="date-filter">
            <label for="min">From:</label>
            <input type="text" id="min" placeholder="Select Date" />
            <label for="max">To:</label>
            <input type="text" id="max" placeholder="Select Date" />
            <button id="clearDates" class="clear-btn">Clear</button>
            <button id="todayMax" class="today-btn">Today</button>
            <button id="lastMonthMax" class="last-month-btn">Last Month</button>
        </div> --}}
        
        
       
        
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table  class="display transactions-table dataTable cell-border datatbl-advance-land reports-table" data-report-name="Bank Transactions Report">
                        <thead>
                            <tr>
                                <th>{{ __('Transaction Date') }}</th>
                                <th>{{ __('Transaction ID') }}</th>
                                <th>{{ __('Bank Account ') }}</th>
                                <th>{{ __('Opening Balance') }}</th>
                                <th>{{ __('Transaction Amount') }}</th>
                                <th>{{ __('Closing Balance') }}</th>
                                <th>{{ __('Transaction Type') }}</th>
                              
                                <th>{{ __('Reference') }}</th>
                                <th>{{ __('Description') }}</th>
                             
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr role="row">
                                    <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d') }}</td>
                                    <td>{{ $transaction->transaction_id }}</td>
                                    <td>{{ $transaction->account->account_name ?? 'N/A' }}</td>
                                    <td>{{ priceFormat($transaction->opening_balance) }}</td>
                                    <td>{{ priceFormat($transaction->transaction_amount) }}</td>
                                    <td>{{ priceFormat($transaction->closing_balance) }}</td>
                                    <td>{{ ucfirst($transaction->transaction_type) }}</td>
                                
                                    <td>{{ $transaction->reference ?? 'N/A' }}</td>
                                    <td>{{ $transaction->description ?? 'N/A' }}</td>
                                  
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="1">Total(OMR):</th>
                                <th colspan="1"></th>
                                <th colspan="1"></th>
                                <th colspan="1">{{ priceFormat($openingBalance) }}</th>
                                <th colspan="1">{{ priceFormat($transactionAmount) }}</th>
                                <th colspan="1">{{ priceFormat($closingBalance) }}</th>
                                <th colspan="3"></th>
                            </tr>
                        </tfoot>
                    </table>
                    {{-- <div class="d-flex justify-content-end" style="margin-top: 10px;">
                        {!! $transactions->links() !!}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

