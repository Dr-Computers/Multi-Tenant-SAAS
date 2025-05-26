@extends('layouts.app')

@section('page-title')
    {{ __('Balance Sheet') }}
@endsection

@push('script-page')
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterSelect = document.getElementById('filter-select');
            const customDateFields = document.getElementById('custom-date-fields');

            filterSelect.addEventListener('change', function() {
                if (this.value === 'custom') {
                    customDateFields.classList.remove('d-none'); // Show custom date fields
                } else {
                    customDateFields.classList.add('d-none'); // Hide custom date fields
                }
            });
        });
    </script> --}}
@endpush

@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Balance Sheet') }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <!-- Filter Form -->
        {{-- <form method="GET" action="{{ route('report.balance_sheet.index') }}" id="filter-form">
            <div class="row mb-4">
                <div class="col-md-4">
                    <select class="form-select" id="filter-select" name="filter_option">
                        <option value="">{{ __('Select Date') }}</option>
                        <option value="end_of_year">{{ __('End of Last Year') }}</option>
                        <option value="end_of_quarter">{{ __('End of Last Quarter') }}</option>
                        <option value="end_of_month">{{ __('End of Last Month') }}</option>
                        <option value="custom">{{ __('Custom Date') }}</option>
                    </select>
                </div>
                <div class="col-md-4 d-none" id="custom-date-fields">
                    <div class="input-group">
                        <input type="date" class="form-control" name="start_date" placeholder="Start Date">
                        <input type="date" class="form-control" name="end_date" placeholder="End Date">
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-outline-primary">{{ __('Apply Filters') }}</button>
                </div>
            </div>
        </form> --}}

        <!-- Balance Sheet Report -->
        <div class="col-12 mb-4">
            <div class="card border-light shadow-sm rounded">
                <div class="card-body">
                    <h3 class="text-primary mb-4" style="margin-bottom: 10px !important;">{{ __('Balance Sheet') }}</h3>
                    <p style="margin-bottom: 10px !important;">{{ __('As of: ') }}{{ $reportDate ?? \Carbon\Carbon::now()->format('Y-m-d') }}</p>

                    <!-- Assets Section -->
                




                    <table class=" dataTable cell-border  datatbl-advance">
                        <thead>
                            <tr>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Account') }}</th>
                                <th class="text-end">{{ __('Amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rental Income Section -->
                            <tr>
                                <td colspan="3" style="font-weight: bold !important;text-transform: uppercase;font-size: 15px;background: #ffffff;">{{ __('Assets') }}</td>

                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                            </tr>
                            @foreach ($bankAccounts as $account)
                            <tr>
                                <td></td>
                                <td>{{ $account['account_name'] }}- {{$account['account_type'] }} Account</td>
                                <td class="text-end">{{ priceFormat($account['closing_balance']) }} </td>
                            </tr>
                           @endforeach
                            @foreach ($assetsData as $asset)
                                <tr>
                                    <td></td>
                                    <td >
                                    @if($asset->type === 'current_asset')
                                        Current Asset
                                    @elseif($asset->type === 'fixed_asset')
                                      Fixed Asset
                                        @else
                                        {{ $asset->type }}
                                    @endif 
                                </td>
                                    <td class="text-end">{{ priceFormat($asset['total_value']) }}</td></tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td class="font-weight-bold" style="font-weight: bold !important;text-transform: uppercase;font-size: 15px;background: #ffffff;" >{{ __('Total Assets') }}</td>
                                <td class="text-end font-weight-bold">{{ priceFormat($totalAssets) }}</td>
                            </tr>
                            <!-- Spacer Row -->
                            <tr>
                                <td style="display: none;"></td>

                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                            </tr>

                            <!-- Additional Income Section -->
                            <tr>
                                <td colspan="3"  style="font-weight: bold !important;text-transform: uppercase;font-size: 15px;background: #ffffff;">{{ __('Liabilities') }}</td>
                                <td style="display: none;"></td>

                                <td style="display: none;"></td>

                            </tr>
                            <tr>
                                <td></td>
                                <td>Security Deposit Payable </td>
                                <td class="text-end">{{ priceFormat($depositLiability) }}</td> 
                             </tr>
                            @foreach ($liabilitiesData as $liability)
                                <tr>
                                    <td></td>
                                    <td>{{ $liability['type'] }}</td>
                                    <td class="text-end">{{ priceFormat($liability['total_value']) }}</td>  </tr>
                            @endforeach

                             <tr>
                                <td></td>
                                <td class="font-weight-bold" style="font-weight: bold !important;text-transform: uppercase;font-size: 15px;background: #ffffff;" >{{ __('Total Liabilities') }}</td>
                                <td class="text-end font-weight-bold">{{ priceFormat($totalLiabilities) }}</td>
                            </tr>

                            <!-- Spacer Row -->
                            <tr>
                                <td style="display: none;"></td>

                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                            </tr>

                            <!-- Expenses Section -->
                            <tr>
                                <td colspan="3" style="font-weight: bold !important;text-transform: uppercase;font-size: 15px;background: #ffffff;" class="section-header">{{ __('Equity') }}</td>
                                <td style="display: none;"></td>

                                <td style="display: none;"></td>

                            </tr>
                         
                            <tr>
                                <td></td>
                                <td>Total Equity</td>
                                <td class="text-end">{{ priceFormat($equity) }}</td>
                            </tr>
                      
                        {{-- <tr>
                            <td></td>
                            <td class="font-weight-bold" style="font-weight: bold !important;text-transform: uppercase;font-size: 15px;background: #ffffff;" >{{ __('Total Equity') }}</td>
                            <td class="text-end font-weight-bold">{{ priceFormat($totalEquity) }}</td>
                        </tr> --}}

                            <!-- Spacer Row -->
                            <tr>
                                <td style="display: none;"></td>

                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                            </tr>

                            <!-- Net Profit/Loss Section -->
                        {{-- <tr>
                         
                            <td   colspan="3"  style="font-weight: bold !important;text-transform: uppercase;font-size: 15px;background: #ffffff;" >{{ __('Total Liabilities and Equity') }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;{{ priceFormat($totalLiabilities + $equity) }}</td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>  
                        </tr> --}}
                        </tbody>
                    </table>

                    <!-- Total Section -->
                   
                </div>
            </div>
        </div>
    </div>
@endsection
