<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FAB</title>

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
        }

        .table-heading .table-data-heading {
            border: none !important;
            margin-bottom: 100px;
            /*border: 1px solid;*/
            width: 50%;
            text-align: left;
        }

        .table-product, .row-product, .heading-product, .data-table-product {
            width: 100%;
            border: 1px solid;
            padding: 10px;
        }

        .heading-text {
            font-weight: bold;
            font-size: 12px;

        }


        .heading-separator {
            margin-bottom: 50px;
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
@php
    $date = DateTime::createFromFormat('Y-m-d', $fab->date);
        $formattedTransactiondate = $date->format('d M Y');
        $formattedTransactionMonth = $date->format('M Y');
@endphp


<div class="wrapper">
    <div class="kop-header">
        <img class="kop-image-header"
             src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-headers.png'))) }}"/>
    </div>

    <div class="container">
        <div class="heading-separator table-heading-container">
            <table class="table-heading">
                <tbody>
                <tr>
                    <td class="table-data-heading" style="width: 1px">
                        <p class="heading-text">Nama Pelanggan</p>
                    </td>
                    <td class="table-data-heading" style="width: 1px">
                        <p class="heading-text-separator">:</p>
                    </td>
                    <td class="table-data-heading" style="width: 20px">
                        <p class="heading-text"> {{ $fab->contact->full_name }}</p>
                    </td>

                </tr>
                <tr>
                    <td class="table-data-heading" style="width: 1px">
                        <p class="heading-text"> NPWP</p>
                    </td>
                    <td class="table-data-heading" style="width: 1px">
                        <p class="heading-text-separator">:</p>
                    </td>
                    <td class="table-data-heading">
                        <p class="heading-text heading-text-separator">{{ $fab->contact->npwp }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="table-data-heading" style="width: 1px">
                        <p class="heading-text heading-text-separator" style="text-transform: uppercase"> Identitas
                            ({{ $fab->contact->identity_type }}
                            )</p>
                    </td>
                    <td class="table-data-heading" style="width: 1px">
                        <p class="heading-text-separator">:</p>
                    </td>
                    <td class="table-data-heading">
                        <p class="heading-text">{{ $fab->contact->identity_number }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="table-data-heading" style="width: 1px">
                        <p class="heading-text heading-text-separator" style="text-transform: uppercase">
                            No. Telepon
                        </p>
                    </td>
                    <td class="table-data-heading" style="width: 1px">
                        <p class="heading-text-separator">:</p>
                    </td>
                    <td class="table-data-heading">
                        <p class="heading-text">{{ $fab->contact->phone_number }}</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="heading-separator table-heading-container">
            <table class="table-heading">
                <tbody>
                <tr>
                    <td class="table-data-heading" style="width: 1px">
                        <p class="heading-text">Kode FAB</p>
                    </td>
                    <td class="table-data-heading" style="width: 1px">
                        <p class=" heading-text-separator">:</p>
                    </td>
                    <td class="table-data-heading">
                        <p class="heading-text heading-text-separator"> {{ $fab->fab_number   }}</p>
                    </td>

                </tr>
                <tr>
                    <td class="table-data-heading" style="width: 1px">
                        <p class="heading-text heading-text-separator">Tanggal</p>
                    </td>
                    <td class="table-data-heading" style="width: 1px">
                        <p class="heading-text-separator">:</p>
                    </td>
                    <td class="table-data-heading" style="width: 1px">
                        <p class="heading-text">{{ $formattedTransactiondate }}</p>
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
                <th class="heading-product">Layanan</th>
                <th class="heading-product">@</th>
                <th class="heading-product">Qty</th>
                <th class="heading-product">Jumlah</th>
            </tr>
            </thead>
            <tbody>
            @foreach($fabServices as $service)
                <tr class="row-product">
                    <td class="data-table-product" style="width: 30px; text-align: center">{{ $loop->iteration }}</td>
                    <td class="data-table-product" style="text-align: center">{{ $service->service->name }}
                        / {{ $service->service->capacity }} Mbps
                    </td>
                    <td class="data-table-product" style="text-align: center">
                        Rp.{{ number_format($service->unit_price) }}</td>
                    <td class="data-table-product" style="text-align: center">{{ $service->qty }}</td>
                    <td class="data-table-product" style="text-align: center">
                        Rp. {{ number_format($service->total_price) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr class="row-product">
                <td colspan="4" class="data-table-product text-end">TOTAL</td>
                <td class="data-table-product py-1" style="text-align: center">
                    Rp. {{ number_format($fabServices->sum('total_price')) }}
                </td>
            </tr>
            </tfoot>
        </table>

        <div class="heading-separator table-heading-container">
            <table class="table-heading">
                <tbody>
                <tr>
                    <td class="table-data-heading" style="width: 1px; text-align: left">
                        <p class="heading-text">Alamat Penagihan</p>
                    </td>
                    <td class="table-data-heading" style="width: 1px">
                        <p class=" heading-text-separator">:</p>
                    </td>
                    <td class="table-data-heading">
                        <p class="heading-text heading-text-separator">{{ $fab->billing_address }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="table-data-heading" style="width: 1px; text-align: left">
                        <p class="heading-text heading-text-separator">Alamat Pemasangan</p>
                    </td>
                    <td class="table-data-heading" style="width: 1px">
                        <p class="heading-text-separator">:</p>
                    </td>
                    <td class="table-data-heading" style="width: 100px">
                        <p class="heading-text">{{ $fab->installation_address }}</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="kop-footer">
        <img class="kop-image-footer"
             src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-footer.png'))) }}"/>
    </div>
</div>
</body>
</html>
