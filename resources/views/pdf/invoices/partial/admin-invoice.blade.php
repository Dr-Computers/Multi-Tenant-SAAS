@extends($adminTemplate && $adminTemplate->templateData->file_path ? $adminTemplate->templateData->file_path : 'pdf.base')

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
                <th>VAT (AED)</th>
                <th>Amount (AED)</th>
            </tr>
        </thead>
        <tbody>
            @if ($order->plan && $order->plan->count() > 0)

                @foreach ($order->plan ?? [] as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ number_format($item->tax, 2) }}</td>
                        <td>{{ $item->price_currency }} {{ number_format($item->price, 2) }}</td>
                    </tr>
                    @php
                        $currency = $item->price_currency;
                        $total = $total + (number_format($item->price, 2) + number_format($item->tax, 2));
                    @endphp
                @endforeach
            @else
                @if ($order->companySubscriptions && $order->companySubscriptions->count() > 0)
                    @foreach ($order->companySubscriptions ?? [] as $index => $sections)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $sections->section->name }}</td>
                            <td>{{ 0 }}</td>
                            <td>{{ number_format($sections->section->price, 2) }}</td>
                        </tr>
                        @php
                            $currency = $sections->section->price_currency;
                            $total = $total + number_format($sections->section->price, 2);
                        @endphp
                    @endforeach
                @endif
            @endif
        </tbody>
    </table>

    <table width="100%" style="margin-top: 20px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <p><strong>Total:</strong></p>
                <p><strong>Total (in words):</strong></p>
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top; text-transform: capitalize;">
                <p><strong> AED {{ number_format($total) }}</strong></p>
                <p>{{ numberToWords($total) }} United Arab Emirates dirhams</p>
            </td>
        </tr>
    </table>

    <footer>
        {{-- <div class="payment-info">
            <p><strong>Mode of Payment:</strong> Prepaid</p>
        </div> --}}

        <div class="company-signature">
            <p>for DR Computers</p><br><br><br>
            <p>Authorised Signatory</p>
        </div>
    </footer>

    <div class="center-text">
        <p><strong>Declaration:</strong> We declare that this invoice shows the actual price of the goods described and that
            all particulars are true and correct.</p>
        <p>This is a Computer Generated Invoice</p>
    </div>

@endsection
