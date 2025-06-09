<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unit Details</title>
    <style>
    body {
        font-family: "dejavusans", sans-serif;
        font-size: 8px; /* Reduced font size for content */
    }

    table {
        width: 100%;
        border-collapse: collapse; /* Ensures borders are unified and properly aligned */
        margin: 20px 0;
    }

    th, td {
        border: 1px solid #ddd; /* Light border color */
        padding: 30px 32px !important;/* Increased padding for more space inside cells */
        text-align: left;
        font-size: 8px; /* Reduced font size for table content */
        line-height: 2.5; /* Adds more space between lines in each cell */
        vertical-align: middle; 
    }

    th {
        background-color: #f4f4f4; /* Light gray background for headers */
        font-weight: bold; /* Make header text bold */
        padding-top: 16px; /* Slightly larger padding for headers */
        padding-bottom: 16px; 
    }

    td {
        background-color: #fff; /* White background for table data cells */
    }

    /* Optional: Styling for rows to alternate background colors */
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:nth-child(odd) {
        background-color: #ffffff;
    }

    /* Heading Style */
    h2 {
        text-align: left;
        font-size: 12px; /* Slightly larger font size for section titles */
        margin: 10px 0;
    }



    /* Adjustments for page margins */
    @page {
        margin: 15mm; /* Adds margin to the page */
    }
</style>

    
    
    
    
</head>
<body>
   <!-- Unit Details Section -->
    <h2>Unit Details</h2>
    <table class="table">
        <thead>
            <tr>
                <th colspan="2">{{ $unit->name }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong class="text-primary">{{ __('Rent: ') }}</strong></td>
                <td>{{ priceFormat(($unit->latestRateChange)->unit_amount) }}</td>
            </tr>
            <tr>
                <td><strong class="text-primary">{{ __('Property: ') }}</strong></td>
                <td>{{ $unit->properties->name ?? '-' }}</td>
            </tr>
        </tbody>
    </table>


<!-- Tenant Details Section -->
<h2>Tenant Details</h2>
@if($tenant)
<table class="table">
    <thead>
        <tr>
            <th colspan="2">{{ __('Tenant Details') }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>{{ __('Name:') }}</strong></td>
            <td>{{ $tenant->user->first_name }} {{ $tenant->user->last_name }}</td>
        </tr>
        <tr>
            <td><strong>{{ __('Email:') }}</strong></td>
            <td>{{ $tenant->user->email }}</td>
        </tr>
        <tr>
            <td><strong>{{ __('Phone:') }}</strong></td>
            <td>{{ $tenant->user->phone_number ?? '-' }}</td>
        </tr>
    </tbody>
</table>
@else
<p class="text-muted">{{ __('No tenant details found') }}</p>
@endif

   
    <!-- Cheque Details Section -->
    <h2>Cheque Details</h2>
    @if($checkDetails)
    <table>
        <thead>
            <tr>
                <th>{{ __('Check Number') }}</th>
                <th>{{ __('Amount') }}</th>
                <th>{{ __('Bank Name') }}</th>
                <th>{{ __('Bank Account') }}</th>
                <th>{{ __('Routing Number') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Notes') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $checkDetails->check_number }}</td>
                <td>{{ priceFormat($checkDetails->amount) }}</td>
                <td>{{ $checkDetails->bank_name }}</td>
                <td>{{ $checkDetails->bank_account_number }}</td>
                <td>{{ $checkDetails->routing_number }}</td>
                <td>{{ $checkDetails->status }}</td>
                <td>{{ $checkDetails->notes ?? '-' }}</td>
            </tr>
        </tbody>
    </table>
    @else
        <p class="text-muted">{{ __('No check details found') }}</p>
    @endif

    <!--Invoices and Payments Section -->
    <h2>Invoices and Payments</h2>
    <table>
        <thead>
            <tr>
                <th>{{ __('Invoice ID') }}</th>
                            <th>{{ __('Invoice Date') }}</th>
                            <th>{{ __('Invoice Period') }}</th>
                            {{-- <th>{{ __('Invoice Period End') }}</th> --}}
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Pending Amount') }}</th>
            </tr>
        </thead>
        <tbody>
            @php
            $totalAmount = 0;
            $totalPending = 0;
            $totalPayments = 0;
        @endphp

        @foreach($invoices as $invoice)
            @php
                $invoiceAmount = $invoice->getInvoiceSubTotalAmount();
                $pendingAmount = $invoice->getInvoiceDueAmount();
                $paymentAmount = $invoice->payments->sum('amount');

                $totalAmount += $invoiceAmount;
                $totalPending += $pendingAmount;
                $totalPayments += $paymentAmount;
            @endphp
            <tr>
                <td>{{ (optional($invoice->properties)->invoice_prefix ?: invoicePrefix()) .$invoice->invoice_id }}</td>
                <td>{{ dateFormat($invoice->end_date) }}</td>
                <td>{{ $invoice->invoice_period . ' years' }}</td>
                {{-- <td>{{ $invoice->invoice_period_end_date }}</td> --}}
                <td>{{ priceFormat($invoiceAmount) }}</td>
                <td>{{ priceFormat($pendingAmount) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>{{ __('Total') }}</strong></td>
                <td><strong>{{ priceFormat($totalAmount) }}</strong></td>
                <td><strong>{{ priceFormat($totalPending) }}</strong></td>
                <td><strong>{{ priceFormat($totalPayments) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- Security Deposit Payments Section -->
    <h2>Security Deposit Payments</h2>
    <table>
        <thead>
            <tr>
                <th>{{ __('Unit Name') }}</th>
                <th>{{ __('Tenant Name') }}</th>
                <th>{{ __('Amount Paid') }}</th>
                <th>{{ __('Payment Date') }}</th>
            </tr>
        </thead>
        <tbody>
            @php
            $totalSecurityDeposits = 0;
        @endphp

        @foreach($securityDepositPayments as $payment)
            @php
                $totalSecurityDeposits += $payment->amount;
            @endphp
            <tr>
                <td>{{ optional($payment->unit)->name ?? 'N/A' }}</td>
                <td>{{ optional(optional($payment->unit))->tenants()->user->first_name ?? 'N/A' }}</td>
                <td>{{ priceFormat($payment->amount) }}</td>
                <td>{{ $payment->created_at->format('Y-m-d') }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-right"><strong>{{ __('Total') }}</strong></td>
                <td colspan="2"><strong>{{ priceFormat($totalSecurityDeposits) }}</strong></td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
