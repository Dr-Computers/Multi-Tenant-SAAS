{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start; /* Align items to the start */
            margin-bottom: 20px;
        }

        .company-info {
            flex: 1; /* Takes available space */
        }

        .tenant-info {
            flex: 1; /* Takes available space */
            text-align: right; /* Align tenant details to the right */
        }

        .company-info img {
            max-width: 150px; /* Adjust logo size */
        }

        .company-info h1, .tenant-info h2 {
            margin: 0;
            font-size: 24px;
        }

        .company-info p, .tenant-info p {
            margin: 5px 0;
            font-size: 14px;
        }

        .details {
            margin: 20px 0;
        }

        .details h2 {
            margin: 10px 0;
            font-size: 20px;
            color: #333;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .details th, .details td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .details th {
            background-color: #f2f2f2;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .tenant-info {
                text-align: left; /* Align tenant details to the left on small screens */
                margin-top: 20px; /* Add margin top for spacing */
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="company-info">
            <img src="path/to/logo.png" alt="Company Logo"> <!-- Replace with your logo path -->
            <h1>Company Name</h1>
            <p>Address Line 1<br>Address Line 2<br>City, State, ZIP<br>Phone: (123) 456-7890</p>
        </div>
        <div class="tenant-info">
            <h2>Tenant Details</h2>
            <p><strong>Name:</strong> {{ $tenant->name }}</p>
            <p><strong>Property:</strong> {{ $tenant->property }}</p>
            <p><strong>Unit:</strong> {{ $tenant->unit }}</p>
        </div>
    </div>

    <div class="details">
        <h2>Payment Details</h2>
        <table>
            <tr>
                <th>Transaction ID</th>
                <th>Amount</th>
                <th>Payment Type</th>
                <th>Payment Date</th>
                <th>Notes</th>
            </tr>
            <tr>
                <td>{{ $payment->transaction_id }}</td>
                <td>${{ number_format($payment->amount, 2) }}</td>
                <td>{{ ucfirst($payment->payment_type) }}</td>
                <td>{{ $payment->payment_date }}</td>
                <td>{{ $payment->notes }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Thank you for your payment!</p>
    </div>
</div>

</body>
</html> --}}
@php
    $admin_logo=getSettingsValByName('company_logo');
    $settings=settings();
@endphp
{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('app.menu.payments') }} {{ $invoice->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .header .company-info {
            text-align: left;
            flex: 1;
        }
        .header .address-info {
            text-align: left;
            flex: 1;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #007BFF;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        h2 {
            border-bottom: 2px solid #007BFF;
            padding-bottom: 10px;
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }
        .summary p {
            margin: 5px 0;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="company-info">
                <h1>Receipt</h1>
                <p><strong>Invoice ID:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Client:</strong> {{!empty($tenant) && !empty($tenant->user)?$tenant->user->first_name.' '.$tenant->user->last_name:''}}</p>
                <p>{{!empty($tenant) && !empty($tenant->user) ?$tenant->user->phone_number:'-'}}</p>
                <p> {{!empty($tenant)?$tenant->address:''}}</p>
                <p><strong>Date:</strong> {{ dateFormat($payment->payment_date) }}</p>
            </div>
            <div class="address-info">
                <p>{{$settings['company_name']}}</p>
            <p>{{$settings['company_phone']}}</p>
            <p>{{$settings['company_email']}}</p>
            </div>
        </div>

        <h2>Payment Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Amount</th>
                    <th>Payment Type</th>
                    <th>Payment Date</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
              
                    <tr>
                        <td>{{ $payment->transaction_id }}</td>
                        <td>{{priceFormat($payment->amount)}}</td>
                        <td>{{ ucfirst($payment->payment_type) }}</td>
                        <td>{{ $payment->payment_date }}</td>
                        <td>{{ $payment->notes }}</td>
                    </tr>
             
            </tbody>
        </table>

        <h2>Invoice Summary</h2>
        <div class="summary">
            <p><strong>Total Amount:</strong> {{priceFormat($invoice->getInvoiceSubTotalAmount())}}</p>
            <p><strong>Outstanding Amount:</strong> {{priceFormat($invoice->getInvoiceDueAmount())}}</p>
        </div>

        <div class="footer">
            <p><strong></strong>{{$settings['company_name']}}</p>
           
        </div>
    </div>
</body>
</html>
 --}}
 <!doctype html>
 <html lang="en">
 
 <head>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <title>{{$payment->receipt_number}}</title>
     <meta name="msapplication-TileColor" content="#ffffff">
     <meta name="msapplication-TileImage" content="">
     <meta name="theme-color" content="#ffffff">
 
     <style>
         body {
             margin: 0;
             font-family: Verdana, Arial, Helvetica, sans-serif;
         }
 
         .bg-grey {
             background-color: #F2F4F7;
         }
 
         .bg-white {
             background-color: #fff;
         }
 
         .border-radius-25 {
             border-radius: 0.25rem;
         }
 
         .p-25 {
             padding: 1.25rem;
         }
 
         .f-13 {
             font-size: 13px;
         }
 
         .f-14 {
             font-size: 14px;
         }
 
         .f-15 {
             font-size: 15px;
         }
 
         .f-21 {
             font-size: 21px;
         }
 
         .text-black {
             color: #28313c;
         }
 
         .text-grey {
             color: #616e80;
         }
 
         .font-weight-700 {
             font-weight: 700;
         }
 
         .text-uppercase {
             text-transform: uppercase;
         }
 
         .text-capitalize {
             text-transform: capitalize;
         }
 
         .line-height {
             line-height: 24px;
         }
 
         .mt-1 {
             margin-top: 1rem;
         }
 
         .mb-0 {
             margin-bottom: 0px;
         }
 
         .b-collapse {
             border-collapse: collapse;
         }
 
         .heading-table-left {
             padding: 6px;
             border: 1px solid #DBDBDB;
             font-weight: bold;
             background-color: #f1f1f3;
             border-right: 0;
         }
 
         .heading-table-right {
             padding: 6px;
             border: 1px solid #DBDBDB;
             border-left: 0;
         }
 
         .unpaid {
             color: #000000;
             border: 1px solid #000000;
             position: relative;
             padding: 11px 22px;
             font-size: 15px;
             border-radius: 0.25rem;
             width: 120px;
             text-align: center;
             margin-top: 50px;
         }
 
         .main-table-heading {
             border: 1px solid #DBDBDB;
             background-color: #f1f1f3;
             font-weight: 700;
         }
 
         .main-table-heading td {
             padding: 11px 10px;
             border: 1px solid #DBDBDB;
         }
 
         .main-table-items td {
             padding: 11px 10px;
             border: 1px solid #e7e9eb;
         }
 
         .total-box {
             border: 1px solid #e7e9eb;
             padding: 0px;
             border-bottom: 0px;
         }
 
         .subtotal {
             padding: 11px 10px;
             border: 1px solid #e7e9eb;
             border-top: 0;
             border-left: 0;
         }
 
         .subtotal-amt {
             padding: 11px 10px;
             border: 1px solid #e7e9eb;
             border-top: 0;
             border-right: 0;
         }
 
         .total {
             padding: 11px 10px;
             border: 1px solid #e7e9eb;
             border-top: 0;
             font-weight: 700;
             border-left: 0;
         }
 
         .total-amt {
             padding: 11px 10px;
             border: 1px solid #e7e9eb;
             border-top: 0;
             border-right: 0;
             font-weight: 700;
         }
 
         .balance {
             font-size: 16px;
             font-weight: bold;
             background-color: #f1f1f3;
         }
 
         .balance-left {
             padding: 11px 10px;
             border: 1px solid #e7e9eb;
             border-top: 0;
             border-left: 0;
         }
 
         .balance-right {
             padding: 11px 10px;
             border: 1px solid #e7e9eb;
             border-top: 0;
             border-right: 0;
         }
 
         .centered {
             margin: 0 auto;
         }
 
         .rightaligned {
             margin-right: 0;
             margin-left: auto;
         }
 
         .leftaligned {
             margin-left: 0;
             margin-right: auto;
         }
 
         .page_break {
             page-break-before: always;
         }
 
         #logo {
             height: 50px;
         }
 
         .word-break {
             max-width:175px;
             word-wrap:break-word;
         }
 
         .summary {
             padding: 11px 10px;
             border: 1px solid #e7e9eb;
         }
 
         .text-center {
             text-align: center;
         }
 /* Example CSS */
 .content-wrapper {
     position: relative;
 }
 
 .unpaid.rightaligned {
     text-align: right;
 }
 
 
     </style>
     
 </head>
 
 <body class="content-wrapper">
     
     <h3 class="text-center">Payment Receipt</h3>
     <h5 class="text-center text-primary">{{$payment->receipt_number}}</h5>
     <table class="bg-white" border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
         <tbody>
             <!-- Table Row Start -->
             <tr>
                 <td style="width: 70%;">
                     <p class="line-height mt-1 mb-0 f-14 text-black">
                        {{$settings['company_name']}}<br>
                        {{$settings['company_phone']}}<br>
                        {{$settings['company_email']}}
                     </p>
                 </td>
                 <td style="width: 30%; text-align: right;">
                     <img src="{{asset(Storage::url('upload/logo/')).'/'.(isset($admin_logo) && !empty($admin_logo)?$admin_logo:'logo.png')}}" alt="Company Logo" style="max-width: 150px;">
                 </td>
             </tr>
             <!-- Table Row End -->
          
             <!-- Client Details and Payment Status -->
             <tr>
                 <td colspan="2">
                  
                     <table border="0" cellpadding="0" cellspacing="0" width="100%">
                         <tr>
                            
                             <td class="f-14 text-black" style="width:70%">
                                 <p class="line-height mb-0">
                                     <span class="text-grey text-capitalize">Billed To</span><br>
                                     {{!empty($tenant) && !empty($tenant->user)?$tenant->user->first_name.' '.$tenant->user->last_name:''}}<br>
                                     {{!empty($tenant) && !empty($tenant->user) ?$tenant->user->phone_number:'-'}}<br>
                                     {{!empty($tenant)?$tenant->address:''}}
                                 </p>
                             </td>
                           
                             <td align="right" style="width:30%">
                                 <br />
                                 {{-- <div class="text-uppercase bg-white unpaid rightaligned">
                                     @lang('app.'.$payment->status)
                                 </div> --}}
                             </td>
                         </tr>
                     </table>
                 </td>
             </tr>
         </tbody>
     </table>
 
     <!-- Payment Details -->
     <table class="f-14 b-collapse" width="100%">
         <tr>
             <td height="20"></td>
         </tr>
         <tr class="main-table-items">
             <td class="text-grey">Amount</td>
             <td>{{priceFormat($payment->amount)}}</td>
         <tr class="main-table-items">
             <td class="text-grey">Payment Method</td>
             <td>{{ ucfirst($payment->payment_type) }}</td>
         </tr>
         <tr class="main-table-items">
             <td class="text-grey">Transaction Id</td>
             <td>{{ $payment->transaction_id }}</td>
         </tr>
         <tr class="main-table-items">
             <td class="text-grey">Paid On</td>
             <td>{{ $payment->payment_date }}</td>
         </tr>
    
      
             <tr class="main-table-items">
                 <td class="text-grey">Invoice Number</td>
                 <td>{{ invoicePrefix().$payment->invoice->invoice_id }}</td>
             </tr>
       
     </table>
     <!-- Stamp Image -->
     <p class="mt-5 text-center"> {{$settings['company_name']}}</p>
         {{-- <img src="{{ asset('img/stamp_sign.png') }}" alt="Stamp Image" style="max-width: 150px;">
      --}}
     
 
 </body>
 
 </html>
 