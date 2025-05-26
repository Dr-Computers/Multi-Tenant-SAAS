@extends('layouts.app')

@section('page-title')
    {{ __('Profit and Loss Report') }}
@endsection

@push('script-page')
    <script>
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
    </script>
@endpush

@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Profit and Loss Report') }}</a>
        </li>
    </ul>
@endsection
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profit and Loss Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  
   
<style>


    /* General Styles */
body {
  font-family: 'Poppins', sans-serif !important;
  margin: 0;
  padding: 0;
  background-color: #f7f9fc;
  color: #333;
}

.container {
  width: 100%;
  max-width: 1400px;
  margin: 10px auto;
  padding: 20px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Date Filter */
.filter-section {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  margin-bottom: 20px;
  gap: 10px;
}

.filter-section label {
  font-weight: 600;
  color: #333;
}

.filter-section input[type="date"] {
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 8px;
  font-size: 14px !important;
}

.filter-section button {
  padding: 8px 16px;
  background-color: #0cc0df;
  color: #fff;
  border: none;
  border-radius: 4px;
  font-size: 14px !important;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.filter-section button:hover {
  background-color: #099ab3;
}

/* Section Headings */
h2 {
  font-size: 16px !important;
  font-weight: 600;
  color: #0cc0df;
  margin-bottom: 15px !important;
  border-left: 4px solid #0cc0df;
  padding-left: 10px;
}

/* Top Section: Plain Text */
.summary {
  margin-bottom: 20px;
  font-size: 14px !important;
  line-height: 1.6;
}

.summary span {
  font-weight: 600;
  color: #555;
}

.summary .total {
  font-weight: 700;
  color: #0cc0df;
}

/* Tables */
table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
  background-color: #fdfdfd !important;
  border: 1px solid #ddd !important;
  border-radius: 4px;
  overflow: hidden;
}

table th,
table td {
  padding: 12px !important;
  text-align: left;
  border-bottom: 1px solid #ddd !important;
}

table th {
  background-color: #0cc0df;
  color: #fff !important;
  font-size: 12px !important;
  text-transform: uppercase;
}

table td {
  font-size: 12px !important;
  color: #555 !important;
}

.total-row {
  background-color: #e7f9fd !important;
  font-weight: 600;
  color: #333;
}

/* Responsive Design */
@media (max-width: 768px) {
  .summary p {
    font-size: 12px !important;
  }

  table th,
  table td {
    font-size: 10px !important;
  }

  .filter-section {
    flex-wrap: wrap;
    gap: 5px;
  }
}

  </style>
</head>
@section('content')

  

    <div class="container">
        <!-- Date Filter -->
        <div class="filter-section">
          <label for="date-range">Filter by Date:</label>
          <input type="date" id="start-date" placeholder="Start Date">
          <span>to</span>
          <input type="date" id="end-date" placeholder="End Date">
          <button>Apply Filter</button>
        </div>
    
        <!-- Top Section -->
        <section class="top-section">
          <h2>Income: 
      </h2>
          <div class="summary">
            <p><span>Rental Income:  </span>{{priceFormat($totalRentPayments)}} </p>
            <p><span>Other Income:  </span>{{priceFormat($totalOtherPayments) }}</p>
            <p><span>Security Deposit:  </span> {{priceFormat($totalSecurityDepositPayments)}}</p>

            <p class="total"><span>Total Income</span> {{$totalIncome}}</p>
          </div>
        </section>
    
        <section class="top-section">
          <h2>Expenses Overview</h2>
          <div class="summary">
            <p><span>Operational Expenses:</span> {{priceFormat($operationalExpenses)}}</p>
            <p><span>Liabilities Expenses:</span> {{priceFormat($liabilityExpenses)}}</p>
            <p><span>Tax Expenses:</span> {{priceFormat($taxExpenses)}}</p>
            <p class="total"><span>Total Expenses:</span>{{priceFormat($totalExpenses)}}</p>
          </div>
        </section>
        <section class="top-section">
            <h2>Profit or Loss</h2>
            <div class="summary">
              
              @php
              // Remove commas and ensure numeric values
              $totalIncome = is_numeric(str_replace(',', '', $totalIncome)) ? (float) str_replace(',', '', $totalIncome) : 0;
              $totalExpenses = is_numeric($totalExpenses) ? (float) $totalExpenses : 0;
          
              $profitOrLoss = $totalIncome - $totalExpenses;
          @endphp
          <p class="total"><span>Profit / Loss:</span> {{ priceFormat($profitOrLoss) }}</p>
          
            </div>
        </section>
        <!-- Expense Tables -->
        <section class="expense-section">
          <!-- Liabilities Expenses Table -->
          <h2>Income</h2>
          <table class="expense-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop through the invoice payments and display them -->
                @foreach($invoicePayments as $payment)
                    <tr>
                        <td>{{ ucwords(str_replace('_', ' ', $payment->payment_for)) }}</td>
                        <td>{{ priceFormat($payment->total_amount) }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td>Total Income</td>
                    <td>{{priceFormat($totalIncome)}}</td>
                  </tr>
                <!-- Total Liabilities Expenses (manually calculated or fetched) -->
               
            </tbody>
        </table>
        
   
          <!-- Total Expenses Overview Table -->
          <h2>Total Expenses</h2>
          <table class="expense-table">
            <thead>
              <tr>
                <th>Category</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <!-- Format type_name by replacing underscores and capitalizing words -->
                    <td>Liabilities</td>
                    <!-- Format total_amount using your priceFormat function -->
                    <td>{{priceFormat($liabilityExpenses)}}</td>
                </tr>

                <tr>
                    <!-- Format type_name by replacing underscores and capitalizing words -->
                    <td>Taxes</td>
                    <!-- Format total_amount using your priceFormat function -->
                    <td>{{priceFormat($taxExpenses)}}</td>
                </tr>   
                <tr>
                    <!-- Format type_name by replacing underscores and capitalizing words -->
                    <td>Properties</td>
                    <!-- Format total_amount using your priceFormat function -->
                    <td>{{priceFormat($propertyExpenses)}}</td>
                </tr>      
                          @foreach($expenseSummaries as $expense)
                <tr>
                    <!-- Format type_name by replacing underscores and capitalizing words -->
                    <td>{{ ucwords(str_replace('_', ' ', $expense['type_name'])) }}</td>
                    <!-- Format total_amount using your priceFormat function -->
                    <td>{{ priceFormat($expense['total_amount']) }}</td>
                </tr>
            @endforeach
            
             
              <tr class="total-row">
                <td>Total Expenses</td>
                <td>{{ priceFormat($totalExpenses) }}</td>
              </tr>
            </tbody>
          </table>
        </section>
      </div>
@endsection
