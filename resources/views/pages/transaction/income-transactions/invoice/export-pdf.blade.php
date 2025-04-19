<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice #{{ $invoice->invoice_number }}</title>

    <style>

        *, *::before, *::after {
            box-sizing: border-box;
        }


        * {
            margin: 0;
        }

        html {
            -webkit-print-color-adjust: exact;
        }


        @page {
            margin: 0 0;
        }


        body {
            margin: 4cm 0.6cm 2cm;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            font-family: Poppins, Helvetica, sans-serif;
            font-size: 62.5%;
            box-sizing: border-box;
        }


        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 4.5cm;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 5cm;
        }


        .wrapper {
            margin-top: 170px;
            position: relative;
        }


        .d-flex {
            display: flex !important;
        }

        .align-items-start {
            align-items: flex-start !important
        }

        .justify-content-between {
            justify-content: space-between !important
        }

        .text-justify {
            text-align: justify;
            line-height: 1.7;
        }

        .flex-column {
            flex-direction: column !important
        }


        .mt-n3 {
            margin-top: -.75rem !important
        }

        .border {
            border: 1px solid black !important
        }

        .border-bottom {
            border-bottom: 1px solid black;
        }


        .border-top {
            border-top: 1px solid black;
        }

        .border-3 {
            border-width: 3px !important
        }

        .p-1 {
            padding: .25rem !important
        }

        .text-center {
            text-align: center;
        }

        .text-white {
            color: #ffffff !important;
        }

        .fw-bolder {
            font-weight: 700 !important
        }

        .text-uppercase {
            text-transform: uppercase !important
        }

        .mt-10 {
            margin-top: 2.5rem !important
        }

        .mt-1 {
            margin-top: 0.5rem;
        }

        .mb-10 {
            margin-bottom: 2.5rem !important
        }

        .row {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            display: flex;
            flex-wrap: wrap;
            margin-top: calc(-1 * var(--bs-gutter-y));
            margin-right: calc(-.5 * var(--bs-gutter-x));
            margin-left: calc(-.5 * var(--bs-gutter-x))
        }


        .ms-n2 {
            margin-left: -.5rem !important
        }

        .mb-4 {
            margin-bottom: 1rem !important
        }

        .col-lg-4 {
            flex: 0 0 auto;
            width: 33.33333333%
        }

        .fs-9 {
            font-size: .50rem !important
        }

        .text-danger {
            opacity: 1;
            color: rgba(248, 40, 90, 1) !important
        }

        .col-lg-6 {
            flex: 0 0 auto;
            width: 50%
        }

        .col-lg-12 {
            flex: 0 0 auto;
            width: 100%
        }

        .col-md-5 {
            flex: 0 0 auto;
            width: 41.66666667%
        }

        .mt-2 {
            margin-top: .5rem !important
        }

        .form-check-input[type=checkbox] {
            border-radius: .4em;
            border: 1px solid #000000;
        }

        .fw-bold {
            font-weight: 600 !important
        }

        .ms-1 {
            margin-left: .25rem !important
        }

        .align-items-center {
            align-items: center !important
        }


        .p-1 {
            padding: .25rem !important
        }

        .mb-1 {
            margin-bottom: .25rem !important
        }

        .w-150px {
            width: 140px !important
        }

        .p-4 {
            padding: 1rem !important
        }

        .p-3 {
            padding: .75rem !important
        }

        .p-2 {
            padding: .7rem !important
        }

        .w-200px {
            width: 200px !important
        }

        .me-3 {
            margin-right: .75rem !important
        }

        .me-4 {
            margin-right: 1rem !important
        }

        .w-150px {
            width: 150px !important
        }

        .me-2 {
            margin-right: .5rem !important
        }

        .justify-content-center {
            justify-content: center !important
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch
        }

        .px-10 {
            padding-right: 2.5rem !important;
            padding-left: 2.5rem !important
        }

        .table {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .text-center {
            text-align: center !important
        }


        .ms-5 {
            margin-left: 1.25rem !important
        }

        .text-start {
            text-align: left !important
        }

        .py-1 {
            padding-top: .25rem !important;
            padding-bottom: .25rem !important
        }

        .text-end {
            text-align: right !important
        }


        .px-10 {
            padding-right: 2.5rem !important;
            padding-left: 2.5rem !important
        }


        .ms-2 {
            margin-left: .5rem !important
        }


        .custom-bordered, .custom-bordered th .custom-bordered tr, .custom-bordered td {
            border-left: none;
            border-right: none;
            border-top: 1px solid black;
            border-bottom: 1px solid black;
            border-collapse: collapse;

        }

        .mt-5 {
            margin-top: 1.25rem !important
        }

        .mb-5 {
            margin-bottom: 1.25rem !important
        }

        .px-3 {
            padding-right: .75rem !important;
            padding-left: .75rem !important
        }

        .mb-10 {
            margin-bottom: 2.5rem !important
        }

        .px-4 {
            padding-right: 1rem !important;
            padding-left: 1rem !important
        }

        .px-9 {
            padding-right: 3.25rem !important;
            padding-left: 3.25rem !important
        }

        .w-400px {
            width: 400px !important
        }

        .px-1 {
            padding-right: .25rem !important;
            padding-left: .25rem !important
        }

        .ms-4 {
            margin-left: 1rem !important
        }

        .m-0 {
            margin: 0 !important
        }


        .mt-0 {
            margin-top: 0 !important
        }


        .mb-3 {
            margin-bottom: .75rem !important
        }


        .justify-content-around {
            justify-content: space-around !important
        }

        .mb-20 {
            margin-bottom: 5rem !important
        }

        .mb-10 {
            margin-bottom: 2.5rem !important
        }

        .w-1px {
            width: 1px !important
        }

        .margin-after-page-break {
            margin-top: 170px;
        }

        .form-check-input:disabled {
            pointer-events: none;
            filter: none;
            opacity: .5
        }

        .form-check-input[type=checkbox]:indeterminate {
            background-color: #0d6efd;
            border-color: #000000;
            --bs-form-check-bg-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10h8'/%3e%3c/svg%3e")
        }

        .form-check-input:checked[type=checkbox] {
            --bs-form-check-bg-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='m6 10 3 3 6-6'/%3e%3c/svg%3e")
        }

        .form-check-input:checked {
            background-color: #7dbbf5;
            border-color: #7dbbf5
        }

        .form-check-input:focus {
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 .25rem rgba(13, 110, 253, .25)
        }

        .form-check-reverse .form-check-input {
            float: right;
            margin-right: -1.5em;
            margin-left: 0
        }

        .form-check-input {
            --bs-form-check-bg: var(--bs-body-bg);
            flex-shrink: 0;
            width: 1em;
            height: 1em;
            margin-top: .25em;
            vertical-align: top;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-color: var(--bs-form-check-bg);
            background-image: var(--bs-form-check-bg-image);
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            border: var(--bs-border-width) solid var(--bs-border-color);
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            print-color-adjust: exact
        }

        .form-check-input[type=checkbox] {
            border-radius: .25em
        }

        .form-check-input:active {
            filter: brightness(90%)
        }

        .form-check .form-check-input {
            float: left;
            margin-left: -1.5em
        }

        .mt-0 {
            margin-top: 0 !important
        }

        .mt-5 {
            margin-top: 1.25rem !important
        }

        .w20px {
            width: 20px;
        }

        .text-black {
            color: rgba(0, 0, 0, 1) !important
        }

        .text-danger {
            color: var() !important
        }


        .mt-4 {
            margin-top: 1rem !important
        }

        .mt-3 {
            margin-top: .75rem !important
        }

        .mb-3 {
            margin-bottom: .75rem !important;
        }


        .py-10 {
            padding-top: .3rem !important;
            padding-bottom: .3rem !important
        }

        .mb-2 {
            margin-bottom: .5rem !important
        }

        .ms-2 {
            margin-left: .5rem !important
        }

        .ms-3 {
            margin-left: .75rem !important
        }

        .ms-4 {
            margin-left: 1rem !important
        }

        .m-0 {
            margin: 0 !important
        }

        .border-1 {
            border-width: 1px !important
        }

        .p-custom {
            padding: 0.1rem;
        }

        .px-7 {
            padding-right: 1.75rem !important;
            padding-left: 1.75rem !important
        }

        .px-8 {
            padding-right: 2rem !important;
            padding-left: 2rem !important
        }

        .mb-6 {
            margin-bottom: 1.5rem !important
        }


        .custom-bordered, .custom-bordered th .custom-bordered tr, .custom-bordered td {
            border-left: none;
            border-right: none;
            border-top: 1px solid black;
            border-bottom: 1px solid black;
            border-collapse: collapse;

        }
    </style>
</head>

<body>

<header>
    <img
        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-header.png'))) }}"
        width="100%" height="100%"/>
</header>

<footer>
    <img
        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-footer.png'))) }}"
        width="100%" height="100%"/>
</footer>


<div class="wrapper">
    <div class="container">
        <div>
            <table>
                <tbody>
                <tr>
                    <td style="width: 100%">
                        <p>{{ $companyProfile->name }}</p>
                    </td>
                    <td style="width: 1px; text-align: left;">
                    </td>
                    <td>
                    </td>

                </tr>
                <tr>
                    <td style="width: 12px">
                        <p> Jalan Sultan Hasanudin 8A Dumai</p>
                    </td>
                    <td style="width: 1px; text-align: left;">
                    </td>
                    <td style="width: 100%">

                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;">
                        <p>NPWP: {{ $companyProfile->npwp }}</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div>
            <table class="table">
                <tbody>
                <tr>
                    <td>
                        <p>Invoice No.</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p> {{ $invoice->invoice_number   }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Tanggal</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p>{{ $invoice->created_at->format('d M y') }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Periode</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p>{{ $invoice->created_at->format('M') }}</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="clearfix"></div>

        <table class="table custom-bordered">
            <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Keterangan</th>
                <th>@</th>
                <th>Qty</th>
                <th>Jumlah</th>
            </tr>
            </thead>
            <tbody class="text-center">
            @foreach($invoiceServiceList as $invoiceService)
                <tr>
                    <td class="py-1">{{ $loop->iteration }}</td>
                    <td class="py-1">{{ $invoiceService->description }}</td>
                    <td class="py-1">
                        Rp.{{ number_format($invoiceService->unit_price) }}</td>
                    <td class="py-1">{{ $invoiceService->qty }}</td>
                    <td class="py-1">
                        Rp. {{ number_format($invoiceService->total_price) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4" class="text-end py-1 fw-bold">SUBTOTAL</td>
                <td style="padding: 0 10px 0 10px;">
                    Rp. {{ number_format($invoiceServiceList->sum('total_price')) }}
                </td>
            </tr>
            <tr>
                <td colspan="4" class="text-end py-1 fw-bold">PPN 11%</td>
                <td style="padding: 0 10px 0 10px;">
                    Rp. -
                </td>
            </tr>
            <tr>
                <td colspan="4" class="text-end py-1 fw-bold">PPH 2%</td>
                <td style="padding: 0 10px 0 10px;">
                    Rp. -
                </td>
            </tr>
            <tr>
                <td colspan="4" class="text-end py-1 fw-bold">TOTAL</td>
                <td style="padding: 0 10px 0 10px;">
                    Rp. {{ number_format($invoiceServiceList->sum('total_price')) }}
                </td>
            </tr>
            </tfoot>
        </table>
        <div>
            <table>
                <tbody>
                <tr>
                    <td>
                        <p>Bank</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p>{{ $companyProfile->bank }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>No. Rekening</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p>{{ $companyProfile->bank_account_number }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 1px ;text-align: left">
                        <p>Atas Nama</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p>{{ $companyProfile->bank_account_name }}
                        </p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
    <div class="signature">
        <p class="text-center">{{ $companyProfile->name }} <br><br><br><br><br>
            <br><u>YOGA ARYA ESA PRATAMA</u><br>Direktur
        </p>
    </div>

    <div class="notes" style="border: 1px solid; margin: 0 20px 0 20px">
        <p>
            1.Pembayaran wajib melampirkan bukti transfer ke email <a style="text-decoration: none" href="#">finance@mayatama.net</a>
            <br>
            2.Keterlambatan pembayaran dapat menyebabkan layanan anda terblokir.<br>
            3.Mohon abaikan tagihan ini apabila anda telah melakukan pembayaran.<br>
            4.Untuk konfirmasi silahkan menghubungi account representative kami.<br>
        </p>
    </div>

</div>
</body>
</html>
