@extends($companyTemplate && $companyTemplate->templateData->file_path ? $companyTemplate->templateData->file_path : 'pdf.base')

@section('content')

    @php
        $total = 0;
        $currency = 'AED';

    @endphp

    <table class="items">
        <thead>
            <tr>
                <th>Sl. No.</th>
                <th>Description</th>
                <th>VAT ({{ currencySymbol() }})</th>
                <th>Amount ({{ currencySymbol() }})</th>
            </tr>
        </thead>
        <tbody>
            @if ($invoice->invoiceItems && $invoice->invoiceItems->count() > 0)
                @foreach ($invoice->invoiceItems ?? [] as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ \Auth::user()->priceFormat($item->tax_amount, 2) }}</td>
                        <td>{{ \Auth::user()->priceFormat($item->amount) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center">
                        {{ 'No data Found..' }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <table width="100%" style="margin-top: 20px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <p><strong>Sub Total</strong></p>   
                <p><strong>Discount</strong></p>
                <p><strong>Total:</strong></p>
                {{-- <p><strong>Total (in words):</strong></p> --}}
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top; text-transform: capitalize;">
                <p><strong>{{ \Auth::user()->priceFormat($invoice->sub_total) }}</strong></p>
                <p><strong>{{ \Auth::user()->priceFormat($invoice->discount_amount) }}</strong></p>
                <p><strong> {{ \Auth::user()->priceFormat($invoice->grand_total) }}</strong></p>
                {{-- <p>{{ numberToWords($invoice->grand_total) }}</p> --}}
            </td>
        </tr>
    </table>

    <footer>
        {{-- <div class="payment-info">
            <p><strong>Mode of Payment:</strong> Prepaid</p>
        </div> --}}

        <div class="company-signature">
            <p class="fw-bold">for {{ $invoice->company->name }}</p><br><br><br>
            <p>Authorised Signatory</p>
        </div>
    </footer>

    <div class="center-text">
        <p><strong>Declaration:</strong> We declare that this invoice shows the actual price of the goods described and that
            all particulars are true and correct.</p>
        <p>This is a Computer Generated Invoice</p>
    </div>

@endsection
