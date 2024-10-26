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

        body {
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            font-family: Poppins, Helvetica, sans-serif;
            font-size: 62.5%;
        }

        .kop-header {
            margin: 0 auto;
        }

        .kop-image-header {
            width: 100%;
            margin-bottom: 20px;
        }

        .kop-image-footer {
            width: 100%;
            margin-bottom: 100px;
        }

        .container {
            margin: 20px 20px 190px 20px;
        }

        .wrapper {
            position: relative;
            min-height: 100%;
            margin-bottom: -100px;
        }


        table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-heading-container {
            width: 48%;
            margin-right: 10%;
            float: left;
            display: inline-block;
        }

        .clearfix {
            clear: both;
            margin-bottom: 40px;
        }

        .table-heading .table-data-heading {
            /*border: 1px solid;*/
            border: none !important;
            margin-bottom: 100px;
            width: 50%;
            text-align: left;
        }

        .table-product, .row-product, .heading-product, .data-table-product {
            width: 100%;
            border: 1px solid;
            padding: 10px 0 10px 0;
            margin-bottom: 10px;
        }

        .heading-text {
            font-weight: bold;
            font-size: 12px;

        }


        .heading-separator {
            margin-bottom: 0;
        }


        .bg-primary {
            background-color: rgb(0, 158, 247);
        }

        .text-end {
            text-align: right;
        }


        .notes {
            font-size: 13px;
            margin-bottom: 50px;
        }

        .signature {
            font-size: 13px;
            padding-right: 40px;
            font-weight: bold;
            float: right;
        }

        .text-center {
            text-align: center;
        }

        .kop-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body>

<div class="wrapper">
    <div class="kop-header">
        <img class="kop-image-header"
             src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-header.png'))) }}"/>
    </div>
    <div class="container">
        <div class="heading-separator table-heading-container">
            <table class="table-heading" >
                <tbody>
                <tr>
                    <td style="width: 100%">
                        <p class="heading-text">{{ $companyProfile->name }}</p>
                    </td>
                    <td style="width: 1px; text-align: left;">
                    </td>
                    <td style="width: 1px">
                    </td>

                </tr>
                <tr>
                    <td style="width: 12px">
                        <p class="heading-text"> Jalan Sultan Hasanudin 8A Dumai</p>
                    </td>
                    <td style="width: 1px; text-align: left;">
                    </td>
                    <td style="width: 100%">

                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;">
                        <p class="heading-text">NPWP: {{ $companyProfile->npwp }}</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="heading-separator table-heading-container">
            <table class="table-heading">
                <tbody>
                <tr>
                    <td class="table-data-heading" style="width: 1px; text-align: center">
                        <p class="heading-text">Invoice No.</p>
                    </td>
                    <td class="table-data-heading" style="width: 1px">
                        <p class=" heading-text-separator">:</p>
                    </td>
                    <td class="table-data-heading">
                        <p class="heading-text heading-text-separator"> {{ $invoice->invoice_number   }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="table-data-heading" style="width: 1px; text-align: center">
                        <p class="heading-text heading-text-separator">Tanggal</p>
                    </td>
                    <td class="table-data-heading" style="width: 1px">
                        <p class="heading-text-separator">:</p>
                    </td>
                    <td class="table-data-heading" style="width: 100px">
                        <p class="heading-text">{{ $invoice->created_at->format('d M y') }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="table-data-heading" style="width: 1px ;text-align: center">
                        <p class="heading-text heading-text-separator">Periode</p>
                    </td>
                    <td class="table-data-heading" style="width: 1px">
                        <p class="heading-text-separator">:</p>
                    </td>
                    <td class="table-data-heading" style="width: 100px">
                        <p class="heading-text">{{ $invoice->created_at->format('M') }}</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="clearfix"></div>

        <table class="table-product">
            <thead>
            <tr class="bg-primary row-product">
                <th class="heading-product">No</th>
                <th class="heading-product">Keterangan</th>
                <th class="heading-product">@</th>
                <th class="heading-product">Qty</th>
                <th class="heading-product">Jumlah</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoiceServiceList as $invoiceService)
                <tr class="row-product">
                    <td class="data-table-product" style="text-align: center">{{ $loop->iteration }}</td>
                    <td class="data-table-product" style="text-align: center">{{ $invoiceService->description }}</td>
                    <td class="data-table-product" style="text-align: center">
                        Rp.{{ number_format($invoiceService->unit_price) }}</td>
                    <td class="data-table-product" style="text-align: center">{{ $invoiceService->qty }}</td>
                    <td class="data-table-product" style="padding: 0 10px 0 10px;">
                        Rp. {{ number_format($invoiceService->total_price) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr class="row-product">
                <td colspan="4" class="data-table-product text-end" style="padding: 0 10px 0 10px;">SUBTOTAL</td>
                <td class="data-table-product " style="padding: 0 10px 0 10px;">
                    Rp. {{ number_format($invoiceServiceList->sum('total_price')) }}
                </td>
            </tr>
            <tr class="row-product">
                <td colspan="4" class="data-table-product text-end" style="padding: 0 10px 0 10px;">PPN 11%</td>
                <td class="data-table-product py-1" style="padding: 0 10px 0 10px;">
                    Rp. -
                </td>
            </tr>
            <tr class="row-product">
                <td colspan="4" class="data-table-product text-end" style="padding: 0 10px 0 10px;">PPH 2%</td>
                <td class="data-table-product py-1" style="padding: 0 10px 0 10px;">
                    Rp. -
                </td>
            </tr>
            <tr class="row-product">
                <td colspan="4" class="data-table-product text-end" style="padding: 0 10px 0 10px;">TOTAL</td>
                <td class="data-table-product" style="padding: 0 10px 0 10px;">
                    Rp. {{ number_format($invoiceServiceList->sum('total_price')) }}
                </td>
            </tr>
            </tfoot>
        </table>
        <div class="heading-separator table-heading-container">
            <table class="table-heading">
                <tbody>
                <tr>
                    <td class="table-data-heading" style="width: 1px; text-align: left">
                        <p class="heading-text">Bank</p>
                    </td>
                    <td class="table-data-heading" style="width: 1px">
                        <p class=" heading-text-separator">:</p>
                    </td>
                    <td class="table-data-heading">
                        <p class="heading-text heading-text-separator">{{ $companyProfile->bank }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="table-data-heading" style="width: 1px; text-align: left">
                        <p class="heading-text heading-text-separator">No. Rekening</p>
                    </td>
                    <td class="table-data-heading" style="width: 1px">
                        <p class="heading-text-separator">:</p>
                    </td>
                    <td class="table-data-heading" style="width: 100px">
                        <p class="heading-text">{{ $companyProfile->bank_account_number }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="table-data-heading" style="width: 1px ;text-align: left">
                        <p class="heading-text heading-text-separator">Atas Nama</p>
                    </td>
                    <td class="table-data-heading" style="width: 1px">
                        <p class="heading-text-separator">:</p>
                    </td>
                    <td class="table-data-heading" style="width: 100px">
                        <p class="heading-text">{{ $companyProfile->bank_account_name }}
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
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <div class="notes" style="border: 1px solid; margin: 0 20px 0 20px">
        <p>
            1.Pembayaran wajib melampirkan bukti transfer ke email <a style="text-decoration: none" href="#">finance@mayatama.net</a>
            <br>
            2.Keterlambatan pembayaran dapat menyebabkan layanan anda terblokir.<br>
            3.Mohon abaikan tagihan ini apabila anda telah melakukan pembayaran.<br>
            4.Untuk konfirmasi silahkan menghubungi account representative kami.<br>
        </p>
    </div>

    <div class="clearfix"></div>
    <div class="kop-footer">
        <img class="kop-image-footer"
             src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-footer.png'))) }}"/>
    </div>

</div>
</body>
</html>
