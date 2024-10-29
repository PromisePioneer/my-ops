<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
        }


        * {
            margin: 0;
        }


        @page {
            margin: 0 0;
        }

        body {
            margin: 3cm 2cm 2cm;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            font-family: Poppins, Helvetica, sans-serif;
            font-size: 62.5%;
        }


        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 3cm;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 5cm;
        }

        .wrapper {
            position: relative;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-heading .table-data-heading {
            /*border: none !important;*/
            width: 50%;
            text-align: left;
        }

        .heading-text {
            font-size: 14px;
        }

    </style>


</head>

<body>

<header>
    <img
        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-header.png'))) }}"
        width="100%" height="100%"/>
</header>

<div class="wrapper">
    <div class="container">
        <div style="float: right">
            <table>
                <thead>
                <tr>
                    <td style="font-size: 13px">{{ \App\Helper\formatDate($data->date) }}</td>
                </tr>
                </thead>
            </table>
        </div>

        <table class="table-heading">
            <tbody>
            <tr>
                <td class="table-data-heading" style="font-size: 13px; width: 20%;">
                    No.
                </td>
                <td class="table-data-heading" style="font-size: 13px; text-align: left;">
                    :
                </td>
                <td class="table-data-heading" style="font-size: 13px;width: 100%">
                    {{ $data->offering_number }}
                </td>
            </tr>
            <tr>
                <td class="table-data-heading" style="font-size: 13px; width: 20%;">
                    <p class="heading-text">Perihal</p>
                </td>
                <td class="table-data-heading" style="font-size: 13px; width: 1px; text-align: left;">
                    :
                </td>
                <td class="table-data-heading" style="font-size: 13px; width: 100%">
                    {{ $data->regarding }}
                </td>
            </tr>
            </tbody>
        </table>

        <div class="heading-text" style="margin-top: 10px">
            <p>Kepada Yth, <br> {{ $data->contact->company_name }} <br> <b>Ditempat</b></p>
            <br>
            <p>Dengan hormat, Kami dari PT. Mayatama Solusindo bermaksud menawarkan harga layanan dedicated
                untuk {{ $data->contact->company_name }}, berikut adalah harga terbaik yang kami tawarkan :</p>
            <br>
        </div>


        <table class="table-heading" style=" border: 1px solid;">
            <thead>
            <tr style=" border: 1px solid;  background-color: rgb(0, 158, 247);">
                <th style="border: 1px solid; font-size: 14px; padding: 10px">No</th>
                <th style="border: 1px solid; font-size: 14px; padding: 10px">Layanan</th>
                <th style="border: 1px solid; font-size: 14px; padding: 10px">Kapasitas</th>
                <th style="border: 1px solid; font-size: 14px; padding: 10px">Harga/Bulan</th>
            </tr>
            </thead>
            <tbody>
            @foreach($offeringLetterServices as $service)
                <tr>
                    <td style=" border: 1px solid; font-size: 14px; text-align: center; padding: 10px">{{ $loop->iteration }}</td>
                    <td style=" border: 1px solid; font-size: 14px; text-align: left; padding: 10px">{{ $service->serviceCategory->name }}</td>
                    <td style=" border: 1px solid; font-size: 14px; text-align: center; padding: 10px">
                        {{ $service->capacity }} {{ $service->unitType->name }}
                    </td>
                    <td style="border: 1px solid; font-size: 14px; text-align: right; padding-top: 9px">
                        <div style="float: left; align-items: center">Rp</div>
                        <div
                                style="float: right; align-items: center">{{ number_format($service->price, false, '.', '.') }}</div>
                    </td>

                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3"
                    style="border: 1px solid; font-size: 14px; text-align: right; font-weight: 500; padding-right: 10px">
                    PPN
                </td>
                <td style="text-align: right; font-size: 14px;">
                    <div style="float: left; align-items: center">Rp</div>
                    <div style="float: right; align-items: center">{{ number_format($totalPPN, false,'.', '.') }}</div>
                </td>
            </tr>
            <tr style="border: 1px solid">
                <td colspan="3"
                    style="border: 1px solid; font-size: 14px; text-align: right; font-weight: 500; padding-right: 10px">
                    TOTAL
                </td>
                <td style="text-align: right; font-size: 14px;">
                    <div style="float: left; align-items: center">Rp</div>
                    <div style="float: right; align-items: center">{{ number_format($total, false,'.', '.') }}</div>
                </td>
            </tr>

            </tfoot>
        </table>

        <div class="notes" style="margin-top: 12px">
            <p style="font-size: 13px;">
                Adapun syarat, ketentuan dan layanan yang kami berikan antara lain :
            </p>
            <ul>
                <li style="font-size: 13px">SLA 99,5%</li>
                <li style="font-size: 13px">Support Pelayanan 7 x 24 jam, online maupun onsite.</li>
                <li style="font-size: 13px">Masa berlaku penawaran 1 bulan</li>
                <li style="font-size: 13px">
                    Minimum kontrak 1 tahun dan otomatis diperpanjang apabila tidak ada permintaan berhenti berlangganan
                </li>
                @foreach($offeringLetterServiceDescription as $desc)
                    <li style="font-size: 13px">{{ $desc->skl->name }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <br>
    <br>
    <br>
    <div style="float: right">
        <table>
            <tr>
                <th style="text-align: center;">
                    <p style="font-size: 12px; margin: 0;">PT MAYATAMA SOLUSINDO</p>
                </th>
                <th style="text-align: center; padding: 8px;"></th>
            </tr>
            <tr>
                <th style="text-align: center; padding-bottom: 50px;">
                    <p style="font-size: 12px; margin: 0;">

                    </p>
                </th>
            </tr>
            <tr>
                <th style="text-align: center; padding: 8px 8px 0 8px;">
                    <p style="font-size: 12px; margin: 0; text-decoration: underline">
                        {{ $data?->user->name }}
                    </p>
                </th>
            </tr>
            <tr style="padding: 0">
                <th style="text-align: center; padding: 8px;">
                    <p style="font-size: 12px; margin: 0;">{{ $data?->user->roles[0]?->name ?? '' }}</p>
                </th>
            </tr>
        </table>
    </div>

    <footer>
        <img
                src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-footer.png'))) }}"
            width="100%" height="100%"/>
    </footer>
</div>
</body>
</html>
